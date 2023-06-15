<?php

declare(strict_types=1);

namespace Pest\Arch\Options;

use Closure;
use Pest\Arch\SingleArchExpectation;
use PHPUnit\Architecture\Elements\ObjectDescription;

/**
 * @internal
 */
final class LayerOptions
{
    /**
     * @param  array<int, string>  $exclude
     * @param  array<int, Closure(ObjectDescription): bool>  $excludeCallbacks
     */
    private function __construct(
        public readonly array $exclude,
        public readonly array $excludeCallbacks,
    ) {
        // ...
    }

    /**
     * Creates a new Layer Options instance, with the context of the given expectation.
     */
    public static function fromExpectation(SingleArchExpectation $expectation): self
    {
        $exclude = array_merge(
            test()->arch()->ignore, // @phpstan-ignore-line
            $expectation->ignoring,
        );

        return new self($exclude, $expectation->excludeCallbacks);
    }
}
