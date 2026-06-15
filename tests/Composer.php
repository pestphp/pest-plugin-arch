<?php

use Composer\Autoload\ClassLoader;
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

it('retrieves all non-vendor namespaces with directories', function () {
    $all = Composer::allNamespacesWithDirectories();
    $user = Composer::userNamespacesWithDirectories();

    expect($all)->toBeArray()
        ->and(array_values($all))->toContain('Pest\Arch')
        ->and(array_values($all))->toContain('Tests')
        ->and($all)->each(fn ($namespace, $directory) => expect($directory)->toBeDirectory())
        ->and(array_keys($user))->each(fn ($directory) => expect($all)->toHaveKey($directory->value));
});

it('removes composer loaders registered during a callback', function () {
    $vendorDirectory = __DIR__.'/Fixtures/ScopedVendor';
    $loader = new ClassLoader($vendorDirectory);

    $result = Composer::withoutNewLoaders(function () use ($loader, $vendorDirectory): string {
        $loader->register(true);

        expect(ClassLoader::getRegisteredLoaders())->toHaveKey($vendorDirectory);

        return 'done';
    });

    expect($result)->toBe('done')
        ->and(ClassLoader::getRegisteredLoaders())->not->toHaveKey($vendorDirectory);
});
