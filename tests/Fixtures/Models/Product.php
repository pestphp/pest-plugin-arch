<?php

declare(strict_types=1);

namespace Tests\Fixtures\Models;

use Tests\Fixtures\Contracts\Models\Barable;
use Tests\Fixtures\Contracts\Models\Fooable;
use Tests\Fixtures\Contracts\Models\Storable;

class Product implements Barable, Fooable, Storable
{
    // ...
}
