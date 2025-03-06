<?php

session_start();

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

require_once($_SERVER['DOCUMENT_ROOT'] . '/examroombooker/clases/bd.class.php');
$bd = new BD();
try {
    $bd->abrirConexion();

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        //guardo los datos que me ha pasado el usuario en variables, que voy a usar en la consulta
        $idProfesor = $_POST["id"];
        $activo = $_POST["activo"];
        //consulta para insertar en la tabla profesores un nuevo profesor con los datos indcados
        $query2 = "
                    UPDATE profesores SET 
                    activo = '$activo'
                    WHERE id = '$idProfesor';
                ";
        //uso el metodo actualizar datos de la clase bd
        $bd->actualizarDatos($query2);
        //cierro la conexion con la bd
        $bd->cerrarConexion();
    }
    header("Location:modificarProfesor.php?idProfesor=$idProfesor");
    exit();
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    $bd->cerrarConexion();
}
