<?php
// Asignado a Adrián
// Muestra todas las reservas del profesor logeado ordenados por fecha

/* TAREA PENDIENTE:
* Hacer consulta, ejecutar consulta y mostrar consulta
*/

// Inicia la sesión e incluimos la clase con la base de datos
session_start();
require_once('../clases/bd.class.php');

// Comrobacion de la sesion
//if(!isset($_SESSION["idProfesor"])){
//    header("Location: ../login.php");
//}

// Consulta sql para obtener todas las reservas del usuario logeado
/* Del profesor logeado se obtiene:
*   - Tabla reservas: descr, numAlumnos, clase, fecha
*   - Tabla turno: horario
*   - Tabla asignatura: nombre
*
*   Ordenar por fecha (Desde el mas cercano al dia actual)
*/
$reservas = "SELECT
FROM
ORDER BY
";

// Ejecutar consulta

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area personal</title>
    <link rel="shortcut icon" href="../assets/Logo_type_1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <?php require_once "../_header.php"; ?>

    <h1>Area personal</h1>

    <!-- Impresion de la consulta en secciones (divs) -->
    <main>
        
    </main>

    <?php require_once "../_footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>