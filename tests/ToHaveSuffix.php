<?php

use Pest\Arch\Exceptions\ArchExpectationFailedException;
use Tests\Fixtures\Controllers\UserController;

test('passes')
    ->expect('Tests\Fixtures\Controllers')
    ->toHaveSuffix('Controller');

test('fail')
    ->expect(UserController::class)
    ->toHaveSuffix('Controllers')
    ->throws(ArchExpectationFailedException::class);
