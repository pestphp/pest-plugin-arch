<?php

declare(strict_types=1);

namespace Pest\Arch;

use Pest\Expectation;

/**
 * @internal
 */
final class GroupArchExpectation implements Contracts\ArchExpectation
{
    /**
     * Creates a new Arch Expectation instance.
     *
     * @param  array<int, SingleArchExpectation>  $expectations
     */
    private function __construct(private readonly array $expectations)
    {
        // ...
    }

    /**
     * Ignores the given layers.
     *
     * @param  array<int, string>|string  $targetsOrDependencies
     * @return $this
     */
    public function ignoring(array|string $targetsOrDependencies): self
    {
        foreach ($this->expectations as $expectation) {
            $expectation->ignoring($targetsOrDependencies);
        }

        return $this;
    }

    /**
     * Ignores the global "user defined" functions.
     *
     * @return $this
     */
    public function ignoringGlobalFunctions(): self
    {
        foreach ($this->expectations as $expectation) {
            $expectation->ignoringGlobalFunctions();
        }

        return $this;
    }

    /**
     * Creates a new Arch Expectation instance from the given expectations.
     *
     * @param  array<int, SingleArchExpectation>  $expectations
     */
    public static function fromExpectations(array $expectations): self
    {
        return new self($expectations);
    }

    /**
     * Proxies the call to the first expectation.
     *
     * @param  array<array-key, mixed>  $arguments
     * @return Expectation<string>
     */
    public function __call(string $name, array $arguments): mixed
    {
        $this->ensureLazyExpectationIsVerified();

        return $this->expectations[0]->$name(...$arguments); // @phpstan-ignore-line
    }

    /**
     * Proxies the call to the expectation.
     *
     * @return Expectation<string>
     */
    public function __get(string $name): mixed
    {
        $this->ensureLazyExpectationIsVerified();

        return $this->expectations[0]->$name; // @phpstan-ignore-line
    }

    /**
     * Ensures the lazy expectation is verified when the object is destructed.
     */
    public function __destruct()
    {
        $this->ensureLazyExpectationIsVerified();
    }

    /**
     * Ensures the lazy expectation is verified.
     */
    private function ensureLazyExpectationIsVerified(): void
    {
        foreach ($this->expectations as $expectation) {
            $expectation->ensureLazyExpectationIsVerified();
        }
    }
}
