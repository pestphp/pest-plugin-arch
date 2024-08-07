<?php

namespace Tests\Fixtures\Misc;

use Pest\Support\Str;

class DependsOnVendor
{
    public function makeStrFromVendor(): Str
    {
        return new Str;
    }
}
