<?php

declare(strict_types=1);

namespace Pest\Arch;

/**
 * @internal
 */
final class LayerOptions
{
    /**
     * @param  array<int, string>  $exclude
     */
    public function __construct(public readonly array $exclude)
    {
        // ...
    }
}
