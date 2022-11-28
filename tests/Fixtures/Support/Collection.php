<?php

namespace Tests\Fixtures\Support;

use Countable;

class Collection implements Countable
{
    public function __construct(protected array $values)
    {
        // ...
    }

    public function count(): int
    {
        return count($this->values);
    }
}
