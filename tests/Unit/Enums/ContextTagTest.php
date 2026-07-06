<?php

use App\Enums\ContextTag;

it('orders travel before everyday-social and professional', function () {
    expect(ContextTag::Travel->sortOrder())->toBeLessThan(ContextTag::EverydaySocial->sortOrder())
        ->and(ContextTag::EverydaySocial->sortOrder())->toBeLessThan(ContextTag::Professional->sortOrder());
});
