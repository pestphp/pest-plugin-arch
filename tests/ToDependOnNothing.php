<?php

use Pest\Arch\Exceptions\LayerNotFound;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Contracts\Models\Storable;
use Tests\Fixtures\Models\Product;

it('passes', function () {
    expect('Tests\Fixtures\Contracts')->toDependOnNothing()
        ->and(Product::class)->not->toDependOnNothing();
});

it('fails 1', function () {
    expect(Product::class)->toDependOnNothing();
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' to depend on nothing. However, it depends on 'Tests\Fixtures\Contracts\Models\Fooable'."
);

test('ignoring', function () {
    expect(Product::class)
        ->toDependOnNothing()
        ->ignoring('Tests\Fixtures\Contracts')
        ->not->toDependOnNothing()
        ->ignoring(Storable::class);
});

test('ignoring opposite message', function () {
    expect(Product::class)
        ->not
        ->toDependOnNothing()
        ->ignoring('Tests\Fixtures\Contracts');
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' not to depend on nothing ."
);

test('ignoring as layer does not exist', function () {
    expect(Product::class)
        ->toDependOn(Fooable::class)
        ->ignoring(Fooable::class);
})->throws(
    LayerNotFound::class,
    "Layer 'Tests\Fixtures\Contracts\Models\Fooable' does not exist",
);
