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

// Función crearReserva
function crearReserva() {

    // Capturar valores de los inputs
    let plazas = document.getElementById("iptPlazas").value;
    let fecha = document.getElementById("iptFecha").value;
    let turno = document.getElementById("iptTurno").value;

    const params = {
        'plazas': plazas,
        'fecha': fecha,
        'turno': turno
    };

    // let paramsString = `plazas=${plazas}&fecha=${fecha}&turno=${turno}`;

    // window.location.href = `reserva/crearReserva.php?${paramsString}`;

    fetch("reserva/crearReserva.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: JSON.stringify(params)
    })
        .then(response => {
            if (response.ok) {
                window.location.href = "reserva/crearReserva.php";
            } else {
                throw new Error("Error al enviar los datos para crear reserva.");
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });

}