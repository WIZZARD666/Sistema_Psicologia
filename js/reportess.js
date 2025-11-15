$(document).ready(function () {
    console.log("✅ JS de reportes cargado correctamente.");

    const $contentWrapper = $("#contentWrapper");
    const $mainContent = $("#mainContent");

    // 1. INYECTAR EL SKELETON AL INICIO DEL CONTENEDOR
    // Requerimos que la función generateReportSkeleton esté definida en main.js
    if (typeof generateReportSkeleton === 'function' && $contentWrapper.length) {
        $contentWrapper.prepend(generateReportSkeleton());
    }

    const $skeletonLoader = $("#skeletonLoaderReports");
    
    // Simular tiempo de carga/renderizado PHP (1.5 segundos)
    setTimeout(function() {
        if ($skeletonLoader.length) {
            // Ocultar skeleton
            $skeletonLoader.hide();
            // Opcional: remover el elemento completamente si no se va a usar más
            // $skeletonLoader.remove();
        }
        
        if ($mainContent.length) {
            // Mostrar contenido real
            $mainContent.show();
        }
        
        // --- INICIALIZACIÓN DE LA LÓGICA DEL FORMULARIO DE REPORTES ---
        initReportesLogic(); 

    }, 1500); // 1.5 segundos de simulación de carga

});

// ----------------------------------------------------------------------
// FUNCIÓN PRINCIPAL DE INICIALIZACIÓN DE LA LÓGICA DEL FORMULARIO
// La movemos aquí para que solo se ejecute DESPUÉS de que el contenido real es visible.
// ----------------------------------------------------------------------

function initReportesLogic() {
    const $form = $("#f");
    const $accion = $("#accion");
    const $paciente = $("#id_paciente");
    const $mes = $("#mes");
    const $errorMes = $("#errorMes");

    // --- Función para mostrar mensaje simple ---
    function muestraMensaje(mensaje) {
        alert(mensaje);
    }

    // --- Validaciones individuales ---
    function validarPaciente() {
        return $paciente.val().trim() !== "";
    }

    function validarMes() {
        if ($mes.val().trim() === "") {
            $errorMes.show();
            return false;
        } else {
            $errorMes.hide();
            return true;
        }
    }

    function validarAccion() {
        return $accion.val().trim() !== "";
    }

    // --- Selección de tarjeta ---
    $(".seleccionable").on("click", function () {
        $(".seleccionable").removeClass("selected"); // Usar clase 'selected' para CSS
        $(this).addClass("selected");
        const valor = $(this).data("value");
        $accion.val(valor);
        console.log("➡️ Acción seleccionada:", valor);
    });
    
    // Mejorar el estado inicial (Si PHP establece un valor en #mes)
    validarMes();

    // --- Envío del formulario ---
    $("#proceso").on("click", function (e) {
        e.preventDefault();
        console.log("🟢 Botón 'Generar' clickeado.");

        const pacienteValido = validarPaciente();
        const mesValido = validarMes();
        const accionValida = validarAccion();

        if (!pacienteValido || !mesValido || !accionValida) {
            muestraMensaje("⚠️ Por favor complete todos los campos correctamente antes de generar el reporte.");
            return;
        }

        console.log("📤 Enviando formulario al servidor...");

        // ✅ Ruta corregida (desde raíz del proyecto)
        $form.attr("action", "Controller/reportes.php");

        // Envía el formulario a una nueva pestaña
        $form[0].submit();
    });
}



