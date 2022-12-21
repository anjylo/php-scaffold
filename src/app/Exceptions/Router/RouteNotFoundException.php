<?php

declare(strict_types=1);

namespace App\Exceptions\Router;

class RouteNotFoundException extends \Exception
{
    protected $message = 'Route does not exist';
}