<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\CefrLevel;
use Database\Seeders\Concerns\SeedsUnitDefinitions;
use Illuminate\Database\Seeder;

/**
 * Seeds the Spanish A2 units, one data file each under data/spanish-a2 in file
 * name order, after the A1 units in the course order.
 *
 * @phpstan-import-type UnitDefinition from SpanishA1Seeder
 */
class SpanishA2Seeder extends Seeder
{
    use SeedsUnitDefinitions;

    private const FIRST_SORT_ORDER = 101;

    public function run(): void
    {
        $this->seedUnitDefinitions('es', CefrLevel::A2, $this->units(), self::FIRST_SORT_ORDER);
    }

    /** @return list<UnitDefinition> */
    private function units(): array
    {
        $units = [];

        foreach (glob(database_path('seeders/data/spanish-a2/*.php')) ?: [] as $path) {
            /** @var UnitDefinition $unit */
            $unit = require $path;
            $units[] = $unit;
        }

        return $units;
    }
}
