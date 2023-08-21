<?php

use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Controllers\ProductController;
use Tests\Fixtures\Controllers\UserController;

it('passes', function () {
    foreach ([Fooable::class, [Fooable::class]] as $value) {
        expect($value)
            ->toBeUsedIn('Tests\Fixtures\Models')
            ->not->toBeUsedIn('Tests\Fixtures\Controllers');
    }
});

it('failure with native functions', function (string $function) {
    expect(fn () => expect($function)->not->toBeUsedIn('Tests\Fixtures\Misc\HasNativeFunctions'))->toThrow(
        ExpectationFailedException::class,
        "Expecting '$function' not to be used in 'Tests\Fixtures\Misc\HasNativeFunctions'."
    );
})->with(['sleep', 'die', 'eval', 'exit', 'clone', 'empty', 'isset', 'print']);

it('fails 1', function () {
    expect([Fooable::class])
        ->toBeUsedIn(['Tests\Fixtures\Models', 'Tests\Fixtures\Controllers']);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Controllers' to use 'Tests\Fixtures\Contracts\Models\Fooable'."
);

it('fails 2', function () {
    expect(Fooable::class)
        ->not
        ->toBeUsedIn(['Tests\Fixtures\Models', 'Tests\Fixtures\Controllers']);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Contracts\Mode…ooable' not to be used in 'Tests\Fixtures\Models'."
);

test('ignoring', function () {
    expect(Fooable::class)
        ->not
        ->toBeUsedIn(['Tests\Fixtures\Controllers'])
        ->ignoring(ProductController::class);
});

test('ignoring opposite message', function () {
    expect(Fooable::class)
        ->toBeUsedIn(['Tests\Fixtures\Controllers'])
        ->ignoring(ProductController::class);
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests\Fixtures\Controllers' to use 'Tests\Fixtures\Contracts\Models\Fooable'."
);

test('layer may not exist', function () {
    expect(Fooable::class)
        ->not
        ->toBeUsedIn(['Tests\Fixtures\Controllers'])
        ->ignoring([UserController::class, ProductController::class]);
});
