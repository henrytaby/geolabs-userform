# Proyecto de Registro de Usuarios en PHP

Este es un proyecto base en PHP diseñado para ser robusto, escalable y seguir buenas prácticas de desarrollo como la orientación a objetos y los principios SOLID. La funcionalidad inicial implementada es un sistema de registro de usuarios con validación en el cliente y en el servidor, utilizando AJAX para una experiencia de usuario fluida y sin recargas de página.

## ✨ Características Principales

- **Diseño Minimalista:** Interfaz de usuario limpia y centrada en el contenido, construida con **Bootstrap 5**.
- **Experiencia de Usuario Moderna:**
    - Validación de datos en tiempo real en el formulario con **jQuery Validation Plugin**.
    - Comunicación asíncrona con el servidor mediante **AJAX (jQuery)**, evitando recargas de página.
    - Notificaciones amigables e interactivas para el usuario con **SweetAlert2**.
- **Backend Robusto:**
    - Desarrollado en **PHP 8+** con una arquitectura Orientada a Objetos.
    - Gestión de variables de entorno segura a través de archivos `.env` con la librería `vlucas/phpdotenv`.
    - Respuestas del servidor en formato **JSON** para una fácil integración con el frontend.
    - Validación de datos también en el lado del servidor como segunda capa de seguridad.
- **Seguridad:**
    - Encriptación de contraseñas antes de guardarlas en la base de datos.
    - Verificación de unicidad para el correo electrónico para evitar registros duplicados.
- **Base de Datos:**
    - Interacción con **MySQL**.
    - Conexión a la base de datos gestionada a través de una clase `Database` para centralizar la configuración.

## 💻 Tecnologías Utilizadas

- **Backend:** PHP 8.0.30+, Composer
- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript, jQuery, jQuery Validation Plugin, SweetAlert2
- **Base de Datos:** MySQL

## 📂 Estructura del Proyecto

El proyecto sigue una estructura organizada para separar responsabilidades, facilitando su mantenimiento y escalabilidad.

```
/
├── app/
│   ├── Database.php      # Gestiona la conexión a la BD.
│   └── User.php          # Modelo de Usuario, lógica de negocio del usuario.
├── assets/
│   ├── css/
│   │   └── style.css     # Estilos personalizados.
│   └── js/
│       └── main.js       # Lógica del frontend (jQuery, AJAX, validaciones).
├── vendor/               # Dependencias de Composer.
├── .env                  # Archivo de variables de entorno (NO versionado).
├── .env.example          # Plantilla para el archivo .env.
├── composer.json         # Define las dependencias PHP.
├── index.php             # Vista principal con el formulario de registro.
├── register.php          # Endpoint del backend para procesar el registro.
└── README.md             # Este archivo.
```

## 🚀 Instalación y Puesta en Marcha

Sigue estos pasos para configurar el proyecto en un entorno de desarrollo local o en un servidor.

1.  **Clonar el Repositorio:**
    ```bash
    git clone <URL_DEL_REPOSITORIO>
    cd <NOMBRE_DEL_DIRECTORIO>
    ```

