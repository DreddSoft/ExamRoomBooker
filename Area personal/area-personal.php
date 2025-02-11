<?php
// Asignado a Adrián
// Muestra las reservaas en una tabla, de un profesor ordenados por fecha
// No se muestran las imagenes

// Inicia la sesión e incluimos la clase de la base de datos
session_start();
require('../clases/bd.class.php');

// Si no hay id de usuario, redirige a la página de login
//if (!isset($_SESSION['id'])) {
//    header('Location: ../login.php');
//    exit;
//}

// Hacemos la consulta a la base de datos por el id del usuario
//$sql = "SELECT "
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/Logo_type_1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Area personal</title>
</head>

<body>
    <?php require_once "../_header.php"; ?>

    <main>

    </main>

    <?php require_once "../_footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../script.js"></script>
</body>
</html>