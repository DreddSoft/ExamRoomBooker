<?php
session_start();
require_once('../clases/bd.class.php');
$bd = new BD;

$fechaIntroducida;
$turnoIntroducido;
$plazasLimite;

if (isset($_GET["fecha"])) {
    $fechaIntroducida = htmlspecialchars($_GET["fecha"]);
    $fechaActualizada = strtotime($fechaIntroducida);
}

if (isset($_GET["turno"])) {
    $turnoIntroducido = htmlspecialchars($_GET["turno"]);
}

if (isset($_GET["plazas"])) {
    $plazasLimite = htmlspecialchars($_GET["plazas"]);
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION["idProfesor"])) {
        $usuarioIntroducido = htmlspecialchars(string: $_SESSION["idProfesor"]); // almaceno el ide del profesorF
        if (isset($_POST["numAlumno"])) {
            $numeroAlumnos = htmlspecialchars($_POST["numAlumno"]);
            if (isset($_POST["clase"])) {
                $clase = htmlspecialchars($_POST["clase"]);
                if (isset($_POST["descripcion"])) {

                    $descripcion = htmlspecialchars($_POST["descripcion"]);

                    try {
                        $bd->abrirConexion();

                        $consultaIdAsignatura = "SELECT idAsignatura FROM asignaturasprofesores where  idProfesor=$usuarioIntroducido";
                        $idAsignatura = $bd->capturarDatos($consultaIdAsignatura);


                        $sql = "INSERT INTO 
                                reservas (
                                id,
                                idProfesor,
                                descripcion,
                                numAlumnos,
                                clase,
                                fecha,
                                idAsignatura) 


                                VALUES (
                                $idReserva,
                                $usuarioIntroducido,
                                '$descripcion',
                                $numeroAlumnos,
                                '$clase',
                                $fechaIntroducida,
                                $idAsignatura)";

                        // $datos = $bd->capturarDatos($sql);
                        // foreach ($datos as $registro) {
                        //     echo "|" . $registro['id'] . " - " . $registro['descripcion'] . " | " . $registro['numAlumnos'] . " | " . $registro['clase'] .  " | " . $registro['fecha'] . " | " . $registro['idAsignatura'] . "|" . "<br>";
                        // }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    } finally {
                        $bd->cerrarConexion();
                    }
                }
            }
        }
    }
} else {
    // echo "Se produjo un erro con los datos, reviselos bien";

    // header("Location: ../login.php"); 
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/Logo_type_1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Crear Reservas</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <!-- FOMULARIO CON LOS CAMPOS DE LA TABLA DE RESERVAS, CON LOS CAMPOS QUE HACEN FALTA             
    / -->
    <?php
    require_once("../_header.php");
    ?>
    <main>
        <?php echo $fechaIntroducida ?>

        <form action="crearReserva.php" method="post">
            <input type="dateTime" id="fecha" value="<?php echo $fechaActualizada;  ?>">


            <label for="numAlumno" name="numAlumno"> Ingrese la cantidad de alumnos para la reserva: </label><input type="number" min="1" name="numAlumno">
            <!-- Segun peticion y consulta con el cliente el minimo de alumnos por reserva es 1  -->
            <label for="clase" name="clase"> Ingrese la clase a la que se realiza la reserva: </label><input type="text" name="clase" maxlength="50">
            <label for="descripcion" name="descripcion"> Ingrese la descripcion de la clase: </label><textarea name="descripcion" id="descripcion" maxlength="250"></textarea>

            <!-- Esto se debe quitar, es solo de prueba, cambiar fecha reserva e id turno por get  -->
            <input type="submit" value="Enviar">
        </form>
    </main>

    <?php
    require_once("../_footer.php");
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>