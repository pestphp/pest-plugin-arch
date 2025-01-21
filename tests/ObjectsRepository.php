<?php

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
            'Tests\Fixtures\Models\Product',
            'Tests\Fixtures\Models\User',
        ],
    ],
    [
        'namespace' => 'Tests\Fixtures\Misc\TestsNestedNamespace',
        'expected' => [
            'Tests\Fixtures\Misc\TestsNestedNamespace\IsNested',
        ],
    ],
]);
