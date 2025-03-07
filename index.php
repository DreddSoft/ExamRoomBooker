<?php
// Iniciamos una nueva sesión.
session_start();

require_once "clases/bd.class.php";

// Si no hay ningún usuario registrado, lo redirigiríamos al login para así poder registrarse y/o iniciar sesión
if (!$_SESSION['idProfesor']) {
    header("Location: login.php");
}

// * CODIGO PARA CONTROLAR LA INACTIVIDAD DEL USUARIO
$maxTime = 600;

if (isset($_SESSION["ultimo_acceso"])) {
    $tiempo_transcurrido = time() - $_SESSION["ultimo_acceso"];

    if ($tiempo_transcurrido > $maxTime) {

        header("Location: cerrarSesion.php");
        exit();
    }
}

// Actualizamos en cada accion del user
$_SESSION['ultimo_acceso'] = time();

// Si ha transcurrido mas del tiempo de inactividad

// Siempre sanitizamos el codigo en variables
$profesor = null;

if (isset($_SESSION['nombre'])) {

    $profesor = $_SESSION['nombre'];
}

$idProfesor = $_SESSION["idProfesor"];

$esAdmin = false;
if ($_SESSION["admin"] == 1) {
    $esAdmin = true;
}

// Capturamos el lunes de la semana actual
$diaActual = date("d-m-Y");

// Sacamos el lunes de la semana actual
$semanaActual = strtotime('monday this week');

// Si recibimos parametros get de la semana
$numSemana = isset($_GET["semana"]) ? intval($_GET["semana"]) : 0;

// Nueva semana en base a la semana actual
$nuevaSemana = strtotime("+$numSemana week", $semanaActual);

if ($nuevaSemana < $semanaActual) {
    $nuevaSemana = $semanaActual;
}


$lunes = Date("d-m-Y", $nuevaSemana);
$martes = Date("d-m-Y", strtotime('+1 day', $nuevaSemana));
$miercoles = Date("d-m-Y", strtotime('+2 days', $nuevaSemana));
$jueves = Date("d-m-Y", strtotime('+3 days', $nuevaSemana));
$viernes = Date("d-m-Y", strtotime('+4 days', $nuevaSemana));


$semana = [$lunes, $martes, $miercoles, $jueves, $viernes];

// Base de datos
$bd = new BD();
$showModal = false;

if (isset($_GET["mensaje"])) {
    $mensaje = htmlspecialchars($_GET["mensaje"]);
    $showModal = true;
}

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

