<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Interfaces\GeneratorInterface;

class ProfileController implements GeneratorInterface
{
    public function generate()
    {
        return rand(1, 1000000);
    }
}