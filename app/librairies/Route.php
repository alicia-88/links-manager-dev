<?php

namespace App\Librairies;

class Route
{
    protected string $method;
    protected string $path;
    protected $handler;
    protected array $parameters = [];

    public function __construct(
        string $method,
        string $path,
        $handler
    ) {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;
    }
    public function parameters()
    {
        return $this->parameters;
    }
    public function method()
    {
        return $this->method;
    }
    public function path()
    {
        return $this->path;
    }
    public function matches(string $method, string $path)
    {

        if (
            $this->method === $method
            && $this->path === $path
        ) {
            return true;
        }
        $parameterNames = [];
        $pattern = $this->normalisePath($this->path);
        $url = $this->normalisePath($path);
        if (substr_count($url, '/') > substr_count($pattern, '/')) {
            return false;
        };

        $pattern = preg_replace_callback('#{([^}]+)}/#', function (array $found) use (&$parameterNames) {
            array_push($parameterNames, rtrim($found[1], '?'));

            if (str_ends_with($found[1], '?')) {
                return '([^/]*)(?:/?)';
            }

            return '([^/]+)/';
        }, $pattern);
        if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'dev') {
            echo "<pre>";
            echo "\r\n";
            echo 'Patern : ';
            print_r($pattern);
            echo "\r\n";
            echo 'Parameter Names : ';
            print_r($parameterNames);
            echo "<pre>";
        }

        if (
            !str_contains($pattern, '+')
            && !str_contains($pattern, '*')
        ) {
            return false;
        }

        preg_match_all("#{$pattern}#", $this->normalisePath($path), $matches);

        $parameterValues = [];


        if (count($matches[1]) > 0) {
            foreach ($matches[1] as $value) {
                if ($value) {
                    array_push($parameterValues, $value);
                    continue;
                }

                array_push($parameterValues, null);
            }

            $emptyValues = array_fill(0, count($parameterNames), false);
            $parameterValues += $emptyValues;

            $this->parameters = array_combine($parameterNames, $parameterValues);
            if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'dev') {
                echo "<pre>";
                echo "\r\n";
                echo 'Parameter Values : ';
                print_r($parameterValues);
                echo "<pre>";
            }
            return true;
        }
        return false;
    }
    public function dispatchRoute()
    {
        if (is_array($this->handler)) {
            [$class, $method] = $this->handler;

            if (isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'dev') {
                echo "<pre>";
                echo "\r\n";
                print_r($class);
                print_r($method);
                echo "<pre>";
            }
            $parameters = !empty($this->parameters()) ? $this->parameters() : null;
            return (new $class)->{$method}($parameters);
        }
        return call_user_func($this->handler);
    }
    private function normalisePath(string $path)
    {
        $path = trim($path, '/');
        $path = filter_var($path, FILTER_SANITIZE_URL);
        $path = "/{$path}/";
        $path = preg_replace('/[\/]{2,}/', '/', $path);
        return $path;
    }
}
