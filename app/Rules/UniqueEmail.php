<?php

declare(strict_types=1);

namespace App\Rules;

use App\Models\User;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

/**
 * An address differing only in case reaches the same inbox, and id-client
 * links an ID sign-in by exact email, so a second account under another casing
 * would split one person across two rows.
 */
class UniqueEmail implements ValidationRule
{
    public function __construct(private readonly ?int $ignoreUserId = null) {}

    /**
     * LIKE is the query builder's only case-insensitive comparison, and its
     * wildcards (an underscore is common in addresses) can match more than the
     * address itself, so the candidates it finds are compared exactly after.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            return;
        }

        $candidates = User::query()->whereLike('email', $value);

        if ($this->ignoreUserId !== null) {
            $candidates->whereKeyNot($this->ignoreUserId);
        }

        $taken = $candidates->pluck('email')
            ->contains(fn (string $email): bool => Str::lower($email) === Str::lower($value));

        if ($taken) {
            $fail('validation.unique')->translate();
        }
    }
}
