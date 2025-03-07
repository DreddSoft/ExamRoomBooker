<?php
//inicio la session
session_start();

//compruebo si hay un usuario conectado, si no lo mando al login
if (!isset($_SESSION["idProfesor"])) {
    header("Location:../login.php");
    exit();
}
if ($_SESSION["admin"] != 1) {
    header("Location:../index.php");
    exit();
}

// * CODIGO PARA CONTROLAR LA INACTIVIDAD DEL USUARIO
$maxTime = 600;

if (isset($_SESSION["ultimo_acceso"])) {
    $tiempo_transcurrido = time() - $_SESSION["ultimo_acceso"];

    if ($tiempo_transcurrido > $maxTime) {

        header("Location: ../cerrarSesion.php");
        exit();
    }
}

// Actualizamos en cada accion del user
$_SESSION['ultimo_acceso'] = time();

//requiero la clase bd
require_once($_SERVER['DOCUMENT_ROOT'] . '/examroombooker/clases/bd.class.php');
$bd = new BD();

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/Logo_type_1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Listado de Profesores</title>
    <style>
        .cursor {
            cursor: pointer;
        }

        .cursor:hover {
            filter: brightness(70%);
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php require_once("../_header.php") ?>
    <?php


    try {
        //conecto a la base de datos
        $conexion = $bd->abrirConexion();
        //compruebo si el usuario conectado es admin
        if ($_SESSION["admin"] != 1) {
            header("Location:index.php");
            exit();
        }
        //si existe mActivos en post, creo una variable con la que voy a permitir al admin filtar por profesores activos
        if (isset($_POST['mActivos'])) {
            if ($_POST['mActivos'] == 1) {
                $consulta = "WHERE activo = 1";
            } else {
                $consulta = "WHERE activo = 0";
            }
        } else {
            $consulta = "";
        }
        //consulta para seleccionar todos los profesores
        $query2 = "
                SELECT * FROM Profesores $consulta;
            ";
        //llamo al metodo capturar datos de la clase bd
        $resultado = $bd->capturarDatos($query2);
        //hago una tabla con los datos de resultado
        echo "<main>";
        echo "<h2 style='color: #642686; text-align: center;' class='m-5'>Listado de Profesores</h2>";
        //boton para filtar por activo o inactivo
        echo "<form action='mostrarProfesor.php' method='post' id='form-filtros' class='d-flex justify-content-around align-items-center'>
                <div>
                    <input class='btn-check' type='radio' name='mActivos' id='act1' value='1'>
                    <label class='btn btn-outline-primary' for='act1'>
                        Activos
                    </label>
                    <input class='btn-check' type='radio' name='mActivos' id='act2' value='0'>
                    <label class='btn btn-outline-primary' for='act2'>
                        Inactivos
                    </label>
                </div>
                <button class='btn btn-primary' type='submit'>
                    <i class='bi bi-search'></i> Filtrar
                </button>
            </span>
            </form>";
        echo "<i class='text-secundary d-flex justify-content-center align-items-center mb-0'>Doble click sobre la fila para modificar el profesor.</i>";
        echo "<section class='text-center m-0'>";
        echo "<table id='tabla' class='table table-bordered m-5' style='width: 90%;'>";
        echo "<th style='background-color:DodgerBlue;'>ID</th><th style='background-color:DodgerBlue;'>Usuario</th><th style='background-color:DodgerBlue;'>Nombre</th><th style='background-color:DodgerBlue;'>1er Apellido</th><th style='background-color:DodgerBlue;'>2do Apellido</th><th style='background-color:DodgerBlue;'>Estado</th><th style='background-color:DodgerBlue;'>Email</th><th style='background-color:DodgerBlue;'>Admin</th>";
        foreach ($resultado as $registro) {
            echo "<tr  id='" . $registro['id'] . "' class='cursor'>";
            //paso el valor numerico tanto de "activo" como de "admin" a algo mas comprensible para el usuario
            if ($registro["activo"] == 1) {
                $estado = "Activo";
            } else {
                $estado = "Inactivo";
            }
            if ($registro["admin"] == 1) {
                $esAdmin = "Admin";
            } else {
                $esAdmin = "No admin";
            }
            echo "<td>" . $registro['id'] . "</td><td>" . $registro['usuario'] . "</td><td>" . $registro['nombre'] . "</td><td>" . $registro['ape1'] . "</td><td>" . $registro['ape2'] . "</td><td>" . $estado . "</td><td>" . $registro['email'] . "</td><td>" . $esAdmin . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "</section>";
        echo "</main>";
    } catch (Exception $e) {
        echo $e->getMessage();
    } finally {
        //llamo al metodo cerrar conexxion de la clase bd
        $bd->cerrarConexion();
    }
    ?>
    <div class="position-fixed top-50 start-50 translate-middle w-100 h-100 d-none justify-content-center align-items-center bg-white bg-opacity-75" id="loading-screen" style="z-index: 999;">
        <div class="spinner-border text-primary m-auto" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <?php require_once("../_footer.php") ?>
    <script src="admin_logica.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</body>

</html>