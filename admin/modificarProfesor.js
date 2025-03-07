// Capturar variables
const loading = document.getElementById("loading-screen");
const formChangeProfe = document.getElementById("form-change-profesor");
const formDeleteProfe = document.getElementById("form-delete-profesor");


window.addEventListener("DOMContentLoaded", () => {

    console.log("Esta usando modificarProfesor!!");

    // Ocultar loading
    hideLoading();

}, false);

// Si existe el formulario de modificar al profesor
if (formChangeProfe) {

    // Evento modificar profesor
    formChangeProfe.addEventListener("submit", (event) => {

        // Cortar el envio del form
        event.preventDefault();

        let ok = confirm("Esta usted a punto de modificar el profesor, ¿Desea continuar?");

        if (ok) {
            showLoading();
            formChangeProfe.submit();
        }

        return;

    }, false);
}

// Si existe el formulario de eliminar el profesor
if (formDeleteProfe) {

    // Evento
    formDeleteProfe.addEventListener("submit", (event) => {

        // Cortamos evento
        event.preventDefault();

        let ok = confirm("Esta usted a punto de eliminar el profesor, ¿Desea continuar?")

        if (ok) {
            showLoading();
            formDeleteProfe.submit();
        }

        return;

    }, false);

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