<?php

use Pest\Arch\Support\Composer;

it('retrieves user namespaces', function () {
    $namespaces = Composer::userNamespaces();

    expect($namespaces)->toBeArray()
        ->and($namespaces)->toContain('Pest\Arch')
        ->and($namespaces)->toContain('Tests');
});

it('retrieves user namespaces with directories', function () {
    $namespaces = Composer::userNamespacesWithDirectories();

    expect($namespaces)->toBeArray()
        ->and(array_values($namespaces))->toContain('Pest\Arch')
        ->and(array_values($namespaces))->toContain('Tests')
        ->and($namespaces)->each(fn ($namespace, $directory) => expect($directory)->toBeDirectory());
});
