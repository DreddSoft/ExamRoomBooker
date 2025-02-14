<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="admin_logica.js"></script>
</head>
<body>
    <?php
        //dar por hecho que el booleano activo es 1 y el booleano admin es 0
        //comprobar tambien que el usuario que esta conectado es el admin antes de hcer nada(usando la bd), sino redirigir al index.php
        //poner en el main, no hacer estilos
        session_start();
        //ESTO ES PARA PRUEBA, BORRAR LUEGO!!!!!!!!!!!
        $_SESSION["id"] = 11;
        //compruebo si hay un usuario conectado, si no lo mando al login
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
        }
        //consulta para seleccionar todos los profesores
        $query2 = "
            SELECT * FROM Profesores;
        ";
        //llamo al metodo capturar datos de la clase bd
        $resultado = $bd->capturarDatos($query2);
        //hago una tabla con los datos de resultado
        echo "<table id='tabla'>";
        echo "<th>ID</th><th>Usuario</th><th>Contraseña</th><th>Nombre</th><th>1er Apellido</th><th>2do Apellido</th><th>Estado</th><th>Email</th><th>Admin</th>";
        foreach ($resultado as $registro) {
            echo "<tr  id='" . $registro['id'] . "'>";
            //paso el valor numerico tanto de "activo" como de "admin" a algo mas comprensible para el usuario
                if($registro["activo"] == 1){
                    $estado = "Activo";
                }else{
                    $estado = "Inactivo";
                }
                if($registro["admin"] == 1){
                    $esAdmin = "Admin";
                }else{
                    $esAdmin = "No admin";
                }
                echo "<td>" . $registro['id'] . "</td><td>" . $registro['usuario'] . "</td><td>" . $registro['passw'] . "</td><td>" . $registro['nombre'] . "</td><td>" . $registro['ape1'] . "</td><td>" . $registro['ape2'] . "</td><td>" . $estado . "</td><td>" . $registro['email'] . "</td><td>" . $esAdmin . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        //llamo al metodo cerrar conexxion de la clase bd
        $bd->cerrarConexion();
        
    ?>
</body>
</html>
