<?php

// Cargar todas las dependencias y configuraciones iniciales
require_once __DIR__ . '/app/bootstrap.php';

use App\Router;

// Cargar las definiciones de rutas
$router = Router::load('routes.php');

// Obtener la URI y el método de la petición actual
$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$method = $_SERVER['REQUEST_METHOD'];

// Despachar la ruta
try {
    $router->dispatch($uri, $method);
} catch (Exception $e) {
    // Manejo de errores (ej. ruta no encontrada)
    http_response_code(404);
    echo "<h1>404 Not Found</h1>";
    // En un proyecto real, aquí se mostraría una vista de error bonita.
    // error_log($e->getMessage()); // También se podría loggear el error.
}
