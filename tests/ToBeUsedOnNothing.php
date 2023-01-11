<?php

use Pest\Exceptions\InvalidExpectation;
use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Support\Env;

it('passes', function () {
    foreach ([Env::class, [Env::class]] as $value) {
        expect($value)->toBeUsedOnNothing();
    }
});

it('passes as aliases', function () {
    expect([Env::class])->not->toBeUsed();
});

it('fails 1', function () {
    expect(Fooable::class)->toBeUsedOnNothing();
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Contracts\Models\Fooable' not to be used on 'Tests\Fixtures\Models\Product'."
);

it('fails 2', function () {
    expect(Fooable::class)->not->toBeUsed();
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Contracts\Models\Fooable' not to be used on 'Tests\Fixtures\Models\Product'."
);

test('ignoring', function () {
    expect(Fooable::class)->toBeUsedOnNothing()->ignoring('Tests\Fixtures\Models');
});

test('layer may not exist', function () {
    expect(Fooable::class)
        ->toBeUsedOnNothing()
        ->ignoring(Fooable::class);
});

test('opposite', function () {
    expect(Fooable::class)
        ->not
        ->toBeUsedOnNothing();
})->throws(InvalidExpectation::class, 'Expectation [not->toBeUsedOnNothing] is not valid.');

test('opposite as aliases', function () {
    expect(Fooable::class)
        ->toBeUsed();
})->throws(InvalidExpectation::class, 'Expectation [toBeUsed] is not valid.');
