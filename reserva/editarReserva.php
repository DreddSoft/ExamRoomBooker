<?php
session_start();
require_once('../clases/bd.class.php');
$bd = new BD;

$error = false;
$success = false;

$idProfesor = null;
$profesor = null;
$reserva = [];

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

$idReserva = (isset($_GET["id"])) ? intval(htmlspecialchars($_GET["id"])) : null;
$primerTurno = 0;

if (isset($_GET["success"])) {
    $success = true;
}

if (isset($_GET["mensaje"])) {
    $msj = htmlspecialchars($_GET["mensaje"]);
    $error = true;
}

// Capturar los datos de la reserva y de los turnos
try {
    // Abrir conexion
    $bd->abrirConexion();

    // Sacar los datos de la reserva
    $sql = "SELECT 
        id,
        idProfesor,
        descripcion,
        numAlumnos,
        clase,
        fecha,
        idAsignatura
    FROM Reservas
    WHERE id = $idReserva
    ";


    $dataReserva = $bd->capturarDatos($sql);

    if (empty($dataReserva)) {
        throw new Exception("No se han sacado correctamente los datos de la reserva.");
    }

    $reserva["id"] = $dataReserva[0]["id"];
    $reserva["idProfesor"] = $dataReserva[0]["idProfesor"];
    $reserva["descripcion"] = $dataReserva[0]["descripcion"];
    $reserva["numAlumnos"] = $dataReserva[0]["numAlumnos"];
    $reserva["clase"] = $dataReserva[0]["clase"];
    $reserva["fecha"] = $dataReserva[0]["fecha"];
    $reserva["idAsignatura"] = $dataReserva[0]["idAsignatura"];


    // Sacar asignaturas
    $sql = "SELECT A.id, A.nombre FROM Asignaturas A
    LEFT JOIN AsignaturasProfesores AP ON AP.idAsignatura = A.id
    WHERE AP.idProfesor = $idProfesor";

    $asignaturas = $bd->capturarDatos($sql);

    if (empty($asignaturas)) {
        throw new Exception("No se han obtenido las asignaturas correctamente.");
    }

    $fecha = Date('Y-m-d', strtotime($reserva["fecha"]));

    // Sacar las plazas disponibles del día
    $sql = "SELECT 
        RT.idTurno, 
        100 - COALESCE(SUM(R.numAlumnos), 0) AS disponibilidad
    FROM ReservasTurnos AS RT
    LEFT JOIN Reservas AS R ON RT.idReserva = R.id AND R.fecha = '$fecha'
    GROUP BY RT.idTurno";

    $plazas = $bd->capturarDatos($sql);

    if (empty($plazas)) {
        throw new Exception("No se han obtenido las plazas correctamente.");
    }

    // Sacar los turnos de la reserva
    $sql = "SELECT T.id, T.horario 
    FROM Turnos T
    LEFT JOIN ReservasTurnos RT ON RT.idTurno = T.id
    WHERE RT.idReserva = $idReserva";

    $turnosReserva = $bd->capturarDatos($sql);

    if (empty($turnosReserva)) {
        throw new Exception("No se han obtenido los turnos correctamente.");
    }

    $primerTurno = intval($turnosReserva[0]["id"]);
} catch (Exception $e) {

    $msj = "Error: " . $e->getMessage();
    $error = true;
} finally {
    $bd->cerrarConexion();
}


