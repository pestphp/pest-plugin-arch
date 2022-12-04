<?php

declare(strict_types=1);

namespace Pest\Arch\Exceptions;

use InvalidArgumentException;

/**
 * @internal
 */
final class LayerNotFound extends InvalidArgumentException // @phpstan-ignore-line
{
    /**
     * Creates a new Layer Not Found instance.
     */
    public function __construct(string $name)
    {
        parent::__construct("Layer '$name' does not exist.");
    }
}
