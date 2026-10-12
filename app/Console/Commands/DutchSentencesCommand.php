<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Lessons\DutchSentenceCollector;
use App\Lessons\DutchSentences;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('dutch:sentences {action : export or coverage} {--out= : Directory to write the batches to} {--chars=12000 : Characters of English per batch}')]
#[Description('Export the English exercise texts still without a Dutch translation, or report how many there are')]
class DutchSentencesCommand extends Command
{
    public function handle(DutchSentenceCollector $collector, DutchSentences $sentences): int
    {
        $action = (string) $this->argument('action');
        $all = $collector->handle();
        $missing = array_filter($all, fn (array $row): bool => $sentences->translate($row['english']) === null);

        if ($action === 'coverage') {
            $this->info(count($all) - count($missing).' of '.count($all).' texts have a Dutch translation, '.count($missing).' missing.');

            return self::SUCCESS;
        }

        if ($action !== 'export') {
            $this->error('The action is export or coverage.');

            return self::FAILURE;
        }

        $out = (string) $this->option('out');

        if ($out === '') {
            $this->error('Give --out=directory.');

            return self::FAILURE;
        }

        if (! is_dir($out) && ! mkdir($out, 0775, true)) {
            $this->error("Cannot create [{$out}].");

            return self::FAILURE;
        }

        $batches = $this->batches(array_values($missing), max(1, (int) $this->option('chars')));

        foreach ($batches as $index => $batch) {
            file_put_contents(sprintf('%s/batch-%03d.json', $out, $index + 1), json_encode($batch, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        }

        $this->info(count($missing).' texts written to '.count($batches).' batches in '.$out.'.');

        return self::SUCCESS;
    }

    /**
     * Texts of one kind stay together, so a batch is coherent for the translator.
     *
     * @param  list<array{english: string, format: string, field: string, language: string, context: string}>  $rows
     * @return list<list<array{english: string, format: string, field: string, language: string, context: string}>>
     */
    private function batches(array $rows, int $chars): array
    {
        usort($rows, fn (array $a, array $b): int => [$a['field'] === 'explanation' ? 1 : 0, $a['format'], $a['field'], $a['language'], $a['english']] <=> [$b['field'] === 'explanation' ? 1 : 0, $b['format'], $b['field'], $b['language'], $b['english']]);

        $batches = [];
        $current = [];
        $size = 0;

        foreach ($rows as $row) {
            $length = mb_strlen($row['english']) + mb_strlen($row['context']);

            if ($current !== [] && $size + $length > $chars) {
                $batches[] = $current;
                $current = [];
                $size = 0;
            }

            $current[] = $row;
            $size += $length;
        }

        if ($current !== []) {
            $batches[] = $current;
        }

        return $batches;
    }
}
