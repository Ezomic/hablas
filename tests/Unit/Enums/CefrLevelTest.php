<?php

use App\Enums\CefrLevel;

it('orders levels from A1 to C2', function () {
    expect(CefrLevel::A1->sortOrder())->toBeLessThan(CefrLevel::A2->sortOrder())
        ->and(CefrLevel::A2->sortOrder())->toBeLessThan(CefrLevel::B1->sortOrder())
        ->and(CefrLevel::B1->sortOrder())->toBeLessThan(CefrLevel::B2->sortOrder())
        ->and(CefrLevel::B2->sortOrder())->toBeLessThan(CefrLevel::C1->sortOrder())
        ->and(CefrLevel::C1->sortOrder())->toBeLessThan(CefrLevel::C2->sortOrder());
});

it('returns the lowest of several levels regardless of argument order', function () {
    expect(CefrLevel::lowest(CefrLevel::B2, CefrLevel::A2, CefrLevel::C1))->toBe(CefrLevel::A2);
});

it('returns the single level when given only one', function () {
    expect(CefrLevel::lowest(CefrLevel::B1))->toBe(CefrLevel::B1);
});

it('throws a clear exception when called with no levels', function () {
    CefrLevel::lowest();
})->throws(InvalidArgumentException::class, 'CefrLevel::lowest() requires at least one level.');
