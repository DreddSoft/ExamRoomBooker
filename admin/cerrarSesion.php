<?php
    //para cerrar sesion
    //hago session start, unset y destroy para eliminar la sesion actual y mando al usuario al login.php
    session_start();
    session_unset();
    session_destroy();
    header("Location:../login.php");
?>