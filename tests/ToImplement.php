<?php

use Pest\Arch\Exceptions\ArchExpectationFailedException;
use Tests\Fixtures\Contracts\Controllers\Indexable;
use Tests\Fixtures\Controllers\ProductController;
use Tests\Fixtures\Controllers\UserController;

it('passes', function () {
    expect(UserController::class)
        ->toImplement(Indexable::class)
        ->and(ProductController::class)
        ->not->toImplement(Indexable::class);
});

it('fails 1', function () {
    expect([ProductController::class])->toImplement(Indexable::class);
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/ProductController.php' to implement 'Tests\Fixtures\Contracts\Controllers\Indexable'."
);

test('ignoring', function () {
    expect('Tests\Fixtures\Controllers')
        ->toImplement(Indexable::class)
        ->ignoring(ProductController::class)
        ->not->toImplement(Indexable::class)
        ->ignoring(UserController::class);
});

test('ignoring opposite message', function () {
    expect(UserController::class)
        ->not
        ->toImplement(Indexable::class);
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/UserController.php' not to implement 'Tests\Fixtures\Contracts\Controllers\Indexable'."
);
