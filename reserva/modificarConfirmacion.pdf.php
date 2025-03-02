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

$text_base = "<!DOCTYPE html>
<html>
<head>
    <meta charset='UTF-8'>
    <title>Confirmación de Reserva</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #2c3e50;
        }
        p {
            color: #555;
            font-size: 16px;
            line-height: 1.5;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class='container'>
        <img src='cid:logo_header' style='width: 150px; height: auto; display: block; margin: 0 auto;'>
        <h2>Modificación de Reserva #$idReserva</h2>
        <p>Estimado/a $profesor,</p>
        <p>Nos complace informarle que su reserva ha sido modificada con éxito. Adjunto a este correo encontrará su comprobante de reserva. Le recomendamos guardarlo para futuras referencias.</p>
        <p>Si tiene alguna consulta, no dude en ponerse en contacto con nosotros.</p>
        <p><b>Atentamente,</b><br>ExamRoomBooker</p>
        <div class='footer'>
            <p>Este es un mensaje generado automáticamente. Por favor, no responda a este correo.</p>
        </div>
    </div>
</body>
</html>
";


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
    $mail->setFrom($_ENV["SMTP_USER"], 'ExamRoomBooker');                         //Sender's email address
    $mail->addAddress($email);                                  //Recipient's email address

    //Content
    $mail->addAttachment("reserva/confirmaciones/confirmacionReserva_$idReserva.pdf");
    $mail->isHTML(true);                                        //Set email format to HTML
    $mail->Subject = "Confirmación de Reserva $idReserva";
    $mail->Body    = $text_base;
    $mail->CharSet = 'UTF-8';

    // Adjuntar la imagen y asignarle un ID
    $mail->addEmbeddedImage($_SERVER['DOCUMENT_ROOT'] . '/ExamRoomBooker/assets/Logo_type_1.png', 'logo_header');

    $mail->send();
    // echo 'El correo se ha enviado de forma exitosa, su destinatario debe haber recivido el correo';

    // $msj = "Modificada reserva $idReserva con exito.";
    header("Location: editarReserva.php?id=$idReserva&success=1");
    exit();
} catch (Exception $e) {
    $msj = "Error: " . $e->getMessage();
    header("Location: editarReserva.php?id=$idReserva&mensaje=$msj");
    exit();
}
