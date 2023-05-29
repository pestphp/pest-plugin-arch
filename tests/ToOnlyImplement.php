<?php

use Pest\Arch\Exceptions\ArchExpectationFailedException;
use Tests\Fixtures\Contracts\Controllers\Indexable;
use Tests\Fixtures\Contracts\Models\Barable;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Contracts\Models\Storable;
use Tests\Fixtures\Models\Product;

it('passes', function () {
    expect(Product::class)->toOnlyImplement([Fooable::class, Storable::class, Barable::class]);
});

it('fail 1', function () {
    expect(Product::class)->toOnlyImplement([Fooable::class, Barable::class]);
})->throws(ArchExpectationFailedException::class);

it('fail 2', function () {
    expect(Product::class)->toOnlyImplement([Fooable::class, Storable::class, Barable::class, Indexable::class]);
})->throws(ArchExpectationFailedException::class);
