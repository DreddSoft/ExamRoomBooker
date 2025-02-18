<?php

// Iniciamos sesion
session_start();

// Importamos la clase bd desde el archivo bd.class que se encuentra en la carpeta clases.
require_once "clases/bd.class.php";

// Definimos las variables $usuarios como un array vacío y $mensaje como una cadena vacía.
$usuarios = [];
$mensaje = "";


// Verificamos si el método de solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Creamos una instancia de la clase BD.
    $bd = new BD;

    // Tratamos de ejecutar el bloque de código que sigue, si ocurre una excepción se manejaría con el catch.
    try {

        // Llamamos al método abrirConexion de la clase $bd para establecer una conexión con la base de datos.
        $bd->abrirConexion();

        // Definimos una consulta SQL para seleccionar los campos id, nombre, primer apellido, usuario y contraseña de la tabla Profesores.
        $sql = "SELECT id, nombre, ape1, usuario, passw FROM Profesores";

        // Ejecutamos la consulta SQL y almacenamos los resultados en el array $usuarios.
        $usuarios = $bd->capturarDatos($sql);
    } catch (Exception $e) {
        echo "Error al capturar usuarios en la base de datos: " . $e->getMessage();
    } finally {
        $bd->cerrarConexion();
    }

    // Capturamos y sanitizamos los datos del formulario enviados por POST, eliminando espacios en blanco y convirtiendo caracteres especiales a entidades HTML.
    $usuario = trim(htmlspecialchars($_POST['usuario']));
    $pass = trim(htmlspecialchars($_POST['pass']));

    // Convertimos el nombre de usuario a mayúsculas.
    $usuario = strtoupper($usuario);

    // Verificamos si el nombre del usuario y la contraseña proporcionados coinciden con algún registro en el array $usuarios.
    $usuarioValido = false;
    foreach ($usuarios as $u) {
        if ($u['usuario'] === $usuario && $u['passw'] === $pass) {
            $usuarioValido = true;
            $_SESSION["idProfesor"] = $u["id"];
            $_SESSION['nombre'] = $u['nombre'] . " " . $u['ape1'];
            $_SESSION['usuario'] = $usuario;
            break;
        }
    }

    if ($usuarioValido) {
        // Si las credenciales coinciden, redirigimos al usuario a index.php
        header("Location: index.php");
        exit();
    } else {
        // Si las credenciales no coinciden, definimos un mensaje de error y redirigimos al usuario a login.php con el mensaje de error como parámetro.
        $mensaje = "Usuario o contraseña incorrectos.";
        header("Location: login.php?mensaje=$mensaje");
        exit();
    }
} else {
    // Si el método de solicitud no es POST, definiremos un mensaje de error indicando que el método de envío es incorrecto y redirige al usaurio a login.php.
    $mensaje = "El método de envío es incorrecto.";
    header("Location: login.php?mensaje=$mensaje");
    exit();
}
