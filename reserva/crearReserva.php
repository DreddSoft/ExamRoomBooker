<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/Logo_type_1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Crear Reservas</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php
    require_once("../_header.php");

    $fechaIntroducida = '';
    $fechaActualizada = '';
    $turnoIntroducido = '';
    $plaza1 = '';
    $plaza2 = '';
    $plaza3 = '';
    $plaza4 = '';
    $plaza5 = '';
    $plaza6 = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["fecha"])) {
            $fechaIntroducida = htmlspecialchars($_POST["fecha"]);
            $fechaActualizada = date('Y-m-d', strtotime($fechaIntroducida)); // Formatear la fecha para MySQL
        }

        if (isset($_POST["turno"])) {
            $turnoIntroducido = htmlspecialchars($_POST["turno"]);
        }

        if (isset($_POST["plaza1"])) {
            $plaza1 = htmlspecialchars($_POST["plaza1"]);
        }

        if (isset($_POST["plaza2"])) {
            $plaza2 = htmlspecialchars($_POST["plaza2"]);
        }

        if (isset($_POST["plaza3"])) {
            $plaza3 = htmlspecialchars($_POST["plaza3"]);
        }

        if (isset($_POST["plaza4"])) {
            $plaza4 = htmlspecialchars($_POST["plaza4"]);
        }

        if (isset($_POST["plaza5"])) {
            $plaza5 = htmlspecialchars($_POST["plaza5"]);
        }

        if (isset($_POST["plaza6"])) {
            $plaza6 = htmlspecialchars($_POST["plaza6"]);
        }
    }
    ?>
    <main class="container my-4">
        <h1 class="mb-4">Crear Reserva</h1>
        <form action="crearReservaServicio.php" method="post">
            <input type="hidden" name="fecha" value="<?php echo $fechaActualizada; ?>">
            <input type="hidden" name="turno" value="<?php echo $turnoIntroducido; ?>">
            <input type="hidden" name="plaza1" value="<?php echo $plaza1; ?>">
            <input type="hidden" name="plaza2" value="<?php echo $plaza2; ?>">
            <input type="hidden" name="plaza3" value="<?php echo $plaza3; ?>">
            <input type="hidden" name="plaza4" value="<?php echo $plaza4; ?>">
            <input type="hidden" name="plaza5" value="<?php echo $plaza5; ?>">
            <input type="hidden" name="plaza6" value="<?php echo $plaza6; ?>">

            <div class="mb-3">
                <label for="numAlumno" class="form-label">Ingrese la cantidad de alumnos para la reserva:</label>
                <input type="number" class="form-control" id="numAlumno" name="numAlumno" min="1" required>
            </div>

            <div class="mb-3">
                <label for="clase" class="form-label">Ingrese la clase a la que se realiza la reserva:</label>
                <input type="text" class="form-control" id="clase" name="clase" maxlength="50" required>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Ingrese la descripción de la clase:</label>
                <textarea class="form-control" id="descripcion" name="descripcion" maxlength="250" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
    </main>

    <?php
    require_once("../_footer.php");
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>