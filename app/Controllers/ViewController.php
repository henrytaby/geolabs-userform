<?php

namespace App\Controllers;

class ViewController
{
    /**
     * Muestra la vista del formulario de registro.
     */
    public function showRegistrationForm()
    {
        require_once __DIR__ . '/../../resources/views/register_form.php';
    }
}
