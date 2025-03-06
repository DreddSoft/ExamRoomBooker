<?php

// Importamos la clase bd desde el archivo bd.class que se encuentra en la carpeta clases.
require_once "clases/bd.class.php";

// Definimos las variables $usuarios como un array vacío y $mensaje como una cadena vacía.
$usuarios = [];
$mensaje = "";


// Verificamos si el método de solicitud es POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Creamos una instancia de la clase BD.
    $bd = new BD;

    // Capturamos y sanitizamos la entrada
    $usuario = trim(htmlspecialchars($_POST['usuario']));
    $pass = trim($_POST['pass']);

    // Tratamos de ejecutar el bloque de código que sigue, si ocurre una excepción se manejaría con el catch.
    try {

        // Llamamos al método abrirConexion de la clase $bd para establecer una conexión con la base de datos.
        $bd->abrirConexion();

        // Definimos una consulta SQL para seleccionar los campos id, nombre, primer apellido, usuario y contraseña de la tabla Profesores.
        $sql = "SELECT id, nombre, ape1, usuario, passw, admin, email 
        FROM Profesores 
        WHERE usuario = '$usuario' 
        OR email = '$usuario'";

        // Ejecutamos la consulta SQL y almacenamos los resultados en el array $usuarios.
        $usuarioData = $bd->capturarDatos($sql);

        // if (password_verify($pass, $usuarioData[0]["passw"])) {
        //     echo "Si";
        // }else {
        //     echo "No";
        // }

        if (!empty($usuarioData) && password_verify($pass, $usuarioData[0]["passw"])) {

            // Iniciamos sesion
            session_start();

            $_SESSION["idProfesor"] = $usuarioData[0]["id"];
            $_SESSION['nombre'] = $usuarioData[0]['nombre'] . " " . $usuarioData[0]['ape1'];
            $_SESSION['usuario'] = $usuarioData[0]['usuario'];
            $_SESSION['admin'] = $usuarioData[0]['admin'];
            $_SESSION['ultimo_acceso'] = time();

            header("Location: index.php");
            exit();
        } else {
            $mensaje = "Usuario o contraseña incorrectos.";
            header("Location: login.php?mensaje=" . urlencode($mensaje));
            exit();
        }
    } catch (Exception $e) {
        $mensaje = "Error al capturar usuarios en la base de datos: " . $e->getMessage();
        header("Location: login.php?mensaje=" . urlencode($mensaje));
        exit();
    } finally {
        $bd->cerrarConexion();
    }
}
