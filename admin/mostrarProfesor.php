<?php
    //dar por hecho que el booleano activo es 1 y el booleano admin es 0
    //comprobar tambien que el usuario que esta conectado es el admin antes de hcer nada(usando la bd), sino redirigir al index.php
    //poner en el main, no hacer estilos
    session_start();

    //compruebo si hay un profesor conectado, so no lo mando al login
    if(!isset($_SESSION["idProfesor"])){
        header("Location:../login.php");
    }
    //compruebo que el método es post
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        //id del prof que usare en la consulta
        $idAdmin = $_SESSION["idProfesor"];
        //datos y conexión bd
        $host = "localhost";
        $user = "root";
        $pass = "";
        $name_db = "profesores";

        $conexion = mysqli_connect($host, $user, $pass, $name_db);
        //consulta para sacar si el profesor conectado es admin o no
        $query1 = "
            SELECT admin FROM profesores
            where idProfesor = '$idAdmin';
        ";

        $resultado = mysqli_query($conexion, $query1);

        while($registro = mysqli_fetch_assoc($resultado)){
            $esAdmin = $registro["admin"];
        }
        //si no es admin le mando al index
        if($esAdmin == false){
            header("Location:../index.php");
        //si lo es hago un select de toda la tabla profesores
        }else{
            $query2 = "
                SELECT * FROM Profesores;
            ";

            $resultado = mysqli_query($conexion, $query2);

            while($registro = mysqli_fetch_assoc($resultado)){
                echo "<pre>";
                           $resgistro["idProfesor"] . $registros["nombreProfesor"] ;
                echo "</pre>";
            }
            $conexion->close();
        }
    }
?>