<?php

use Pest\Arch\Exceptions\ArchExpectationFailedException;
use Tests\Fixtures\Controllers\ProductController;
use Tests\Fixtures\Controllers\UserController;

it('passes', function () {
    expect(ProductController::class)
        ->toBeFinal()
        ->and(UserController::class)
        ->not->toBeFinal();
});

it('fails 1', function () {
    expect([UserController::class])->toBeFinal();
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/UserController.php' to be final."
);

test('ignoring', function () {
    expect('Tests\Fixtures\Controllers')
        ->toBeFinal()
        ->ignoring(UserController::class)
        ->not->toBeFinal()
        ->ignoring(ProductController::class);
});

test('ignoring opposite message', function () {
    expect(ProductController::class)
        ->not
        ->toBeFinal();
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/ProductController.php' not to be final."
);
