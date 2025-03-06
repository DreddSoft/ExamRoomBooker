<?php

session_start();
require_once('../clases/bd.class.php');
$bd = new BD;

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

$msj = "";

// metodo envio post
if ($_SERVER["REQUEST_METHOD"] === "POST") {


    // Capturar los datos enviados por post
    $idReserva = (isset($_POST["idReserva"])) ? htmlspecialchars($_POST["idReserva"]) : null;
    // El idProfesor lo tenemos de la sesion
    $desc = (isset($_POST["desc"])) ? htmlspecialchars($_POST["desc"]) : null;
    $clase = (isset($_POST["clase"])) ? htmlspecialchars($_POST["clase"]) : null;
    $alumnos = (isset($_POST["alumnos"])) ? intval(htmlspecialchars($_POST["alumnos"])) : null;
    $fecha = (isset($_POST["fecha"])) ? htmlspecialchars($_POST["fecha"]) : null;

    // Formatear fecha para SQL
    $fecha = Date('Y-m-d', strtotime($fecha));

    $asig = (isset($_POST["asig"])) ? intval(htmlspecialchars($_POST["asig"])) : null;


    // Turnos
    if (isset($_POST["turnos"]) && is_array($_POST["turnos"])) {
        $turnos = $_POST["turnos"];
    } else {
        $turnos = [];
    }


    try {

        $bd->abrirConexion();

        // Actualizacion
        $sql = "UPDATE Reservas
        SET descripcion = '$desc',
            numAlumnos = $alumnos,
            clase = '$clase',
            fecha = '$fecha',
            idAsignatura = $asig
        WHERE id = $idReserva
        ";

        $fila = $bd->actualizarDatos($sql);

        if ($fila == -1) {
            // Es que ha salido mal
            throw new Exception("No se ha actualizado la reserva.");
        }

        // Ahora actualizar los turnos
        // Para eso borramos todos y creamos nuevos
        $sql = "DELETE FROM ReservasTurnos
        WHERE idReserva = $idReserva";

        $filaDelete = $bd->actualizarDatos($sql);

        if ($filaDelete == -1) {
            throw new Exception("No se han borrado los turnos en ReservasTurnos.");
        }

        // Nueva inserccion
        foreach ($turnos as $turno) {

            // Se introduce uno por cada turno enviado
            $sql = "INSERT INTO ReservasTurnos (idReserva, idTurno) VALUES ($idReserva, $turno)";

            $insertado = $bd->insertarDatos($sql);

            if ($insertado == -1) {
                throw new Exception("No se ha insertado el turno correctamente en: $idReserva");
            }
        }

        // Redireccion con idReserva
        header("Location: modificarConfirmacion.pdf.php?idReserva=$idReserva");
        exit();
    } catch (Exception $e) {
        $msj = "Error: " . $e->getMessage();
        header("Location: editarReserva.php?mensaje=$msj");
        exit();
    } finally {
        $bd->cerrarConexion();
    }
}
