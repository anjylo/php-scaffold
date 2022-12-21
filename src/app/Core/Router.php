<?php

declare(strict_types=1);

namespace App\Core;

use App\Exceptions\Router\{RouteDuplicateException, RouteNotFoundException};

use App\Core\View;

class Router
{
    private array $routes;

    public function resolve(string $method, string $route)
    {
        $method = strtolower($method);

        $route = explode('?', $route)[0];
        $action = $this->routes[$method][$route] ?? null;
        
        try {
            if (!$action) {
                throw new RouteNotFoundException();
            }

            if (is_callable($action)) {
                return call_user_func($action);
            }

            if (is_array($action)) {
                [$class, $method] = $action;

                $class = new $class();

                if (method_exists($class, $method)) {
                    return call_user_func_array([$class, $method], []);
                }
            }

            throw new RouteNotFoundException();

        } catch (RouteNotFoundException $e) {
            http_response_code(404);
            
            return $e->getMessage();
        }
    }

    public function get(string $route, callable|array $action)
    {
        return $this->register('get', $route, $action);
    }

    public function post(string $route, callable|array $action)
    {
        return $this->register('post', $route, $action);
    }

    private function register(string $method, string $route, callable|array $action)
    {
        try {
            if (isset($this->routes[$method][$route])) {
                throw new RouteDuplicateException();
            }

            $this->routes[$method][$route] = $action;

            return $this;
        } catch (RouteDuplicateException $e) {
            return $e->getMessage();
        }
    }
}