<?php

declare(strict_types=1);

use App\Enums\EmailCodePurpose;
use App\Models\LoginCode;
use App\Models\User;
use Illuminate\Support\Facades\Date;

/**
 * Codes issued before HAB-92 did not record the address they went to, so the
 * backfill may only prove an address it can show a used code was sent to.
 */
function legacyCode(User $user, bool $consumed, EmailCodePurpose $purpose = EmailCodePurpose::Login): void
{
    LoginCode::query()->forceCreate([
        'user_id' => $user->id,
        'code_hash' => 'legacy',
        'purpose' => $purpose,
        'expires_at' => Date::now()->addMinutes(10),
        'consumed_at' => $consumed ? Date::now() : null,
    ]);
}

function runProofBackfill(): void
{
    (require database_path('migrations/2026_09_27_000002_prove_addresses_that_used_a_code.php'))->up();
}

it('proves only addresses a used code was sent to', function () {
    $signedIn = User::factory()->unverified()->create();
    $confirmed = User::factory()->unverified()->create();
    $onlyInvalidated = User::factory()->unverified()->create();
    $changedSince = User::factory()->unverified()->create();
    $neverCoded = User::factory()->unverified()->create();

    $this->travel(1)->minutes();

    legacyCode($signedIn, consumed: true);
    legacyCode($confirmed, consumed: true, purpose: EmailCodePurpose::Confirm);

    // Requesting a code consumes the one before it, so a consumed code with a
    // newer one behind it may never have been used.
    legacyCode($onlyInvalidated, consumed: true);
    legacyCode($onlyInvalidated, consumed: false);

    legacyCode($changedSince, consumed: true);
    $this->travel(1)->minutes();
    $changedSince->forceFill(['email' => 'typed-in-later@example.com'])->save();

    runProofBackfill();

    expect($signedIn->fresh()?->email_verified_at)->not->toBeNull()
        ->and($confirmed->fresh()?->email_verified_at)->not->toBeNull()
        ->and($onlyInvalidated->fresh()?->email_verified_at)->toBeNull()
        ->and($changedSince->fresh()?->email_verified_at)->toBeNull()
        ->and($neverCoded->fresh()?->email_verified_at)->toBeNull();
});
