<?php

use App\Enums\CefrSubLevel;

it('returns the lowest of several tiers regardless of argument order', function () {
    expect(CefrSubLevel::lowest(CefrSubLevel::B1_1, CefrSubLevel::A2_1, CefrSubLevel::B2))
        ->toBe(CefrSubLevel::A2_1);
});

it('returns the single tier when given only one', function () {
    expect(CefrSubLevel::lowest(CefrSubLevel::A1_2))->toBe(CefrSubLevel::A1_2);
});

it('throws a clear exception when called with no tiers', function () {
    CefrSubLevel::lowest();
})->throws(InvalidArgumentException::class, 'CefrSubLevel::lowest() requires at least one tier.');
