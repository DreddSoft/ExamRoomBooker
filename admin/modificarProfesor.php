<?php
//inicio sesion
session_start();

$idProfesor = null;
//compruebo si existe el idProfesor 'pasado mediante get'
if (isset($_GET["idProfesor"])) {
    //si existe creo una variable y guardo su valor en ella
    $idProfesor = $_GET["idProfesor"];
}
//compruebo si hay un usuario conectado, si no lo mando al login
if (!isset($_SESSION["idProfesor"])) {
    header("Location:../login.php");
    exit();
}
//compruebo si el usuario conectado es admin, si no lo mando al index
if ($_SESSION["admin"] != 1) {
    header("Location:../index.php");
    exit();
}

$error = null;
$success = null;

if (isset($_GET["error"])) {
    $error = true;
}

if (isset($_GET["success"])) {
    $success = true;
}

$msj = null;
if (isset($_GET["mensaje"])) {
    $msj = $_GET["mensaje"];
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

if ($_SESSION["idProfesor"] == $idProfesor) {
    $bloquear = true;
} else {
    $bloquear = false;
}
require_once($_SERVER['DOCUMENT_ROOT'] . '/examroombooker/clases/bd.class.php');
$bd = new BD();
try {
    $bd->abrirConexion();
    //consulta que me muestra los datos de un profesor con una id concreta, la guardada anteriormente
    $query1 = "
            SELECT usuario, passw, nombre, ape1, ape2, activo, email, admin, super FROM profesores
            where id = '$idProfesor';
        ";
    $resultado = $bd->capturarDatos($query1);

    if (empty($resultado)) {
        throw new Exception("No se han capturado los datos del profesor.");
    }
    //guardo todos los datos de ese usuario
    $usuario = $resultado[0]["usuario"];
    $nombre = $resultado[0]["nombre"];
    $ape1 = $resultado[0]["ape1"];
    $ape2 = $resultado[0]["ape2"] ? $resultado[0]["ape2"] : "";
    $activo = $resultado[0]["activo"] == 1 ? "Activo" : "Inactivo";
    $email = $resultado[0]["email"];
    $admin = $resultado[0]["admin"] == 1 ? "Admin" : "No admin";

    //guardo las asignaturas que tiene ese profesor
    $query3 = "
        SELECT idAsignatura FROM asignaturasprofesores
        WHERE idProfesor = $idProfesor;
        ";
    $resultado = $bd->capturarDatos($query3);

    //creo un array con las asignaturas que tiene ese profesor y que lo usare en el select poara que me aparezcan preseleccionadas
    $asignaturas = [];
    foreach ($resultado as $asignatura) {
        $asignaturas[] = $asignatura['idAsignatura'];
    }

    $sql = "SELECT id, nombre FROM Asignaturas";

    $todasAsignaturas = $bd->capturarDatos($sql);

    if (empty($todasAsignaturas)) {
        throw new Exception("No se han podido capturar todas las asignaturas.");
    }
} catch (Exception $e) {
    $msj = "Erorr: " . $e->getMessage();
    $error = true;
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
    <title>Modificar Profesor</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php require_once("../_header.php") ?>
    <main class="d-flex justify-content-center">
        <section class="text-center m-5 py-5 px-4 d-flex flex-column align-items-center bg-light rounded shadow" style="width: 600px;">
            <?php if ($success) : ?>
                <div class="bg-success bg-opacity-10 border border-success text-success p-2 rounded mb-3">
                    <p class="mb-0"><?= $msj ?></p>
                </div>
            <?php endif; ?>

            <?php if ($error) : ?>
                <div class="bg-danger bg-opacity-10 border border-danger text-danger p-2 rounded mb-3">
                    <p class="mb-0"><?= $msj ?></p>
                </div>
            <?php endif; ?>
            <form action="cambiosProfesor.php" method="post" id="form-change-profesor">
                <h2 class="mb-5" style="color: #642686;">Modificar Profesor</h2>
                <div class="d-flex justify-content-center">
                    <table style="font-weight: bold; text-align: left; width: 500px;">
                        <tr>
                            <td>
                                <input type="text" name="idProfesor" class="form-control" style="width: 280px;" hidden id="idProfesor" value="<?= $idProfesor ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="usuario">Usuario</label>
                            </td>
                            <td>
                                <input type="text" placeholder="Usuario" name="usuario" class="form-control" style="width: 280px;" id="usuario" value="<?= $usuario ?>">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="text-muted fst-italic">Solo rellenar si se desea cambiar la contraseña</td>
                        </tr>
                        <tr>
                            <td>
                                <label for="passw">Contraseña</label>
                            </td>
                            <td>
                                <input type="password" placeholder="Contraseña" name="passw" class="form-control" id="passw" value="">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="nombre">Nombre</label>
                            </td>
                            <td>
                                <input type="text" placeholder="Nombre Profesor" name="nombre" class="form-control" id="nombre" value="<?= $nombre ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="ape1">Primer Apellido</label>
                            </td>
                            <td>
                                <input type="text" placeholder="Primer apellido" name="ape1" class="form-control" id="ape1" value="<?= $ape1 ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="ape2">Segundo Apellido</label>
                            </td>
                            <td>
                                <input type="text" placeholder="Segundo apellido" name="ape2" class="form-control" id="ape2" value="<?= $ape2 ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="activo">Estado</label>
                            </td>
                            <td>
                                <input type="text" placeholder="Estado" name="activo" class="form-control" disabled id="activo" value="<?= $activo ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="email">Correo Electrónico</label>
                            </td>
                            <td>
                                <input type="email" placeholder="Correo electrónico" name="email" class="form-control" id="email" value="<?= $email ?>">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="admin">Privilegios</label>
                            </td>
                            <td>
                                <input type="text" placeholder="Privilegios" name="admin" class="form-control" disabled id="admin" value="<?= $admin ?>">
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2"><i class="text-secondary">Pulse la tecla control (crtl) para seleccionar varias</i></td>
                        </tr>
                        <tr>
                            <td>
                                <label for="asignaturas">Asignaturas</label>
                            </td>
                            <td>
                                <select name="asignaturas[]" id="asignaturas" multiple class="form-control">
                                    <?php foreach ($todasAsignaturas as $asignatura) : ?>
                                        <option value="<?= $asignatura["id"] ?>" <?php if (in_array($asignatura["id"], $asignaturas)) echo 'selected'; ?>><?= $asignatura["nombre"] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <section class="d-inline-flex p-2 m-3">
                    <button type="submit" class="btn btn-primary m-1">Modificar</button>
            </form>
            <form action="eliminarProfesor.php" method="post" id="form-delete-profesor">
                <input type="hidden" value="<?= $idProfesor ?>" name="id">
                <button type="submit" class="btn btn-danger m-1" <?php if ($bloquear) echo "hidden" ?>>Eliminar</button>
            </form>
        </section>
        <?php if ($_SESSION["super"] == 1) : ?>
            <section class="d-inline-flex p-2">

                <form action="cambiarStatusProfesor.php" method="post">
                    <?php if ($activo == 1 || $activo == "Activo"): ?>
                        <input type="hidden" value="<?= $idProfesor ?>" name="id">
                        <input type="hidden" value="0" name="activo">
                        <button type="submit" class="btn btn-secondary m-1" <?php if ($bloquear) echo "hidden" ?>>Desactivar</button>
                    <?php else: ?>
                        <input type="hidden" value="<?= $idProfesor ?>" name="id">
                        <input type="hidden" value="1" name="activo">
                        <button type="submit" class="btn btn-success m-1" <?php if ($bloquear) echo "hidden" ?>>Activar</button>
                    <?php endif ?>
                </form>
                <form action="cambiarPrivilegios.php" method="post">
                    <?php if ($admin == 1 || $admin == "Admin"): ?>
                        <input type="hidden" value="<?= $idProfesor ?>" name="id">
                        <input type="hidden" value="0" name="admin">
                        <button type="submit" class="btn btn-warning m-1" <?php if ($bloquear) echo "hidden" ?>>Quitar privilegios</button>
                    <?php else: ?>
                        <input type="hidden" value="<?= $idProfesor ?>" name="id">
                        <input type="hidden" value="1" name="admin">
                        <button type="submit" class="btn btn-info m-1" <?php if ($bloquear) echo "hidden" ?>>Dar privilegios</button>
                    <?php endif ?>
                </form>

            </section>
        <?php endif; ?>
    </main>
    <div class="position-fixed top-50 start-50 translate-middle w-100 h-100 d-none justify-content-center align-items-center bg-white bg-opacity-75" id="loading-screen" style="z-index: 999;">
        <div class="spinner-border text-primary m-auto" role="status" style="width: 3rem; height: 3rem;">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
    <?php require_once("../_footer.php") ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="modificarProfesor.js"></script>
</body>

</html>