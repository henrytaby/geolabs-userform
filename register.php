<?php

header('Content-Type: application/json');

// Inicializar Eloquent y cargar variables de entorno
require_once __DIR__ . '/app/bootstrap.php';

use App\User;

// Función para encriptar la contraseña
function encryptPassword($password, $key)
{
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($password, 'aes-256-cbc', $key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

// Función para enviar respuestas JSON y terminar el script
function send_json($response) {
    echo json_encode($response);
    exit;
}

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        send_json(['success' => false, 'error_code' => 3, 'message' => 'Método no permitido.']);
    }

    $data = $_POST;

    // 1. Validación del lado del servidor
    $validation_error = null;
    if (empty($data['nombre']) || mb_strlen($data['nombre']) < 4) {
        $validation_error = 'El nombre es requerido y debe tener al menos 4 caracteres.';
    } elseif (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $validation_error = 'El correo electrónico no es válido.';
    } elseif (empty($data['password']) || mb_strlen($data['password']) < 7) {
        $validation_error = 'La contraseña es requerida y debe tener al menos 7 caracteres.';
    }

    if ($validation_error) {
        send_json(['success' => false, 'error_code' => 2, 'message' => $validation_error]);
    }

    // 2. Verificar si el email ya existe
    if (User::where('email', $data['email'])->exists()) {
        send_json(['success' => false, 'error_code' => 1, 'message' => 'Este correo electrónico ya está registrado.']);
    }

    // 3. Encriptar contraseña y guardar
    $encryption_key = $_ENV['ENCRYPTION_KEY'];
    if (empty($encryption_key)) {
        // Este es un error de configuración del servidor, por lo que es un error grave.
        throw new Exception("La clave de encriptación no está configurada.");
    }

    User::create([
        'nombre' => $data['nombre'],
        'apellido' => $data['apellido'] ?? '',
        'email' => $data['email'],
        'password' => encryptPassword($data['password'], $encryption_key),
        'activo' => 1
    ]);

    send_json(['success' => true, 'message' => '¡Registro exitoso!']);

} catch (Exception $e) {
    // Captura errores inesperados (ej. fallo de BD, error de configuración grave)
    http_response_code(500); // Internal Server Error
    send_json(['success' => false, 'error_code' => 3, 'message' => 'Ha ocurrido un error inesperado en el servidor.']);
}
