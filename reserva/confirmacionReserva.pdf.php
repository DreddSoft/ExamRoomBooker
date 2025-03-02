<?php

use Dompdf\Dompdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

session_start();
require_once('../clases/bd.class.php');
require('C:\xampp\htdocs\ExamRoomBooker\vendor\autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/ExamRoomBooker/vendor/autoload.php');

$dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'] . '/ExamRoomBooker');
$dotenv->load();


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
    usuario,
    email
    FROM Profesores
    WHERE id = $idProfesor";

    $profesores = $bd->capturarDatos($sql);

    if (empty($profesores)) {
        throw new Exception("No se han podido capturar los datos de los profesores en confirmacionReserva.pdf.php.");
    }

    $usuario = $profesores[0]["usuario"];
    $email = $profesores[0]["email"];

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

    // Sacar los horarios de turnos de la reserva
    $sql = "SELECT 
    T.id,
    T.horario
    FROM Turnos T
    LEFT JOIN ReservasTurnos RT ON RT.idTurno = T.id
    WHERE RT.idReserva = $idReserva";

    $turnos = $bd->capturarDatos($sql);

    if (empty($turnos)) {
        throw new Exception("No se han podido capturar los datos de los turnos en confirmacionReserva.pdf");
    } 

    $turnosStr = "";

    for ($i = 0; $i < sizeof($turnos); $i++) {

        if ($i == sizeof($turnos) - 1) {
            $turnosStr .= $turnos[$i]["horario"];
            break;
        }

        $turnosStr .= $turnos[$i]["horario"] . ", ";

    }
} catch (Exception $e) {
    $msj = "Error: " . $e->getMessage();
    header("../index.php?mensaje=$msj");
    exit();
} finally {
    $bd->cerrarConexion();
}

// PDF
$dompdf = new Dompdf();

chdir("../");
$currentSite = getcwd();
$logoPath = $currentSite . "/assets/ExamRoomBooker.jpg";

$path = $logoPath;
$type = pathinfo($path, PATHINFO_EXTENSION);
$data = file_get_contents($path);
$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);

$selloPath = $currentSite . "/assets/logo-iesjorgeguillen-footer.svg";
$selloType = pathinfo($selloPath, PATHINFO_EXTENSION);
$selloData = file_get_contents($selloPath);
$selloBase64 = 'data:image/' . $selloType . ';base64,' . base64_encode($selloData);


$html = "
<style>
    body { font-family: Arial, sans-serif; }
    .container { border: 1px solid black; padding: 20px; }
    .header { text-align: center; margin-bottom: 20px; }
    .logo { width: 400px; }
    .title { font-size: 22px; font-weight: bold; color: #642686;}
    .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .table th, .table td { border: 1px solid black; padding: 10px; text-align: left; }
    .footer { margin-top: 20px; font-style: italic; }
    .firma {width: 200px;}
</style>

<div class='container'>
    <div class='header'>
        <img class='logo' src='$base64' />
        <p class='title'>Confirmación de Reserva: <b style='color: #642686; font-size: 30px'>$idReserva</b></p>
    </div>
    <p>Estimado/a <strong>$profesor</strong>,</p>
    <p>Le confirmamos que su reserva ha sido procesada con éxito. A continuación, los detalles:</p>
    <table class='table'>
        <tr><th>Fecha de reserva</th><td>$fecha</td></tr>
        <tr><th>Clase o sala</th><td>$clase</td></tr>
        <tr><th>Número de alumnos</th><td>$numAlumnos</td></tr>
        <tr><th>Asignatura o actividad</th><td>$nombreAsignatura</td></tr>
        <tr><th>Descripción</th><td>$descripcion</td></tr>
        <tr><th>Horarios</th><td>$turnosStr</td></tr>
    </table>
    <p class='footer'>Por favor, conserve este documento como comprobante de su reserva.</p>
    <p><strong>Administración del centro</strong></p>
    <img class='firma' src='$selloBase64' />
</div>
";

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// $newFileName = "confirmacionReserva_$idReserva.pdf";
// $dompdf->stream("confirmacionReserva_$idReserva.pdf", array("Attachment" => false));
// $output = $dompdf->output();


$confirmacionesDir = "reserva/confirmaciones";
if (!is_dir($confirmacionesDir)) {
    mkdir($confirmacionesDir, 0777, true);
}
file_put_contents("$confirmacionesDir/confirmacionReserva_$idReserva.pdf", $dompdf->output());

//TODO: Código para en envío de email

$text_base = "<h2>Confirmación de Reserva $idReserva</h2>
<p>Su reserva ha sido confirmada, adjunto a este email tiene usted la confirmación de su reserva, recuerde guardar dicha confirmación.</p>
<p><b>Un cordial saludo.</b></p>";;

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

try {
    //Server settings               
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = $_ENV["SMTP_HOST"];                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = $_ENV["SMTP_USER"];                     //SMTP username
    $mail->Password   = $_ENV["SMTP_PASS"];                     //SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = $_ENV["SMTP_PORT"];                     //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //Recipients
    $mail->setFrom($_ENV["SMTP_USER"]);                         //Sender's email address
    $mail->addAddress($email);                                  //Recipient's email address

    //Content
    $mail->addAttachment("reserva/confirmaciones/confirmacionReserva_$idReserva.pdf"); 
    $mail->isHTML(true);                                        //Set email format to HTML
    $mail->Subject = "Confirmación de Reserva $idReserva";
    $mail->Body    = $text_base;
    $mail->CharSet = 'UTF-8';

    $mail->send();
    // echo 'El correo se ha enviado de forma exitosa, su destinatario debe haber recivido el correo';

    $msj = "Creada Reserva $idReserva con exito.";
    header("Location: ../index.php?mensaje=$msj");
    exit();

} catch (Exception $e) {
    $msj = "Error: " . $e->getMessage();
    header("Location: ../index.php?mensaje=$msj");
    exit();
}

