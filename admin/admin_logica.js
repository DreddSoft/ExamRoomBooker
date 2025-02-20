

let filas = document.getElementsByTagName("tr");

for (let i = 1; i < filas.length; i++) {
    filas[i].addEventListener("dblclick", function() {
        let filaid = filas[i].id;
        window.location.href = `modificarProfesor.php?idProfesor=${filaid}`
    });
}