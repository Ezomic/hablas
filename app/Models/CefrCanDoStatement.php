<?php

namespace App\Models;

use App\Enums\CefrLevel;
use App\Enums\Skill;
use Carbon\CarbonImmutable;
use Database\Factories\CefrCanDoStatementFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property CefrLevel $cefr_level
 * @property Skill $skill
 * @property string $statement_text
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 */
#[Fillable(['cefr_level', 'skill', 'statement_text'])]
class CefrCanDoStatement extends Model
{
    /** @use HasFactory<CefrCanDoStatementFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'cefr_level' => CefrLevel::class,
            'skill' => Skill::class,
        ];
    }
}
