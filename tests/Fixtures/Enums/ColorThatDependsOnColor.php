<?php

namespace Tests\Fixtures\Enums;

enum ColorThatDependsOnColor: string
{
    public function color(): Color
    {
        return Color::BLUE;
    }
}
