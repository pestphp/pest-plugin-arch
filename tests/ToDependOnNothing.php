<?php

use PHPUnit\Framework\ExpectationFailedException;
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
