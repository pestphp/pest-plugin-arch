<?php

declare(strict_types=1);

namespace Tests\Fixtures\Controllers;

use Tests\Fixtures\Models\Product;

class ProductController
{
    public function index(): Product
    {
        return [
            new Product(),
        ];
    }
}
