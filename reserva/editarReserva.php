<?php
session_start();
require_once('../clases/bd.class.php');
$bd = new BD;

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION["idProfesor"])) {
        $usuarioIntroducido = htmlspecialchars($_SESSION["idProfesor"]); // almaceno el id del profesor

        if (isset($_POST["fecha"])) {
            $fechaIntroducida = htmlspecialchars($_POST["fecha"]);

            if (isset($_POST["numAlumno"])) {
                $numeroAlumnos = htmlspecialchars($_POST["numAlumno"]);
                if (isset($_POST["clase"])) {
                    $clase = htmlspecialchars($_POST["clase"]);
                    if (isset($_POST["descripcion"])) {
                        $descripcion = htmlspecialchars($_POST["descripcion"]);

                        try {
                            $bd->abrirConexion();

                            $consultaIdAsignatura = "SELECT idAsignatura FROM asignaturasprofesores WHERE idProfesor='$usuarioIntroducido'";
                            $idAsignaturaArray = $bd->capturarDatos($consultaIdAsignatura);
                            if (!empty($idAsignaturaArray)) {
                                $idAsignatura = $idAsignaturaArray[0]['idAsignatura'];

                                $sql = "UPDATE reservas SET
                                            descripcion='$descripcion',
                                            numAlumnos=$numeroAlumnos,
                                            clase='$clase',
                                            fecha='$fechaIntroducida',
                                            idAsignatura=$idAsignatura
                                        WHERE 
                                            idProfesor='$usuarioIntroducido' AND 
                                            fecha='$fechaIntroducida'";

                                $resultado = $bd->insertarDatos($sql);

                                if ($resultado != -1) {
                                    $success = "Reserva actualizada con éxito.";
                                } else {
                                    $error = "Error al actualizar la reserva.";
                                }
                            } else {
                                $error = "Error: No se encontró la asignatura para el profesor.";
                            }
                        } catch (Exception $e) {
                            $error = $e->getMessage();
                        } finally {
                            $bd->cerrarConexion();
                        }
                    } else {
                        $error = "Error: Descripción no proporcionada.";
                    }
                } else {
                    $error = "Error: Clase no proporcionada.";
                }
            } else {
                $error = "Error: Número de alumnos no proporcionado.";
            }
        } else {
            $error = "Error: Fecha no proporcionada.";
        }
    } else {
        $error = "Error: ID de profesor no encontrado.";
    }
} else if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    $error = "Método de solicitud no permitido.";
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/Logo_type_1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Editar Reservas</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php
    require_once("../_header.php");

    // Obtener las reservas del profesor
    if (isset($_SESSION["idProfesor"])) {
        $usuarioIntroducido = htmlspecialchars($_SESSION["idProfesor"]); // almaceno el id del profesor

        try {
            $bd->abrirConexion();
            $consultaReservas = "SELECT id, descripcion, fecha FROM reservas WHERE idProfesor = '$usuarioIntroducido'";
            $reservas = $bd->capturarDatos($consultaReservas);
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        } finally {
            $bd->cerrarConexion();
        }
    }
    ?>
    <main class="container mt-5">
        <h1 class="mb-4">Editar Reserva</h1>
        <?php
        if ($error) {
            echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
        }
        if ($success) {
            echo '<div class="alert alert-success" role="alert">' . $success . '</div>';
        }
        ?>
        <form action="editarReserva.php" method="post">
            <div class="mb-3">
                <label for="fecha" class="form-label">Fecha de la reserva:</label>
                <input type="date" class="form-control" name="fecha" required>
            </div>
            <div class="mb-3">
                <label for="numAlumno" class="form-label">Ingrese la cantidad de alumnos para la reserva:</label>
                <input type="number" class="form-control" min="1" name="numAlumno" required>
            </div>
            <div class="mb-3">
                <label for="clase" class="form-label">Ingrese la clase a la que se realiza la reserva:</label>
                <input type="text" class="form-control" name="clase" maxlength="50" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Ingrese la descripción de la clase:</label>
                <textarea class="form-control" name="descripcion" id="descripcion" maxlength="250" required></textarea>
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