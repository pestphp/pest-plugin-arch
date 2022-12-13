<?php

declare(strict_types=1);

namespace Pest\Arch\Expectations;

use Pest\Arch\ArchExpectation;
use Pest\Expectation;

/**
 * @internal
 */
final class ToDependOnNothing
{
    /**
     * @param  Expectation<string>  $expectation
     * @return ArchExpectation<string>
     */
    public static function make(Expectation $expectation): ArchExpectation
    {
        return ToOnlyDependOn::make($expectation, []);
    }
}
