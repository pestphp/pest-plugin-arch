<?php

use Pest\Arch\Repositories\ObjectsRepository;
use Tests\Fixtures\Misc\TestsNestedNamespace\IsNested;
use Tests\Fixtures\Models\Product;
use Tests\Fixtures\Models\User;
use Tests\Fixtures\Support;
use Tests\Fixtures\Support\Collection;
use Tests\Fixtures\Support\Env;

it('finds objects by namespace', function (string $namespace, array $expected) {
    $sut = new ObjectsRepository([
        'Tests' => [
            __DIR__,
        ],
    ]);

    $result = $sut->allByNamespace($namespace);

    expect($result)->toHaveCount(count($expected));

    foreach ($result as $object) {
        expect($object->name)->toBeIn($expected);
    }
})->with([
    [
        'namespace' => 'Tests\Fixtures\Models',
        'expected' => [
            Product::class,
            User::class,
        ],
    ],
    [
        'namespace' => 'Tests\Fixtures\Misc\TestsNestedNamespace',
        'expected' => [
            IsNested::class,
        ],
    ],
]);

it('finds objects when namespace is both a directory and a file', function () {
    $sut = new ObjectsRepository([
        'Tests' => [
            __DIR__,
        ],
    ]);

    $result = $sut->allByNamespace('Tests\Fixtures\Support');

    $names = array_map(fn ($object) => $object->name, $result);

    expect($names)->toContain(Support::class)
        ->toContain(Collection::class)
        ->toContain(Env::class)
        ->toHaveCount(3);
});

it('finds objects when namespace is only a file', function () {
    $sut = new ObjectsRepository([
        'Tests' => [
            __DIR__,
        ],
    ]);

    $result = $sut->allByNamespace('Tests\Fixtures\Controller');

    expect($result)->toHaveCount(1)
        ->and($result[0]->name)->toBe('Tests\Fixtures\Controller');
});

it('returns empty for non-existent namespace', function () {
    $sut = new ObjectsRepository([
        'Tests' => [
            __DIR__,
        ],
    ]);

    $result = $sut->allByNamespace('Tests\Fixtures\NonExistent');

    expect($result)->toBeEmpty();
});

it('returns empty when prefix has no valid directories', function () {
    $sut = new ObjectsRepository([
        'App' => [
            __DIR__.'/non-existent-directory',
        ],
    ]);

    $result = $sut->allByNamespace('App\Models');

    expect($result)->toBeEmpty();
});

it('reuses the same objects across nested namespaces', function (bool $onlyUserDefinedUses) {
    $sut = new ObjectsRepository([
        'Tests' => [
            __DIR__,
        ],
    ]);

    $parentObjects = [];

    foreach ($sut->allByNamespace('Tests\Fixtures', $onlyUserDefinedUses) as $object) {
        $parentObjects[$object->name] = $object;
    }

    $nestedObjects = $sut->allByNamespace('Tests\Fixtures\Models', $onlyUserDefinedUses);

    expect($nestedObjects)->toHaveCount(2);

    foreach ($nestedObjects as $object) {
        expect($object)->toBe($parentObjects[$object->name]);
    }
})->with([true, false]);

it('filters uses per user defined uses flag', function () {
    $sut = new ObjectsRepository([
        'Tests' => [
            __DIR__,
        ],
    ]);

    $usesOf = function (string $namespace, bool $onlyUserDefinedUses) use ($sut): array {
        $objects = array_values(array_filter(
            $sut->allByNamespace($namespace, $onlyUserDefinedUses),
            fn ($object): bool => $object->name === Collection::class,
        ));

        return iterator_to_array($objects[0]->uses->getIterator());
    };

    expect($usesOf('Tests\Fixtures', true))->not->toContain('Countable')
        ->and($usesOf('Tests\Fixtures\Support', true))->not->toContain('Countable')
        ->and($usesOf('Tests\Fixtures', false))->toContain('Countable')
        ->and($usesOf('Tests\Fixtures\Support', false))->toContain('Countable');
});
