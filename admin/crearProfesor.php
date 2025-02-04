<?php
    //dar por hecho que el booleano activo es 1 y el booleano admin es 0
    //comprobar tambien que el usuario que esta conectado es el admin antes de hcer nada(usando la bd), sino redirigir al index.php
    session_start();

    $baseDatos;

    if(!isset($_SESSION["idProfesor"])){
       // header("Location:../login.php");
    }

    if($_SERVER["REQUEST_METHOD"] === "POST"){



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
    
    <form action="crearProfesor.php" method="post">
        <input type="text" placeholder="ID Profesor" name="idProfesor" id="idProfesor"><br>
        <input type="text" placeholder="Usuario" name="usuario" id="usuario"><br>
        <input type="password" placeholder="Contraseña" name="pass" id="pass"><br>
        <input type="text" placeholder="Nombre Profesor" name="nombre" id="nombre"><br>
        <input type="text" placeholder="Primer apellido" name="ap1" id="ap1"><br>
        <input type="text" placeholder="Segundo apellido" name="ap2" id="ap2"><br>
        <input type="email" placeholder="Correo electrónico" name="email" id="email">

    </form>

</body>
</html>