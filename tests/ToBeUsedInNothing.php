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
    expect([Env::class, 'error_get_last'])->not->toBeUsed();
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

it('fails with native functions', function (string $function, int $violation_line) {
    expect(fn () => expect($function)->not->toBeUsed())->toThrowArchitectureViolation(
        "Expecting '$function' not to be used on 'Tests\Fixtures\Misc\HasNativeFunctions'.",
        'tests/Fixtures/Misc/HasNativeFunctions.php',
        $violation_line,
    );
})->with([
    'sleep' => ['function' => 'sleep', 'violation_line' => 9],
    'die' => ['function' => 'die', 'violation_line' => 14],
    'exit' => ['function' => 'exit', 'violation_line' => 19],
    'eval' => ['function' => 'eval', 'violation_line' => 24],
    'clone' => ['function' => 'clone', 'violation_line' => 29],
    'empty' => ['function' => 'empty', 'violation_line' => 34],
    'isset' => ['function' => 'isset', 'violation_line' => 38],
    'print' => ['function' => 'print', 'violation_line' => 47],
]);

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
