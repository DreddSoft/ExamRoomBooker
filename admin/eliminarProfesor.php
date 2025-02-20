<?php
//inicio sesion
session_start();
    //compruebo si hay un usuario conectado, si no lo mando a login
    if(!isset($_SESSION["idProfesor"])){
        header("Location:../login.php");
        exit();
    }
    //compruebo si el usuario conectado es admin, si no lo mando al index
    if($_SESSION["admin"] != 1){
        header("Location:../index.php");
        exit();
    }
    require_once($_SERVER['DOCUMENT_ROOT'] . '/examroombooker/clases/bd.class.php');
    //creo la base de datos
    $bd = new BD();
    try{
        //conecto a la base de datos
        $bd->abrirConexion();
        //compruebo que el metodo es psot
        if($_SERVER["REQUEST_METHOD"] === "POST"){
            //guardo el id que se ha pasado mediante post
            $idProfesor = $_POST["id"];
            //consulta para eliminar de la tabla el profesor indicado en base a su id
            $query2 = "
                    DELETE FROM profesores 
                    WHERE id = '$idProfesor';
                ";
                //uso el metodo actualizar datos de la clase bd
                $bd->actualizarDatos($query2);
                //cierro la conexion con la bd
                $bd->cerrarConexion();
        }
        //mando al usuario a mostarProfesores
        header("Location:mostrarProfesor.php");
        exit();
    }catch(Exception $e){
        echo $e->getMessage();
    }finally{
        $bd->cerrarConexion();
    }
    
?>