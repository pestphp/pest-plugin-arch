<?php

declare(strict_types=1);

namespace Pest\Arch;

use Stringable;

/**
 * @internal
 */
final class Layers implements Stringable
{
    /**
     * Creates a new Layers instance.
     *
     * @param  array<int, string>  $values
     */
    public function __construct(
        private readonly array $values,
    ) {
        // ..
    }

    /**
     * Creates a new Layers instance from the given "expectation" input.
     *
     * @param  array<int, string>|string  $values
     */
    public static function fromExpectationInput(array|string $values): self
    {
        return new self(is_string($values) ? [$values] : $values);
    }

    /**
     * Returns the Layers as an array.
     *
     * @return array<int, string>
     */
    public function toArray(): array
    {
        return $this->values;
    }

    public function __toString(): string
    {
        return implode(', ', $this->values);
    }
}
