<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\Router;
use App\{Config, DB};

class App
{
    private static DB $db;

    public function __construct(
        private array $request,
        private Router $router,
        private Config $config
    ) {
        static::$db = new DB($config->db ?? []);
    }

    public static function db()
    {
        return static::$db;
    }

    public function run()
    {
        echo $this->router
            ->resolve($this->request['method'], $this->request['uri']);
    }
}