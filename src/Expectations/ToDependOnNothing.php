<?php

declare(strict_types=1);

namespace Pest\Arch\Expectations;

use Pest\Arch\Contracts\ArchExpectation;
use Pest\Expectation;

/**
 * @internal
 */
final class ToDependOnNothing
{
    /**
     * Creates an "ToDependOnNothing" expectation.
     */
    public static function make(Expectation $expectation): ArchExpectation
    {
        return ToOnlyDependOn::make($expectation, []);
    }
}
