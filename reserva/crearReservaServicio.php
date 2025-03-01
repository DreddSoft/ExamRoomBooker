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


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Descripción
    if (isset($_POST["descripcion"]))
        $descripcion = $_POST['descripcion'];


    // NumAlumnos
    if (isset($_POST["numAlumno"]))
        $numeroAlumnos = htmlspecialchars($_POST["numAlumno"]);


    // Clase
    if (isset($_POST["clase"]))
        $clase = htmlspecialchars($_POST["clase"]);

    // fecha
    if (isset($_POST["fecha"])) {
        $fecha = htmlspecialchars($_POST["fecha"]);
        $fecha = Date('Y-m-d', strtotime($fecha)); // Formatear la fecha para MySQL
    }

    // idAsignatura
    if (isset($_POST["asig"])) {
        $idAsignatura = htmlspecialchars($_POST["asig"]);
    }

    // Turnos
    if (isset($_POST["turnos"]) && is_array($_POST["turnos"])) {
        $turnos = $_POST["turnos"]; 
    } else {
        $turnos = []; 
    }

    foreach ($turnos as $turno) {
        echo $turno . "<br>";
    }

    // Insercción en base de datos
    try {

        $bd->abrirConexion();

        $sql = "INSERT INTO reservas (
                        idProfesor,
                        descripcion,
                        numAlumnos,
                        clase,
                        fecha,
                        idAsignatura) 
                    VALUES (
                        '$idProfesor',
                        '$descripcion',
                        $numeroAlumnos,
                        '$clase',
                        '$fecha',
                        $idAsignatura)";

        $idRva = $bd->insertarDatos($sql);

        // Bucle for each para introducir los turnos
        foreach ($turnos as $turno) {

            // Se introduce uno por cada turno enviado
            $sql = "INSERT INTO ReservasTurnos (idReserva, idTurno) VALUES ($idRva, $turno)";

            $insertado = $bd->insertarDatos($sql);

            if ($insertado == -1) {
                throw new Exception("No se ha insertado el turno correctamente en: $idRva");
            }

        }


        if ($idRva == -1)
            throw new Exception("No se ha creado la reserva, probable error en base de datos.");

        
    } catch (Exception $e) {
        echo $e->getMessage();
        $msj = "Error: " . $e->getMessage();
        // Redirección a index con mensaje de error
        header("Location: ../index.php?mensaje=$msj");
        exit();
    } finally {
        $bd->cerrarConexion();
    }

    //TODO: Crear un PDF y enviarlo con los datos de la reserva
    $msj = "Exito: Nueva Reserva creada con id: $idRva";
    header("Location: confirmacionReserva.pdf.php?idReserva=$idRva");
    exit();


    // $msj = "Exito: Nueva Reserva creada con id: $idRva";
    // header("Location: ../index.php?mensaje=$msj");
    // exit();
} else {
    $msj = "Error: método de envío erróneo en crearReserva.php";
    header("Location: ../index.php?mensaje=$msj");
    exit();
}
