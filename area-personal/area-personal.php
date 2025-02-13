<?php
// Asignado a Adrián
// Muestra todas las reservas del profesor logeado ordenados por fecha

/* TAREA PENDIENTE:
* Arreglar para que no haya duplicados en las reservas
* Añadir la funcionalidad para editar y eleminar una reserva desde aqui
* Descomentar el bloque de comprobacion de la sesion (Cambiar el idProfesor de la consulta por el id del profesor logeado)
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
$reservas =
"SELECT
    R.descripcion,
    R.numAlumnos,
    R.clase,
    R.fecha,
    T.horario,
    A.nombre
FROM 
    RESERVAS R
    INNER JOIN TURNOS T ON R.idTurno = T.id
    INNER JOIN ASIGNATURASPROFESORES AP ON R.idProfesor = AP.idProfesor
    INNER JOIN ASIGNATURAS A ON AP.idAsignatura = A.id
WHERE 
    R.idProfesor = 12
ORDER BY 
    R.fecha ASC;"
;

// Ejecutar consulta
$bd = new BD;

try {
    $bd->abrirConexion();

    $resultados = $bd->capturarDatos($reservas);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    $bd->cerrarConexion();
}
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

    <h1 class="text-center mt-4 text-primary">Area personal</h1>
    <!--<h1 class="display-4 fw-bold text-center my-4">Area personal</h1>-->

    <!-- Impresion de la consulta en secciones -->
    <main class="container mt-4">
        <?php if (empty($resultados)): ?>
            <div class="alert alert-info" role="alert">
                No tienes reservas pendientes
            </div>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($resultados as $reserva): ?>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header bg-primary text-white">
                                <?php echo htmlspecialchars($reserva['nombre']); ?>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">Aula: <?php echo htmlspecialchars($reserva['clase']); ?></h5>

                                <p class="card-text">
                                    <strong>Descripción:</strong> <?php echo htmlspecialchars($reserva['descripcion']); ?><br>
                                    <strong>Nº Alumnos:</strong> <?php echo htmlspecialchars($reserva['numAlumnos']); ?><br>
                                    <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($reserva['fecha'])); ?><br>
                                    <strong>Horario:</strong> <?php echo htmlspecialchars($reserva['horario']); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

    <?php require_once "../_footer.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>