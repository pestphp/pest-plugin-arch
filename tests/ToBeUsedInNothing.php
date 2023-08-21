<?php

use Pest\Exceptions\InvalidExpectation;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Support\Env;

it('passes', function () {
    foreach ([Env::class, [Env::class]] as $value) {
        expect($value)->toBeUsedInNothing();
    }
});

it('passes as aliases', function () {
    expect([Env::class])->not->toBeUsed();
});

it('fails 1', function () {
    expect(fn () => expect(Fooable::class)->toBeUsedInNothing())->toThrowArchitectureViolation(
        "Expecting 'Tests\Fixtures\Contracts\Models\Fooable' not to be used on 'Tests\Fixtures\Models\Product'.",
        'tests/Fixtures/Models/Product.php',
        8
    );
});

it('fails 2', function () {
    expect(fn () => expect(Fooable::class)->not->toBeUsed())->toThrowArchitectureViolation(
        "Expecting 'Tests\Fixtures\Contracts\Models\Fooable' not to be used on 'Tests\Fixtures\Models\Product'.",
        'tests/Fixtures/Models/Product.php',
        8
    );
});

it('fails with native functions', function () {
    expect(fn () => expect('sleep')->not->toBeUsed())->toThrowArchitectureViolation(
        "Expecting 'sleep' not to be used on 'Tests\Fixtures\Misc\HasSleepFunction'.",
        'tests/Fixtures/Misc/HasSleepFunction.php',
        10
    );
});

test('ignoring', function () {
    expect(Fooable::class)->toBeUsedInNothing()->ignoring('Tests\Fixtures\Models');
});

test('layer may not exist', function () {
    expect(Fooable::class)
        ->toBeUsedInNothing()
        ->ignoring(Fooable::class);
});

test('opposite', function () {
    expect(Fooable::class)
        ->not
        ->toBeUsedInNothing();
})->throws(InvalidExpectation::class, 'Expectation [not->toBeUsedInNothing] is not valid.');

test('opposite as aliases', function () {
    expect(Fooable::class)
        ->toBeUsed();
})->throws(InvalidExpectation::class, 'Expectation [toBeUsed] is not valid.');
