<?php

use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Contracts\Models\Barable;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Contracts\Models\Storable;
use Tests\Fixtures\Controllers\ProductController;
use Tests\Fixtures\Models\Product;

it('passes', function () {
    foreach ([ProductController::class, [ProductController::class]] as $value) {
        expect($value)
            ->toUse([Product::class])
            ->and(Product::class)
            ->not->toUse(ProductController::class)
            ->and('Tests\Fixtures\Controllers')
            ->toUse('Tests\Fixtures\Models')
            ->and('Tests\Fixtures\Models')
            ->not->toUse('Tests\Fixtures\Controllers');
    }
});

it('fails 1', function () {
    expect([Product::class])->toUse('Tests\Fixtures\Controllers');
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' to use 'Tests\Fixtures\Controllers'"
);

it('fails 2', function () {
    expect('Tests\Fixtures\Models')->not->toUse('Tests\Fixtures\Contracts\Models');
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models' not to use 'Tests\Fixtures\Contracts\Models'."
);

it('fails 3', function () {
    expect(ProductController::class)
        ->not->toUse([
            'Tests\Fixtures\Controllers',
            'Tests\Fixtures\Models',
        ]);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Controllers\Pr…roller' not to use 'Tests\Fixtures\Models'."
);

test('ignoring', function () {
    expect(Product::class)
        ->not()
        ->toUse('Tests\Fixtures\Contracts')
        ->ignoring([Fooable::class, Storable::class, Barable::class])
        ->toUse('Tests\Fixtures\Contracts')
        ->toUse([Fooable::class, Storable::class])
        ->toUse([Fooable::class])
        ->ignoring(Storable::class);
});

test('ignoring opposite message', function () {
    expect(Product::class)
        ->not
        ->toUse([Fooable::class, Storable::class])
        ->ignoring('Tests\Fixtures\Enums');
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' not to use 'Tests\Fixtures\Contracts\Mode…orable'."
);

test('layer may not exist', function () {
    expect(Product::class)
        ->toUse(Fooable::class)
        ->ignoring(Fooable::class);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' to use 'Tests\Fixtures\Contracts\Models\Fooable'."
);
