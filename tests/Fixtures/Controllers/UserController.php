<?php

namespace Tests\Fixtures\Controllers;

use Tests\Fixtures\Contracts\Controllers\Indexable;
use Tests\Fixtures\Controller;

class UserController extends Controller implements Indexable
{
    public function index(): array
    {
        return [
            //
        ];
    }
}
