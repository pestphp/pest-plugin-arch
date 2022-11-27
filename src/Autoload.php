<?php

declare(strict_types=1);

use Pest\Arch\Blueprint;
use Pest\Arch\Dependencies;
use PHPUnit\Framework\ExpectationFailedException;

expect()->extend('toDependOn', function (array|string $targets) {
    assert(is_string($this->value));

    $blueprint = new Blueprint(
        $this->value,
        Dependencies::fromExpectationInput($targets),
    );

    $blueprint->expectToDependOn(static fn (string $value, string $dependOn) => throw new ExpectationFailedException(
        "Expecting '{$value}' to depend on '{$dependOn}'.",
    ));

    return $this;
});

expect()->extend('toOnlyDependOn', function (array|string $targets) {
    assert(is_string($this->value));

    $blueprint = new Blueprint(
        $this->value,
        Dependencies::fromExpectationInput($targets),
    );

    $blueprint->expectToOnlyDependOn(static fn (string $value, string $dependOn, string $notAllowedDependOn) => throw new ExpectationFailedException(
        empty($dependOn)
            ? "Expecting '{$value}' to not have any dependencies. However, it depends on '{$notAllowedDependOn}'."
            : "Expecting '{$value}' to depend only on '{$dependOn}'. However, it also depends on '{$notAllowedDependOn}'.",

    ));

    return $this;
});
