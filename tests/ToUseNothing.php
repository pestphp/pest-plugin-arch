<?php

use Pest\Exceptions\InvalidExpectation;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Models\Product;

it('passes', function () {
    expect('Tests\Fixtures\Contracts')->toUseNothing();
});

it('fails 1', function () {
    expect(Product::class)->toUseNothing();
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' to use nothing. However, it uses 'Tests\Fixtures\Contracts\Models\Barable'."
);

test('ignoring', function () {
    expect(Product::class)
        ->toUseNothing()
        ->ignoring('Tests\Fixtures\Contracts');
});

test('layer may not exist', function () {
    expect(Fooable::class)
        ->toUseNothing()
        ->ignoring(Fooable::class);
});

test('opposite', function () {
    expect(Product::class)
        ->not
        ->toUseNothing();
})->throws(InvalidExpectation::class, 'Expectation [not->toUseNothing] is not valid.');
