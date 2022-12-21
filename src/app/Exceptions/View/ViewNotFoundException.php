<?php

declare(strict_types=1);

namespace App\Exceptions\View;

class ViewNotFoundException extends \Exception
{
    protected $message = 'View not found';
}