2.  **Instalar Dependencias:**
    Asegúrate de tener [Composer](https://getcomposer.org/) instalado y ejecuta:
    ```bash
    composer install
    ```

3.  **Configurar la Base de Datos:**
    Importa la siguiente estructura de tabla en tu base de datos MySQL.

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
    *Nota: Se ha añadido una clave única (`UNIQUE KEY`) al campo `email` para garantizar la integridad de los datos a nivel de base de datos.*

4.  **Configurar Variables de Entorno:**
    Crea una copia del archivo `.env.example` y renómbrala a `.env`.
    ```bash
    cp .env.example .env
    ```
    Luego, edita el archivo `.env` con tus credenciales y configuraciones:
    ```ini
    DB_HOST=localhost
    DB_PORT=3306
    DB_DATABASE=tu_base_de_datos
    DB_USERNAME=tu_usuario
    DB_PASSWORD=tu_contraseña

    # Llave para encriptar/desencriptar datos sensibles. Debe ser una cadena segura y aleatoria.
    ENCRYPTION_KEY=tu_llave_secreta_generada
    ```

    > **💡 ¿Cómo generar una `ENCRYPTION_KEY` segura?**
    > Puedes generar una cadena aleatoria y segura desde tu terminal usando OpenSSL (comúnmente disponible en sistemas Linux y macOS).
    > ```bash
    > openssl rand -base64 32
    > ```
    > Copia el resultado de este comando y pégalo como el valor de `ENCRYPTION_KEY` en tu archivo `.env`.

5.  **Ejecutar el Proyecto:**
    Sube todos los archivos a tu servidor web (por ejemplo, dentro de `public_html`). Asegúrate de que el servidor web tenga permisos de lectura sobre los archivos. Accede a `index.php` desde tu navegador.

## ⚙️ Funcionamiento Detallado

1.  **Carga del Formulario:** El usuario accede a `index.php`, que muestra el formulario de registro.
2.  **Validación en Cliente:** El script `assets/js/main.js` utiliza el plugin **jQuery Validate** para comprobar los campos (`nombre`, `email`, `password`) en tiempo real mientras el usuario escribe, proporcionando feedback instantáneo.
3.  **Envío con AJAX:** Al hacer clic en "Registrar", `main.js` intercepta el envío del formulario. Previene la recarga de la página y envía los datos mediante una petición `POST` de AJAX al script `register.php`.
4.  **Procesamiento en Backend (`register.php`):**
    - El script carga las variables de entorno y las clases `Database` y `User`.
    - Realiza una **validación en el servidor** para los campos requeridos y sus formatos.
    - Verifica si ya existe un usuario con el mismo `email` en la base de datos.
    - Si hay un error de validación o el email ya existe, devuelve una respuesta JSON de error: `{"status": "error", "message": "Mensaje descriptivo del error"}`.
    - Si todo es correcto, encripta la contraseña utilizando la `ENCRYPTION_KEY` del `.env` y procede a guardar el nuevo usuario en la base de datos a través de la clase `User`.
    - Devuelve una respuesta JSON de éxito: `{"status": "success", "message": "¡Registro exitoso!"}`.
5.  **Respuesta en Frontend:**
    - El callback de la petición AJAX en `main.js` recibe la respuesta JSON.
    - Utiliza **SweetAlert2** para mostrar un popup de éxito o error basado en el `status` de la respuesta.
    - Si el registro fue exitoso, el formulario se oculta y se muestra un mensaje de bienvenida con un botón para "Registrar un nuevo usuario", que reinicia la vista.

## 🌱 Futuras Mejoras y Contribuciones

Este proyecto está diseñado para crecer. Algunas ideas para futuras funcionalidades son:

- **Módulo de Login:** Implementar la autenticación de usuarios.
- **Panel de Usuario:** Crear una sección privada donde el usuario pueda ver sus datos.
- **Sistema de Ruteo:** Integrar un enrutador simple para manejar diferentes URLs (ej. `/login`, `/dashboard`) de una manera más limpia.
- **Tests:** Añadir tests unitarios y de integración para asegurar la fiabilidad del código.
- **Refactorización:** Mejorar la encriptación de la contraseña utilizando `password_hash()` y `password_verify()` de PHP, que es el estándar recomendado y no requiere una llave manual.

## 📜 Licencia

Este proyecto está bajo los términos de la licencia especificada en el archivo [LICENSE.md](LICENSE.md).

## 👨‍💻 Autor

- **Henry Alejandro Taby**
- **Email:** henry.taby@gmail.com
- **LinkedIn:** [https://www.linkedin.com/in/henrytaby/](https://www.linkedin.com/in/henrytaby/)
