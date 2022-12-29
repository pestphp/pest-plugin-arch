<?php

use Pest\Arch\Exceptions\LayerNotFound;
use Tests\Fixtures\Enums\Color;
use Tests\Fixtures\Enums\ColorThatDependsOnColor;
use Tests\Fixtures\Misc\DependOnGlobalFunctions;
use Tests\Fixtures\Misc\DependOnNamespacedFunctions;
use Tests\Fixtures\Misc\DependsOnVendor;
use Tests\Fixtures\NonExistingClass;
use Tests\Fixtures\Support\Collection;

it('does not include native classes', function () {
    expect(Collection::class)->toUseNothing();
});

it('does not allow empty layers', function () {
    expect(NonExistingClass::class)->toUseNothing();
})->throws(
    LayerNotFound::class,
    "Layer 'Tests\Fixtures\NonExistingClass' does not exist.",
);

it('it does include vendor dependencies', function () {
    expect(DependsOnVendor::class)
        ->toOnlyUse('Pest')
        ->toOnlyUse('Pest\Support')
        ->toOnlyUse('Pest\Support\Str');
});

it('does support enums', function () {
    expect(Color::class)->toUseNothing()
        ->and(ColorThatDependsOnColor::class)->toUse([Color::class]);
});

it('supports global functions', function () {
    expect(DependOnGlobalFunctions::class)
        ->toUse('my_request_global_function')
        ->not->toUse('Tests\Fixtures\my_request_namespaced_function');
});

it('may ignore global functions', function () {
    expect(DependOnGlobalFunctions::class)
        ->not
        ->toUse('my_request_global_function')
        ->ignoringGlobalFunctions();
})->throws(
    LayerNotFound::class,
    "Layer 'my_request_global_function' does not exist.",
);

it('supports namespaced functions', function () {
    expect(DependOnNamespacedFunctions::class)
        ->toUse('Tests\Fixtures\my_request_namespaced_function')
        ->not->toUse('my_request_global_function');
});
