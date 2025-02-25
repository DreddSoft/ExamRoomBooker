<?php
session_start();
require_once('../clases/bd.class.php');
$bd = new BD;

if (isset($_POST["fecha"])) {
    $fechaIntroducida = htmlspecialchars($_POST["fecha"]);
    $fechaActualizada = date('Y-m-d', strtotime($fechaIntroducida)); // Formatear la fecha para MySQL
}

if (isset($_POST["turno"])) {
    $turnoIntroducido = htmlspecialchars($_POST["turno"]);
}

if (isset($_POST["plaza1"])) {
    $plaza1 = htmlspecialchars($_POST["plaza1"]);
}

if (isset($_POST["plaza2"])) {
    $plaza2 = htmlspecialchars($_POST["plaza2"]);
}

if (isset($_POST["plaza3"])) {
    $plaza3 = htmlspecialchars($_POST["plaza3"]);
}

if (isset($_POST["plaza4"])) {
    $plaza4 = htmlspecialchars($_POST["plaza4"]);
}

if (isset($_POST["plaza5"])) {
    $plaza5 = htmlspecialchars($_POST["plaza5"]);
}

if (isset($_POST["plaza6"])) {
    $plaza6 = htmlspecialchars($_POST["plaza6"]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_SESSION["idProfesor"])) {
        $usuarioIntroducido = htmlspecialchars($_SESSION["idProfesor"]); // almaceno el id del profesor

        if (isset($_POST["numAlumno"])) {
            $numeroAlumnos = htmlspecialchars($_POST["numAlumno"]);

            if (isset($_POST["clase"])) {
                $clase = htmlspecialchars($_POST["clase"]);

                if (isset($_POST["descripcion"])) {
                    $descripcion = htmlspecialchars($_POST["descripcion"]);

                    try {
                        $bd->abrirConexion();

                        $consultaIdAsignatura = "SELECT idAsignatura FROM asignaturasprofesores WHERE idProfesor='$usuarioIntroducido'";
                        $idAsignaturaArray = $bd->capturarDatos($consultaIdAsignatura);
                        if (!empty($idAsignaturaArray)) {
                            $idAsignatura = $idAsignaturaArray[0]['idAsignatura'];

                            $sql = "INSERT INTO reservas (
                                        idProfesor,
                                        descripcion,
                                        numAlumnos,
                                        clase,
                                        fecha,
                                        idAsignatura) 
                                    VALUES (
                                        '$usuarioIntroducido',
                                        '$descripcion',
                                        $numeroAlumnos,
                                        '$clase',
                                        '$fechaActualizada',
                                        $idAsignatura)";

                            $resultado = $bd->insertarDatos($sql);

                            
                        } else {
                            echo "Error: No se encontró la asignatura para el profesor.";
                        }
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    } finally {
                        $bd->cerrarConexion();
                    }
                } else {
                    echo "Error: Descripción no proporcionada.";
                }
            } else {
                echo "Error: Clase no proporcionada.";
            }
        } else {
            echo "Error: Número de alumnos no proporcionado.";
        }
    } else {
        echo "Error: ID de profesor no encontrado.";
    }
} else if ($_SERVER["REQUEST_METHOD"] !== "GET") {
    echo "Método de solicitud no permitido.";
}
?>