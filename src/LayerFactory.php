<?php

namespace Pest\Arch;

use PHPUnit\Architecture\Asserts\Dependencies\Elements\ObjectUses;
use PHPUnit\Architecture\Elements\Layer\Layer;

/**
 * @internal
 */
final class LayerFactory
{
    /**
     * Make a new layer using the given blueprint and name.
     */
    public function make(Blueprint $blueprint, string $name): Layer
    {
        $layer = $blueprint->layer()->leaveByNameStart($name);

        foreach ($layer as $object) {
            $object->uses = new ObjectUses(array_filter(
                iterator_to_array($object->uses->getIterator()),
                static fn (string $use): bool => (new \ReflectionClass($use))->isUserDefined()
            ));
        }

        return $layer;
    }
}
