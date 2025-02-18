<?php
//Este bloque de código PHP se utiliza para recibir y sanitizar un posible mensaje de error a través de un parámetro GET, y mostrar dicho mensaje debajo de un formulario de inicio de sesión. Además, el formulario se valida en el HTML y se envía mediante POST a procesarDatos.php.


// Definimos la variable $mensaje como una cadena vacía.
$mensaje = "";

// Verificamos si el parámetro mensaje está presente en la URL, de ser así sanitizamos y guardamos el valor en la variable $mensaje.
if (isset($_GET['mensaje'])) {
    $mensaje = trim(htmlspecialchars($_GET['mensaje']));
}

?>
<!--Definimos la estructura básica del documento HTML con su cabecera, incluyendo el título y meta etiquetas.-->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/Logo_type_1.png" type="image/x-icon">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="shortcut icon" href="./assets/Logo_type_1.png" type="image/x-icon">
</head>
<body>
    <!--Incluimos el archivo _header.php, que contiene el encabezado común a todas las páginas del sitio.-->
    <?php require_once "_header.php"; ?>

    <!--Creamos un formulario HTML para iniciar sesión, con campos para el nombre de usuario y contraseña, además de un botón para enviar datos.-->
    <!--Si la variable $mensaje tiene contenido, se mostrará el mensaje debajo del formulario.-->
    <main>
        <div>
            <h2>Iniciar Sesion</h2>
        </div>
        <form action="procesarDatos.php" method="post">
            <div>
                <label for="usuario">Usuario</label>
                <input type="text" name="usuario" id="usuario">
            </div>
            <div>
                <label for="pass">Contraseña</label>
                <input type="password" name="pass" id="pass">
            </div>
            <div>
                <button type="submit">Acceder</button>
            </div>
            <?php if (isset($mensaje)) : ?>

                <div>
                    <p><?php echo $mensaje; ?></p>
                </div>

            <?php endif; ?>
        </form>
    </main>
    <!--Incluimos el archivo _footer.php, que contendrá el pie de página común a todas las páginas del sitio.-->
    <?php require_once "_footer.php"; ?>
    <script src="script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>