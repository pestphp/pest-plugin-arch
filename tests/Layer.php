<?php

use PHPUnit\Framework\ExpectationFailedException;
use Tests\Fixtures\Support\Collection;

it('does not include native classes', function () {
    expect(Collection::class)->toDependOnNothing();
});

it('does not allow empty layers', function () {
    expect(NonExistingClass::class)->toDependOnNothing();
})->throws(
    ExpectationFailedException::class,
    "Layer 'NonExistingClass' does not exist.",
);
