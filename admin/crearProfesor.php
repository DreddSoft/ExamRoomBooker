<?php
//inicio sesion
session_start();
//compruebo si hay un profesor conectado, si no lo mando al login
if (!isset($_SESSION["idProfesor"])) {
    header("Location:../login.php");
    exit();
}
if ($_SESSION["admin"] != 1) {
    header("Location:../index.php");
    exit();
}

// * CODIGO PARA CONTROLAR LA INACTIVIDAD DEL USUARIO
$maxTime = 600;

if (isset($_SESSION["ultimo_acceso"])) {
    $tiempo_transcurrido = time() - $_SESSION["ultimo_acceso"];

    if ($tiempo_transcurrido > $maxTime) {

        header("Location: ../cerrarSesion.php");
        exit();
    }
}

// Actualizamos en cada accion del user
$_SESSION['ultimo_acceso'] = time();

//requiero la clase bd
require_once($_SERVER['DOCUMENT_ROOT'] . '/examroombooker/clases/bd.class.php');
$bd = new BD();

$idInsertado = -1;

try {
    //conecto a la base de datos
    $conexion = $bd->abrirConexion();
    //compruebo que el método es post
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        //guardo los datos que me ha pasado el usuario en variables, que voy a usar en la consulta
        $usuario = htmlspecialchars($_POST['usuario']);
        $passw = htmlspecialchars($_POST['passw']);

        // Cifrar la contraseña
        $hashedPasswd = password_hash($passw, PASSWORD_DEFAULT);

        $nombre = htmlspecialchars($_POST['nombre']);
        $ape1 = htmlspecialchars($_POST['ape1']);
        $ape2 = htmlspecialchars($_POST['ape2']);
        $email = htmlspecialchars($_POST['email']);
        //consulta para insertar en la tabla profesores un nuevo profesor con los datos indcados
        $query2 = "
                    INSERT INTO profesores (usuario, passw, nombre, ape1, ape2, activo, email, admin)
                    VALUES ('$usuario', '$hashedPasswd', '$nombre', '$ape1', '$ape2', 1, '$email', 0);
                ";
        //uso el metodo insertar datos de la clase bd
        $idInsertado = $bd->insertarDatos($query2);

        if ($idInsertado == -1) {
            throw new Exception("No se ha creado el profesor correctamente.");
        }
        //mando al usuario a mostrar profesor
        header("Location:mostrarProfesor.php");
    }
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    //cierro la conexion con la bd
    $bd->cerrarConexion();
}

try {
    //conecto a la base de datos
    $conexion = $bd->abrirConexion();
    //compruebo que el método es post
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        //guardo el id del profesor
        //compruebo si se han seleccionado las asignaturas , si es asi guardo los datos 
        if (isset($_POST['asignaturas'])) {
            $asignaturas = $_POST['asignaturas'];
            //para dcada una de las asignaturas guardadas hago un insert into con el id de cada una y el id del profesor
            foreach ($asignaturas as $asignatura) {
                $query = "
                        INSERT INTO asignaturasprofesores (idProfesor, idAsignatura)
                        VALUES ('$idInsertado', '$asignatura');
                    ";
                //uso el metodo insertar datos de la clase bd
                $bd->insertarDatos($query);
            }
        }
    }
} catch (Exception $e) {
    echo $e->getMessage();
} finally {
    //cierro la conexion con la bd
    $bd->cerrarConexion();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../assets/Logo_type_1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <title>Crear Profesor</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php require_once("../_header.php") ?>
    <main>
        <section class="text-center m-5"><br>
            <form action="crearProfesor.php" method="post" class="py-5 px-4 d-flex flex-column align-items-center bg-light rounded shadow" style="max-width: 500px; margin: auto;">
                <h2 class="mb-5" style="color: #642686;">Crear Profesor</h2>
                <input type="text" placeholder="Usuario" require name="usuario" id="usuario" class="form-control"><br>
                <input type="password" placeholder="Contraseña" require name="passw" id="passw" class="form-control"><br>
                <input type="text" placeholder="Nombre Profesor" require name="nombre" id="nombre" class="form-control"><br>
                <input type="text" placeholder="Primer apellido" require name="ape1" id="ape1" class="form-control"><br>
                <input type="text" placeholder="Segundo apellido" name="ape2" id="ape2" class="form-control"><br>
                <input type="email" placeholder="Correo electrónico" require name="email" id="email" class="form-control"><br><br>
                <label for="opciones">Seleccione asignaturas:</label>
                <i class="text-secondary">Pulse la tecla control (crtl) para seleccionar varias</i>
                <select name="asignaturas[]" id="asignaturas" multiple class="form-control">
                    <option value="1">Biología</option>
                    <option value="2">Química</option>
                    <option value="3">Matemáticas I</option>
                    <option value="4">Álgebra</option>
                    <option value="5">Lengua Española</option>
                    <option value="6">Literatura Universal</option>
                    <option value="7">Bases De Datos</option>
                    <option value="8">Entorno Servidor</option>
                    <option value="9">Entorno Cliente</option>
                </select><br>
                <button type="submit" class="btn btn-primary" style="width: 60%;">Crear</button>
            </form>
        </section>
    </main>
    <?php require_once("../_footer.php") ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>