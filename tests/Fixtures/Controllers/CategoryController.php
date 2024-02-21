<?php

// declare(strict_types=1);

namespace Tests\Fixtures\Controllers;

use Tests\Fixtures\Models\Category;

final class CategoryController
{
    public function index(): array
    {
        return [
            new Category(),
        ];
    }
}
