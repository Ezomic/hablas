<?php

declare(strict_types=1);

it('ships well formed stories', function () {
    $stories = require database_path('seeders/data/stories-es.php');

    expect($stories)->toHaveCount(6);

    foreach ($stories as $story) {
        expect($story['questions'])->not->toBeEmpty();

        foreach ($story['questions'] as $question) {
            expect($question['options'])->toContain($question['correct_answer'])
                ->and($question['options'])->toHaveCount(count(array_unique($question['options'])));
        }

        $tokens = array_map(
            fn (string $token): string => mb_strtolower(trim($token, " \t\n.,;:!?¡¿«»\"()")),
            preg_split('/\s+/', $story['body']) ?: [],
        );

        foreach (array_keys($story['glosses']) as $word) {
            expect($tokens)->toContain($word);
        }

        expect($story['body'])->not->toContain('—');
    }
});
