<?php

namespace Tests\Fixtures\Misc;

use function Tests\Fixtures\my_request_namespaced_function;

class DependOnNamespacedFunctions
{
    public function bar()
    {
        my_request_namespaced_function();
    }
}
