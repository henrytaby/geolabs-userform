<?php

namespace App\Services;

use App\Exceptions\UserAlreadyExistsException;
use App\Exceptions\ValidationException;
use App\User;

class RegistrationService
{
    /**
     * Registra un nuevo usuario con los datos proporcionados.
     *
     * @param array $data
     * @return User
     * @throws ValidationException
     * @throws UserAlreadyExistsException
     * @throws \Exception
     */
    public function register(array $data): User
    {
        $this->validate($data);

        if (User::where('email', $data['email'])->exists()) {
            throw new UserAlreadyExistsException('Este correo electrónico ya está registrado.');
        }

        $password = $this->encryptPassword($data['password']);

        return User::create([
            'nombre' => $data['nombre'],
            'apellido' => $data['apellido'] ?? '',
            'email' => $data['email'],
            'password' => $password,
            'activo' => 1
        ]);
    }

    /**
     * Valida los datos de entrada.
     *
     * @param array $data
     * @throws ValidationException
     */
    private function validate(array $data): void
    {
        if (empty($data['nombre']) || mb_strlen($data['nombre']) < 4) {
            throw new ValidationException('El nombre es requerido y debe tener al menos 4 caracteres.');
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException('El correo electrónico no es válido.');
        }
        if (empty($data['password']) || mb_strlen($data['password']) < 7) {
            throw new ValidationException('La contraseña es requerida y debe tener al menos 7 caracteres.');
        }
    }

    /**
     * Encripta la contraseña.
     *
     * @param string $password
     * @return string
     * @throws \Exception
     */
    private function encryptPassword(string $password): string
    {
        $key = $_ENV['ENCRYPTION_KEY'];
        if (empty($key)) {
            throw new \Exception('La clave de encriptación no está configurada.');
        }

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($password, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($encrypted . '::' . $iv);
    }
}
