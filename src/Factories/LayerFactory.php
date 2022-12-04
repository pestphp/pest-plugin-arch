<?php

declare(strict_types=1);

namespace Pest\Arch\Factories;

use Pest\Arch\Exceptions\LayerNotFound;
use Pest\Arch\Repositories\ObjectsRepository;
use PHPUnit\Architecture\Elements\Layer\Layer;

/**
 * @internal
 */
final class LayerFactory
{
    /**
     * Creates a new Layer Factory instance.
     */
    public function __construct(
        private readonly ObjectsRepository $objectsStorage,
    ) {
        // ...
    }

    /**
     * Make a new Layer using the given name.
     *
     * @throws LayerNotFound
     */
    public function make(string $name): Layer
    {
        $objects = $this->objectsStorage->allByNamespace($name);

        $layer = (new Layer($objects))->leaveByNameStart($name);

        if ($layer->getIterator()->count() === 0) { // @phpstan-ignore-line
            throw new LayerNotFound($name);
        }

        return $layer;
    }
}
