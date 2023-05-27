<?php

use Pest\Arch\ValueObjects\Dependency;
use Pest\Arch\ValueObjects\Targets;
use Pest\Arch\ValueObjects\Violation;
use Pest\Expectation;
use Whoops\Exception\Frame;

test('base')
    ->expect('Pest\Arch')
    ->toOnlyUse([
        'dd',
        'dump',
        'expect',
        'test',
        'Pest',
        'PHPUnit\Architecture',
        'Symfony\Component\Finder\Finder',
        'PhpParser\Node',
        'Whoops\Exception\Frame',
    ])->ignoring(['PHPUnit\Framework', 'Composer']);

test('collections')
    ->expect('Pest\Arch\Collections')
    ->toOnlyUse('Pest\Arch\ValueObjects');

test('exceptions')
    ->expect('Pest\Arch\Exceptions')
    ->toOnlyUse([
        Frame::class,
        Violation::class,
    ])->ignoring('PHPUnit\Framework');

test('expectations')
    ->expect('Pest\Arch\Expectations')
    ->toOnlyUse([
        'expect',
        'Pest\Expectation',
        'Pest\Arch',
        'PHPUnit\Architecture\Elements\ObjectDescription',
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
    ->ignoring([Targets::class, Dependency::class, 'PHPUnit\Framework', Expectation::class])
    ->expect(Targets::class)
    ->toOnlyUse([Expectation::class])
    ->ignoring('PHPUnit\Framework');
