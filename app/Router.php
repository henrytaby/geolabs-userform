<?php

namespace App;

class Router
{
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    public static function load($file)
    {
        $router = new static;
        require $file;
        return $router;
    }

    public function get($uri, $controllerAction)
    {
        $this->routes['GET'][$uri] = $controllerAction;
    }

    public function post($uri, $controllerAction)
    {
        $this->routes['POST'][$uri] = $controllerAction;
    }

    public function dispatch($uri, $method)
    {
        if (array_key_exists($uri, $this->routes[$method])) {
            [$controller, $action] = $this->routes[$method][$uri];

            $controllerInstance = new $controller();

            if (method_exists($controllerInstance, $action)) {
                return $controllerInstance->$action();
            } else {
                throw new \Exception("La acción {$action} no fue encontrada en el controlador {$controller}");
            }
        }

        throw new \Exception('No se encontró una ruta para esta URI.');
    }
}
