<?php
    //dar por hecho que el booleano activo es 1 y el booleano admin es 0
    //comprobar tambien que el usuario que esta conectado es el admin antes de hcer nada(usando la bd), sino redirigir al index.php
    //poner en el main, no hacer estilos
    session_start();


    //compruebo si hay un profesor conectado, so no lo mando al login
    if(!isset($_SESSION["id"])){
        header("Location:../login.php");
    }
    require_once($_SERVER['DOCUMENT_ROOT'] . '/clases/bd.class.php');
    $bd = new BD();
    
    $conexion = $bd->abrirConexion();
    //compruebo que el método es post
    
    if($_SERVER["REQUEST_METHOD"] === "POST"){
    //id del prof que usare en la co1nsulta
        $idAdmin = $_SESSION["id"];
       
        //consulta para sacar si el profesor conectado es admin o no
        $query1 = "
            SELECT admin FROM profesores
            where id = '$idAdmin';
        ";

        $resultado = mysqli_query($conexion, $query1);

        while($registro = mysqli_fetch_assoc($resultado)){
            $esAdmin = $registro["admin"];
        }
        //si no es admin le mando al index
        if($esAdmin == false){
            header("Location:../index.php");
        //si lo es hago un insert into con los datos del form
        //}else{//el 1 es el campo activo y el 0 el campo admin
        $id = $_POST['id'];
        $usuario = $_POST['usuario'];
        $passw = $_POST['passw'];
        $nombre = $_POST['nombre'];
        $ape1 = $_POST['ape1'];
        $ape2 = $_POST['ape2'];
        $email = $_POST['email'];
        $query2 = "
                INSERT INTO profesores (id, usuario, passw, nombre, ape1, ape2, activo, email, admin)
                VALUES ('$id', '$usuario', '$passw', '$nombre', '$ape1', '$ape2', 1, '$email', 0);
            ";
            $bd->insertarDatos($query2);
            //cierro la conexion con la bd
            $bd->cerrarConexion();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <main>
        <h1>Crear profesor</h1>
        <form action="crearProfesor.php" method="post">
            <input type="text" placeholder="ID Profesor" name="id" id="id"><br>
            <input type="text" placeholder="Usuario" name="usuario" id="usuario"><br>
            <input type="password" placeholder="Contraseña" name="passw" id="passw"><br>
            <input type="text" placeholder="Nombre Profesor" name="nombre" id="nombre"><br>
            <input type="text" placeholder="Primer apellido" name="ape1" id="ape1"><br>
            <input type="text" placeholder="Segundo apellido" name="ape2" id="ape2"><br>
            <input type="email" placeholder="Correo electrónico" name="email" id="email"><br>
            <button type="submit">Crear</button>
        </form>
    </main>
</body>
</html>