$conf = "confirmaciones/confirmacionReserva_$idReserva.pdf";

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/Logo_type_1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        input:focus,
        textarea:focus {
            outline: none;
        }

        select:focus {
            outline: none;
        }
    </style>
    <title>Editar Reservas</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php require_once("../_header.php"); ?>

    <main class="container mt-5">
        <div class="w-100 d-flex justify-content-center align-center">
            <h2 class="mb-4" style="color: #642686;">Editar Reserva</h2>
        </div>

        <div class="d-flex justify-content-center">
            <form action="editarReservaServicio.php" method="post" class="d-flex flex-column align-items-center mb-4" style="width: 600px; margin: auto;" id="form-editar">
                <table class="table table-bordered w-100">
                    <tr>
                        <th class="w-auto bg-info text-dark">Id de la Reserva</th>
                        <td class="d-flex align-items-center justify-content-center"><input type="text" id="idReserva" name="idReserva" class="w-100 h-100 border-0 text-center" value="<?= $idReserva ?>" readonly></td>
                    </tr>
                    <tr>
                        <th class="w-auto bg-info text-dark">Profesor</th>
                        <td class="d-flex align-items-center justify-content-center"><input type="text" id="profesor" name="profesor" class="w-100 h-100 border-0 text-center" value="<?= $profesor ?>" readonly></td>
                    </tr>

                    <tr>
                        <th class="w-auto bg-info text-dark">Descripcion</th>
                        <td class="d-flex align-items-center justify-content-center"><textarea type="text" id="desc" name="desc" class="w-100 h-100 border-0 text-center" required><?= $reserva["descripcion"] ?></textarea></td>
                    </tr>

                    <tr>
                        <th class="w-auto bg-info text-dark">Número de Alumnos</th>
                        <td class="d-flex align-items-center justify-content-center"><input type="number" min="1" max="100" pattern="\d{1,3}" id="alumnos" name="alumnos" class="w-100 h-100 border-0 text-center" value="<?= $reserva["numAlumnos"] ?>" required></td>
                    </tr>

                    <tr>
                        <th class="w-auto bg-info text-dark">Curso</th>
                        <td class="d-flex align-items-center justify-content-center"><input type="text" id="clase" name="clase" class="w-100 h-100 border-0 text-center" value="<?= $reserva["clase"] ?>" required></td>
                    </tr>

                    <tr>
                        <th class="w-auto bg-info text-dark">Fecha</th>
                        <td class="d-flex align-items-center justify-content-center"><input type="date" id="fecha" name="fecha" class="w-100 h-100 border-0 text-center" value="<?= date('Y-m-d', strtotime($reserva["fecha"])); ?>" required></td>
                    </tr>

                    <tr>
                        <th class="w-auto bg-info text-dark">Asignaturas</th>
                        <td class="d-flex align-items-center justify-content-center">
                            <select class="w-100 h-100 border-0 text-center" name="asig" id="asig" required>
                                <?php foreach ($asignaturas as $asignatura): ?>

                                    <option value="<?= $asignatura["id"] ?>" <?= ($reserva["idAsignatura"] == $asignatura["id"]) ? " selected" : "" ?>><?= $asignatura["nombre"] ?></option>
                                <?php endforeach; ?>

                            </select>
                        </td>
                    </tr>

                    <tr>
                        <th class="w-auto bg-info text-dark">Turno</th>
                        <td class="d-flex align-items-center justify-content-center gap-2">
                            <input type="hidden" name="primerTurno" id="primerTurno" value="<?= $primerTurno ?>">
                            <?php foreach ($plazas as $plaza) :
                                $seleccionado = false;
                                $disponibilidad = $plaza['disponibilidad'];
                                foreach ($turnosReserva as $turno) {
                                    if ($turno["id"] == $plaza["idTurno"]) {
                                        $seleccionado = true;
                                        $disponibilidad += $reserva["numAlumnos"];
                                    }
                                }

                            ?>

                                <input class="btn-check" type="checkbox" name="turnos[]" id="<?= $plaza['idTurno']; ?>" value="<?= $plaza['idTurno']; ?>" data-disp="<?= $disponibilidad ?>" <?= ($seleccionado) ? " checked" : "" ?>>
                                <label class="btn btn-outline-primary" for="<?= $plaza['idTurno']; ?>" id="turno<?= $plaza['idTurno']; ?>" >
                                    <?= $plaza['idTurno']; ?>
                                </label>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <?php if ($success) : ?>
                        <div class="bg-success bg-opacity-10 border border-success text-success p-2 rounded mb-3">
                            <p class="mb-0">Reserva Modificada correctamente</p>
                        </div>
                    <?php endif; ?>

                    <?php if ($error) : ?>
                        <div class="bg-danger bg-opacity-10 border border-danger text-danger p-2 rounded mb-3">
                            <p class="mb-0"><?= $msj ?></p>
                        </div>
                    <?php endif; ?>


                </table>
                <div class="d-flex justify-content-center align-items-center gap-2">
                    <button type="submit" class="btn btn-primary">Modificar</button>
                    <button class="btn btn-danger" id="btn-delete" type="button">Eliminar</button>
                </div>
            </form>

            <?php if (file_exists($conf)) : ?>
                <div class="d-flex justify-content-center">
                    <embed src="<?= $conf ?>" type="application/pdf" width="600" height="400">
                </div>

            <?php endif; ?>

            <div class="position-fixed top-50 start-50 translate-middle w-100 h-100 d-none justify-content-center align-items-center bg-white bg-opacity-75" id="loading-screen" style="z-index: 999;">
                <div class="spinner-border text-primary m-auto" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>

    </main>

    <?php require_once("../_footer.php"); ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="editarReserva.js"></script>
</body>

</html>