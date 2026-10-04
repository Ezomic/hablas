<?php

declare(strict_types=1);

namespace Tests\Support;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class LocaleCatalogs
{
    /**
     * @return array<string, mixed>
     */
    public static function frontend(string $locale): array
    {
        return json_decode((string) file_get_contents(resource_path("js/lang/{$locale}.json")), true, flags: JSON_THROW_ON_ERROR);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, string>
     */
    public static function flatten(array $data, string $prefix = ''): array
    {
        $flat = [];

        foreach ($data as $key => $value) {
            $path = $prefix === '' ? $key : "{$prefix}.{$key}";

            $flat = is_array($value)
                ? [...$flat, ...self::flatten($value, $path)]
                : [...$flat, $path => (string) $value];
        }

        return $flat;
    }

    /**
     * @return list<string>
     */
    public static function placeholders(string $message): array
    {
        preg_match_all('/\{(\w+)\}/', $message, $matches);

        $names = array_values(array_unique($matches[1]));
        sort($names);

        return $names;
    }

    /**
     * @return list<string>
     */
    public static function phpSourceFiles(): array
    {
        return self::phpFilesIn([app_path(), base_path('routes')]);
    }

    /**
     * @return list<string>
     */
    public static function serverTextFiles(): array
    {
        return self::phpFilesIn([
            app_path('Notifications'),
            app_path('Http/Requests'),
            app_path('Http/Controllers'),
            app_path('Actions'),
        ]);
    }

    /**
     * @return array<string, string>
     */
    public static function rawTextPatterns(): array
    {
        return [
            'a notification line' => '/->(?:subject|line|greeting|action|title|body)\(\s*[\'"]/',
            'a validation message' => '/withMessages\(\s*\[[^\]]*=>\s*\[?\s*[\'"]/s',
            'a refusal' => '/\brefuse\(\s*[\'"]/',
            'a custom request message' => '/[\'"][\w*]+\.\w+[\'"]\s*=>\s*[\'"]/',
            'an abort message' => '/\babort(?:_if|_unless)?\((?:[^;]*?,)?\s*\d{3}\s*,\s*[\'"]/',
            'a flashed message' => '/Inertia::flash\(\s*[\'"]\w+[\'"]\s*,\s*[\'"]/',
            'a sentence in a match arm or array' => '/=>\s*[\'"][A-Z][a-z]+(?:\s+[\w\']+)+[.!?][\'"]/',
            'a status or toast message' => '/->with\(\s*[\'"](?:status|error|success)[\'"]\s*,\s*[\'"]|[\'"]message[\'"]\s*=>\s*[\'"]/',
        ];
    }

    /**
     * @param  list<string>  $directories
     * @return list<string>
     */
    private static function phpFilesIn(array $directories): array
    {
        $files = [];

        foreach ($directories as $directory) {
            /** @var SplFileInfo $file */
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)) as $file) {
                if ($file->getExtension() === 'php') {
                    $files[] = $file->getPathname();
                }
            }
        }

        return $files;
    }
}
