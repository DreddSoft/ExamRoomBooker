<?php
//Este bloque de código PHP se utiliza para recibir y sanitizar un posible mensaje de error a través de un parámetro GET, y mostrar dicho mensaje debajo de un formulario de inicio de sesión. Además, el formulario se valida en el HTML y se envía mediante POST a procesarDatos.php.


// Definimos la variable $mensaje como una cadena vacía.
$mensaje = null;

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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>ExamRoomBooker | Login</title>
</head>

<body class="d-flex flex-column min-vh-100">

    <!--Incluimos el archivo _header.php, que contiene el encabezado común a todas las páginas del sitio.-->
    <?php require_once "_header.php"; ?>

    <!--Creamos un formulario HTML para iniciar sesión, con campos para el nombre de usuario y contraseña, además de un botón para enviar datos.-->
    <!--Si la variable $mensaje tiene contenido, se mostrará el mensaje debajo del formulario.-->
    <main>
        <div class="d-flex flex-column align-items-center my-4" style="color: #642686;">
            <h2>Iniciar Sesion</h2>
        </div>
        <form class="py-5 px-4 d-flex flex-column align-items-center bg-light rounded shadow my-5" action="procesarDatos.php" method="post" style="max-width: 400px; margin: auto;">
            <img class="pb-3" src="assets/Logo_type_1.svg" alt="Logo de examRoomBooker pequeño" style="width: 100px;">
            <div class="mb-3 w-100">
            <label for="usuario" class="form-label">Usuario</label>
            <input type="text" name="usuario" id="usuario" class="form-control" required>
            </div>
            <div class="mb-3 w-100">
            <label for="pass" class="form-label">Contraseña</label>
            <input type="password" name="pass" id="pass" class="form-control" required>
            </div>
            <div class="d-grid w-100">
            <button type="submit" class="btn btn-primary">Acceder</button>
            </div>
            <?php if (isset($mensaje)) : ?>
            <div class="mt-3 w-100">
            <p class="alert alert-danger"><?php echo $mensaje; ?></p>
            </div>
            <?php endif; ?>
        </form>
    </main>
    <!--Incluimos el archivo _footer.php, que contendrá el pie de página común a todas las páginas del sitio.-->
    <?php require_once "_footer.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>


</body>
</html>