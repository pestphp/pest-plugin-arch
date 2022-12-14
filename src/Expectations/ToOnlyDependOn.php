<?php

declare(strict_types=1);

namespace Pest\Arch\Expectations;

use Pest\Arch\ArchExpectation;
use Pest\Arch\Blueprint;
use Pest\Arch\Collections\Dependencies;
use Pest\Arch\LayerOptions;
use Pest\Arch\ValueObjects\Target;
use Pest\Expectation;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @internal
 */
final class ToOnlyDependOn
{
    /**
     * @param  Expectation<mixed>  $expectation
     * @param  array<int, string>|string  $targets
     * @return ArchExpectation<string>
     */
    public static function make(Expectation $expectation, array|string $targets): ArchExpectation
    {
        assert(is_string($expectation->value));
        /** @var Expectation<string> $expectation */
        $blueprint = Blueprint::make(
            Target::fromExpectation($expectation),
            Dependencies::fromExpectationInput($targets),
        );

        return ArchExpectation::fromExpectation($expectation, static function (LayerOptions $options) use ($blueprint): void {
            $blueprint->expectToOnlyDependOn(
                $options, static fn (string $value, string $dependOn, string $notAllowedDependOn) => throw new ExpectationFailedException(
                    $dependOn === ''
                        ? "Expecting '{$value}' to depend on nothing. However, it depends on '{$notAllowedDependOn}'."
                        : "Expecting '{$value}' to only depend on '{$dependOn}'. However, it also depends on '{$notAllowedDependOn}'.",

                ));
        });
    }
}
