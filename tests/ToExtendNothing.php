<?php

use Pest\Arch\Exceptions\ArchExpectationFailedException;
use Tests\Fixtures\Controllers\ProductController;
use Tests\Fixtures\Controllers\UserController;

it('passes', function () {
    expect(ProductController::class)
        ->toExtendNothing()
        ->and(UserController::class)
        ->not->toExtendNothing();
});

it('fails 1', function () {
    expect([UserController::class])->toExtendNothing();
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/UserController.php' to extend nothing."
);

test('ignoring', function () {
    expect('Tests\Fixtures\Controllers')
        ->toExtendNothing()
        ->ignoring(UserController::class)
        ->not->toExtendNothing()
        ->ignoring(ProductController::class);
});

test('ignoring opposite message', function () {
    expect(ProductController::class)
        ->not
        ->toExtendNothing();
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/ProductController.php' to extend a class."
);
