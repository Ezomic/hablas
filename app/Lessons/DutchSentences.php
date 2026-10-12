<?php

declare(strict_types=1);

namespace App\Lessons;

/**
 * English exercise text with its Dutch translation, read from the data files in
 * database/seeders/data/dutch-sentences (each returns [english => dutch]).
 */
final class DutchSentences
{
    /** @var array<string, array<string, string>> */
    private static array $loaded = [];

    public function __construct(private readonly ?string $directory = null) {}

    public function translate(string $english): ?string
    {
        return $this->all()[$english] ?? null;
    }

    /** @return array<string, string> */
    public function all(): array
    {
        $directory = $this->directory ?? database_path('seeders/data/dutch-sentences');

        if (! isset(self::$loaded[$directory])) {
            $sentences = [];

            foreach (glob($directory.'/*.php') ?: [] as $path) {
                /** @var array<string, string> $file */
                $file = require $path;
                $sentences = array_replace($sentences, $file);
            }

            self::$loaded[$directory] = $sentences;
        }

        return self::$loaded[$directory];
    }

    public static function flush(): void
    {
        self::$loaded = [];
    }
}
