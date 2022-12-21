<?php

declare(strict_types=1);

namespace Pest\Arch\Expectations;

use Pest\Arch\Blueprint;
use Pest\Arch\Collections\Dependencies;
use Pest\Arch\Options\LayerOptions;
use Pest\Arch\SingleArchExpectation;
use Pest\Arch\ValueObjects\Target;
use Pest\Expectation;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @internal
 */
final class ToDependOn
{
    /**
     * Creates an "ToDependOn" expectation.
     *
     * @param  array<int, string>|string  $dependencies
     */
    public static function make(Expectation $expectation, array|string $dependencies): SingleArchExpectation
    {
        assert(is_string($expectation->value));
        /** @var Expectation<string> $expectation */
        $blueprint = Blueprint::make(
            Target::fromExpectation($expectation),
            Dependencies::fromExpectationInput($dependencies),
        );

        return SingleArchExpectation::fromExpectation(
            $expectation,
            static function (LayerOptions $options) use ($blueprint): void {
                $blueprint->expectToDependOn(
                    $options,
                    static fn (string $value, string $dependOn) => throw new ExpectationFailedException(
                        "Expecting '{$value}' to depend on '{$dependOn}'.",
                    ),
                );
            },
        );
    }
}
