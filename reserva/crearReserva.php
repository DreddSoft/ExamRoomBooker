<?php
session_start();
require_once('../clases/bd.class.php');
$bd = new BD;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION["idProfesor"]) && isset($_GET["fecha"]) &&  isset($_GET["id"])) //id reserva {
        $usuarioIntroducido = htmlspecialchars($_SESSION["idProfesor"]); // almaceno el ide del profesor
        $fechaIntroducida = htmlspecialchars($_GET["fecha"]); // almaceno la fecha, que pido por get
        $turnoIntroducido = htmlspecialchars($_GET["id"]); // almaceno el id reserva, que pido por get

        //pillar el id Asignatura, haciendo select de la tabla de asignaturasprofesores, donde el id del profesor sea $usuarioIntroducido

    try {
        //reenvia con el submit para hacer el insert


        $bd->abrirConexion();

        $sql = "SELECT * FROM reservas where  idProfesor=1 ";

        //pasar variable por parametro para hacer la consulta
        $datos = $bd->capturarDatos($sql);

        foreach ($datos as $profesor) {
            echo $profesor['id'] . " - " . $profesor['usuario'] . " | " . $profesor['nombre'] . "<br>";
        }









    } catch (Exception $e) {
        echo $e->getMessage();
    } finally {
        $bd->cerrarConexion();
    }
}
// header("Location: ../login.php"); 


if (empty($usuarioIntroducido)) {
    $mensaje = "Debes rellenar todos los campos";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formualrio</title>
</head>

<body>
    <!-- FOMULARIO CON LOS CAMPOS DE LA TABLA DE RESERVAS, CON LOS CAMPOS QUE HACEN FALTA            

   / -->
    <form action="crearReserva.php" method="post">
        <label for="numAlumno" name="numAlumno"> Ingrese la cantidad de alumnos para la reserva: </label><input type="number" min="1" name="numAlumno"><br> 
        <!-- Segun peticion y consulta con el cliente el minimo de alumnos por reserva es 1  -->
        <label for="clase" name="clase"> Ingrese la clase a la que se realiza la reserva: </label><input type="text" name="clase" maxlength="50"><br>
        <label for="descripcion" name="descripcion"> Ingrese la descripcion de la clase: </label><textarea name="descripcion" id="descripcion" maxlength="250"></textarea><br>

        <!-- Esto se debe quitar, es solo de prueba, cambiar fecha reserva e id turno por get  -->
        <input type="submit" value="Enviar">
    </form>
</body>

</html>