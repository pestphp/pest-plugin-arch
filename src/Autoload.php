<?php

declare(strict_types=1);

use Pest\Arch\Blueprint;
use Pest\Arch\Layers;
use PHPUnit\Framework\ExpectationFailedException;

expect()->extend('toDependOn', function (array|string $targets) {
    $blueprint = new Blueprint(
        Layers::fromExpectationInput($this->value),
        Layers::fromExpectationInput($targets),
    );

    $blueprint->assert(fn (string $value, string $dependOn) => throw new ExpectationFailedException(
        "Expecting '{$value}' to depend on '{$dependOn}'.",
    ));

    return $this;
});
