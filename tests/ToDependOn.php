<?php

use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Controllers\ProductController;
use Tests\Fixtures\Models\Product;

it('passes', function () {
    expect(ProductController::class)
        ->toDependOn(Product::class)
        ->and(Product::class)
        ->not->toDependOn(ProductController::class)
        ->and('Tests\Fixtures\Controllers')
        ->toDependOn('Tests\Fixtures\Models')
        ->and('Tests\Fixtures\Models')
        ->not->toDependOn('Tests\Fixtures\Controllers');
});

it('fails 1', function () {
    expect(Product::class)->toDependOn('Tests\Fixtures\Controllers');
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' to depend on 'Tests\Fixtures\Controllers\ProductController'."
);

it('fails 2', function () {
    expect('Tests\Fixtures\Models')->not->toDependOn('Tests\Fixtures\Contracts\Models');
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models' not to depend on 'Tests\Fixtures\Contracts\Models'."
);
