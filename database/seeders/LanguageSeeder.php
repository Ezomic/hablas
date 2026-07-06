<?php

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
            ['name' => 'Spanish', 'is_active' => true],
        );

        Language::query()->updateOrCreate(
            ['code' => 'pt'],
            ['name' => 'Portuguese', 'is_active' => false],
        );
    }
}
