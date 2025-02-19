<?php

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
<footer class="container-fluid d-flex flex-row justify-content-between p-3 bg-dark shadow mt-auto">
    <div class="d-flex flex-row justify-content-center align-items-center">
        <a href="https://github.com/DreddSoft/ExamRoomBooker" target="_blank"><img src="assets/GitHub_Logo_White.png" alt="Logo de Github" style="width: 100px;"></a>
    </div>
    <div class="d-flex flex-row justify-content-center align-items-center">
        <img src="<?= $ruta ?>assets/ExamRoomBooker.svg" alt="Logo largo en enlace" style="width: 450px;">
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <a class="nav-link" target="_blank" href="https://github.com/ajimvil713">&commat;ajimvil713</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" target="_blank" href="https://github.com/davix1997">&commat;davix1997</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" target="_blank" href="https://github.com/danielgr29">&commat;danielgr29</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" target="_blank" href="https://github.com/ajimvil713">&commat;Ivan-Trevi</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" target="_blank" href="https://github.com/DreddSoft">&commat;DreddSoft</a>
            </li>
        </ul>
    </nav>

</footer>