<?php

use App\Controllers\UserController;
use App\Controllers\ViewController;

// Aquí se definen las rutas de la aplicación.
// El formato es: $router->[get|post](URL, [Controlador::class, 'método']);

$router->get('', [ViewController::class, 'showRegistrationForm']);
$router->post('register', [UserController::class, 'store']);