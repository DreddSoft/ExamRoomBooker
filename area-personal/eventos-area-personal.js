// Evento para redirigir la web si se hace doble clic en una tarjeta de reserva
document.addEventListener('DOMContentLoaded', function() {
    // Seleccionar todas las tarjetas de reserva
    const tarjetasReserva = document.querySelectorAll('[id^="reserva-"]');

    // Agregar evento de doble clic a cada tarjeta
    tarjetasReserva.forEach(tarjeta => {
        tarjeta.addEventListener('dblclick', function() {
            // Obtener el ID de la reserva del atributo id
            const idReserva = this.id.split('-')[1];
            // Redirigir a la página de edición
            window.location.href = `../reserva/editarReserva.php?id=${idReserva}`;
        });
    });
});