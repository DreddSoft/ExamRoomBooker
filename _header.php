<?php

$adminHeader = 1;
// Siempre necesario comprobar y sanitizar
if (isset($_SESSION['admin'])) {
    $adminHeader = 1;
}

// Capturamos el nombre del archivo
$filename = basename($_SERVER['PHP_SELF']);

// Si es el index o el login, que están en la carpeta padre
if ($filename == "index.php" || $filename == "login.php") {
    // La ruta relativa es esta
    $ruta = "./";
} else {    // Cualquier otra
    // La ruta relativa es esta
    $ruta = "../";
}

?>

<header class="container-fluid d-flex flex-row justify-content-between p-3 bg-dark shadow">
    <div>
        <div class="d-flex flex-row justify-content-center align-items-center">
            <a href="index.php" class="text-decoration-none"><img src="<?= $ruta ?>assets/Logo_type_1.svg" alt="Logo de ExamRoomBooker" style="width: 100px;"></a>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $ruta ?>index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $ruta?>area-personal/area-personal.php">Area Personal</a>
                    </li>

                    <?php if ($adminHeader == 1) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Admin
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="<?= $ruta?>admin/mostrarProfesor.php">Listado Profesores</a>
                            <a class="dropdown-item" href="<?= $ruta?>admin/crearProfesor.php">Crear Profesor</a>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>

            </nav>
        </div>



    </div>
    <div class="d-flex alig-center px-2">
        <img src="<?= $ruta ?>assets/logo-iesjorgeguillen-footer.svg" alt="Logo del IES Jorge Guillen" style="width: 200px; fill: white;">
    </div>

</header>