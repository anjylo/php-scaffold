<?php

declare(strict_types=1);

namespace App\Core;

use App\Core\App;
use Generator;

class Model
{
    public function create(array $data)
    {
        $class = new static;

        $table = $class->table ?? strtolower((new \ReflectionClass($class))->getShortName());
        
        $columns = implode(', ', array_keys($data));
        $placeholders = rtrim(str_repeat('?, ', count($data)), ', ');

        $query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        
        $connection = App::db();

        try {
            $stmt = $connection->prepare($query);
            $stmt->execute($data);

            return true;
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    public function insert(array $data)
    {
        $class = new static;

        $table = $class->table ?? strtolower((new \ReflectionClass($class))->getShortName());
        
        $insert_values = [];

        $columns = implode(', ', array_keys($data[0]));

        foreach ($data as $d) {
            $placeholders[] = '(' . rtrim(str_repeat('?, ', count($d)), ', ') . ')';
            $insert_values = array_merge($insert_values ?? [], array_values($d));
        }

        $query = "INSERT INTO $table ($columns) VALUES " . implode(', ', $placeholders);

        $connection = App::db();

        try {
            if (!$connection->beginTransaction()) {
                $connection->beginTransaction();
            }

            $stmt = $connection->prepare($query);
            $stmt->execute($insert_values);

            $connection->commit();

            return true;
        } catch (\PDOException $e) {
            if (!$connection->inTransaction()) {
                $connection->rollBack();
            }

            throw new \PDOException($e->getMessage());
        }
    }

    public function fetchAll(\PDOStatement $records): Generator
    {
        foreach ($records as $key => $record) {
            yield $key => $record;
        }
    }

}