<?php
// Iniciamos una nueva sesión.
session_start();

require_once "clases/bd.class.php";


// Si no hay ningún usuario registrado, lo redirigiríamos al login para así poder registrarse y/o iniciar sesión
if (!$_SESSION['idProfesor']) {
    header("Location: login.php");
}

// Siempre sanitizamos el codigo en variables
$profesor = null;

if (isset($_SESSION['nombre'])) {

    $profesor = $_SESSION['nombre'];
}

$idProfesor = $_SESSION["idProfesor"];

// Capturamos el lunes de la semana actual
$diaActual = date("d/m/Y");

// Sacamos el lunes de la semana actual
$lunesSemanaActual = strtotime('monday this week');

// Si recibimos parametros get de la semana
if (isset($_GET["semana"])) {
    $numSemana = $_GET["semana"];
    $lunesSemanaActual = strtotime("+$numSemana week", $lunesSemanaActual);
}
$lunes = Date("d/m/Y", $lunesSemanaActual);
$martes = Date("d/m/Y", strtotime('+1 day', $lunesSemanaActual));
$miercoles = Date("d/m/Y", strtotime('+2 days', $lunesSemanaActual));
$jueves = Date("d/m/Y", strtotime('+3 days', $lunesSemanaActual));
$viernes = Date("d/m/Y", strtotime('+4 days', $lunesSemanaActual));


$semana = [$lunes, $martes, $miercoles, $jueves, $viernes];

// Base de datos
$bd = new BD();
$showModal = false;

