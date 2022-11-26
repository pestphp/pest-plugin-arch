<?php

declare(strict_types=1);

namespace Pest\Arch;

use PHPUnit\Architecture\ArchitectureAsserts;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\ExpectationFailedException;

/**
 * @internal
 */
final class Blueprint
{
    use ArchitectureAsserts;

    /**
     * Creates a new Blueprint instance.
     */
    public function __construct(private readonly Layers $targets, private readonly Layers $dependencies)
    {
        // ...
    }

    /**
     * Validates the Blueprint, and calls the given callback if the validation fails.
     */
    public function assert(callable $failure): void
    {
        foreach ($this->targets->toArray() as $target) {
            $targetLayer = $this->layer()->leaveByNameStart($target);

            foreach ($this->dependencies->toArray() as $dependency) {
                $dependencyLayer = $this->layer()->leaveByNameStart($dependency);

                try {
                    $this->assertDoesNotDependOn($targetLayer, $dependencyLayer);
                } catch (ExpectationFailedException $e) {
                    continue;
                }

                $failure($targetLayer->getName(), $dependencyLayer->getName());
            }
        }
    }

    /**
     * Asserts that a condition is true.
     *
     * @throws ExpectationFailedException
     */
    public static function assertTrue(mixed $condition, string $message = ''): void
    {
        Assert::assertTrue($condition, $message);
    }

    /**
     * Asserts that two variables are not equal.
     *
     * @throws ExpectationFailedException
     */
    public static function assertNotEquals(mixed $expected, mixed $actual, string $message = ''): void
    {
        Assert::assertNotEquals($expected, $actual, $message);
    }

    /**
     * Asserts that two variables are equal.
     *
     * @throws ExpectationFailedException
     */
    public static function assertEquals(mixed $expected, mixed $actual, string $message = ''): void
    {
        Assert::assertEquals($expected, $actual, $message);
    }
}
