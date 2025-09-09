<?php

// Cargar todas las dependencias y configuraciones iniciales
require_once __DIR__ . '/app/bootstrap.php';

use App\Router;

// Cargar las definiciones de rutas
$router = Router::load('routes.php');

// --- Lógica de Enrutamiento para Subdirectorios ---

// Obtener la ruta de la petición (ej. /henryt/register)
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Obtener la ruta del script base (ej. /henryt/index.php)
$scriptName = $_SERVER['SCRIPT_NAME'];

// Deducir el directorio base de la aplicación (ej. /henryt)
$basePath = dirname($scriptName);
if ($basePath === '/' || $basePath === '\\') {
    $basePath = '';
}

// Eliminar el directorio base de la ruta de la petición para obtener la URI limpia
$uri = $requestUri;
if (strlen($basePath) > 0 && strpos($requestUri, $basePath) === 0) {
    $uri = substr($requestUri, strlen($basePath));
}

// Limpiar la URI para el matching
$uri = trim($uri, '/');

// --- Fin de la Lógica de Enrutamiento ---

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