<?php

use Pest\Arch\Exceptions\ArchExpectationFailedException;
use Tests\Fixtures\Controllers\ProductController;
use Tests\Fixtures\Controllers\UserController;

it('passes', function () {
    expect(ProductController::class)
        ->toBeReadonly()
        ->and(UserController::class)
        ->not->toBeReadonly();
});

it('fails 1', function () {
    expect([UserController::class])->toBeReadonly();
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/UserController.php' to be readonly."
);

test('ignoring', function () {
    expect('Tests\Fixtures\Controllers')
        ->toBeReadonly()
        ->ignoring(UserController::class)
        ->not->toBeReadonly()
        ->ignoring(ProductController::class);
});

test('ignoring opposite message', function () {
    expect(ProductController::class)
        ->not
        ->toBeReadonly();
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/ProductController.php' not to be readonly."
);
