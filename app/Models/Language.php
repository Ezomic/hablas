<?php

namespace App\Models;

use Database\Factories\LanguageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable(['code', 'name', 'is_active'])]
class Language extends Model
{
    /** @use HasFactory<LanguageFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * The single active language for Milestone 1 (Spanish). Centralizes the
     * "how do we resolve the current language" question so it only needs to
     * change in one place if that ever becomes per-user.
     *
     * Ordered by id so this stays deterministic even if more than one row
     * is ever active at once (e.g. after a second language is unlocked
     * without deactivating the first) — see THI-297 for the underlying
     * per-user-vs-global activation gap this papers over.
     */
    public static function active(): ?self
    {
        return static::query()->where('is_active', true)->orderBy('id')->first();
    }

    /** @return HasMany<UserSkillLevel, $this> */
    public function userSkillLevels(): HasMany
    {
        return $this->hasMany(UserSkillLevel::class);
    }
}
