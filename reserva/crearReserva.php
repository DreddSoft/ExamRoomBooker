
<!-- funcion para insertar datos, para select y para delete,
Funcion inserta con los id del porfesor y de la asignatura, usando los de la base de datos con un select  -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formualrio</title>
</head>
<body>

    <form action="crearReserva.php" method="post">
        <label for="consulta" name="consulta"> Ingrese el id del profesor para ver la reserva: </label><input type="text" name="consulta">
        <input type="submit" value="Enviar">
    </form>
    
</body>
</html>

<?php



if($_SERVER["REQUEST_METHOD"] === "POST"){
    $consulta=htmlspecialchars($_REQUEST["id"]);
    $hots="localhost";
    $user="root";
    $pass="";
    $name_db="examroombooker";


    //Establer conexion
    $conexion= mysqli_connect($hots,$user,$pass,$name_db);


    //Crear y ejecutar consultas
    $query1 = "SELECT id,descripcion,numAlumnos FROM reservas WHERE id='$consulta'";
    $resultado = mysqli_query($conexion, $query1);


    echo "  <h1>Asignatura DE consulta DE " .$consulta."</h1>";
    //Para ver resultados se usan, mysqli_fetch_row() ó mysqli_fetch_assoc()
    while ($registro = mysqli_fetch_assoc($resultado)) {
        echo "
      
        Id de la asignatura:  ".$registro["id"]. "<br>"." consulta: ".$registro["nombre"]. "<br>"." IdDepartamento: ".$registro["idDepartamento"] . "<br><br><br>";
        
    }



    function obtenerReservasId(): void{
        
    }

}


?>