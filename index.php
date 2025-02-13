<?php
// Iniciamos una nueva sesión.
session_start();

// Esto será la página principal que presentará la aplicación.

// Si no hay ningún usuario registrado, lo redirigiríamos al login para así poder registrarse y/o iniciar sesión
if (!$_SESSION['profesor']) {
    header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index de la página</title>
</head>
<body>

    <?php require_once "_header.php"; ?>

    <main>
        <h2 class="blue">Página Principal</h2>
        <h3 class="blue">Bienvenido/a</h3>
        <h4 class="blue"><?= $_SESSION['profesor'] ?></h4>
    </main>

    <?php require_once "_footer.php"; ?>
    <!--<script src="script.js"></script> --> <!--Lo dejo comentado porque no se si se implementará-->
    
</body>

<!-- 
    Proyecto: Aplicación para la gestión informatizada de una sala de exámenes del instituto Jorge Guillén
    Fecha: Febrero de 2025
    Descripción: Página principal de la aplicación.
-->

</html>