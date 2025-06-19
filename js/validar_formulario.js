// Esperar a que el documento esté completamente cargado antes de ejecutar el código
document.addEventListener("DOMContentLoaded", function () {
    // Seleccionar el formulario y agregar un evento al intentar enviarlo
    document.querySelector("form").addEventListener("submit", function (event) {
        event.preventDefault(); // Evita que el formulario se envíe automáticamente

        // Llamar a la función de validación
        if (validarFormulario()) {
            this.submit(); // Enviar el formulario si todas las validaciones son correctas

            // Mostrar mensaje antes de redirigir
            alert("Formulario enviado correctamente. Redirigiendo...");

            // Redirigir a inicio después de 1 segundo para que el usuario vea el feedback
            setTimeout(() => {
                window.location.href = "inicio.html";
            }, 1000);
        }
    });
});

// Función para validar los datos ingresados en el formulario
function validarFormulario() {
    // Obtener los valores de los campos y eliminar espacios en blanco
    let rut = document.getElementById("rut").value.trim();
    let nombre = document.getElementById("nombre").value.trim();
    let carrera = document.getElementById("carrera").value.trim();
    let anio = document.getElementById("anio").value.trim();

    // Validación del RUT usando la función `validarRut`
    if (!validarRut(rut)) {
        alert("RUT inválido. Verifica el formato y el dígito verificador con el siguiente formato: XX.XXX.XXX-X.");
        return false; // Detiene el envío si hay error
    }

    // Expresión regular para validar que Nombre y Carrera solo contengan letras y espacios
    let regexTexto = /^[a-zA-ZáéíóúñÑ\s]+$/;
    if (!regexTexto.test(nombre) || !regexTexto.test(carrera)) {
        alert("Nombre y Carrera solo pueden contener letras y espacios.");
        return false;
    }

    // Validación del Año de Carrera asegurando que sea un número entre 1 y 4
    if (isNaN(anio) || anio < 1 || anio > 4) {
        alert("El año de carrera debe estar entre 1 y 4.");
        return false;
    }

    return true; // Retorna `true` si todas las validaciones son correctas
}

// Función para validar el formato y dígito verificador del RUT chileno
function validarRut(rut) {
    // Limpiar el RUT para eliminar puntos antes de la validación
    rut = rut.replace(/\./g, "").replace(/-/g, "");

    // Expresión regular para asegurar que el formato sea correcto (8 o 9 dígitos más un verificador)
    let regexRut = /^[0-9]{7,8}[0-9kK]$/;
    if (!regexRut.test(rut)) {
        return false; // Retorna falso si el formato es incorrecto
    }

    // Separar número y dígito verificador
    let num = rut.slice(0, -1); // Número del RUT
    let dv = rut.slice(-1).toUpperCase(); // Dígito verificador convertido a mayúscula

    // Comparar el dígito verificador calculado con el ingresado
    return calcularDigitoVerificador(num) === dv;
}

// Función para calcular el dígito verificador del RUT chileno
function calcularDigitoVerificador(rut) {
    let suma = 0, factor = 2;

    // Recorrer los dígitos de derecha a izquierda, multiplicándolos por el factor de validación
    for (let i = rut.length - 1; i >= 0; i--) {
        suma += rut[i] * factor;
        factor = factor === 7 ? 2 : factor + 1; // Reiniciar el factor cuando llega a 7
    }

    // Calcular el dígito verificador usando la fórmula oficial
    let dv = 11 - (suma % 11);
    return dv === 11 ? "0" : dv === 10 ? "K" : dv.toString(); // Ajustar salida en caso de valores especiales
}
