<?php

header("Content-type: application/json");

require_once('../clases/bd.class.php');
$bd = new BD;

// Sacamos la fecha que la recibimos por get
$fecha = (isset($_GET["fecha"])) ? htmlspecialchars($_GET["fecha"]) : null;


if (!$fecha) {
    echo json_encode(["error" => "Se requiere una fecha"]);
    exit();
}

try {

    $bd->abrirConexion();

    $fecha = Date('Y-m-d', strtotime($fecha));

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

    $disponibilidad = [];

    // Sanitizar
    foreach ($plazas as $plaza) {

        $disponibilidad[] = [
            "idTurno" => htmlspecialchars($plaza["idTurno"]),
            "disponibilidad" => htmlspecialchars($plaza["disponibilidad"])
        ];
    }

    echo json_encode($disponibilidad);
    exit();
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
    exit();
} finally {
    $bd->cerrarConexion();
}
