<?php

declare(strict_types=1);

namespace Pest\Arch\ValueObjects;

use Pest\Expectation;

/**
 * @internal
 */
final class Dependency
{
    /**
     * Creates a new Dependency instance.
     */
    public function __construct(
        public readonly string $value,
    ) {
        // ..
    }

    /**
     * Creates a new Dependency instance from the given "expectation" input.
     */
    public static function fromString(string $value): self
    {
        return new self($value);
    }
}
