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
    public static function phpSourceFiles(): array
    {
        $files = [];

        foreach ([app_path(), base_path('routes')] as $directory) {
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
