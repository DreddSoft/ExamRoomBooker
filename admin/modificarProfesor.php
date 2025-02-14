<?php

    session_start();

    $idProfesor = null;
    if(isset($_GET["idProfesor"])){
        $idProfesor = $_GET["idProfesor"];
    }
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

    $query1 = "
        SELECT usuario, passw, nombre, ape1,ape2, activo,email,admin FROM profesores
        where id = '$idProfesor';
    ";

    $resultado = $bd->capturarDatos($query1);

    $usuario = $resultado[0]["usuario"];
    $pass = $resultado[0]["passw"];
    $nombre = $resultado[0]["nombre"];
    $ape1 = $resultado[0]["ape1"];
    $ape2 = $resultado[0]["ape2"] ? $resultado[0]["ape2"] : "";
    $activo = $resultado[0]["activo"];
    $email = $resultado[0]["email"];
    $admin = $resultado[0]["admin"];
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/Logo_type_1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Modificar Profesor</title>
</head>
<body>
    <?php require_once("../_header.php")?>
    <main>
        <section>
            <form action="modificarProfesor.php" method="post">
                <input type="text" placeholder="Usuario" name="usuario" id="usuario" value="<?=$usuario ?>"><br>
                <input type="password" placeholder="Contraseña" name="passw" id="passw" value="<?=$pass ?>"><br>
                <input type="text" placeholder="Nombre Profesor" name="nombre" id="nombre" value="<?=$nombre ?>"><br>
                <input type="text" placeholder="Primer apellido" name="ape1" id="ape1" value="<?=$ape1 ?>"><br>
                <input type="text" placeholder="Segundo apellido" name="ape2" id="ape2" value="<?=$ape2 ?>"><br>
                <input type="email" placeholder="Correo electrónico" name="email" id="email" value="<?=$email ?>"><br>
            <button type="submit">Crear</button>
            </form>
        </section>
    </main>
    <?php require_once("../_footer.php")?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>