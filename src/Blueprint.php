<?php

declare(strict_types=1);

namespace Pest\Arch;

use PHPUnit\Architecture\ArchitectureAsserts;
use PHPUnit\Architecture\Elements\Layer\Layer;
use PHPUnit\Architecture\Elements\ObjectDescription;
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
    }

    /**
     * Expects the given targets to depend on the given dependencies.
     *
     * @param  callable(string, string): mixed  $failure
     */
    public function expectToDependOn(callable $failure): void
    {
        foreach ($this->targets->toArray() as $target) {
            $targetLayer = $this->layer()->leaveByNameStart($target);

            foreach ($this->dependencies->toArray() as $dependency) {
                $dependencyLayer = $this->layer()->leaveByNameStart($dependency);

                try {
                    $this->assertDoesNotDependOn($targetLayer, $dependencyLayer);
                } catch (ExpectationFailedException) {
                    continue;
                }

                $failure($targetLayer->getName(), $dependencyLayer->getName());
            }
        }
    }

    /**
     * Expects the given targets to only depend on the given dependencies.
     *
     * @param  callable(string, string): mixed  $failure
     */
    public function expectToOnlyDependOn(callable $failure): void
    {
        foreach ($this->targets->toArray() as $target) {
            $targetLayer = $this->layer()->leaveByNameStart($target);

            try {
                $this->assertOnlyDependOn(
                    $targetLayer,
                    array_map(fn (string $dependency) => $this->layer()->leaveByNameStart($dependency), $this->dependencies->toArray())
                );
            } catch (ExpectationFailedException $e) {
                $failure($targetLayer->getName(), $this->dependencies->__toString(), $e->getMessage());
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

    /**
     * Check that layerA only depends on layerB.
     *
     * @param  array<int, Layer>  $layerB
     */
    public function assertOnlyDependOn(Layer $layerA, array $layersB): void
    {
        $names = $this->getObjectsWhichUsesOnLayerAButNotFromLayersB($layerA, $layersB);

        if (count($names) > 0) {
            throw new ExpectationFailedException($names[0]);
        }

        self::assertTrue(true);
    }

    /**
     * Get objects which uses on layer A, but not from layer B
     *
     * @param  array<int, Layer>  $layerB
     * @return array<int, string>
     */
    private function getObjectsWhichUsesOnLayerAButNotFromLayersB(Layer $layerA, array $layerB): array
    {
        $result = [];

        $allowedUses = array_merge(...array_map(
            static fn (Layer $layer) => array_map(
                static fn (ObjectDescription $object) => $object->name, iterator_to_array($layer->getIterator())
            ), $layerB)
        );

        foreach ($layerA as $object) {
            foreach ($object->uses as $use) {
                if (! in_array($use, $allowedUses, true)) {
                    $result[] = $use;
                }
            }
        }

        return $result;
    }
}
