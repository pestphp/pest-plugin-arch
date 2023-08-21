<?php

use PHPUnit\Framework\ExpectationFailedException;

test('native functions', function () {
    expect('Tests')->toUse('sleep');
    expect('Tests')->toUse('die');

    expect('sleep')->toBeUsedIn('Tests')
        ->and('die')->toBeUsedIn('Tests');

    expect('die')->toOnlyBeUsedIn('Tests');
});

test('failure 1', function () {
    expect('Tests')->not->toUse('die');
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests' not to use 'die'."
);

test('failure 2', function () {
    expect('Tests')->not->toUse('sleep');
})->throws(
    ExpectationFailedException::class,
    "Expecting 'Tests' not to use 'sleep'."
);

test('failure 3', function () {
    expect('sleep')->not->toBeUsed();
})->throws(
    \Pest\Arch\Exceptions\ArchExpectationFailedException::class,
    'Expecting \'sleep\' not to be used on \'Tests\Fixtures\Misc\HasNativeFunctions\'.'
);

test('failure 4', function () {
    expect('sleep')->toBeUsedInNothing();
})->throws(
    \Pest\Arch\Exceptions\ArchExpectationFailedException::class,
    'Expecting \'sleep\' not to be used on \'Tests\Fixtures\Misc\HasNativeFunctions\'.'
);

test('failure 6', function () {
    expect('sleep')->not->toBeUsedIn('Tests\Fixtures\Misc\HasNativeFunctions');
})->throws(
    ExpectationFailedException::class,
    'Expecting \'sleep\' not to be used in \'Tests\Fixtures\Misc\HasNativeFunctions\'.',
);
