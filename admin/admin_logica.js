// Capturar variables
const formFiltros = document.getElementById("form-filtros");
const loading = document.getElementById("loading-screen");

window.addEventListener("DOMContentLoaded", () => {

    // Ocultar loading
    hideLoading();

}, false);

if (formFiltros) {

    formFiltros.addEventListener("submit", (event) => {

        // Cortar el evento
        event.preventDefault();

        // Mostrar loading
        showLoading();
        formFiltros.submit();

    }, false);
}


let filas = document.getElementsByTagName("tr");

for (let i = 1; i < filas.length; i++) {
    filas[i].addEventListener("dblclick", function () {
        let filaid = filas[i].id;
        showLoading();
        window.location.href = `modificarProfesor.php?idProfesor=${filaid}`
    });
}

// Funciones para pantallas de cargas
function showLoading() {

    loading.classList.remove("d-none");
    loading.classList.add("d-flex");

}

function hideLoading() {

    loading.classList.remove("d-flex");
    loading.classList.add("d-none");

}