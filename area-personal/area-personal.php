<?php
// Asignado a Adrián
// Muestra todas las reservas del profesor logeado ordenados por fecha

// Inicia la sesión e incluimos la clase con la base de datos
session_start();
require_once('../clases/bd.class.php');

// Comrobacion de la sesion
if(!isset($_SESSION["idProfesor"])){
    header("Location: ../login.php");
}

// Recatamos el id del profesor logeado
$idProfesor = $_SESSION["idProfesor"];

// Consulta sql para obtener todas las reservas del usuario logeado
/* Del profesor logeado se obtiene:
*   - Tabla reservas: id, descripcion, numAlumnos, clase, fecha
*   - Tabla turno: horario
*   - Tabla asignatura: nombre
*
*   Ordenar por fecha (Desde el mas cercano al dia actual)
*   y por horario
*/
$reservas =
"SELECT DISTINCT
    R.id,
    R.descripcion,
    R.numAlumnos,
    R.clase,
    R.fecha,
    GROUP_CONCAT(T.horario ORDER BY T.horario ASC SEPARATOR ', ') as horario,
    A.nombre
FROM
    RESERVAS R
    INNER JOIN RESERVASTURNOS RT ON R.id = RT.idReserva
    INNER JOIN TURNOS T ON RT.idTurno = T.id
    INNER JOIN ASIGNATURAS A ON R.idAsignatura = A.id
WHERE
    R.idProfesor = $idProfesor AND
    R.fecha >= CURRENT_DATE()
GROUP BY
    R.id,
    R.descripcion,
    R.numAlumnos,
    R.clase,
    R.fecha,
    A.nombre
ORDER BY
    R.fecha ASC,
    T.horario ASC;"
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
    
    <title>Area Personal</title>
    <link rel="shortcut icon" href="../assets/Logo_type_1.png" type="image/x-icon">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        [id^="reserva-"] {
            cursor: pointer;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }

        [id^="reserva-"]:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
    </style>

    <script src="./eventos-area-personal.js" defer></script>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once "../_header.php"; ?>

    <h1 class="text-center mt-4 text-primary">Area Personal</h1>

    <!-- Contenedor para imprimir el resultado de las reservas -->
    <main class="container mt-4 my-4">
        <!-- En caso de que no haya reservas -->
        <?php if (empty($resultados)): ?>
            <div class="alert alert-info" role="alert">
                Actualmente no dispones de ninguna reserva de la sala.<br>
                <a href="../index.php" class="alert-link">Reservar una sala</a>
            </div>
        <!-- En caso de que si haya reservas -->
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($resultados as $reserva): ?>
                    <div class="col-md-4" id="reserva-<?php echo $reserva['id']; ?>">
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