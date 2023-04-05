<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;

class TestController
{
    public function __construct(protected \App\Interfaces\GeneratorInterface $generator)
    {
        
    }

    public function index()
    {
        return View::make('index', ['data' => $this->generator->generate()]);
    }
}