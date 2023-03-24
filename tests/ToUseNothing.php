<?php

use Pest\Exceptions\InvalidExpectation;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Models\Product;

it('passes', function () {
    foreach (['Tests\Fixtures\Contracts', ['Tests\Fixtures\Contracts']] as $value) {
        expect($value)->toUseNothing();
    }
});

it('fails 1', function () {
    expect(fn () => expect([Product::class])->toUseNothing())
        ->toThrowArchitectureViolation(
            "Expecting 'Tests\Fixtures\Models\Product' to use nothing. However, it uses 'Tests\Fixtures\Contracts\Models\Barable'.",
            'tests/Fixtures/Models/Product.php',
            7
        );
});

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
