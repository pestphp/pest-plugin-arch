<?php

use Tests\Fixtures\Contracts\Models\Barable;
use Tests\Fixtures\Contracts\Models\NotUsed;
use Tests\Fixtures\Contracts\Models\Storable;
use Tests\Fixtures\Models\Product;
use Tests\Fixtures\Models\User;

it('passes 1', function () {
    foreach ([Storable::class, [Storable::class]] as $value) {
        expect($value)->toOnlyBeUsedIn([
            Product::class,
        ])->toOnlyBeUsedIn([
            'Tests',
        ])->toOnlyBeUsedIn([
            Product::class,
            'Tests',
        ]);
    }
});

it('passes 2', function () {
    expect(NotUsed::class)->toOnlyBeUsedIn('Tests\Fixtures\Concerns');
});

it('fail 1', function () {
    expect(fn () => expect(Barable::class)->toOnlyBeUsedIn([User::class]))->toThrowArchitectureViolation(
        "Expecting 'Tests\Fixtures\Contracts\Models\Barable' not to be used on 'Tests\Fixtures\Models\Product'.",
        'tests/Fixtures/Models/Product.php',
        7
    );
});
