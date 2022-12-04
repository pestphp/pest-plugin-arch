<?php

declare(strict_types=1);

namespace Pest\Arch\ValueObjects;

use Pest\Expectation;

/**
 * @internal
 */
final class Target
{
    /**
     * Creates a new Target instance.
     */
    public function __construct(
        public readonly string $value,
    ) {
        // ..
    }

    /**
     * Creates a new Target instance from the given "expectation" input.
     *
     * @param  Expectation<string>  $expectation
     */
    public static function fromExpectation(Expectation $expectation): self
    {
        assert(is_string($expectation->value)); // @phpstan-ignore-line

        return new self($expectation->value);
    }
}
