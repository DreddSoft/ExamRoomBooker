<?php

// Incluir el autoload de composer
require('../vendor/autoload.php');


use Dotenv\Dotenv;  // Esto es para abrir el archivo .env con la información delicada


$dotenv = Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT']);
$dotenv->load();

class BD
{


    // Variables privadas
    private $host;
    private $user;
    private $pass;
    private $name_database;
    private $conn;

    // Constructor
    public function __construct()
    {
        // Asignar las variables de entorno a las propiedades para asi proteger mas la conexión
        // También que sea más fácil personalizarlas dependiendo
        $this->host = $_ENV['DB_HOST'];
        $this->user = $_ENV['DB_USER'];
        $this->pass = $_ENV['DB_PASS'];
        $this->name_database = $_ENV['DB_NAME'];
    }

    //* Funcion para conectar la base de datos
    public function abrirConexion()
    {

        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->name_database);

        if ($this->conn->connect_error) {
            throw new Exception("Error al conectar la base de datos: " . $this->conn->connect_error);
        }
    }

    public function cerrarConexion()
    {
        if ($this->conn) {
            $this->conn->close();
        }
    }

    //* CapturarDatos
    public function capturarDatos($sql)
    {


        if ($result = $this->conn->query($sql)) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        // Retornamos array vacio si fallo
        return [];
    }

    //* Insertar DAtos
    public function insertarDatos($sql)
    {

        $result = mysqli_query($this->conn, $sql);

        // Si hay resultado, se ha hecho la inserccion
        if ($result) {
            // Devuelve el ultimo id insertado
            return mysqli_insert_id($this->conn);
        }

        // Devuelve menos uno si no hace nada
        return -1;
    }

    //* Actualizar datos o eliminar datos
    public function actualizarDatos($sql)
    {

        mysqli_query($this->conn, $sql);

        // Devolvemos numero de filas afectadas
        // Ojo, si es 0, es error porque no hemos hecho nada
        return mysqli_affected_rows($this->conn);
    }
}

// $bd = new BD;

// try {

//     $bd->abrirConexion();

//     $sql = "SELECT * FROM Profesores";

//     $datos = $bd->capturarDatos($sql);

//     foreach ($datos as $profesor) {
//         echo $profesor['id'] . " - " . $profesor['usuario'] . " | " . $profesor['nombre'] . "<br>";
//     }

// } catch (Exception $e) {
//     echo $e->getMessage();
// } finally {
//     $bd->cerrarConexion();
// }
