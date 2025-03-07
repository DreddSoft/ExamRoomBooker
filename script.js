const btnDropdown = document.getElementById("navbarDropdown");
const loading = document.getElementById("loading-screen");

window.addEventListener("DOMContentLoaded", () => {

    // Ocultar carga
    hideLoading();

}, false)

if (btnDropdown) {
    btnDropdown.addEventListener("click", () => {

    }, false);
    
}

$(".closeModal").click(() => {
    
    $("#modal").removeClass("d-block");
    
});
// Función modificarReserva
function modificarReserva(id) {

    showLoading();

    window.location.href = `reserva/editarReserva.php?id=${id}`;

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