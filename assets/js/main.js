$(document).ready(function() {

    // Configuración de jQuery Validation
    $('#registration-form').validate({
        rules: {
            nombre: {
                required: true,
                minlength: 4
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 7
            }
        },
        messages: {
            nombre: {
                required: "Por favor, ingresa tu nombre.",
                minlength: "El nombre debe tener al menos 4 caracteres."
            },
            email: {
                required: "Por favor, ingresa tu correo electrónico.",
                email: "Por favor, ingresa un correo electrónico válido."
            },
            password: {
                required: "Por favor, ingresa una contraseña.",
                minlength: "La contraseña debe tener al menos 7 caracteres."
            }
        },
        errorElement: 'div',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.mb-3').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
        submitHandler: function(form) {
            var formData = $(form).serialize();
            var submitButton = $(form).find('button[type="submit"]');

            // Deshabilitar botón y mostrar carga
            submitButton.prop('disabled', true);
            Swal.fire({
                title: 'Procesando...',
                text: 'Por favor, espera un momento.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                type: 'POST',
                url: 'register.php',
                data: formData,
                dataType: 'json',
                success: function(response) {
                    submitButton.prop('disabled', false);
                    if (response.success) {
                        Swal.close();
                        // Oculta el formulario y muestra el mensaje de éxito
                        $('#registration-form-container').hide();
                        $('#success-message-container').show();
                    } else {
                        // Manejo de errores según el error_code
                        let iconType = 'error';
                        let titleText = 'Error en el Registro';

                        switch (response.error_code) {
                            case 1: // Email duplicado
                                iconType = 'warning';
                                titleText = 'Atención';
                                break;
                            case 2: // Datos inválidos
                                iconType = 'error';
                                titleText = 'Datos Inválidos';
                                break;
                        }

                        Swal.fire({
                            icon: iconType,
                            title: titleText,
                            text: response.message,
                            confirmButtonColor: '#0d6efd'
                        });
                    }
                },
                error: function() {
                    submitButton.prop('disabled', false);
                    // Este bloque ahora solo se ejecutará para errores graves (HTTP 500, etc.)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error del Servidor',
                        text: 'No se pudo procesar la solicitud. Inténtalo de nuevo más tarde.',
                        confirmButtonColor: '#0d6efd'
                    });
                }
            });
        }
    });

    // Botón para registrar un nuevo usuario
    $('#register-new-user').on('click', function() {
        $('#success-message-container').hide();
        $('#registration-form-container').show();
        $('#registration-form').trigger('reset');
        $('#registration-form .is-invalid').removeClass('is-invalid');
    });

});