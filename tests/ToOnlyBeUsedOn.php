<?php

use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Contracts\Models\Barable;
use Tests\Fixtures\Contracts\Models\Storable;
use Tests\Fixtures\Models\Product;
use Tests\Fixtures\Models\User;

it('passes', function () {
    foreach ([Storable::class, [Storable::class]] as $value) {
        expect($value)->toOnlyBeUsedOn([
            Product::class,
        ])->toOnlyBeUsedOn([
            'Tests',
        ])->toOnlyBeUsedOn([
            Product::class,
            'Tests',
        ]);
    }
});

it('fail 1', function () {
    expect([Storable::class])->toOnlyBeUsedOn([
        User::class,
    ]);
})->throws(ExpectationFailedException::class, "Expecting 'Tests\Fixtures\Models\User' to use 'Tests\Fixtures\Contracts\Models\Storable'.");

it('fail 2', function () {
    expect(Barable::class)->toOnlyBeUsedOn([
        User::class,
    ]);
})->throws(ExpectationFailedException::class, "Tests\Fixtures\Contracts\Models\Barable' not to be used on 'Tests\Fixtures\Models\Product'.");
