<?php

use Pest\Arch\Exceptions\LayerNotFound;
use Tests\Fixtures\Enums\Color;
use Tests\Fixtures\Enums\ColorThatDependsOnColor;
use Tests\Fixtures\Misc\DependsOnVendor;
use Tests\Fixtures\NonExistingClass;
use Tests\Fixtures\Support\Collection;

it('does not include native classes', function () {
    expect(Collection::class)->toDependOnNothing();
});

it('does not allow empty layers', function () {
    expect(NonExistingClass::class)->toDependOnNothing();
})->throws(
    LayerNotFound::class,
    "Layer 'Tests\Fixtures\NonExistingClass' does not exist.",
);

it('it does include vendor dependencies', function () {
    expect(DependsOnVendor::class)
        ->toOnlyDependOn('Pest')
        ->toOnlyDependOn('Pest\Support')
        ->toOnlyDependOn('Pest\Support\Str');
});

it('does support enums', function () {
    expect(Color::class)->toDependOnNothing()
        ->and(ColorThatDependsOnColor::class)->toDependOn([Color::class]);
});
