<?php

use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Contracts\Models\Storable;
use Tests\Fixtures\Models\Product;
use Tests\Fixtures\Models\User;

it('passes', function () {
    expect(Storable::class)->toOnlyBeUsedOn([
        Product::class,
    ])->toOnlyBeUsedOn([
        'Tests',
    ])->toOnlyBeUsedOn([
        Product::class,
        'Tests',
    ]);
});

it('fail 1', function () {
    expect(Storable::class)->toOnlyBeUsedOn([
        User::class,
    ]);
})->throws(ExpectationFailedException::class, "Expecting 'Tests\Fixtures\Contracts\Models\Storable' not to be used on 'Tests\Fixtures\Models\Product'.");
