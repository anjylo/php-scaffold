<?php

declare(strict_types=1);

namespace App;

use PDOException;
use PDO;

/**
 *  @mixin PDO
 */
class DB
{
    private PDO $pdo;

    public function __construct(array $config)
    {
        if(isset($config['database'])) {
            $defaultSettings = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH
            ];

            try {
                $driver     = $config['driver'];
                $host       = ':host=' . $config['host'];
                $db         = ';dbname=' . $config['database'];
                $user       = $config['user'];
                $password   = $config['password'];
    
                $this->pdo  = new PDO($driver . $host . $db, $user, $password, $defaultSettings);
    
            } catch (PDOException $e) {
                throw new PDOException($e->getMessage());
            }
        }
    }

    public function __call(string $name, array $arguments)
    {
        return call_user_func_array([$this->pdo, $name], $arguments);
    }
}