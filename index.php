<?php
// Iniciamos una nueva sesión.
session_start();

// Esto será la página principal que presentará la aplicación.

//TODO: Descomentar la redireccion
// Si no hay ningún usuario registrado, lo redirigiríamos al login para así poder registrarse y/o iniciar sesión
// if (!$_SESSION['profesor']) {
//     header("Location: login.php");
// }

// Siempre sanitizamos el codigo en variables
$profesor = null;

if (isset($_SESSION['nombre'])) {

    $profesor = $_SESSION['nombre'];
}


?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/Logo_type_1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>ExamRoomBooker</title>
</head>

<body>

    <?php require_once "_header.php"; ?>

    <main class="d-flex flex-column justify-content-center align-items-center">
        <h2 class="blue">Página Principal</h2>
        <h3 class="blue">Bienvenido/a <?= ($profesor) ? $profesor : "No Identificado" ?></h3>

        <?= $_SERVER['DOCUMENT_ROOT'] . "/examroombooker/assets/Logo_type_1.svg" ?>
    </main>

    <!--<script src="script.js"></script> --> <!--Lo dejo comentado porque no se si se implementará-->
    
<!-- 
    Proyecto: Aplicación para la gestión informatizada de una sala de exámenes del instituto Jorge Guillén
    Fecha: Febrero de 2025
    Descripción: Página principal de la aplicación.
-->


    <?php require_once "_footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="script.js"></script>
</body>
</html>