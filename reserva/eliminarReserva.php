<?php
session_start();
require_once('../clases/bd.class.php');
$bd = new BD;

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION["idProfesor"])) {
        $usuarioIntroducido = htmlspecialchars($_SESSION["idProfesor"]); // almaceno el id del profesor

        if (isset($_POST["idReserva"])) {
            $idReserva = htmlspecialchars($_POST["idReserva"]);

            try {
                $bd->abrirConexion();

                // Comprobamos si la reserva existe antes de eliminarla
                $consultaComprobacion = "SELECT * FROM reservas WHERE id = $idReserva AND idProfesor = '$usuarioIntroducido'";
                $resultadoComprobacion = $bd->capturarDatos($consultaComprobacion);

                // Si la reserva existe, procedemos a eliminarla
                if (count($resultadoComprobacion) > 0) {
                    // Sentencia DELETE para eliminar la reserva
                    $sqlDelete = "DELETE FROM reservas WHERE id = $idReserva";
                    $resultadoDelete = $bd->actualizarDatos($sqlDelete);

                    if ($resultadoDelete != -1) {
                        $success = "Reserva eliminada exitosamente.";
                    } else {
                        $error = "Error al eliminar la reserva.";
                    }
                } else {
                    $error = "No se encontró la reserva o no tienes permisos para eliminarla.";
                }
            } catch (Exception $e) {
                $error = "Error: " . $e->getMessage();
            } finally {
                $bd->cerrarConexion();
            }
        } else {
            $error = "Error: ID de reserva no proporcionado.";
        }
    } else {
        $error = "Error: ID de profesor no encontrado.";
    }
} else {
    $error = "Método no permitido.";
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
    <title>Eliminar Reservas</title>
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
        <h1 class="mb-4">Eliminar Reserva</h1>
        <?php
        if ($error) {
            echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
        }
        if ($success) {
            echo '<div class="alert alert-success" role="alert">' . $success . '</div>';
        }
        ?>
        <form action="eliminarReserva.php" method="post">
            <div class="mb-3">
                <label for="idReserva" class="form-label">Seleccione la reserva a eliminar:</label>
                <select class="form-control" name="idReserva" required>
                    <?php
                    if (!empty($reservas)) {
                        foreach ($reservas as $reserva) {
                            echo '<option value="' . $reserva['id'] . '">ID: ' . $reserva['id'] . ' - ' . $reserva['descripcion'] . ' - ' . $reserva['fecha'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No hay reservas disponibles</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-danger">Eliminar Reserva</button>
        </form>
    </main>
    <?php
    require_once("../_footer.php");
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>