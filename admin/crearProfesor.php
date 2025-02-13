<?php
    //dar por hecho que el booleano activo es 1 y el booleano admin es 0
    //comprobar tambien que el usuario que esta conectado es el admin antes de hcer nada(usando la bd), sino redirigir al index.php
    //poner en el main, no hacer estilos
    //inicio sesion
    session_start();
    //ESTO ES PARA PRUEBA, BORRAR LUEGO!!!!!!!!!!!
    $_SESSION["id"] = 11;
    //compruebo si hay un profesor conectado, si no lo mando al login
    if(!isset($_SESSION["id"])){
        header("Location:../login.php");
    }
    //requiero la clase bd
    require_once($_SERVER['DOCUMENT_ROOT'] . '/examroombooker/clases/bd.class.php');
    $bd = new BD();
    //conecto a la base de datos
    $conexion = $bd->abrirConexion();
    //guardo el id de sesion en una variable para usarlo luego
    $idAdmin = $_SESSION["id"];
       
        //consulta para sacar si el profesor conectado es admin o no
        $query1 = "
            SELECT admin FROM profesores
            where id = '$idAdmin';
        ";
        $resultado = $bd->capturarDatos($query1);

        //si no es admin le mando al index
        if($resultado[0]["admin"] == 0){
            header("Location:../index.php");
    //compruebo que el método es post
    if($_SERVER["REQUEST_METHOD"] === "POST"){
        //guardo los datos que me ha pasado el usuario en variables, que voy a usar en la consulta
        $id = htmlspecialchars($_POST['id']);
        $usuario = htmlspecialchars($_POST['usuario']);
        $passw = htmlspecialchars($_POST['passw']);
        $nombre = htmlspecialchars($_POST['nombre']);
        $ape1 = htmlspecialchars($_POST['ape1']);
        $ape2 = htmlspecialchars($_POST['ape2']);
        $email = htmlspecialchars($_POST['email']);
        //consulta para insertar en la tabla profesores un nuevo profesor con los datos indcados
        $query2 = "
                INSERT INTO profesores (id, usuario, passw, nombre, ape1, ape2, activo, email, admin)
                VALUES ('$id', '$usuario', '$passw', '$nombre', '$ape1', '$ape2', 1, '$email', 0);
            ";
            //uso el metodo insertar datos de la clase bd
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