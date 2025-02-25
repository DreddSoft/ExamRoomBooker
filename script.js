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
    let plazas1 = document.getElementById("iptPlazas1").value;
    let plazas2 = document.getElementById("iptPlazas2").value;
    let plazas3 = document.getElementById("iptPlazas3").value;
    let plazas4 = document.getElementById("iptPlazas4").value;
    let plazas5 = document.getElementById("iptPlazas5").value;
    let plazas6 = document.getElementById("iptPlazas6").value;

    let fecha = document.getElementById("iptFecha").value;
    let turno = document.getElementById("iptTurno").value;

    const params = {
        'fecha': fecha,
        'turno': turno,
        'plazas1': plazas1,
        'plazas2': plazas2,
        'plazas3': plazas3,
        'plazas4': plazas4,
        'plazas5': plazas5,
        'plazas6': plazas6
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