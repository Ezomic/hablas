<?php

declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

$minimum = (float) ($argv[1] ?? 0);
$merged = null;

foreach (array_slice($argv, 2) as $file) {
    $coverage = require $file;

    if ($merged === null) {
        $merged = $coverage;

        continue;
    }

    $merged->merge($coverage);
}

if ($merged === null) {
    fwrite(STDERR, "No coverage files given.\n");
    exit(2);
}

$percentage = $merged->getReport()->percentageOfExecutedLines()->asFloat();

printf("Total lines covered: %.2f%% (minimum %.2f%%)\n", $percentage, $minimum);

exit($percentage >= $minimum ? 0 : 1);
