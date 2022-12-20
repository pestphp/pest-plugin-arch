<?php

use Pest\Arch\Exceptions\LayerNotFound;
use Pest\Support\Str;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Contracts\Models\Storable;
use Tests\Fixtures\Models\Product;

it('passes', function () {
    expect(Product::class)->toOnlyDependOn([Fooable::class, Storable::class])
        ->not->toOnlyDependOn(Fooable::class)
        ->and(Fooable::class)->toOnlyDependOn([])
        ->and('Tests\Fixtures\Models')->toOnlyDependOn('Tests\Fixtures\Contracts\Models')
        ->and('Tests\Fixtures')->toOnlyDependOn([
            'Tests\Fixtures',
            Str::class,
            'my_request_global_function',
        ]);
});

it('fail 1', function () {
    expect(Product::class)->toOnlyDependOn([
        Fooable::class,
    ]);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' to only depend on 'Tests\Fixtures\Contracts\Models\Fooable'. However, it also depends on 'Tests\Fixtures\Contracts\Models\Storable'."
);

it('fail 2', function () {
    expect(Product::class)->toOnlyDependOn([]);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' to depend on nothing. However, it depends on 'Tests\Fixtures\Contracts\Models\Fooable'."
);

test('ignoring', function () {
    expect(Product::class)
        ->toOnlyDependOn([])
        ->ignoring('Tests\Fixtures\Contracts')
        ->toOnlyDependOn(Storable::class)
        ->ignoring(Fooable::class)
        ->toOnlyDependOn([Storable::class, Fooable::class])
        ->not->toOnlyDependOn([])
        ->ignoring(Fooable::class);
});

test('ignoring opposite message', function () {
    expect(Product::class)
        ->not
        ->toOnlyDependOn([Fooable::class, Storable::class])
        ->ignoring('Tests\Fixtures\Enums');
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Models\Product' not to only depend on 'Tests\Fixtures\Contracts\Mode...ooable' 'Tests\Fixtures\Contracts\Mode...orable'."
);

test('ignoring as layer does not exist', function () {
    expect(Product::class)
        ->toOnlyDependOn('Tests\Fixtures\Contracts\Models')
        ->ignoring('Tests\Fixtures\Contracts\Models');
})->throws(
    LayerNotFound::class,
    "Layer 'Tests\Fixtures\Contracts\Models' does not exist",
);