try {

    // Conectamos
    $bd->abrirConexion();

    // Capturar turnos, para crear rows
    $sql = "SELECT
    id as idTurno,
    horario
    FROM Turnos";

    $turnos = $bd->capturarDatos($sql);

    $sql = "SELECT 
        R.id as idReserva,
        CONCAT(P.nombre, ' ', P.ape1) as profesor,
        R.descripcion,
        R.numAlumnos as numeroAlumnos,
        R.clase,
        R.fecha,
        A.nombre as asignatura,
        RT.idTurno as turno,
        R.idProfesor
    FROM Reservas AS R
    LEFT JOIN Profesores AS P ON P.id = R.idProfesor
    LEFT JOIN Asignaturas AS A ON A.id = R.idAsignatura
    INNER JOIN ReservasTurnos AS RT ON RT.idReserva = R.id
    ORDER BY R.id;";

    $reservas = $bd->capturarDatos($sql);
} catch (Exception $e) {
    $mensaje = "Error de base de datos: " . $e->getMessage();
    $showModal = true;
} finally {
    $bd->cerrarConexion();
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
    <title>ExamRoomBooker</title>
</head>

<body class="d-flex flex-column min-vh-100">

    <?php require_once "_header.php"; ?>

    <main class="d-flex flex-column justify-content-center align-items-center py-4 bg-light">
        <div class="d-flex flex-column justify-content-center align-items-center my-3">
            <h2 class="" style="color: #642686;">APP Reserva de Sala de Examen - IES Jorge Guillén</h2>
            <h3 class="text-primary">Bienvenido/a <?= ($profesor) ? $profesor : "No Identificado" ?></h3>
        </div>


        <div class="d-flex flex-column justify-content-center align-items-center container-fluid">
            <div class="d-flex justify-content-between w-100 my-4">
                <div><a href="index.php?semana=-1" class="btn btn-link">&lt;&lt; Anterior</a></div>
                <div><a href="index.php?semana=1" class="btn btn-link">Siguiente &gt;&gt;</a></div>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center">
                <table class="table table-bordered w-80">
                    <thead>
                        <tr>
                            <th class="text-center bg-secondary text-white fw-bold">Tramos</th>
                            <th class="text-center" style="<?= ($lunes < $diaActual) ? 'background-color: rgba(196, 196, 196, 0.2); cursor: not-allowed;' : '' ?><?= ($lunes == $diaActual) ? ' background-color: rgba(144, 238, 144, 0.1);' : '' ?>">Lunes <br><?= $lunes ?></th>
                            <th class="text-center" style="<?= ($martes < $diaActual) ? 'background-color: rgba(196, 196, 196, 0.2); cursor: not-allowed;' : '' ?><?= ($martes == $diaActual) ? ' background-color: rgba(144, 238, 144, 0.1);' : '' ?>">Martes <br><?= $martes ?></th>
                            <th class="text-center" style="<?= ($miercoles < $diaActual) ? 'background-color: rgba(196, 196, 196, 0.2); cursor: not-allowed;' : '' ?><?= ($miercoles == $diaActual) ? ' background-color: rgba(144, 238, 144, 0.1);' : '' ?>">Miércoles <br><?= $miercoles ?></th>
                            <th class="text-center" style="<?= ($jueves < $diaActual) ? 'background-color: rgba(196, 196, 196, 0.2); cursor: not-allowed;' : '' ?><?= ($jueves == $diaActual) ? ' background-color: rgba(144, 238, 144, 0.1);' : '' ?>">Jueves <br><?= $jueves ?></th>
                            <th class="text-center" style="<?= ($viernes < $diaActual) ? 'background-color: rgba(196, 196, 196, 0.2); cursor: not-allowed;' : '' ?><?= ($viernes == $diaActual) ? ' background-color: rgba(144, 238, 144, 0.1);' : '' ?>">Viernes <br><?= $viernes ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($turnos as $turno) : ?>

                            <tr>
                                <td class="bg-secondary text-white fw-bold"><?= $turno["horario"] ?></td>
                                <?php foreach ($semana as $dia) :
                                    $blocked = $dia < $diaActual;
                                ?>
                                    <td class="text-center" style="<?= ($blocked) ? 'background-color: rgba(196, 196, 196, 0.2); cursor: not-allowed;' : '' ?>">
                                        <?php
                                        // Aqui tengo que sacar los datos de las reservas
                                        $reservaEncontrada = false;
                                        $plazas = 100;

                                        foreach ($reservas as $reserva) {

                                            // Convertir la fecha de la reserva en date
                                            $fechaReserva = Date("d/m/Y", strtotime($reserva['fecha']));
                                            if ($fechaReserva == $dia && $reserva['turno'] == $turno['idTurno']) {

                                                // Si es de ese profesor
                                                if ($reserva['idProfesor'] === $idProfesor) {
                                                    echo "<div class='d-flex flex-column justify-content-center align-items-center border border-success rounded p-1 mb-1' style='background-color: rgba(144, 238, 144, 0.3); border-radius: 5px; cursor: " . ($blocked ? "not-allowed" : "pointer") . ";' id='{$reserva['idReserva']}' " . ($blocked ? "" : "ondblclick='modificarReserva(this.id);'") . ">
                                                            <h6>{$reserva['profesor']} | {$reserva['asignatura']}</h6>
                                                            <p>{$reserva['clase']} | Alumnos: {$reserva['numeroAlumnos']}</p>
                                                          </div>";
                                                }
                                                $plazas -= $reserva['numeroAlumnos'];
                                            }
                                        }
                                        if ($plazas > 0) : ?>
                                            <p>Plazas libres <?= $plazas ?></p>
                                            <input type="hidden" name="plazas" id="iptPlazas" value="<?= $plazas ?>">
                                            <input type="hidden" name="fecha" id="iptFecha" value="<?= $dia ?>">
                                            <input type="hidden" name="turno" id="iptTurno" value="<?= $turno['idTurno'] ?>">
                                            <?php if ($blocked) : ?>
                                                <button type='button' class='p-0' style='background: none; border: none;' disabled="true">
                                                    <i class='bi bi-plus-circle' style="font-size: 1.5rem; color: blue; cursor:not-allowed;"></i>
                                                </button>
                                            <?php else : ?>
                                                <button type='button' class='p-0' style='background: none; border: none;' id="" onclick="crearReserva();">
                                                    <i class='bi bi-plus-circle' style="font-size: 1.5rem; color: blue; cursor:pointer;"></i>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>


        <?php if ($showModal): ?>
            <!-- MODAL -->
            <div class="modal d-block" tabindex="999" role="dialog" id="modal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Información</h5>
                            <button type="button" class="close closeModal" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p><?= empty($mensaje) ? "Mensaje informativo base: Nada que informar" : $mensaje ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary closeModal" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>





    <?php require_once "_footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
</body>

</html>