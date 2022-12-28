<?php

use Pest\Arch\Exceptions\LayerNotFound;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Contracts\Models\Barable;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Contracts\Models\Storable;
use Tests\Fixtures\Controllers\ProductController;
use Tests\Fixtures\Models\Product;

it('passes', function () {
    expect(ProductController::class)
        ->toDependOn([Product::class])
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

it('fails 3', function () {
    expect(ProductController::class)
        ->not->toDependOn([
            'Tests\Fixtures\Controllers',
            'Tests\Fixtures\Models',
        ]);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Controllers\Pr...roller' not to depend on 'Tests\Fixtures\Models'."
);

test('ignoring', function () {
    expect(Product::class)
        ->not()
        ->toDependOn('Tests\Fixtures\Contracts')
        ->ignoring([Fooable::class, Storable::class, Barable::class])
        ->toDependOn('Tests\Fixtures\Contracts')
        ->toDependOn([Fooable::class, Storable::class])
        ->toDependOn([Fooable::class])
        ->ignoring(Storable::class);
});

test('ignoring opposite message', function () {
    expect(Product::class)
        ->not
        ->toDependOn([Fooable::class, Storable::class])
        ->ignoring('Tests\Fixtures\Enums');
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' not to depend on 'Tests\Fixtures\Contracts\Mode...orable'."
);

test('ignoring as layer does not exist', function () {
    expect(Product::class)
        ->toDependOn(Fooable::class)
        ->ignoring(Fooable::class);
})->throws(
    LayerNotFound::class,
    "Layer 'Tests\Fixtures\Contracts\Models\Fooable' does not exist",
);
