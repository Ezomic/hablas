<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::query()->updateOrCreate(
            ['code' => 'es'],
            ['name' => 'Spanish'],
        );

        Language::query()->updateOrCreate(
            ['code' => 'pt'],
            ['name' => 'Portuguese'],
        );

        Language::query()->updateOrCreate(
            ['code' => 'fr'],
            ['name' => 'French'],
        );

        Language::query()->updateOrCreate(
            ['code' => 'it'],
            ['name' => 'Italian'],
        );
    }
}
