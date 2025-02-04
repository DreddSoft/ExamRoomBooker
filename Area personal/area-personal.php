<?php
// Asignado a Adrián
// Muestra las reservaas en una tabla, de un profesor ordenados por fecha

// Inicia la sesión e incluimos la clase de la base de datos
session_start();
require_once '../bbdd.php'; // Nombre temporal

// Si no hay id de usuario, redirige a la página de login
if (!isset($_SESSION['id'])) {
    header('Location: ../login.php');
    exit;
}

// Hacemos la consulta a la base de datos por el id del usuario
$sql = "SELECT "
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area personal</title>
</head>
<body>
    <table>

    </table>
</body>
</html>