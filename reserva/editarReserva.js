// Capturamos todos los inputs
const checkboxes = document.querySelectorAll("input[type='checkbox']");

// Capturar la fecha
const iptFecha = document.getElementById("fecha");
const formEditar = document.getElementById("form-editar");
const loading = document.getElementById("loading-screen");



// Capturar boton eliminar
const btnDelete = document.getElementById("btn-delete");

// Evento para el envio del formulario de modificacion
formEditar.addEventListener("submit", (event) => {

    // Detener el envio 
    event.preventDefault();

    // Pedir confirmacion
    let ok = confirm("Esta a punto de modificar la reserva, ¿Desea continuar?");

    if (ok) {
        formEditar.submit();

        showLoading();

    }

    return;

}, false);

document.addEventListener("DOMContentLoaded", () => {

    hideLoading();

    actualizarEstadoTurnos();

    establecerMaximoTurnosMarcados();

}, false);

for (let i = 0; i < checkboxes.length; i++) {


    checkboxes[i].addEventListener('click', function () {

        actualizarEstadoTurnos();

        establecerMaximoTurnosMarcados();

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

        // Pantalla de carga
        showLoading();

        // Capturar idReserva
        let idReserva = parseInt(document.getElementById("idReserva").value);

        // Redirigir a eliminarReserva.php
        window.location.href = `eliminarReserva.php?idReserva=${idReserva}`;
    }


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

function actualizarEstadoTurnos() {

    let marcados = [];

    checkboxes.forEach((checkbox, i) => {

        // Si esta marcado lo metemos en el array de marcados
        if (checkbox.checked) {
            marcados.push(i);
        }
    });

    // Si la longitud de marcados es 0 es que no hay ninguno marcado
    if (marcados.length == 0) {
        // Habilitamos todos
        checkboxes.forEach(checkbox => checkbox.removeAttribute("disabled"));
        return;
    }

    // Sacamos el primero y el ultimo marcados
    let primero = marcados[0];
    let ultimo = marcados[marcados.length - 1];

    checkboxes.forEach((checkbox, i) => {
        // El primero, el ultimo y los previos y siguientes habilitados
        if (i === primero - 1 || i === primero || i === ultimo || i === ultimo + 1) {
            checkbox.removeAttribute("disabled");
        } else if (marcados.includes(i)) {
            checkbox.removeAttribute("disabled");
            checkbox.setAttribute("readonly", true);

        } else {
            // Resto bloqueados
            checkbox.setAttribute("disabled", true);
        }
    });

}

// Funcion establecer el maximo de los marcados
function establecerMaximoTurnosMarcados() {

    // menorMax
    let menorMax = Infinity;

    checkboxes.forEach((checkbox, i) => {

        // Si esta marcado
        if (checkbox.checked) {

            let disp = parseInt(checkbox.dataset.disp);

            // Si la disponibilidad es menor que el max
            if (disp < menorMax) {

                // Guardamos como menorMax
                menorMax = disp;
            }
        }
    });

    // Asignamos el menor valor al atributo max
    formEditar.elements[3].setAttribute("max", menorMax);
    console.log(menorMax);

}
