<?php

declare(strict_types=1);

namespace Pest\Arch;

use Closure;
use Pest\Expectation;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @internal
 *
 * @template TValue
 *
 * @mixin Expectation<TValue>
 */
final class ArchExpectation
{
    /**
     * The "opposite" callback.
     */
    private ?Closure $opposite = null;

    /**
     * Whether the expectation has been verified.
     */
    private bool $lazyExpectationVerified = false;

    /**
     * The ignored list of layers.
     *
     * @var array<int, string>
     */
    private array $ignoring = [];

    /**
     * Creates a new Arch Expectation instance.
     */
    private function __construct(private readonly Expectation $expectation, private readonly Closure $lazyExpectation)
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
        $targetsOrDependencies = is_array($targetsOrDependencies) ? $targetsOrDependencies : [$targetsOrDependencies];

        $this->ignoring = [...$this->ignoring, ...$targetsOrDependencies];

        return $this;
    }

    /**
     * Sets the "opposite" callback.
     */
    public function opposite(Closure $callback): self
    {
        $this->opposite = $callback;

        return $this;
    }

    /**
     * Creates a new Arch Expectation instance from the given expectation.
     */
    public static function fromExpectation(Expectation $expectation, Closure $lazyExpectation): self
    {
        return new self($expectation, $lazyExpectation);
    }

    /**
     * Proxies the call to the expectation.
     *
     * @param  array<array-key, mixed>  $arguments
     * @return Expectation<TValue>
     */
    public function __call(string $name, array $arguments): mixed
    {
        $this->ensureLazyExpectationIsVerified();

        return $this->expectation->$name(...$arguments); // @phpstan-ignore-line
    }

    /**
     * Proxies the call to the expectation.
     *
     * @return Expectation<TValue>
     */
    public function __get(string $name): mixed
    {
        $this->ensureLazyExpectationIsVerified();

        return $this->expectation->$name; // @phpstan-ignore-line
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
        if (! $this->lazyExpectationVerified) {
            $this->lazyExpectationVerified = true;

            $e = null;

            try {
                ($this->lazyExpectation)(new LayerOptions(
                    $this->ignoring,
                ));
            } catch (ExpectationFailedException $e) {
                if ($this->opposite === null) {
                    throw $e;
                }
            }
            if (! $this->opposite instanceof Closure) {
                return;
            }
            if (! is_null($e)) {
                return;
            }

            ($this->opposite)(); // @phpstan-ignore-line
        }
    }
}