$fila = 1;


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

                <div>
                    <?php if ($nuevaSemana > $semanaActual) : ?>
                        <a href="index.php?semana=<?= $numSemana - 1 ?>" class="btn btn-link">&lt;&lt; Anterior</a>
                    <?php else: ?>
                        <span class="btn btn-link text-muted">&lt;&lt; Anterior</span>
                    <?php endif; ?>
                </div>
                <div>
                    <span class="text-secondary font-italic text-sm-center">Haga click en el simbolo <i class='bi bi-plus-circle mx-2' style="font-size: 1.5rem; color: blue; cursor:pointer;"></i> para crear una reserva.</span>
                    <br>
                    <span class="text-secondary font-italic text-sm-center">Doble click sobre el recuadro verde de la reserva para editarla.</span>
                </div>
                <div><a href="index.php?semana=<?= $numSemana + 1 ?>" class="btn btn-link">Siguiente &gt;&gt;</a></div>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center">
                <table class="table table-bordered w-80">
                    <thead>
                        <tr>
                            <th class="text-center bg-primary text-white fw-bold" style>Tramos</th>
                            <th class="text-center" style="<?= (strtotime($lunes) < strtotime($diaActual)) ? 'background-color: rgba(196, 196, 196, 0.2); cursor: not-allowed;' : '' ?><?= ($lunes == $diaActual) ? ' background-color: rgba(144, 238, 144, 0.1);' : 'background-color: rgba(144, 194, 238, 0.1);' ?>">Lunes <br><?= str_replace("-", "/", $lunes) ?></th>
                            <th class="text-center" style="<?= (strtotime($martes) < strtotime($diaActual)) ? 'background-color: rgba(196, 196, 196, 0.2); cursor: not-allowed;' : '' ?><?= ($martes == $diaActual) ? ' background-color: rgba(144, 238, 144, 0.1);' : 'background-color: rgba(144, 194, 238, 0.1);' ?>">Martes <br><?= str_replace("-", "/", $martes) ?></th>
                            <th class="text-center" style="<?= (strtotime($miercoles) < strtotime($diaActual)) ? 'background-color: rgba(196, 196, 196, 0.2); cursor: not-allowed;' : '' ?><?= ($miercoles == $diaActual) ? ' background-color: rgba(144, 238, 144, 0.1);' : 'background-color: rgba(144, 194, 238, 0.1);' ?>">Miércoles <br><?= str_replace("-", "/", $miercoles) ?></th>
                            <th class="text-center" style="<?= (strtotime($jueves) < strtotime($diaActual)) ? 'background-color: rgba(196, 196, 196, 0.2); cursor: not-allowed;' : '' ?><?= ($jueves == $diaActual) ? ' background-color: rgba(144, 238, 144, 0.1);' : 'background-color: rgba(144, 194, 238, 0.1);' ?>">Jueves <br><?= str_replace("-", "/", $jueves) ?></th>
                            <th class="text-center" style="<?= (strtotime($viernes) < strtotime($diaActual)) ? 'background-color: rgba(196, 196, 196, 0.2); cursor: not-allowed;' : '' ?><?= ($viernes == $diaActual) ? ' background-color: rgba(144, 238, 144, 0.1);' : 'background-color: rgba(144, 194, 238, 0.1);' ?>">Viernes <br><?= str_replace("-", "/", $viernes) ?></th>
                        </tr>

                    </thead>
                    <tbody>
                        <?php foreach ($turnos as $turno) :
                            $col = 1;
                        ?>

                            <tr>
                                <td class="bg-primary text-white  fw-bold"><?= $turno["horario"] ?></td>
                                <?php foreach ($semana as $dia) :

                                    $blocked = strtotime($dia) < strtotime($diaActual);
                                ?>
                                    <td class="text-center" style="<?= ($blocked) ? 'background-color: rgba(196, 196, 196, 0.2); cursor: not-allowed;' : '' ?>">
                                        <?php
                                        // Aqui tengo que sacar los datos de las reservas
                                        $reservaEncontrada = false;
                                        $plazas = 100;

                                        foreach ($reservas as $reserva) {

                                            // Convertir la fecha de la reserva en date
                                            $fechaReserva = Date("d-m-Y", strtotime($reserva['fecha']));
                                            if ($fechaReserva == $dia && $reserva['turno'] == $turno['idTurno']) {

                                                // Si es de ese profesor
                                                if ($reserva['idProfesor'] === $idProfesor) {
                                                    echo "<div class='d-flex flex-column justify-content-center align-items-center border border-success rounded p-1 mb-1' style='background-color: rgba(144, 238, 144, 0.3); border-radius: 5px; cursor: " . ($blocked ? "not-allowed" : "pointer") . ";' id='{$reserva['idReserva']}' " . ($blocked ? "" : "ondblclick='modificarReserva(this.id);'") . " title='Doble click para editar'>
                                                            <h6>{$reserva['profesor']} | {$reserva['asignatura']}</h6>
                                                            <p>{$reserva['clase']} | Alumnos: {$reserva['numeroAlumnos']}</p>
                                                          </div>";
                                                } else if ($esAdmin) {
                                                        echo "<div class='d-flex flex-column justify-content-center align-items-center border border-warning rounded p-1 mb-1' style='background-color: rgba(238, 238, 144, 0.3); border-radius: 5px; cursor: not-allowed;' id='{$reserva['idReserva']}' title='Reserva bloqueada'>
                                                                <h6>{$reserva['profesor']} | {$reserva['asignatura']}</h6>
                                                                <p>{$reserva['clase']} | Alumnos: {$reserva['numeroAlumnos']}</p>
                                                              </div>";
                                                    
                                                }
                                                $plazas -= $reserva['numeroAlumnos'];
                                            }
                                        }
                                        if ($plazas > 0) :

                                            $idFecha = "iptFecha$fila-$col";
                                            $idTurno = "iptTurno$fila-$col";
                                        ?>
                                            <p>Plazas libres <?= $plazas ?></p>


                                            <?php if ($blocked) : ?>
                                                <button type='button' class='p-0' style='background: none; border: none;' disabled="true">
                                                    <i class='bi bi-plus-circle' style="font-size: 1.5rem; color: blue; cursor:not-allowed;"></i>
                                                </button>
                                            <?php else : ?>
                                                <form action="reserva/crearReserva.php" method="post">
                                                    <input type="hidden" name="fecha" id="<?= $idFecha ?>" value="<?= $dia ?>">
                                                    <input type="hidden" name="turno" id="<?= $idTurno ?>" value="<?= $turno['idTurno'] ?>">
                                                    <button type='submit' class='p-0' style='background: none; border: none;' title="Pulsa aquí">
                                                        <i class='bi bi-plus-circle' style="font-size: 1.5rem; color: blue; cursor:pointer;"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        <?php endif;
                                        $idPlazas = "iptPlazas$fila-$col";
                                        ?>
                                        <input type="hidden" name="plazas" id="<?= $idPlazas ?>" value="<?= $plazas ?>">
                                        <?php $col++; ?>
                                    </td>
                                <?php endforeach; ?>
                                <?php $fila++; ?>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>


        <?php if ($showModal): ?>
            <!-- MODAL -->
            <div class="modal d-block border-white" tabindex="999" role="dialog" id="modal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header d-flex justify-content-between align-items-center bg-light">
                            <h5 class="modal-title text-dark">Información</h5>
                            <button type="button" class="btn btn-light closeModal" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body d-flex justify-content-center align-items-center">
                            <p class="text-center text-primary"><?= empty($mensaje) ? "Mensaje informativo base: Nada que informar" : $mensaje ?></p>
                        </div>
                        <div class="modal-footer bg-light">
                            <button type="button" class="btn btn-secondary closeModal" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="position-fixed top-50 start-50 translate-middle w-100 h-100 d-none justify-content-center align-items-center bg-white bg-opacity-75" id="loading-screen" style="z-index: 999;">
            <div class="spinner-border text-primary m-auto" role="status" style="width: 3rem; height: 3rem;">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </main>





    <?php require_once "_footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script.js"></script>
</body>

</html>