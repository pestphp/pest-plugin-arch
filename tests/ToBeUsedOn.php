<?php

use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Controllers\ProductController;
use Tests\Fixtures\Controllers\UserController;

it('passes', function () {
    foreach ([Fooable::class, [Fooable::class]] as $value) {
        expect($value)
            ->toBeUsedOn('Tests\Fixtures\Models')
            ->not->toBeUsedOn('Tests\Fixtures\Controllers');
    }
});

it('fails 1', function () {
    expect([Fooable::class])
        ->toBeUsedOn(['Tests\Fixtures\Models', 'Tests\Fixtures\Controllers']);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Controllers' to use 'Tests\Fixtures\Contracts\Models\Fooable'."
);

it('fails 2', function () {
    expect(Fooable::class)
        ->not
        ->toBeUsedOn(['Tests\Fixtures\Models', 'Tests\Fixtures\Controllers']);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Contracts\Mode...ooable' not to be used on 'Tests\Fixtures\Models'."
);

test('ignoring', function () {
    expect(Fooable::class)
        ->not
        ->toBeUsedOn(['Tests\Fixtures\Controllers'])
        ->ignoring(ProductController::class);
});

test('ignoring opposite message', function () {
    expect(Fooable::class)
        ->toBeUsedOn(['Tests\Fixtures\Controllers'])
        ->ignoring(ProductController::class);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Controllers' to use 'Tests\Fixtures\Contracts\Models\Fooable'."
);

test('layer may not exist', function () {
    expect(Fooable::class)
        ->not
        ->toBeUsedOn(['Tests\Fixtures\Controllers'])
        ->ignoring([UserController::class, ProductController::class]);
});
