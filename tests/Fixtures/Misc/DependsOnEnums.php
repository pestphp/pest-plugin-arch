<?php

namespace Tests\Fixtures\Misc;

use Tests\Fixtures\Enums\Color;

class DependsOnEnums
{
    public function makeColorFromEnum(): Color
    {
        return Color::BLUE;
    }
}
