<?php

declare(strict_types=1);

namespace Pest\Arch\Expectations;

use Pest\Arch\Blueprint;
use Pest\Arch\Collections\Dependencies;
use Pest\Arch\Exceptions\ArchExpectationFailedException;
use Pest\Arch\Options\LayerOptions;
use Pest\Arch\SingleArchExpectation;
use Pest\Arch\ValueObjects\Targets;
use Pest\Arch\ValueObjects\Violation;
use Pest\Expectation;

/**
 * @internal
 */
final class NotToUseStrictTypes
{
    /**
     * Creates an "ToUseStrictTypes" expectation.
     */
    public static function make(Expectation $expectation): SingleArchExpectation
    {
        assert(is_string($expectation->value) || is_array($expectation->value));
        /** @var Expectation<array<int, string>|string> $expectation */
        $blueprint = Blueprint::make(
            Targets::fromExpectation($expectation),
            Dependencies::fromExpectationInput([]),
        );

        return SingleArchExpectation::fromExpectation(
            $expectation,
            static function (LayerOptions $options) use ($blueprint): void {
                $blueprint->expectToUseStrictTypes(
                    $options,
                    static fn (Violation $violation) => throw new ArchExpectationFailedException(
                        $violation,
                        "Expecting '{$violation->path}' to not use 'declare(strict_types=1);' declaration.",
                    ),
                    false
                );
            },
        );
    }
}
