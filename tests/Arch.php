<?php

use Pest\Arch\ValueObjects\Target;

test('base')
    ->expect('Pest\Arch')
    ->toOnlyDependOn([
        'test',
        'Pest',
        'PHPUnit\Architecture',
        'Symfony\Component\Finder\Finder',
    ])->ignoring('PHPUnit\Framework');

test('collections')
    ->expect('Pest\Arch\Collections')
    ->toOnlyDependOn('Pest\Arch\ValueObjects');

test('exceptions')
    ->expect('Pest\Arch\Exceptions')
    ->toDependOnNothing();

test('expectations')
    ->expect('Pest\Arch\Expectations')
    ->toOnlyDependOn([
        'Pest\Expectation',
        'Pest\Arch',
    ])->ignoring('PHPUnit\Framework');

test('repositories')->expect('Pest\Arch\Repositories')->toOnlyDependOn([
    'Pest\TestSuite',
    'Pest\Arch\Factories',
    'Pest\Arch\Objects',
    'Pest\Arch\ValueObjects',
    'Pest\Arch\Support',
    'PHPUnit\Architecture',
    'Symfony\Component\Finder\Finder',
]);

test('value objects')
    ->expect('Pest\Arch\ValueObjects')
        ->toDependOnNothing()
        ->ignoring(Target::class)
        ->and(Target::class)
        ->toOnlyDependOn(\Pest\Expectation::class);
