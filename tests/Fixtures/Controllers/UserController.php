<?php

namespace Tests\Fixtures\Controllers;

use Tests\Fixtures\Contracts\Controllers\Indexable;
use Tests\Fixtures\Controller;
use Tests\Fixtures\HasResponses;

class UserController extends Controller implements Indexable
{
    use HasResponses;

    public function index(): array
    {
        return [
            //
        ];
    }
}
