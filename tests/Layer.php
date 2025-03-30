<?php

use Tests\Fixtures\Domains\A\Contracts\Models\Bazable as BazableDomainA;
use Tests\Fixtures\Domains\A\Models\Article as ArticleDomainA;
use Tests\Fixtures\Domains\B\Contracts\Models\Bazable as BazableDomainB;
use Tests\Fixtures\Domains\B\Models\Article as ArticleDomainB;
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

it('does allow empty layers', function ($layer) {
    expect($layer)->toUseNothing();
})->with(['App\Repositories', NonExistingClass::class, 'ray']);

it('does include vendor dependencies', function () {
    expect(DependsOnVendor::class)
        ->toOnlyUse('Pest')
        ->toOnlyUse('Pest\Support')
        ->toOnlyUse('Pest\Support\Str');
});

it('loads namespaces', function () {
    expect('Tests\Fixtures\Domains\A\Models')
        ->getTargets()
        ->toBe([ArticleDomainA::class]);
});

it('loads namespaces using wildcards', function () {
    expect('Tests\Fixtures\Domains\*\Models')
        ->getTargets()
        ->toBe([ArticleDomainA::class, ArticleDomainB::class]);
});

it('loads namespaces using multiple wildcards', function () {
    expect('Tests\Fixtures\Domains\*\*\Models')
        ->getTargets()
        ->toBe([BazableDomainA::class, BazableDomainB::class]);
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
});

it('supports namespaced functions', function () {
    expect(DependOnNamespacedFunctions::class)
        ->toUse('Tests\Fixtures\my_request_namespaced_function')
        ->not->toUse('my_request_global_function');
});

it('dependency does not support paths', function () {
    expect('tests/Fixtures/my_request_namespaced_function')
        ->toUse('my_request_global_function');
})->throws(
    \PHPUnit\Framework\ExpectationFailedException::class,
    "Expecting 'tests/Fixtures/my_request_namespaced_function' to be a class name or namespace, but it contains a path.",
);

it('targets does not support paths', function () {
    expect('Tests\Fixtures\my_request_namespaced_function')
        ->toUse('tests/Fixtures/my_request_namespaced_function');
})->throws(
    \PHPUnit\Framework\ExpectationFailedException::class,
    "Expecting 'tests/Fixtures/my_request_namespaced_function' to be a class name or namespace, but it contains a path.",
);
