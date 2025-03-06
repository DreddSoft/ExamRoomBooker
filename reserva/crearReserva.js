// Capturamos todos los inputs
const checkboxes = document.getElementsByClassName("btn-check");
const form = document.getElementById("form-reserva");
const btnSubmit = document.getElementById("btn-submit");

document.addEventListener("DOMContentLoaded", () => {

    // Al cargar que aplique la disponibilidad del check que este marcado
    // Sacar el valor del check marcado
    let valorInit = 0;
    

    for (const chk of checkboxes) {

        if (chk.checked) {
            let val = parseInt(chk.dataset.disp);
            valorInit = val;
        }
    }

    // Asignamos el valor al input maximo
    document.getElementById("max").value = valorInit;
    numMaxAlumnos();

}, false);

form.addEventListener("submit", (e) => {

    // Cortamos el envío del formulario
    e.preventDefault();

    

}, false);

btnSubmit.addEventListener("click", () => {

    btnSubmit.setAttribute("disabled", true);

    // if (btnSubmit.getAttribute("disabled") == true) {

    // }

}, false);

// Recorremos los checkboxes comprobando que 
for (let i = 0; i < checkboxes.length; i++) {
    checkboxes[i].addEventListener('change', function () {
        if (this.checked) {
            if (i > 0 && !checkboxes[i - 1].checked) {
                this.checked = false;
                alert("Los turnos se deben marcar en orden.");
            } else if (i < checkboxes.length - 1) {
                checkboxes[i + 1].removeAttribute("disabled");
            }
        } else {
            for (let j = i + 1; j < checkboxes.length; j++) {
                checkboxes[j].checked = false;
                checkboxes[j].setAttribute("disabled", "true");
            }
        }
    }, false);

    checkboxes[i].addEventListener('change', function () {

        let valores = [];

        for (const check of checkboxes) {

            if (check.checked) {
                console.log("Se guardan!");
                let val = parseInt(check.dataset.disp);
                valores.push(val);
            }

        }

        console.log(valores);
        let elMasBajo = 100;
        for (const valor of valores) {
            if (valor < elMasBajo) {
                console.log(`valor: ${valor} y elMasBajo: ${elMasBajo}`);
                elMasBajo = valor;

            }
        }

        console.log("El masBajo despues: " + elMasBajo);

        // Asignamos el valor al input maximo
        document.getElementById("max").value = elMasBajo;
        numMaxAlumnos();

    }, false);

}


// Funcion que establece el numero maximo de alumnos *atributo* en base al value del max
function numMaxAlumnos() {

    // Capturamos el valor del max
    let max = document.getElementById("max").value;
    max = parseInt(max);

    // Capturamos el numALumnos
    const iptAlumnos = document.getElementById("numAlumno");

    // Asignamos el atributo max al max
    iptAlumnos.setAttribute("max", max);
    
}


