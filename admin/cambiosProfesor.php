<?php
//inicio sesion
session_start();

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

require_once($_SERVER['DOCUMENT_ROOT'] . '/examroombooker/clases/bd.class.php');
$bd = new BD();
try {
    $bd->abrirConexion();
    //aqui es donde se modifican los datos actuales, recogiendo los nuevos datos enviados por post
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $idProfesor = $_POST["idProfesor"];
        //guardo las asignaturas nuevas para usarlas en una consulta
        $asignaturasNuevas = $_POST['asignaturas'];
        //borro todas sus asignaturas actuales
        $query4 = "
                DELETE FROM asignaturasprofesores
                WHERE idProfesor = $idProfesor;
            ";
        $bd->actualizarDatos($query4);

        //hago un insert into con las nuevas asignaturas seleccionadas
        foreach ($asignaturasNuevas as $idAsignatura) {
            $query5 = "
                    INSERT INTO asignaturasprofesores (idProfesor, idAsignatura) 
                    VALUES ($idProfesor, $idAsignatura);
                ";
            $bd->actualizarDatos($query5);
        }
        //guardo los datos que me ha pasado el usuario en variables, que voy a usar en la consulta
        $usuario = htmlspecialchars($_POST['usuario']);
        $passw = (isset($_POST["passw"])) ? htmlspecialchars($_POST['passw']) : null;



        $nombre = htmlspecialchars($_POST['nombre']);
        $ape1 = htmlspecialchars($_POST['ape1']);
        $ape2 = htmlspecialchars($_POST['ape2']);
        $email = htmlspecialchars($_POST['email']);
        //consulta para modificar un profesor con los datos guardados anteriormente

        if ($passw == null) {
            $query2 = "
                        UPDATE profesores SET 
                        usuario = '$usuario', 
                        nombre = '$nombre', 
                        ape1 = '$ape1', 
                        ape2 = '$ape2', 
                        email = '$email'
                        WHERE id = $idProfesor;
                    ";
            //uso el metodo actualizar datos de la clase bd
            $bd->actualizarDatos($query2);
        } else {
            // Cifrar la contraseña
            $hashedPasswd = password_hash($passw, PASSWORD_DEFAULT);

            $query2 = "
            UPDATE profesores SET 
            usuario = '$usuario',
            passw = '$hashedPasswd', 
            nombre = '$nombre', 
            ape1 = '$ape1', 
            ape2 = '$ape2', 
            email = '$email'
            WHERE id = $idProfesor;
        ";
            //uso el metodo actualizar datos de la clase bd
            $bd->actualizarDatos($query2);
        }

        $msj = "Profesor modificado correctamente.";
        header("Location: modificarProfesor.php?idProfesor=$idProfesor&success=1&mensaje=" . urlencode($msj));
        exit();
    }
} catch (Exception $e) {
    $msj = "Error: " . $e->getMessage();
    header("Location: modificarProfesor.php?idProfesor=$idProfesor&error=1&mensaje=" . urlencode($msj));
    exit();
} finally {
    //cierro la conexion con la bd
    $bd->cerrarConexion();
}
