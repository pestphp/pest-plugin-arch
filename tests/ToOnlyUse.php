<?php

use Pest\Support\Str;
use Tests\Fixtures\Contracts\Models\Barable;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Contracts\Models\Storable;
use Tests\Fixtures\Models\Product;

it('passes', function () {
    foreach ([Product::class, [Product::class]] as $value) {
        expect($value)
            ->toOnlyUse([Fooable::class, Storable::class, Barable::class])
            ->and(Fooable::class)->toOnlyUse([])
            ->and('Tests\Fixtures\Models')->toOnlyUse('Tests\Fixtures\Contracts\Models')
            ->and('Tests\Fixtures')->toOnlyUse([
                'Tests\Fixtures',
                Str::class,
                'my_request_global_function',
            ]);
    }
});

it('fail 1', function () {
    expect(fn () => expect([Product::class])->toOnlyUse([Fooable::class]))->toThrowArchitectureViolation(
        "Expecting 'Tests\Fixtures\Models\Product' to only use 'Tests\Fixtures\Contracts\Models\Fooable'. However, it also uses 'Tests\Fixtures\Contracts\Models\Barable'.",
        'tests/Fixtures/Models/Product.php',
        7
    );
});

it('fail 2', function () {
    expect(fn () => expect(Product::class)->toOnlyUse([]))->toThrowArchitectureViolation(
        "Expecting 'Tests\Fixtures\Models\Product' to use nothing. However, it uses 'Tests\Fixtures\Contracts\Models\Barable'.",
        'tests/Fixtures/Models/Product.php',
        7
    );
});

test('ignoring', function () {
    expect(Product::class)
        ->toOnlyUse([])
        ->ignoring('Tests\Fixtures\Contracts')
        ->toOnlyUse([Storable::class])
        ->ignoring([Fooable::class, Barable::class])
        ->toOnlyUse([Storable::class, Fooable::class, Barable::class]);
});

test('layer may not exist', function () {
    expect(Product::class)
        ->toOnlyUse('Tests\Fixtures\Contracts\Models')
        ->ignoring('Tests\Fixtures\Contracts\Models');
});
