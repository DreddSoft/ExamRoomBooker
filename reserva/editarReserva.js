// Capturamos todos los inputs
const checkboxes = document.getElementsByClassName("btn-check");
// Capturar la fecha
const iptFecha = document.getElementById("fecha");

// Capturar boton eliminar
const btnDelete = document.getElementById("btn-delete");

document.addEventListener("DOMContentLoaded", () => {

    // Capturar el primer turno
    let primerTurno = parseInt(document.getElementById("primerTurno").value);

    Array.from(checkboxes).forEach(checkbox => {

        let id = parseInt(checkbox.id);

        if (id < primerTurno) {
            checkbox.setAttribute("hidden", true);
            document.getElementById(`turno${id}`).setAttribute("hidden", true);
        }

    });

}, false);

for (let i = 0; i < checkboxes.length; i++) {
    checkboxes[i].addEventListener('click', function () {
        // Deshabilitar todos los checkboxes
        let todosDesactivados = 0;
        Array.from(checkboxes).forEach(checkbox => {

            if (!checkbox.checked) {
                checkbox.setAttribute("disabled", true);
                todosDesactivados++;

            }
        });

        // Habilitar el checkbox actual
        this.removeAttribute("disabled");

        // Habilitar el checkbox anterior si existe
        if (i > 0) {
            checkboxes[i - 1].removeAttribute("disabled");
        }

        // Habilitar el checkbox siguiente si existe
        if (i < checkboxes.length - 1) {
            checkboxes[i + 1].removeAttribute("disabled");
        }

        if (todosDesactivados == 6) {
            Array.from(checkboxes).forEach(checkbox => {

                if (!checkbox.checked) {
                    checkbox.removeAttribute("disabled");
                    todosDesactivados = 0;

                }
            });
        }
    }, false);
}

iptFecha.addEventListener("change", () => {

    // Cada vez que se cambie la fecha hay que sacar la disponibilidad
    // Actualizar todos los checkboxes, atributo data-dispo
    // Desmarcar los que hubiera marcados
    // Establecer el máximo como atributo max en numeroAlumnos

    fetch('../api/disponibilidad.php?fecha=' + iptFecha.value)
        .then(response => response.json())
        .then(data => {
            // Recorremos los inputs y le aplicamos la disponibilidad en el atributo
            // Los deseleccionamos todos
            Array.from(checkboxes).forEach(checkbox => {
                console.log("Data: " + data[checkbox.id]);
                let id = parseInt(checkbox.id);
                checkbox.dataset.dispo = data[checkbox.id] || 0;
                checkbox.checked = false;
                checkbox.removeAttribute("hidden");
                document.getElementById(`turno${id}`).removeAttribute("hidden");

            });

            // Recorremos las disponibilidaddes, guardando la mas baja
            let minDispo = Infinity;

            console.log(data);

            for (let key in data) {
                if (data[key]["disponibilidad"] < minDispo) {
                    console.log("Esto: " + data[key]["disponibilidad"]);
                    let dispo = parseInt(data[key]["disponibilidad"]);
                    minDispo = dispo;
                }
            }
            console.log("Minima disponibilidad: " + minDispo);

            // Asignamos la mas baja como atributo max 
            document.getElementById("alumnos").max = minDispo;
        })
        .catch(error => console.error('Error fetching disponibilidad:', error));


}, false);

// Evento de click para el boton eliminar Reserva
btnDelete.addEventListener("click", eliminarReserva, false);

// Funcion para eliminar Reserva
function eliminarReserva() {

    ok = confirm("Atento, está usted a punto de eliminar la reserva, ¿Desea continuar?");


    if (ok) {
        // Capturar idReserva
        let idReserva = parseInt(document.getElementById("idReserva").value);

        // Redirigir a eliminarReserva.php
        window.location.href = `eliminarReserva.php?idReserva=${idReserva}`;
    }


}