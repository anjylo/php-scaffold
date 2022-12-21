<?php

declare(strict_types=1);

namespace App\Controllers;

use App\App;
use App\Core\View;

class TestController
{
    public function index()
    {
        return View::make('index', ['message' => 'hello']);
    }
}