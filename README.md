# <img src="assets/ExamRoomBooker.png" width="500"/>

## Descripción
ExamRoomBroker es una aplicación web diseñada para la gestión de reservas de salas de examen o salones multiusos. Cuenta con un sistema de control de acceso basado en roles (RBAC - Role-Based Access Control), permitiendo a los usuarios realizar diferentes acciones según su perfil:

- **Profesores:** pueden crear, editar y eliminar sus propias reservas.

- **Administradores:** además de gestionar reservas, pueden crear, editar, activar, asignar privilegios y eliminar perfiles de profesores en la aplicación.

La aplicación envía un correo de confirmación con un archivo PDF adjunto al crear o modificar una reserva.

## Tecnologías utilizadas
- **Backend:** PHP
- **Frontend:** Bootstrapt, JavaScript.
- **Base de datos:** MySQL.
- **Servidor local recomendado:** XAMPP.

## Instalación
### Requisitos previos
- Tener instalado [XAMPP](https://www.apachefriends.org/es/index.html) o un entorno que soporte PHP y MySQL.

### Pasos de instalación
1. Clonar o descargar este repositorio en tu entorno local:
   ```bash
   git clone https://github.com/DreddSoft/ExamRoomBooker.git
   ```
2. Copiar la carpeta del proyecto ene l directorio ``htdocs`` de XAMPP.
3. Iniciar Apache y MySQL desde el panel de control de XAMPP.
4. Importar la base de datos del ejemplo:
   - Abrir ``phpMyAdmin``.
   - Crear una nueva base de datos con el nombre examroombooker.
   - Importar el archivo ``examroombooker.sql`` ubicado en la carpeta ``database`` del proyecto.
5. Crear un archivo ``.env`` en la raíz del proyecto y agregar la siguiente configuración:
   ```bash
    SMTP_HOST="smtp.gmail.com"
    SMTP_PORT=465
    SMTP_USER="tu_correo@gmail.com"
    SMTP_PASS="tu_contraseña"
    DB_HOST=localhost
    DB_USER=root
    DB_PASS=
    DB_NAME=examroombroker
   ```
   *Reemplaza los valores correspondientes.*
6. Acceder a la aplicación desde el navegador.

## Funcionalidades Principales.
- Gestión de reservas de salas.
- Sitema de control de acceso basado en roles (RBAC): profesores y administradores.
- Envío automático de correos electrónicos con confirmaciones PDF al crear o modificar reservas.
- Interfaz sencilla y responsive basada en Bootstrap.

## Uso
### Profesor
- Puede iniciar o cerrar sesión.
- Realizar nuevas reservas en base a la disponibilidad del turno y organizadas por semanas laborables.
- Editar o cancelar sus propias reservas.

### Administrador
- Mismas funcionalidades que un profesor.
- Puede crear, modificar, activar | desactivar y eliminar cuentas de profesores.
- Puede asignar privilegios a los profesores.

## Licencia

## Contacto

## Autoría
Este proyecto ha sido desarrollado por los siguientes colaboradores:
- [@ajimvil713](https://github.com/ajimvil713)
- [@davix1997](https://github.com/davix1997)
- [@danielgr29](https://github.com/danielgr29)
- [@Ivan-Trevi](https://github.com/Ivan-Trevi)
- [@DreddSoft](https://github.com/DreddSoft)

<img src="assets/Logo_type_1.png" width="150" />
