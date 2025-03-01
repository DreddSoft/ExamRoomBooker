<?php
use Dompdf\Dompdf;
session_start();
require_once('../clases/bd.class.php');
require('C:\xampp\htdocs\ExamRoomBooker\vendor\autoload.php');


$bd = new BD;

if (isset($_SESSION['idProfesor'])) {
    $idProfesor = $_SESSION["idProfesor"];
    $profesor = $_SESSION['nombre'];
} else {
    header("Location: ../login.php");
    exit();
}

// Reserva a null
$idReserva = null;

// Capturamos idReserva por GET
if (isset($_GET["idReserva"])) {
    $idReserva = intval(htmlspecialchars($_GET["idReserva"]));
}

$msj = "";

// Datos de la reserva
try {
    $bd->abrirConexion();

    $sql = "SELECT 
    idProfesor,
    descripcion,
    numAlumnos,
    clase,
    fecha,
    idAsignatura
    FROM Reservas
    WHERE id = $idReserva";

    $reserva = $bd->capturarDatos($sql);

    if (empty($reserva)) { 
        throw new Exception("No se ha podido capturar los datos de la reserva en confirmacionReserva.pdf");
    }

    // Sanitizar los datos de la reserva
    $descripcion = $reserva[0]["descripcion"];
    $numAlumnos = $reserva[0]["numAlumnos"];
    $clase = $reserva[0]["clase"];
    $fecha = $reserva[0]["fecha"];
    $fecha = date("d/m/Y", strtotime($fecha));
    $idAsignatura = $reserva[0]["idAsignatura"];

    // Sacar asignaturas
    $sql = "SELECT nombre FROM Asignaturas WHERE id = $idAsignatura";

    $asignaturas = $bd->capturarDatos($sql);

    if (empty($asignaturas)) {
        throw new Exception("No se ha podido capturar los datos de las asignaturas en confirmacionReserva.pdf");
    }

    $nombreAsignatura = $asignaturas[0]["nombre"];

} catch (Exception $e) {
    $msj = "Error: " . $e->getMessage();
    header("../index.php?mensaje=$msj");
    exit();
} finally {
    $bd->cerrarConexion();
}

// Create new PDF document
$dompdf = new Dompdf();

// Add content
$html = "
<img src='..assets/ExamRoomBooker.png' />
<h1>Confirmación de Reserva <b style='color: red;'>$idReserva</b></h1>
<p>Gracias por su reserva. A continuación se muestran los detalles de su reserva:</p>
<ul>
    <li><strong>Nombre:</strong> $profesor</li>
    <li><strong>Fecha:</strong> $fecha</li>
    <li><strong>Clase:</strong> $clase</li>
    <li><strong>Número de alumnos:</strong> $numAlumnos</li>
    <li><strong>Asignatura:</strong> $nombreAsignatura</li>
</ul>
<p>Por favor, conserve este documento como comprobante de su reserva.</p>
";

// Load HTML content
$dompdf->loadHtml($html);

// (Optional) Set up the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream('confirmacionReserva.pdf', array("Attachment" => false));

$msj = "Exito: Nueva Reserva creada con id: $idReserva";
header("Location: ../index.php?mensaje=$msj");
exit();
?>
