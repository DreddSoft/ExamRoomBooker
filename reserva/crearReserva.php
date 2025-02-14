<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $usuarioIntroducido = htmlspecialchars($_GET["idProfesor"]);
    try {

        $bd->abrirConexion();

        $sql = "SELECT * FROM Profesores";


        





        //pasar variable por parametro para hacer la consulta
        $datos = $bd->capturarDatos($sql);

        foreach ($datos as $profesor) {
            echo $profesor['id'] . " - " . $profesor['usuario'] . " | " . $profesor['nombre'] . "<br>";
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    } finally {
        $bd->cerrarConexion();
    }


    if (empty($usuarioIntroducido)) {
        $mensaje = "Debes rellenar todos los campos";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formualrio</title>
</head>

<body>
    <!-- funcion para insertar datos, para select y para delete,
Funcion inserta con los id del porfesor y de la asignatura, usando los de la base de datos con un select  -->
    <form action="crearReserva.php" method="post">
        <label for="consulta" name="consulta"> Ingrese el id del profesor para ver la reserva: </label><input type="text" name="consulta">
        <input type="submit" value="Enviar">
    </form>
</body>

</html>