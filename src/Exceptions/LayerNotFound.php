<?php

declare(strict_types=1);

namespace Pest\Arch\Exceptions;

use InvalidArgumentException;
use NunoMaduro\Collision\Contracts\RenderlessEditor;
use NunoMaduro\Collision\Contracts\RenderlessTrace;

/**
 * @internal
 */
final class LayerNotFound extends InvalidArgumentException implements RenderlessEditor, RenderlessTrace // @phpstan-ignore-line
{
    /**
     * Creates a new Layer Not Found instance.
     */
    public function __construct(string $name)
    {
        parent::__construct("Layer '$name' does not exist.");
    }
}
