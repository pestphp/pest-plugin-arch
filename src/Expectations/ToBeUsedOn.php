<?php

declare(strict_types=1);

namespace Pest\Arch\Expectations;

use Pest\Arch\GroupArchExpectation;
use Pest\Expectation;

/**
 * @internal
 */
final class ToBeUsedOn
{
    /**
     * Creates an "ToBeUsedOn" expectation.
     *
     * @param  array<int, string>|string  $targets
     */
    public static function make(Expectation $expectation, array|string $targets): GroupArchExpectation
    {
        assert(is_string($expectation->value));
        $targets = is_string($targets) ? [$targets] : $targets;

        return GroupArchExpectation::fromExpectations(
            $expectation,
            array_map(
                static fn ($target): \Pest\Arch\SingleArchExpectation => ToDependOn::make(expect($target), $expectation->value), $targets
            )
        );
    }
}
