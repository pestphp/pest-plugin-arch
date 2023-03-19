<?php

use Pest\Arch\ValueObjects\Dependency;
use Pest\Arch\ValueObjects\Targets;
use Pest\Expectation;

test('globals')
    ->expect('Pest\Arch')
    ->not->toUse(['dd', 'dump', 'ray']);

test('base')
    ->expect('Pest\Arch')
    ->toOnlyUse([
        'expect',
        'test',
        'Pest',
        'PHPUnit\Architecture',
        'Symfony\Component\Finder\Finder',
    ])->ignoring(['PHPUnit\Framework', 'Composer']);

test('collections')
    ->expect('Pest\Arch\Collections')
    ->toOnlyUse('Pest\Arch\ValueObjects');

test('exceptions')
    ->expect('Pest\Arch\Exceptions')
    ->toUseNothing();

test('expectations')
    ->expect('Pest\Arch\Expectations')
    ->toOnlyUse([
        'expect',
        'Pest\Expectation',
        'Pest\Arch',
    ])->ignoring('PHPUnit\Framework');

test('repositories')->expect('Pest\Arch\Repositories')->toOnlyUse([
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
    ->toUseNothing()
    ->ignoring([Targets::class, Dependency::class, 'PHPUnit\Framework'])
    ->expect(Targets::class)
    ->toOnlyUse([Expectation::class])
    ->ignoring('PHPUnit\Framework');
