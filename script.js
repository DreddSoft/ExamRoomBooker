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

// // Función crearReserva
// function crearReserva(col, idFecha, idTurno) {

//     // Capturar valores de los inputs
//     let plazas1 = document.getElementById(`iptPlazas1-${col}`).value;
//     let plazas2 = document.getElementById(`iptPlazas2-${col}`).value;
//     let plazas3 = document.getElementById(`iptPlazas3-${col}`).value;
//     let plazas4 = document.getElementById(`iptPlazas4-${col}`).value;
//     let plazas5 = document.getElementById(`iptPlazas5-${col}`).value;
//     let plazas6 = document.getElementById(`iptPlazas6-${col}`).value;

//     let fecha = document.getElementById(idFecha).value;
//     let turno = document.getElementById(idTurno).value;

//     const params = {
//         'fecha': fecha,
//         'turno': turno,
//         'plazas1': plazas1,
//         'plazas2': plazas2,
//         'plazas3': plazas3,
//         'plazas4': plazas4,
//         'plazas5': plazas5,
//         'plazas6': plazas6
//     };

//     // Enviar formulario oculto
//     let form = document.createElement("form");
//     form.method = "POST";
//     form.action = "reserva/crearReserva.php";

//     for (const key in params) {
//         let input = document.createElement("input");
//         input.type = "hidden";
//         input.name = "key";
//         input.value = params[key];
//         form.appendChild(input); 
//     }

//     document.body.appendChild(form);
//     form.submit();

// }