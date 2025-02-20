<?php
    //dar por hecho que el booleano activo es 1 y el booleano admin es 0
    //comprobar tambien que el usuario que esta conectado es el admin antes de hcer nada(usando la bd), sino redirigir al index.php
    //poner en el main, no hacer estilos
    //inicio sesion
    session_start();
    //ESTO ES PARA PRUEBA, BORRAR LUEGO!!!!!!!!!!!
    $_SESSION["id"] = 11;
    $_SESSION["admin"] = 1;
    //compruebo si hay un profesor conectado, si no lo mando al login
    if(!isset($_SESSION["id"])){
        header("Location:../login.php");
        exit();
    }
    if($_SESSION["admin"] != 1){
        header("Location:index.php");
        exit();
    }
    //requiero la clase bd
    require_once($_SERVER['DOCUMENT_ROOT'] . '/examroombooker/clases/bd.class.php');
    $bd = new BD();

    try{
    //conecto a la base de datos
    $conexion = $bd->abrirConexion();
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
    <title>Crear Profesor</title>
</head>
<body class="d-flex flex-column min-vh-100">
    <?php require_once("../_header.php")?>
    <main>
        <section class="text-center"><br>
        <h2 style="color: #642686;">Crear Profesor</h2>
            <form action="crearProfesor.php" method="post">
                <input type="text" placeholder="ID Profesor" name="id" id="id" class="form-control-lg mt-1"><br>
                <input type="text" placeholder="Usuario" name="usuario" id="usuario" class="form-control-lg mt-1"><br>
                <input type="password" placeholder="Contraseña" name="passw" id="passw" class="form-control-lg mt-1"><br>
                <input type="text" placeholder="Nombre Profesor" name="nombre" id="nombre" class="form-control-lg mt-1"><br>
                <input type="text" placeholder="Primer apellido" name="ape1" id="ape1" class="form-control-lg mt-1"><br>
                <input type="text" placeholder="Segundo apellido" name="ape2" id="ape2" class="form-control-lg mt-1"><br>
                <input type="email" placeholder="Correo electrónico" name="email" id="email" class="form-control-lg mt-1"><br><br>
                <button type="submit" class="btn btn-primary">Crear</button>
            </form>
        </section>
    </main>
    <?php require_once("../_footer.php")?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
