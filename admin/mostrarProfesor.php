<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="assets/Logo_type_1.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Listado de Profesores</title>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php require_once("../_header.php") ?>
    <?php
    //inicio la session
    session_start();
    //compruebo si hay un usuario conectado, si no lo mando al login
    if (!isset($_SESSION["idProfesor"])) {
        header("Location:../login.php");
        exit();
    }
    if($_SESSION["admin"] != 1){
        header("Location:../index.php");
        exit();
    }
    //requiero la clase bd
    require_once($_SERVER['DOCUMENT_ROOT'] . '/examroombooker/clases/bd.class.php');
    $bd = new BD();
    try{
        //conecto a la base de datos
        $conexion = $bd->abrirConexion();
        //compruebo si el usuario conectado es admin
        if ($_SESSION["admin"] != 1) {
            header("Location:index.php");
            exit();
        }
        if(isset($_POST['mActivos'])){
            $mActivos = $_POST['mActivos'];
            if($mActivos == 1){
                $consulta = "WHERE activo = 1";
            }else{
                $consulta = "WHERE activo = 0";
            }
        }else{
            $consulta = "";
        }   
        //consulta para seleccionar todos los profesores
        $query2 = "
                SELECT * FROM Profesores $consulta;
            ";
        //llamo al metodo capturar datos de la clase bd
        $resultado = $bd->capturarDatos($query2);
        //hago una tabla con los datos de resultado
        echo "<h2 style='color: #642686; text-align: center;'>Listado de Profesores</h2>";
        echo "<form action='mostrarProfesor.php' method='post'>
                <button type'submit' class='btn btn-info m-1'>Filtrar por inactivos</button>
                <input type='checkbox' id='mActivos' name='mActivos' class='m-1' role='switch'>
            </form>";
        echo "<table id='tabla' class='table table-bordered'>";
        echo "<th>ID</th><th>Usuario</th><th>Contraseña</th><th>Nombre</th><th>1er Apellido</th><th>2do Apellido</th><th>Estado</th><th>Email</th><th>Admin</th>";
        foreach ($resultado as $registro) {
            echo "<tr  id='" . $registro['id'] . "'>";
            //paso el valor numerico tanto de "activo" como de "admin" a algo mas comprensible para el usuario
            if ($registro["activo"] == 1) {
                $estado = "Activo";
            } else {
                $estado = "Inactivo";
            }
            if ($registro["admin"] == 1) {
                $esAdmin = "Admin";
            } else {
                $esAdmin = "No admin";
            }
            echo "<td>" . $registro['id'] . "</td><td>" . $registro['usuario'] . "</td><td>" . $registro['passw'] . "</td><td>" . $registro['nombre'] . "</td><td>" . $registro['ape1'] . "</td><td>" . $registro['ape2'] . "</td><td>" . $estado . "</td><td>" . $registro['email'] . "</td><td>" . $esAdmin . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        }catch(Exception $e){
            echo $e->getMessage();
        }finally{
            //llamo al metodo cerrar conexxion de la clase bd
            $bd->cerrarConexion();
        }
    ?>
    <?php require_once("../_footer.php") ?>
    <script src="admin_logica.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
