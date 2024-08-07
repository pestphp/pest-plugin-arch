<?php

declare(strict_types=1);

namespace Tests\Fixtures\Controllers;

use Tests\Fixtures\Models\Product;

final class ProductController
{
    public function index(): array
    {
        return [
            new Product,
        ];
    }
}
