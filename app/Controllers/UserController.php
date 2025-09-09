<?php

namespace App\Controllers;

use App\Services\RegistrationService;
use App\Exceptions\ValidationException;
use App\Exceptions\UserAlreadyExistsException;
use Exception;

class UserController
{
    /**
     * Almacena un nuevo usuario llamando a la capa de servicio
     * y manejando la respuesta HTTP.
     */
    public function store()
    {
        $send_json = function ($response, $http_code = 200) {
            header('Content-Type: application/json');
            http_response_code($http_code);
            echo json_encode($response);
            exit;
        };

        $registrationService = new RegistrationService();

        try {
            $user = $registrationService->register($_POST);
            $send_json(['success' => true, 'message' => '¡Registro exitoso!']);

        } catch (ValidationException $e) {
            $send_json(['success' => false, 'error_code' => 2, 'message' => $e->getMessage()]);

        } catch (UserAlreadyExistsException $e) {
            $send_json(['success' => false, 'error_code' => 1, 'message' => $e->getMessage()]);

        } catch (Exception $e) {
            // Loggear el error real en un archivo para depuración
            error_log($e->getMessage()); 
            // Enviar una respuesta genérica al cliente
            $send_json(['success' => false, 'error_code' => 3, 'message' => 'Ha ocurrido un error inesperado en el servidor.'], 500);
        }
    }
}