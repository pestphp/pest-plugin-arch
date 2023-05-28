<?php

use Pest\Arch\Exceptions\ArchExpectationFailedException;
use Tests\Fixtures\Concerns\Deletable;
use Tests\Fixtures\Controllers\UserController;

it('passes', function () {
    expect(Deletable::class)
        ->toBeTrait()
        ->and(UserController::class)
        ->not->toBeTrait();
});

it('fails 1', function () {
    expect([UserController::class])->toBeTrait();
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Controllers/UserController.php' to be trait."
);

test('ignoring', function () {
    expect('Tests\Fixtures\Concerns')
        ->toBeTrait()
        ->ignoring(UserController::class)
        ->not->toBeTrait()
        ->ignoring(Deletable::class);
});

test('ignoring opposite message', function () {
    expect(Deletable::class)
        ->not
        ->toBeTrait();
})->throws(
    ArchExpectationFailedException::class,
    "Expecting 'tests/Fixtures/Concerns/Deletable.php' not to be trait."
);
