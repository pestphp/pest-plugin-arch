<?php

use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Contracts\Models\Storable;
use Tests\Fixtures\Models\Product;

it('passes', function () {
    expect(Product::class)->toOnlyDependOn([Fooable::class, Storable::class])
        ->not->toOnlyDependOn(Fooable::class)
        ->and(Fooable::class)->toOnlyDependOn([])
        ->and('Tests\Fixtures\Models')->toOnlyDependOn('Tests\Fixtures\Contracts\Models')
        ->and('Tests\Fixtures')->toOnlyDependOn('Tests\Fixtures');
});

it('fail 1', function () {
    expect(Product::class)->toOnlyDependOn([
        Fooable::class,
    ]);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' to depend only on 'Tests\Fixtures\Contracts\Models\Fooable'. However, it also depends on 'Tests\Fixtures\Contracts\Models\Storable'."
);

it('fail 2', function () {
    expect(Product::class)->toOnlyDependOn([]);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' to not have any dependencies. However, it depends on 'Tests\Fixtures\Contracts\Models\Fooable'."
);
