<?php

declare(strict_types=1);

namespace App\Exceptions\Router;

class RouteDuplicateException extends \Exception
{
    protected $message = 'Route already exist';
}