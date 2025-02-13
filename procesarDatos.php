<?php

// Iniciamos sesion
session_start();

// Importamos la clase bd desde el archivo bd.class que se encuentra en la carpeta clases.
require_once "clases/bd.class.php";

// Definimos las variables $usuarios como un array vacío y $mensaje como una cadena vacía.
$usuarios = [];
$mensaje = "";


// Creamos una instancia de la clase BD.
$bd = new BD;

//* Tratamo de ejecutar el bloque de código que sigue, si ocurre una excepción se manejaría con el catch.
try {

    // Llamamos al método conectar de la clase $bd para establecer una conexión con la base de datos.
    $bd->abrirConexion();

    // Definimos una consulta SQL para seleccionar los campos nombre, primer apellido, usuario y contraseña de la tabla profesores.
    $sql = "SELECT nombre, ape1, usuario, passw FROM Profesores";

    // Ejecutamos la consulta SQL y almacenamos los resultados en el array $usuarios.
    $usuarios = $bd->capturarDatos($sql);
    // Si ocurre una excepción, se capturaría y mostraría un mensaje de error y, en cualquier caso, se cerraría la conexión con la base de datos.
} catch (Exception $e) {
    echo "Error al capturar usuarios en la base de datos: " . $e->getMessage();
} finally {
    $bd->cerrarConexion();
}

//* Verificamos si el método de solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Capturamos y sanitizamos los datos del formulario enviados por POST, eliminando espacios en blanco y conviertiendo caracteres especiales a entidades HTML.
    $usuario = trim(htmlspecialchars($_POST['usuario']));
    $pass = trim(htmlspecialchars($_POST['pass']));


    // Verificamos si el nombre del usuario y la contraseña proporcionados coinciden con algún registro en el array $usuarios.
    if ($usuarios[$usuario]['passw'] === $pass) {

        // Si las credenciales coinciden, guardamos el nombre completo y el nombre de usuario en la sesión y se redirigirá el usuario a index.php
        $_SESSION['nombre'] = $usuarios[$usuario]['nombre'] . " " . $usuarios[$usuario]['ape1'];
        $_SESSION['usuario'] = $usuario;

        header("Location: index.php");
        exit();

        // Si las credenciales no coinciden, definiremos un mensaje de error y se redirigirá el usuario al login.php con el mensaje de error como parámetro.
    } else {
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
?>
