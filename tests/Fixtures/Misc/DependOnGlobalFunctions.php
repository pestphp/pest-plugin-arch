<?php

namespace Tests\Fixtures\Misc;

class DependOnGlobalFunctions
{
    public function bar()
    {
        my_request_global_function();
    }
}
