# Proyecto de Registro de Usuarios en PHP

Este es un proyecto base en PHP diseñado para ser robusto, escalable y seguir buenas prácticas de desarrollo como la orientación a objetos y los principios SOLID. La funcionalidad inicial implementada es un sistema de registro de usuarios con validación en el cliente y en el servidor, utilizando AJAX para una experiencia de usuario fluida y sin recargas de página.

## ✨ Características Principales

- **Diseño Minimalista:** Interfaz de usuario limpia y centrada en el contenido, construida con **Bootstrap 5**.
- **Backend Robusto:**
    - Desarrollado en **PHP 8+** con una arquitectura Orientada a Objetos.
    - **ORM Eloquent:** Interacción con la base de datos a través de modelos y una sintaxis expresiva, eliminando SQL manual.
    - Gestión de variables de entorno segura a través de archivos `.env` con la librería `vlucas/phpdotenv`.
    - Validación de datos en el lado del servidor como segunda capa de seguridad.
- **Experiencia de Usuario (UX) Avanzada:**
    - Validación de datos en tiempo real en el cliente con **jQuery Validation Plugin**.
    - Comunicación asíncrona con el servidor mediante **AJAX (jQuery)**.
    - **Feedback de Carga:** Bloqueo de UI y mensaje "Procesando..." durante el envío para mejorar la UX y evitar envíos duplicados.
    - **Notificaciones Inteligentes:** Notificaciones amigables con **SweetAlert2**.
    - **Manejo de Errores Específico:** Respuestas JSON con códigos de error para un feedback preciso (ej. advertencia para emails duplicados, error para datos inválidos).
- **Seguridad:**
    - Encriptación de contraseñas antes de guardarlas en la base de datos.
    - Verificación de unicidad para el correo electrónico.
    - Uso de `.gitignore` para mantener datos sensibles fuera del repositorio.

## 💻 Tecnologías Utilizadas

- **Backend:** PHP 8.0.30+, Composer, **Eloquent ORM**
- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript, jQuery, jQuery Validation Plugin, SweetAlert2
- **Base de Datos:** MySQL

## 📂 Estructura del Proyecto

El proyecto sigue una estructura organizada para separar responsabilidades, facilitando su mantenimiento y escalabilidad.

```
/
├── app/
│   ├── bootstrap.php     # Inicializa la conexión de la BD para Eloquent.
│   └── User.php          # Modelo de Eloquent que representa la tabla `usuario`.
├── assets/
│   ├── css/style.css     # Estilos personalizados.
│   └── js/main.js        # Lógica del frontend (jQuery, AJAX, SweetAlert2).
├── vendor/               # Dependencias de Composer (Ignorado por Git).
├── .env                  # Archivo de variables de entorno (Ignorado por Git).
├── .env.example          # Plantilla para el archivo .env.
├── .gitignore            # Define archivos y carpetas a ignorar por Git.
├── composer.json         # Define las dependencias PHP.
├── index.php             # Vista principal con el formulario de registro.
├── register.php          # Endpoint del backend para procesar el registro.
├── LICENSE.md            # Archivo de licencia del proyecto.
└── README.md             # Este archivo.
```

## 🚀 Instalación y Puesta en Marcha

1.  **Clonar el Repositorio:** `git clone <URL_DEL_REPOSITORIO>`
2.  **Instalar Dependencias:** `composer install`
3.  **Configurar Base de Datos:** Importa el script SQL proporcionado abajo en tu base de datos MySQL.
4.  **Configurar Entorno:** Copia `.env.example` a `.env` (`cp .env.example .env`) y rellena las variables de la base de datos y la `ENCRYPTION_KEY`.

    > **💡 ¿Cómo generar una `ENCRYPTION_KEY` segura?**
    > Ejecuta `openssl rand -base64 32` en tu terminal y copia el resultado.

5.  **Ejecutar:** Sube los archivos a tu servidor web y accede a `index.php`.

```sql
CREATE TABLE `usuario`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `activo` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `email_unique` (`email`)
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;
```

## ⚙️ Funcionamiento Detallado

1.  **Validación en Cliente:** `main.js` valida el formulario en tiempo real.
2.  **Envío con Carga:** Al enviar, se deshabilita el botón y un popup de "Procesando..." bloquea la pantalla.
3.  **Procesamiento en Backend (`register.php`):**
    - Se inicializa Eloquent a través de `app/bootstrap.php`.
    - Se valida la data. Si falla, se devuelve un JSON con `success: false` y `error_code: 2`.
    - Se comprueba si el email existe usando `User::where(...)->exists()`. Si existe, devuelve `error_code: 1`.
    - Si todo es correcto, se encripta la contraseña y se crea el usuario con `User::create([...])`.
    - Se devuelve un JSON con `success: true`.
    - Para errores de validación, el servidor siempre responde con HTTP 200, comunicando el error dentro del JSON.
4.  **Respuesta en Frontend:**
    - El callback de AJAX en `main.js` recibe la respuesta.
    - Se vuelve a habilitar el botón de envío.
    - Si `response.success` es `true`, se cierra el popup de carga y se muestra la vista de éxito.
    - Si es `false`, el popup de carga se reemplaza por un SweetAlert de **advertencia** (email duplicado) o de **error** (datos inválidos), según el `error_code`.
    - El popup de "Error de Servidor" solo aparece ante fallos graves (ej. HTTP 500).

## 🌱 Futuras Mejoras y Contribuciones

- **Módulo de Login:** Implementar la autenticación de usuarios.
- **Panel de Usuario:** Crear una sección privada donde el usuario pueda ver sus datos.
- **Sistema de Ruteo:** Integrar un enrutador para manejar diferentes URLs (ej. `/login`, `/dashboard`).
- **Tests:** Añadir tests unitarios y de integración.
- **Refactorización:** Mejorar la encriptación de la contraseña utilizando `password_hash()` y `password_verify()` de PHP, que es el estándar recomendado.

## 📜 Licencia

Este proyecto está bajo los términos de la licencia especificada en el archivo [LICENSE.md](LICENSE.md).

## 👨‍💻 Autor

- **Henry Alejandro Taby**
- **Email:** henry.taby@gmail.com
- **LinkedIn:** [https://www.linkedin.com/in/henrytaby/](https://www.linkedin.com/in/henrytaby/)