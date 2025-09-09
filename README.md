# Proyecto Base PHP con Arquitectura MVC y SOLID

Este es un proyecto PHP que sirve como una base robusta y profesional para aplicaciones web. Su arquitectura está cuidadosamente diseñada siguiendo el patrón **Modelo-Vista-Controlador (MVC)** y los principios **SOLID**, con un enfoque en la separación de responsabilidades, la escalabilidad y la mantenibilidad.

La funcionalidad inicial es un sistema de registro de usuarios con una experiencia de usuario moderna y fluida.

## ✨ Arquitectura y Características Clave

- **Arquitectura MVC con Capa de Servicio:**
    - **Modelo:** Modelos de datos de Eloquent (`app/User.php`).
    - **Vista:** Plantillas PHP simples para la presentación (`resources/views`).
    - **Controlador:** Controladores delgados que manejan el flujo de la petición HTTP (`app/Controllers`).
    - **Capa de Servicio:** Lógica de negocio encapsulada en clases de servicio (`app/Services`), promoviendo la reutilización y la adhesión al principio de Responsabilidad Única (SRP).
- **Enrutamiento Centralizado:** Un punto de entrada único (`index.php`) y un archivo de rutas (`routes.php`) dirigen todo el tráfico de la aplicación.
- **ORM Eloquent:** Interacción con la base de datos a través de modelos y una sintaxis expresiva.
- **Experiencia de Usuario (UX) Avanzada:**
    - Validación en tiempo real en el cliente (`jQuery Validation Plugin`).
    - Comunicación asíncrona sin recarga de página (`AJAX`).
    - Feedback de carga y notificaciones inteligentes con `SweetAlert2`.
- **Manejo de Errores Profesional:**
    - Uso de **Excepciones Personalizadas** para manejar errores de negocio (`app/Exceptions`).
    - Respuestas JSON con códigos de error específicos para un control preciso en el frontend.
- **Gestión de Entorno:** Configuración segura a través de archivos `.env`.

## 💻 Tecnologías Utilizadas

- **Backend:** PHP 8.0.30+, Composer, Eloquent ORM
- **Frontend:** HTML5, CSS3, Bootstrap 5, JavaScript, jQuery, jQuery Validation Plugin, SweetAlert2
- **Base de Datos:** MySQL

## 📂 Estructura del Proyecto

```
/
├── app/
│   ├── Controllers/      # Controladores (Capa HTTP)
│   ├── Exceptions/       # Excepciones personalizadas
│   ├── Services/         # Lógica de negocio (Casos de Uso)
│   ├── User.php          # Modelo de Eloquent (Capa de Datos)
│   └── bootstrap.php     # Script de inicialización de Eloquent
├── resources/
│   └── views/            # Vistas (Capa de Presentación)
├── routes.php            # Definición de las rutas de la aplicación
├── vendor/               # Dependencias de Composer (Ignorado por Git)
├── .env                  # Archivo de configuración (Ignorado por Git)
├── .gitignore            # Archivos ignorados por Git
├── index.php             # Punto de Entrada Único (Front Controller)
└── ...                 # Otros archivos (composer.json, etc.)
```

## ⚙️ Flujo de una Petición (Request Lifecycle)

1.  Toda petición llega al **`index.php`** (Front Controller).
2.  Se inicializa el **`Router`**, que carga las definiciones de **`routes.php`**.
3.  El Router despacha la URI al **Controlador** correspondiente (ej. `UserController@store`).
4.  El Controlador **NO contiene lógica de negocio**. Delega el trabajo a una clase de la **Capa de Servicio** (ej. `RegistrationService`).
5.  El **Servicio** ejecuta la lógica: valida los datos, interactúa con el **Modelo** (`User`) y, si algo falla, lanza una **Excepción** personalizada (ej. `ValidationException`).
6.  El **Controlador** atrapa la excepción (o el resultado exitoso) y construye la **respuesta JSON** apropiada.
7.  El **Frontend** (`main.js`) recibe el JSON y muestra la notificación `SweetAlert2` correspondiente al usuario.

## 🚀 Instalación

1.  **Clonar:** `git clone <URL_DEL_REPOSITORIO>`
2.  **Dependencias:** `composer install`
3.  **Base de Datos:** Importar el script SQL de abajo.
4.  **Entorno:** Copiar `.env.example` a `.env` y configurar las variables (BD y `ENCRYPTION_KEY`).
    - Para generar la `ENCRYPTION_KEY`: `openssl rand -base64 32`

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
) ENGINE = InnoDB;
```

## 🌱 Futuras Mejoras

La arquitectura actual facilita enormemente el crecimiento del proyecto:

- **Añadir un Módulo de Login:**
    1.  Crear un `LoginService` en `app/Services`.
    2.  Añadir un método `login` en un `AuthController`.
    3.  Añadir la ruta `POST /login` en `routes.php`.
- **Tests Unitarios:** La capa de servicio es fácilmente testeable de forma aislada.
- **Inyección de Dependencias:** Implementar un contenedor de dependencias para gestionar la creación de objetos (ej. inyectar servicios en los controladores).

## 📜 Licencia

Este proyecto está bajo los términos de la licencia especificada en el archivo [LICENSE.md](LICENSE.md).

## 👨‍💻 Autor

- **Henry Alejandro Taby**
- **Email:** henry.taby@gmail.com
- **LinkedIn:** [https://www.linkedin.com/in/henrytaby/](https://www.linkedin.com/in/henrytaby/)
