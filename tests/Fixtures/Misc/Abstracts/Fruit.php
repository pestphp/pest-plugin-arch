<?php

namespace Tests\Fixtures\Misc\Abstracts;

abstract class Fruit
{
    public function edible(): bool
    {
        return true;
    }
}
