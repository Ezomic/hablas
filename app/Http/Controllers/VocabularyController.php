<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Languages\GetCurrentLanguage;
use App\Actions\Srs\SearchVocabulary;
use App\Concerns\InteractsWithCurrentUser;
use App\Http\Requests\IndexVocabularyRequest;
use App\Services\SpeechLocaleResolver;
use App\Speech\SpeechClipResolver;
use Inertia\Inertia;
use Inertia\Response;

final class VocabularyController extends Controller
{
    use InteractsWithCurrentUser;

    public function index(IndexVocabularyRequest $request, SearchVocabulary $searchVocabulary, GetCurrentLanguage $getCurrentLanguage, SpeechLocaleResolver $speechLocaleResolver, SpeechClipResolver $speechClipResolver): Response
    {
        $filters = ['q' => $request->searchTerm(), 'sort' => $request->vocabularySort()->value];
        $language = $getCurrentLanguage->handle($this->currentUser());

        if ($language === null) {
            return Inertia::render('vocabulary/Index', [
                'items' => [],
                'pagination' => ['currentPage' => 1, 'lastPage' => 1, 'total' => 0],
                'filters' => $filters,
                'speechLocale' => null,
            ]);
        }

        $results = $searchVocabulary->handle($this->currentUser(), $language, $request->searchTerm(), $request->vocabularySort(), $request->pageNumber());

        $rows = collect($results->items());
        $clips = $speechClipResolver->resolveBoth($language->code, array_values($rows->filter(fn (array $row): bool => $row['kind'] === 'vocabulary')->map(fn (array $row): string => $row['term'])->all()));
        $none = ['audioUrl' => null, 'audioSlowUrl' => null];

        return Inertia::render('vocabulary/Index', [
            'items' => $rows->map(fn (array $row): array => [
                ...$row,
                ...($row['kind'] === 'vocabulary' ? $clips[$row['term']] : $none),
            ])->values(),
            'pagination' => ['currentPage' => $results->currentPage(), 'lastPage' => $results->lastPage(), 'total' => $results->total()],
            'filters' => $filters,
            'speechLocale' => $speechLocaleResolver->forLanguage($language),
        ]);
    }
}
