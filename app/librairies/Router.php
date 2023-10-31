<?php

namespace App\Librairies;

use App\Librairies\Route;

class Router
{
    protected $routes = [];
    protected $errorHandlers = [];
    protected Route $current;


    public function addRoute(string $method, string $path, $handler)
    {
        $route = $this->routes[] = new Route($method, $path, $handler);
        return $route;
    }


    public function dispatch()
    {
        $paths = $this->paths();
        $requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $requestPath = $_SERVER['REQUEST_URI'] ?? '/';
        if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'dev') {
            echo "<pre>";
            echo "\r\n";
            echo 'Request Uri : ';
            print_r($_SERVER['REQUEST_URI']);
            echo "\r\n";
            echo 'Request Method : ';
            print_r($_SERVER['REQUEST_METHOD']);
            echo "<pre>";
        }

        $matching = $this->match($requestMethod, $requestPath);

        if ($matching) {
            $this->current = $matching;

            try {
                return $matching->dispatchRoute();
            } catch (\Throwable $e) {
                var_dump($e);
                return $this->dispatchError();
            }
        }
        if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'dev') {
            echo "<pre>";
            echo "\r\n";
            echo 'Request Uri : ';
            print_r($matching);
            echo "<pre>";
        }

        if (in_array($requestPath, $paths)) {
            return $this->dispatchNotAllowed();
        }
        return $this->dispatchNotFound();
    }
    private function paths()
    {
        $paths = [];
        foreach ($this->routes as $route) {
            $paths[] = $route->path();
        }
        return $paths;
    }
    private function match(string $method, string $path)
    {
        if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'dev') {
            echo "<pre>";
            echo "\r\n";
            echo 'Path : ';
            print_r($path);
            echo "<pre>";
        }
        foreach ($this->routes as $route) {
            if ($route->matches($method, $path)) {
                return $route;
            }
        }
        return null;
    }

    public function errorHandler(int $code, callable $handler)
    {
        $this->errorHandlers[$code] = $handler;
    }
    public function dispatchNotAllowed()
    {

        $this->errorHandlers[400] ??= fn () => "not allowed";
        return $this->errorHandlers[400]();
    }
    public function dispatchNotFound()
    {

        $this->errorHandlers[404] ??= fn () => "not found";
        include __DIR__ . '/../views/pages/404.php';
        return $this->errorHandlers[404]();
    }
    public function dispatchError()
    {
        $this->errorHandlers[500] ??= fn () => "server error";
        include __DIR__ . '/../views/pages/500.php';
        return $this->errorHandlers[500]();
    }
    public function redirect($path)
    {
        header(
            "Location: {$path}",
            $replace = true,
            $code = 301
        );
        exit;
    }
    public function current()
    {
        return $this->current;
    }
}
