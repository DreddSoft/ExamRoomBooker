const loading = document.getElementById("loading-screen");

// Evento cuando la página se muestra (incluye recarga y navegación hacia atrás)
window.addEventListener('pageshow', function(event) {
    hideLoading();
});

// Evento para redirigir la web si se hace doble clic en una tarjeta de reserva
document.addEventListener('DOMContentLoaded', function() {

    // Ocultar la carga
    hideLoading();

    // Seleccionar todas las tarjetas de reserva
    const tarjetasReserva = document.querySelectorAll('[id^="reserva-"]');

    // Agregar evento de doble clic a cada tarjeta
    tarjetasReserva.forEach(tarjeta => {
        tarjeta.addEventListener('dblclick', function() {

            showLoading();

            // Obtener el ID de la reserva del atributo id
            const idReserva = this.id.split('-')[1];
            // Redirigir a la página de edición
            window.location.href = `../reserva/editarReserva.php?id=${idReserva}`;

        });
    });
});

// Funciones para pantallas de cargas
function showLoading() {

    loading.classList.remove("d-none");
    loading.classList.add("d-flex");

}

function hideLoading() {

    loading.classList.remove("d-flex");
    loading.classList.add("d-none");

}