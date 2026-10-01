<?php

declare(strict_types=1);

namespace App\Services;

use App\Lessons\UnitContent;

/**
 * Finds the per-unit content classes under database/content/Lessons, one
 * class per unit, grouped by language folder.
 */
final class UnitContentRegistry
{
    /**
     * @param  list<UnitContent>|null  $contents  fixed content, for tests
     * @param  string|null  $path  the folder of language folders to look in, by default database/content/Lessons
     * @param  string  $namespace  the namespace that folder is autoloaded as
     */
    public function __construct(
        private readonly ?array $contents = null,
        private readonly ?string $path = null,
        private readonly string $namespace = 'Database\\Content\\Lessons\\',
    ) {}

    /** @return list<UnitContent> */
    public function all(): array
    {
        if ($this->contents !== null) {
            return $this->contents;
        }

        $contents = [];

        foreach (glob(($this->path ?? database_path('content/Lessons')).'/*/*.php') ?: [] as $path) {
            $class = $this->namespace.basename(dirname($path)).'\\'.basename($path, '.php');

            if (class_exists($class) && is_subclass_of($class, UnitContent::class)) {
                $contents[] = new $class;
            }
        }

        return $contents;
    }
}
