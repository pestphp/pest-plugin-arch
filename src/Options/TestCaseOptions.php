<?php

declare(strict_types=1);

namespace Pest\Arch\Options;

/**
 * @internal
 */
final class TestCaseOptions
{
    /**
     * The ignored list of layers.
     *
     * @var array<int, string>
     */
    public array $ignore = [];

    /**
     * Ignores the given layers.
     *
     * @param  array<int, string>|string  $targetsOrDependencies
     * @return $this
     */
    public function ignore(array|string $targetsOrDependencies): self
    {
        $targetsOrDependencies = is_array($targetsOrDependencies) ? $targetsOrDependencies : [$targetsOrDependencies];

        $this->ignore = [...$this->ignore, ...$targetsOrDependencies];

        return $this;
    }
}
