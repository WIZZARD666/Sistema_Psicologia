// =======================================================
// === EXPRESIONES REGULARES (REGEX) ===
// =======================================================

// Cédula: 6 a 12 dígitos numéricos obligatorios.
const cedulaRegex = /^\d{6,12}$/; 
const CEDULA_ERROR_MSG = "La cédula debe contener solo números (6 a 12 dígitos).";

// Contraseña: Mínimo 8 caracteres. Debe incluir al menos: 
// - Una minúscula (?=.*[a-z])
// - Una mayúscula (?=.*[A-Z])
// - Un dígito (?=.*\d)
// - Puede contener: letras, números, puntos y caracteres especiales comunes [A-Za-z\d.!,@#$%^&*()_+-=|;:'"<>?/[\]{}~`]
const claveRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d.!,@#$%^&*()_+-=|;:'"<>?/[\]{}~`]{8,}$/; 
const CLAVE_ERROR_MSG = "Mínimo 8 caracteres. Debe incluir mayúsculas, minúsculas, números y puede usar caracteres especiales.";

// Mensaje genérico para validación fallida
const VALIDACION_FALLIDA_MSG = "Por favor, corrija los campos resaltados antes de continuar.";


// =======================================================
// === FUNCIÓN DE MENSAJES MODAL ===
// =======================================================

function muestraMensaje(mensaje){
    // Este código de modal se deja intacto, solo cambia el mensaje recibido
    $("#mostrarmodal").removeClass("animate-confir");
    $("#mostrarmodal").removeClass("animate-error");
    $("#mostrarmodal").removeClass("animate-adver");

    if (mensaje.includes("Usuario y/o Contraseña Incorrecta!!!")) {
        
        const mensajeConLogo = `
            <div>
                <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: block; margin: 0 auto;">
                    <circle cx="50" cy="50" r="45" stroke="#F44336" stroke-width="5" stroke-dasharray="283" stroke-dashoffset="283" class="circle-animation"/>
                    <path d="M30 30 L70 70 M70 30 L30 70" stroke="#F44336" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round" class="cross-animation"/>
                </svg>
                <p style="text-align: center; margin-top: 10px;">${mensaje}</p>
            </div>
        `;
        
        $("#contenidodemodal").html(mensajeConLogo);
        $("#mostrarmodal").addClass("animate-error");

    }
    else {
        
        const mensajeConLogo = `
            <div>
                <svg width="100" height="100" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: block; margin: 0 auto;">
                    <circle cx="50" cy="50" r="45" stroke="#ff5c5c" stroke-width="5" stroke-dasharray="283" stroke-dashoffset="283" class="circle-animation"/>
                    <path d="M30 30 L70 70 M70 30 L30 70" stroke="#ff5c5c" stroke-width="5" fill="none" stroke-linecap="round" stroke-linejoin="round" class="cross-animation"/>
                </svg>
                <p style="text-align: center; margin-top: 10px;">${mensaje}</p>
            </div>
        `;
        
        $("#contenidodemodal").html(mensajeConLogo);
        $("#mostrarmodal").addClass("animate-error");
        
    }
    
    
    $("#mostrarmodal").modal("show");
    setTimeout(function() {
        $("#mostrarmodal").modal("hide");
        $("#mostrarmodal").removeClass("animate-confir");
        $("#mostrarmodal").removeClass("animate-error");
        $("#mostrarmodal").removeClass("animate-adver");
    },3000);
}


// =======================================================
// === LÓGICA DE VALIDACIÓN Y ENVÍO ===
// =======================================================

$(document).ready(function(){

    // Muestra mensajes del servidor si existen (al cargar la página)
    if($.trim($("#mensajes").text()) != ""){
        // Aseguramos que solo mostramos el mensaje del servidor
        muestraMensaje($.trim($("#mensajes").html()));
    }

    const usuarioInput = $("#cedula");
    const claveInput = $("#clave");
    const formulario = $("#f");

    const errorUsuario = $("#scedula"); 
    const errorClave = $("#sclave");   
    
    // --- Funciones de Validación Individual ---

    function validarUsuario() {
        const valor = usuarioInput.val().trim();
        if (valor === "") {
            errorUsuario.text("La cédula es obligatoria.");
            errorUsuario.show();
            return false;
        } else if (!cedulaRegex.test(valor)) {
            errorUsuario.text(CEDULA_ERROR_MSG);
            errorUsuario.show();
            return false;
        } else {
            errorUsuario.hide();
            return true;
        }
    }
    
    function validarClave() {
        const valor = claveInput.val();
        if (valor === "") {
            errorClave.text("La contraseña es obligatoria.");
            errorClave.show();
            return false;
        } else if (!claveRegex.test(valor)) {
            errorClave.text(CLAVE_ERROR_MSG);
            errorClave.show();
            return false;
        } else {
            errorClave.hide();
            return true;
        }
    }

    // --- Validación en Tiempo Real (al escribir/salir del foco) ---
    usuarioInput.on("keyup blur", validarUsuario);
    claveInput.on("keyup blur", validarClave);


    // --- Manejo del Botón de Iniciar Sesión ---
    $("#entrar").on("click", function(e){
        e.preventDefault(); 

        const usuarioValido = validarUsuario();
        const claveValido = validarClave();

        if (usuarioValido && claveValido) {
            // Si todo está bien, seteamos la acción y enviamos el formulario
            $("#accion").val("entrar"); 
            formulario.submit();
        } else { 
            // Si hay errores de validación, mostramos el mensaje genérico corregido
            muestraMensaje(VALIDACION_FALLIDA_MSG); 
        }
    });

    // --- Manejo del Submit del Formulario (Garantía) ---
    formulario.on("submit", function (e) {
        $("#accion").val("entrar");
        // Se ejecuta solo si la validación del 'click' del botón fue exitosa
        return true; 
    });
});