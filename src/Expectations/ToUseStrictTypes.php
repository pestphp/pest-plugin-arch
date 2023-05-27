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
use PHPUnit\Architecture\Elements\ObjectDescription;

/**
 * @internal
 */
final class ToUseStrictTypes
{
    /**
     * Creates an "ToUseStrictTypes" expectation.
     */
    public static function make(Expectation $expectation, bool $strictTypes = true): SingleArchExpectation
    {
        assert(is_string($expectation->value) || is_array($expectation->value));
        /** @var Expectation<array<int, string>|string> $expectation */
        $blueprint = Blueprint::make(
            Targets::fromExpectation($expectation),
            Dependencies::fromExpectationInput([]),
        );

        return SingleArchExpectation::fromExpectation(
            $expectation,
            static function (LayerOptions $options) use ($blueprint, $strictTypes): void {
                $blueprint->expect(
                    fn (ObjectDescription $object) => str_contains((string) file_get_contents($object->path), 'declare(strict_types=1);'),
                    $options,
                    static fn (Violation $violation) => throw new ArchExpectationFailedException(
                        $violation,
                        sprintf(
                            "Expecting '%s' to %suse 'declare(strict_types=1);' declaration.",
                            $violation->path,
                            $strictTypes ? '' : 'not ',
                        ),
                    ),
                    $strictTypes,
                );
            },
        );
    }
}
