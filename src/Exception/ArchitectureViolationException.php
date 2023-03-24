<?php

declare(strict_types=1);

namespace Pest\Arch\Exception;

use Exception;
use NunoMaduro\Collision\Contracts\RenderableOnCollisionEditor;
use Pest\Arch\ValueObjects\ViolationReference;
use Whoops\Exception\Frame;

final class ArchitectureViolationException extends Exception implements RenderableOnCollisionEditor //@phpstan-ignore-line
{
    public function __construct(string $message, private readonly ViolationReference $reference)
    {
        parent::__construct($message);
    }

    public function toCollisionEditor(): Frame
    {
        return new Frame([
            'file' => $this->reference->path,
            'line' => $this->reference->start,
        ]);
    }
}
