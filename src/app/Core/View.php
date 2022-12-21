<?php

declare(strict_types=1);

namespace App\Core;

use App\Exceptions\View\ViewNotFoundException;

class View
{
    public function __construct(
        private string $view, 
        private array $params = []
    ) {
        
    }

    public function __toString(): string
    {
        return $this->render();
    }

    public function __get(string $name)
    {
        
    }

    public static function make(string $view, array $params = []): static
    {
        return new static($view, $params);
    }

    public function render()
    {
        try {
            $viewPath = __DIR__ . '/../../views/' . $this->view . '.php';

            if (! file_exists($viewPath)) {
                throw new ViewNotFoundException();
            }

            extract($this->params);
            
            ob_start();

            include $viewPath;
            
            return (string) ob_get_clean();
            
        } catch (ViewNotFoundException $e) {
            http_response_code(400);
            
            return self::make('error', ['message' => $e->getMessage()]);
        }
    }
}