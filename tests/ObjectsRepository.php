<?php

use Tests\Fixtures\Misc\TestsNestedNamespace\IsNested;
use Tests\Fixtures\Models\Product;
use Tests\Fixtures\Models\User;

it('finds objects by namespace', function (string $namespace, array $expected) {
    $sut = new Pest\Arch\Repositories\ObjectsRepository([
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
