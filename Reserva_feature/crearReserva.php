
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formualrio</title>
</head>
<body>

    <form action="crearReserva.php" method="post">
        <label for="asignaturas" name="asignaturas"> Ingrese el nombre de la asignatura: </label><input type="text" name="asignaturas">
        <input type="submit" value="Enviar">
    </form>
    
</body>
</html>

<?php

require_once ('C:\xampp\htdocs\ExamRoomBooker\clases\bd.class.php');

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $asignaturas=$_REQUEST["asignaturas"];
    $hots="localhost";
    $user="root";
    $pass="";
    $name_db="examroombooker";


    //Establer conexion
    $conexion= mysqli_connect($hots,$user,$pass,$name_db);


    //Crear y ejecutar consultas
    $query1 = "SELECT id,nombre,idDepartamento FROM asignaturas WHERE nombre='$asignaturas'";
    $resultado = mysqli_query($conexion, $query1);


    echo "  <h1>Asignatura DE asignaturas DE " .$asignaturas."</h1>";
    //Para ver resultados se usan, mysqli_fetch_row() ó mysqli_fetch_assoc()
    while ($registro = mysqli_fetch_assoc($resultado)) {
        echo "
      
        Id de la asignatura:  ".$registro["id"]. "<br>"." asignaturas: ".$registro["nombre"]. "<br>"." IdDepartamento: ".$registro["idDepartamento"] . "<br><br><br>";
        
    }
}


?>