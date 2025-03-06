<?php
session_start();

require_once "../clases/bd.class.php";

// Siempre sanitizamos el codigo en variables
$idProfesor = null;
$profesor = null;

if (isset($_SESSION['idProfesor'])) {

    $idProfesor = $_SESSION["idProfesor"];
    $profesor = $_SESSION['nombre'];
} else {
    header("Location: ../login.php");
    exit();
}

// * CODIGO PARA CONTROLAR LA INACTIVIDAD DEL USUARIO
$maxTime = 600;

if (isset($_SESSION["ultimo_acceso"])) {
    $tiempo_transcurrido = time() - $_SESSION["ultimo_acceso"];

    if ($tiempo_transcurrido > $maxTime) {

        header("Location: ../cerrarSesion.php");
        exit();
    }
}

// Actualizamos en cada accion del user
$_SESSION['ultimo_acceso'] = time();


$bd = new BD();
$msj = "";
$showError = false;
$plazas = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitizar variables
    $fecha = (isset($_POST["fecha"])) ? htmlspecialchars($_POST["fecha"]) : null;
    $fecha = Date('Y-m-d', strtotime($fecha));
    $turno = (isset($_POST["turno"])) ? htmlspecialchars($_POST["turno"]) : null;

    // Las plazas disponibles por turno las tenemos que sacar calculando de la base de datos
    try {

        $bd->abrirConexion();

        $sql = "SELECT 
                RT.idTurno, 
                100 - COALESCE(SUM(R.numAlumnos), 0) AS disponibilidad
            FROM ReservasTurnos AS RT
            LEFT JOIN Reservas AS R ON RT.idReserva = R.id AND R.fecha = '$fecha'
            GROUP BY RT.idTurno";

        $plazas = $bd->capturarDatos($sql);

        $sql = "SELECT
                    A.id,
                    A.nombre
                FROM Asignaturas AS A 
                LEFT JOIN AsignaturasProfesores AS AP ON AP.idAsignatura = A.id
                WHERE AP.idProfesor = $idProfesor";

        $asignaturas = $bd->capturarDatos($sql);

        if (empty($plazas)) {
            throw new Exception("Plazas vacias, no se ha capturado bien en la base de datos.");
        } else if (empty($asignaturas)) {
            throw new Exception("Asignaturas vacias, no se han capturado bien.");
        }
    } catch (Exception $e) {
        $msj = "Error: " . $e->getMessage();
        $showError = true;
    } finally {
        $bd->cerrarConexion();
    }
}

// echo "Fecha: $fecha<br>";
// foreach ($plazas as $plaza) {

//     echo "Disponibilidad " . $plaza['idTurno'] . ": " . $plaza['disponibilidad'] . "<br>";
// }

// Las convertimos en globales
global $fecha, $turno;

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/Logo_type_1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Crear Reservas</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php require_once("../_header.php"); ?>


    <main class="container my-4">
        <h2 class="mb-4">Crear Reserva</h2>
        <form class="mx-auto p-4 border rounded shadow" id="form-reserva" style="max-width: 600px;" action="crearReservaServicio.php" method="post">
            <h3 style="color: #642686;" class="my-3 text-center">Datos de la Reserva</h3>

            <div class="mb-3 w-100">
                <label for="nameProfesor" class="form-label me-2">Profesor</label>
                <input type="text" class="form-control text-center" name="nameProfesor" id="nameProfesor" value="<?= $profesor; ?>" readonly>
            </div>

            <div class="mb-3 w-100">
                <label for="fecha" class="form-label me-2">Fecha</label>
                <input type="date" class="form-control text-center" name="fecha" id="fecha" value="<?= date('Y-m-d', strtotime($fecha)); ?>" readonly>
            </div>

            <div class="mb-3 w-100 d-flex flex-row">
                <label for="turnos" class="form-label me-2">Turnos</label>
                <?php foreach ($plazas as $plaza) : ?>
                    <?php if ($plaza['disponibilidad'] > 0 && $plaza['idTurno'] >= $turno) : ?>
                        <div class="form-check">
                            <input class="btn-check" type="checkbox" name="turnos[]" id="turno<?= $plaza['idTurno']; ?>" value="<?= $plaza['idTurno']; ?>" data-disp="<?= $plaza['disponibilidad']; ?>" ; <?= ($turno == $plaza['idTurno']) ? ' checked' : ''; ?>>
                            <label class="btn btn-outline-primary" for="turno<?= $plaza['idTurno']; ?>">
                                <?= $plaza['idTurno']; ?>
                            </label>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <div class="w-100 mb-3 p-2 border border-primary bg-transparent rounded">
                <div class="mb-3 w-100">
                    <label for="max" class="form-label me-2">Maximo de plazas disponibles</label>
                    <input type="number" class="form-control text-center" name="max" id="max" value="" readonly>
                </div>

                <div class="mb-3 w-100 d-flex flex-row">
                    <div class="w-50">
                        <label for="numAlumno" class="form-label me-2">Número de Alumnos</label>
                        <input type="number" class="form-control" id="numAlumno" name="numAlumno" min="1" pattern="[0-9]+" required style="max-width: 100px;">
                    </div>
                    <div class="w-50">
                        <label for="clase" class="form-label me-2">Curso</label>
                        <input type="text" class="form-control" id="clase" name="clase" required>
                    </div>

                </div>
            </div>


            <div class="mb-3 w-100">
                <label for="descripcion" class="form-label">Asignatura</label>
                <select class="form-select" name="asig" id="asig">
                    <?php foreach ($asignaturas as $asignatura) : ?>
                        <option value="<?= $asignatura['id'] ?>"><?= $asignatura["id"] . " - " . $asignatura['nombre'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3 w-100">
                <label for="descripcion" class="form-label">Ingrese la descripción de la clase</label>
                <textarea class="form-control" id="descripcion" name="descripcion" maxlength="250" required></textarea>
            </div>
            <div class="w-100 d-flex justify-content-center align-center">
                <button type="submit" id="btn-submit" class="btn btn-primary">Crear Reserva</button>

            </div>
            <?php if ($showError) : ?>
                <div class="mt-3 w-100">
                    <p class="alert alert-danger" style="color: red;"><?= $msj; ?></p>
                </div>
            <?php endif; ?>
        </form>

        <div class="position-fixed top-50 start-50 translate-middle w-100 h-100 d-none justify-content-center align-items-center bg-white bg-opacity-75" id="loading-screen" style="z-index: 999;">
            <div class="spinner-border text-primary m-auto" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </main>

    <?php require_once("../_footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="crearReserva.js"></script>
</body>

</html>