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
    try{
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
        $admin = $resultado[0]["admin"] == 1 ? 1 : 0;


        if($_SERVER["REQUEST_METHOD"] === "POST"){
            //guardo los datos que me ha pasado el usuario en variables, que voy a usar en la consulta
            $usuario = htmlspecialchars($_POST['usuario']);
            $passw = htmlspecialchars($_POST['passw']);
            $nombre = htmlspecialchars($_POST['nombre']);
            $ape1 = htmlspecialchars($_POST['ape1']);
            $ape2 = htmlspecialchars($_POST['ape2']);
            $email = htmlspecialchars($_POST['email']);
            //consulta para insertar en la tabla profesores un nuevo profesor con los datos indcados
            $query2 = "
                    UPDATE profesores SET 
                    usuario = '$usuario', 
                    passw = '$passw', 
                    nombre = '$nombre', 
                    ape1 = '$ape1', 
                    ape2 = '$ape2', 
                    email = '$email'
                    WHERE id = $idProfesor;
                ";
                //uso el metodo actualizar datos de la clase bd
                $bd->actualizarDatos($query2);       
        }      
    }catch(Exception $e){
        echo $e->getMessage();
    }finally{
        //cierro la conexion con la bd
        $bd->cerrarConexion();
    }
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
<body class="d-flex flex-column min-vh-100">
    <?php require_once("../_header.php")?>
    <main>
        <section class="text-center">
            <form action="modificarProfesor.php?idProfesor=<?=$idProfesor?>" method="post">
                <input type="text" placeholder="Usuario" name="usuario" class="form-control-lg mt-1" id="usuario" value="<?=$usuario ?>"><br>
                <input type="password" placeholder="Contraseña" name="passw" class="form-control-lg mt-1" id="passw" value="<?=$pass ?>"><br>
                <input type="text" placeholder="Nombre Profesor" name="nombre" class="form-control-lg mt-1" id="nombre" value="<?=$nombre ?>"><br>
                <input type="text" placeholder="Primer apellido" name="ape1" class="form-control-lg mt-1" id="ape1" value="<?=$ape1 ?>"><br>
                <input type="text" placeholder="Segundo apellido" name="ape2" class="form-control-lg mt-1" id="ape2" value="<?=$ape2 ?>"><br>
                <input type="email" placeholder="Correo electrónico" name="email" class="form-control-lg mt-1" id="email" value="<?=$email ?>"><br><br>
            <section class="d-inline-flex p-2">
                <button type="submit" class="btn btn-primary m-1">Modificar</button>
                </form>
                <form action="eliminarProfesor.php" method="post">
                    <input type="hidden" value="<?= $idProfesor?>" name="id">
                    <button type="submit" class="btn btn-danger m-1">Eliminar</button>
                </form>
            </section>
            <section class="d-inline-flex p-2">
                <form action="cambiarStatusProfesor.php" method="post">
                    <?php if($activo == 1):?>
                        <input type="hidden" value="<?= $idProfesor?>" name="id">
                        <input type="hidden" value="0" name="activo">
                        <button type="submit" class="btn btn-secondary m-1">Desactivar</button>
                    <?php else:?>
                        <input type="hidden" value="<?= $idProfesor?>" name="id">
                        <input type="hidden" value="1" name="activo">
                        <button type="submit" class="btn btn-success m-1">Activar</button>
                        <?php endif ?>
                </form>
                <form action="cambiarPrivilegios.php" method="post">
                    <?php if($admin == 1):?>
                        <input type="hidden" value="<?= $idProfesor?>" name="id">
                        <input type="hidden" value="0" name="admin">
                        <button type="submit" class="btn btn-warning m-1">Quitar privilegios</button>
                    <?php else:?>
                        <input type="hidden" value="<?= $idProfesor?>" name="id">
                        <input type="hidden" value="1" name="admin">
                        <button type="submit" class="btn btn-info m-1">Dar privilegios</button>
                        <?php endif ?>
                </form>
            </section>
        </section>
    </main>
    <?php require_once("../_footer.php")?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>

