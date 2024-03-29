<?php

use Pest\Arch\Exceptions\ArchExpectationFailedException;
use Tests\Fixtures\Controllers\ProductController;
use Tests\Fixtures\Controllers\UserController;

it('passes', function () {
    expect(ProductController::class)
        ->toUseStrictTypes()
        ->and(UserController::class)
        ->not->toUseStrictTypes();
});

it('fails 1', function () {
    expect([UserController::class])->toUseStrictTypes();
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/UserController.php' to use strict types."
);

test('ignoring', function () {
    expect('Tests\Fixtures\Controllers')
        ->toUseStrictTypes()
        ->ignoring(UserController::class)
        ->not->toUseStrictTypes()
        ->ignoring(ProductController::class);
});

test('ignoring opposite message', function () {
    expect(ProductController::class)
        ->not
        ->toUseStrictTypes();
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/ProductController.php' not to use strict types."
);
