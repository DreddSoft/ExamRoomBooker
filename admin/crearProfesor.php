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
        //si lo es hago un insert into con los datos del form
        }else{//el 1 es el campo activo y el 0 el campo admin
            $query2 = "
                INSERT INTO profesores (id, usuario, pass, nombre, ape1, ape2, activo, email, admin)
                VALUES ('$_POST[idProfesor]', '$_POST[usuario]', '$_POST[pass]', '$_POST[nombre]', '$_POST[ap1]', '$_POST[ape2]', 1, '$_POST[email]', 0); 
            ";
            //compruebo si se ha creado correctamente o ha dado error
            if($conexion->query($query2) === TRUE) {
                echo "Nuevo profesor registrado";
            }else{
                echo "Error" . $query2 . "<br>" . $conexion->error;
            }
            //cierro la conexion con la bd
            $conexion->close();
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
            <input type="text" placeholder="ID Profesor" name="idProfesor" id="idProfesor"><br>
            <input type="text" placeholder="Usuario" name="usuario" id="usuario"><br>
            <input type="password" placeholder="Contraseña" name="pass" id="pass"><br>
            <input type="text" placeholder="Nombre Profesor" name="nombre" id="nombre"><br>
            <input type="text" placeholder="Primer apellido" name="ap1" id="ap1"><br>
            <input type="text" placeholder="Segundo apellido" name="ap2" id="ap2"><br>
            <input type="email" placeholder="Correo electrónico" name="email" id="email">
        </form>
    </main>
</body>
</html>