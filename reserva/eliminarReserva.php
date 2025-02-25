<?php
session_start();
require_once('../clases/bd.class.php');
$bd = new BD();


$fechaIntroducida = htmlspecialchars($_GET["fecha"]);
$turnoIntroducido = htmlspecialchars($_GET["turno"]);
$plazaIntroducida = htmlspecialchars($_GET["plaza"]);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION["idProfesor"])) {
        $usuarioIntroducido = htmlspecialchars(string: $_SESSION["idProfesor"]); // almaceno el ide del profesor

        if (isset($fechaIntroducida) && isset($turnoIntroducido) && isset($plazaIntroducida)) {
            if (isset($_POST["numAlumno"])) {
                $numeroAlumnos = htmlspecialchars($_POST["numAlumno"]);
                if (isset($_POST["clase"])) {
                    $clase = htmlspecialchars($_POST["clase"]);
                    if (isset($_POST["descripcion"])) {
                        try {
                            $bd->abrirConexion();

                            // Comprobamos si la reserva existe antes de eliminarla
                            $consultaComprobacion = "SELECT * FROM reservas WHERE id = $idReserva AND idProfesor = $usuarioIntroducido";
                            $resultadoComprobacion = $bd->capturarDatos($consultaComprobacion);

                            // Si la reserva existe, procedemos a eliminarla
                            if (count($resultadoComprobacion) > 0) {
                                // Sentencia DELETE para eliminar la reserva
                                $sqlDelete = "DELETE FROM reservas WHERE id = $idReserva";
                                $bd->actualizarDatos($sqlDelete);

                                echo "Reserva eliminada exitosamente.";
                            } else {
                                echo "No se encontró la reserva o no tienes permisos para eliminarla.";
                            }
                        } catch (Exception $e) {
                            echo "Error: " . $e->getMessage();
                        } finally {
                            $bd->cerrarConexion();
                        }
                    }
                }
            }
        }
    }
} else {
    echo "Método no permitido.";
}
?>

<!DOCTYPE html>
<html lang="en">

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
    ?>
    <main>
        <form action="eliminarReserva.php" method="post">
            <label for="idReserva">ID de la reserva a eliminar:</label>
            <input type="text" name="idReserva" required><br>

            <input type="submit" value="Eliminar Reserva">
        </form>
    </main>
    <?php
    require_once("../_footer.php");
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>