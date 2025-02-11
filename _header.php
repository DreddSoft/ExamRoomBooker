<?php

$admin = 0;
// Siempre necesario comprobar y sanitizar
if (isset($_SESSION['admin'])) {
    $admin = 1;
}

?>

<header class="container-fluid d-flex flex-row justify-content-between p-3 bg-dark shadow">
    <div>
        <div class="d-flex flex-row justify-content-center align-items-center">
            <a href="index.php" class="text-decoration-none"><img src="assets/Logo_type_1.svg" alt="Logo de ExamRoomBooker" style="width: 100px;"></a>
            <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-2">

                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./Area personal/area-personal.php">Area Personal</a>
                    </li>

                    <?php if ($admin == 1) : ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Admin
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Listado Profesores</a>
                            <a class="dropdown-item" href="#">Crear Profesor</a>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>

            </nav>
        </div>



    </div>
    <div class="d-flex alig-center">
        <img src="assets/ExamRoomBooker.svg" alt="Logo largo de ExamRoomBooker" style="width: 450px;">
    </div>

</header>