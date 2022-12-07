<?php

declare(strict_types=1);

namespace Pest\Arch\Factories;

use Pest\Arch\VendorObjectDescription;
use PHPUnit\Architecture\Asserts\Dependencies\Elements\ObjectUses;
use PHPUnit\Architecture\Elements\ObjectDescription;
use PHPUnit\Architecture\Services\ServiceContainer;
use ReflectionClass;
use ReflectionFunction;
use RuntimeException;

/**
 * @internal
 */
final class ObjectDescriptionFactory
{
    /**
     * Whether the Service Container class has been initialized.
     */
    private static bool $serviceContainerInitialized = false;

    /**
     * Makes a new Object Description instance, is possible.
     */
    public static function make(string $filename): ObjectDescription|null
    {
        self::ensureServiceContainerIsInitialized();

        $object = null;

        $isFromVendor = str_contains($filename, '/vendor/');

        try {
            $object = $isFromVendor
                ? VendorObjectDescription::make($filename)
                : ServiceContainer::$descriptionClass::make($filename);
        } catch (RuntimeException $e) {
            if (! str_contains($e->getFile(), 'phpdocumentor')) {
                throw $e;
            }
        }

        if ($object === null) {
            return null;
        }

        if (! $isFromVendor) {
            $object->uses = new ObjectUses(array_values(array_filter(
                iterator_to_array($object->uses->getIterator()),
                static fn (string $use): bool => self::isUserDefined($use),
            )));
        }

        return $object;
    }

    /**
     * Ensures the Service Container class is initialized.
     */
    private static function ensureServiceContainerIsInitialized(): void
    {
        if (! self::$serviceContainerInitialized) {
            ServiceContainer::init();

            self::$serviceContainerInitialized = true;
        }
    }

    /**
     * Checks if the given use is "user defined".
     */
    private static function isUserDefined(string $use): bool
    {
        return match (true) {
            function_exists($use) => (new ReflectionFunction($use))->isUserDefined(),
            class_exists($use) => (new ReflectionClass($use))->isUserDefined(),
            interface_exists($use) => (new ReflectionClass($use))->isUserDefined(),
            // ...

            default => true,
        };
    }
}
