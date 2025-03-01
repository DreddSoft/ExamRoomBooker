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

$msj = "";



// Reserva a null
$idReserva = null;

// Capturamos idReserva por GET
if (isset($_GET["idReserva"])) {
    $idReserva = intval(htmlspecialchars($_GET["idReserva"]));
}


try {

    // Conexion
    $bd->abrirConexion();

    // Sentencia DELETE para eliminar la reserva
    $sqlDelete = "DELETE FROM reservas 
                    WHERE id = $idReserva";

    if ($idReserva != null) {
        $resultadoDelete = $bd->actualizarDatos($sqlDelete);
    }

    if ($resultadoDelete == -1) {

        throw new Exception("La reserva no se ha eliminado correctamente.");
    }

    $msj = "Reserva eliminada correctamente.";
    header("Location: ../index.php?mensaje=$msj");
    exit();
} catch (Exception $e) {

    $msj = "Error: " . $e->getMessage();
    header("Location: ../index.php?mensaje=$msj");
    exit();
} finally {
    // Cerrar conexion
    $bd->cerrarConexion();
}
