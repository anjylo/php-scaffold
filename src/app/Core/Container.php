<?php

declare(strict_types=1);

namespace App\Core;

use Psr\Container\ContainerInterface;

use App\Exceptions\Container\ContainerException;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function set(string $id, callable|string $concrete)
    {
        $this->entries[$id] = $concrete;
    }

    public function get(string $id)
    {
        if ($this->has($id)) {
            $entry = $this->entries[$id];

            if (is_callable($entry)) {
                return $entry($this);
            }

            $id = $entry;
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function resolve(string $id)
    {
        /**
         * Autowiring using Reflection API
         * 
         */

        // Inspect the class that we are trying to get from the container 
        $reflectionClass = new \ReflectionClass($id);
        
        if (! $reflectionClass->isInstantiable()) {
            throw new ContainerException("Class $id is not instantiable");
        }

        // Inspect the constructor of the class 
        $constructor = $reflectionClass->getConstructor();

        if (! $constructor) {
            return new $id;
        }

        // Inspect the constructor parameters (dependencies)
        $parameters = $constructor->getParameters();

        if (! $parameters) {
            return new $id;
        }
 
        // If the constructor parameter is a class then try to resolve that class using the container
        $dependencies = array_map(
            function(\ReflectionParameter $param) use ($id) {
                $name = $param->getName();
                $type = $param->getType();
    
                if (! $type) {
                    throw new ContainerException(
                        "Failed to resolved class $id because param $name is missing a type hint"
                    );
                }

                if ($type instanceof \ReflectionUnionType) {
                    throw new ContainerException(
                        "Failed to resolved class $id because of union type param $name"
                    );
                }

                if ($type instanceof \ReflectionNamedType && ! $type->isBuiltin()) {
                    return $this->get($type->getName());
                }

                throw new ContainerException(
                    "Failed to resolved class $id because of invalid param $name"
                );

        }, $parameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
} 