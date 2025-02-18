<?php

session_start();

    if(!isset($_SESSION["id"])){
        header("Location:../login.php");
        exit();
    }
    if($_SESSION["admin"] != 1){
        header("Location:index.php");
        exit();
    }
    require_once($_SERVER['DOCUMENT_ROOT'] . '/examroombooker/clases/bd.class.php');
    $bd = new BD();

    $bd->abrirConexion();

if($_SERVER["REQUEST_METHOD"] === "POST"){
    //guardo los datos que me ha pasado el usuario en variables, que voy a usar en la consulta
    $idProfesor = $_POST["id"];
    $admin = $_POST["admin"];
    //consulta para insertar en la tabla profesores un nuevo profesor con los datos indcados
    $query2 = "
            UPDATE profesores SET 
            admin = '$admin'
            WHERE id = '$idProfesor';
        ";
        //uso el metodo actualizar datos de la clase bd
        $bd->actualizarDatos($query2);
        //cierro la conexion con la bd
        $bd->cerrarConexion();
}
header("Location:modificarProfesor.php?idProfesor=$idProfesor")
?>