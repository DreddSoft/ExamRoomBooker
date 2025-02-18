const btnDropdown = document.getElementById("navbarDropdown");

btnDropdown.addEventListener("click", () => {

}, false);


$(".closeModal").click(() => {

    $("#modal").removeClass("d-block");

});

// Función modificarReserva
function modificarReserva(id) {

    window.location.href = `reserva/editarReserva.php?id=${id}`;
    
}