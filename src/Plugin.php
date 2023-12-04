<?php

namespace Pest\Arch;

use Pest\Arch\Support\CommandArgumentMode;
use Pest\Contracts\Plugins\Bootable;
use Pest\Contracts\Plugins\HandlesArguments;
use Pest\Plugins\Concerns\HandleArguments;

/**
 * @internal
 */
final class Plugin implements HandlesArguments, Bootable
{
    use HandleArguments;

    private CommandArgumentMode $mode;

    public function handleArguments(array $arguments): array
    {
        if (! $this->hasArgument('--arch', $arguments)) {
            return $arguments;
        }

        return $this->popArgument('--arch', $arguments);
    }

    public function boot(): void
    {
    }
}
