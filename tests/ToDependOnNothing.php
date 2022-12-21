<?php

use Pest\Arch\Exceptions\LayerNotFound;
use Pest\Exceptions\InvalidExpectation;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Models\Product;

it('passes', function () {
    expect('Tests\Fixtures\Contracts')->toDependOnNothing();
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
        ->ignoring('Tests\Fixtures\Contracts');
});

test('ignoring as layer does not exist', function () {
    expect(Fooable::class)
        ->toDependOnNothing()
        ->ignoring(Fooable::class);
})->throws(
    LayerNotFound::class,
    "Layer 'Tests\Fixtures\Contracts\Models\Fooable' does not exist",
);

test('opposite', function () {
    expect(Product::class)
        ->not
        ->toDependOnNothing();
})->throws(InvalidExpectation::class, 'Expectation [not->toDependOnNothing] is not valid.');
