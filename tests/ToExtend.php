<?php

use Pest\Arch\Exceptions\ArchExpectationFailedException;
use Tests\Fixtures\Controller;
use Tests\Fixtures\Controllers\ProductController;
use Tests\Fixtures\Controllers\UserController;

it('passes', function () {
    expect(UserController::class)
        ->toExtend(Controller::class)
        ->and(ProductController::class)
        ->not->toExtend(Controller::class);
});

it('fails 1', function () {
    expect([ProductController::class])->toExtend(Controller::class);
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/ProductController.php' to extend 'Tests\Fixtures\Controller'."
);

test('ignoring', function () {
    expect('Tests\Fixtures\Controllers')
        ->toExtend(Controller::class)
        ->ignoring(ProductController::class)
        ->not->toExtend(Controller::class)
        ->ignoring(UserController::class);
});

test('ignoring opposite message', function () {
    expect(UserController::class)
        ->not
        ->toExtend(Controller::class);
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/UserController.php' not to extend 'Tests\Fixtures\Controller'."
);
