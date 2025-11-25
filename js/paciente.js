/**
 * Archivo: pacientes.js
 * Descripción: Funcionalidad AJAX, validación y manejo de DataTables para la vista de Pacientes.
 * Versión final con correcciones de nombres, manejo de errores y FUNCIONALIDAD DE BÚSQUEDA.
 */

// =========================================================================================
// VARIABLES GLOBALES (Asegúrate que FormUtils y SweetAlert2 estén cargados)
// =========================================================================================

// Esta variable contendrá la instancia de DataTables.
window.pacienteTable = null; 

// =========================================================================================
// FUNCIONES DE CONSULTA Y DATATABLES
// =========================================================================================

function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviarAjax(datos);
}

function inicializarDataTable() {
    // Es importante que el ID aquí sea el ID de la etiqueta <table> (ej: <table id="tablaPacientes">)
    window.pacienteTable = $('#tablaPacientes').DataTable({ 
        language: { url: 'js/es-ES.json' },
        responsive: true,
        'dom': 'lrtip'
    });
}

// =========================================================================================
// REGLAS DE VALIDACIÓN (USADAS EN REGISTRO Y MODIFICACIÓN)
// =========================================================================================

const REGISTRO_RULES = {
    // ... (Tu objeto REGISTRO_RULES completo)
    cedula: {
        validator: (v) => FormUtils.isCedula(v),
        message: 'La cédula debe contener entre 6 y 10 dígitos.'
    },
    // ...
};

const MODIFICAR_RULES = {
    // ... (Tu objeto MODIFICAR_RULES completo)
    cedulaM: {
        validator: (v) => FormUtils.isCedula(v),
        message: 'La cédula debe contener entre 6 y 10 dígitos.'
    },
    // ...
};


$(document).ready(function () {
    // 1. Inicia la consulta de datos
    consultar();
    
    // 2. Adjunta las reglas de validación
    FormUtils.attachRealtimeValidation('#formularioRegistropaciente', REGISTRO_RULES); 
    FormUtils.attachRealtimeValidation('#formularioModificarpaciente', MODIFICAR_RULES);

    // Asocia el evento 'keyup' (mientras el usuario escribe) al input.
    $('#buscarpaciente').on('keyup', function() {
        if (window.pacienteTable) {
            // Obtiene el valor y lo usa para filtrar la DataTables
            const searchTerm = $(this).val();
            window.pacienteTable.search(searchTerm).draw();
        }
    });
});


// =========================================================================================
// SUBMIT DE FORMULARIOS
// =========================================================================================

$("#formularioRegistropaciente").on("submit", function (e) {
    e.preventDefault();

    if (!FormUtils.validateForm('#formularioRegistropaciente', REGISTRO_RULES)) {
        Swal.fire({
            icon: 'warning',
            title: 'Validación',
            text: 'Por favor, corrige los campos marcados en rojo.',
            showConfirmButton: false,
            timer: 3000
        });
        return; 
    }
    
    var datos = new FormData(this);
    datos.append('accion', 'registrar');
    
    enviarAjax(datos);
});

$('#modificar').on("click", function () {
    if (!FormUtils.validateForm('#formularioModificarpaciente', MODIFICAR_RULES)) {
        Swal.fire({
            icon: 'warning',
            title: 'Validación',
            text: 'Por favor, corrige los campos marcados en rojo.',
            showConfirmButton: false,
            timer: 3000
        });
        return;
    }
    
    var datos = new FormData();
    datos.append('accion', 'modificar');
    datos.append('id_paciente', $("#modificar_id").val()); 
    datos.append('cedula', $("#cedulaM").val());
    datos.append('nombre', $("#nombreM").val());
    datos.append('apellido', $("#apellidoM").val());
    datos.append('telefono', $("#telefonoM").val());
    datos.append('email', $("#emailM").val());
    datos.append('fecha_nacimiento', $("#fecha_nacimientoM").val());
    datos.append('genero', $("#generoM").val());
    datos.append('pais', $("#paisM").val());
    datos.append('ciudad', $("#ciudadM").val());
    
    enviarAjax(datos);
});

// =========================================================================================
// FUNCIONES DE CONTROL Y ACCIONES
// =========================================================================================

$('#botonCerrar').on('click', () => {
    try { $("#registropacienteModal").modal('hide'); } catch (e) { /* ignore */ }
});

$('#modificarCerrar').on('click', () => {
    try { $("#modificarpacienteModal").modal('hide'); } catch (e) { /* ignore */ }
});

function imprimir() {
    console.log("presionaste");
}

function eliminarPaciente(linea, event) {
    event.preventDefault();
    const registro = $(linea).closest('tr');
    
    // Obtener la CÉDULA (Columna 1, índice 0)
    const cedula = $(registro).find("td:eq(0)").text().trim(); 

    Swal.fire({
        title: '¿Eliminar paciente?', 
        text: 'Esta acción no se puede deshacer. ¿Desea continuar?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            var datos = new FormData();
            datos.append('accion', 'eliminar');
            datos.append('cedula', cedula); 
            enviarAjax(datos);
        }
    });
}

function actualizarCampos(linea, event) {
    event.preventDefault();
    const registro = $(linea).closest('tr');
    
    // Asignación de valores de las columnas a los campos del modal de modificación
    $("#cedulaM").val($(registro).find("td:eq(0)").text().trim());
    $("#nombreM").val($(registro).find("td:eq(1)").text().trim());
    $("#apellidoM").val($(registro).find("td:eq(2)").text().trim());
    $("#telefonoM").val($(registro).find("td:eq(3)").text().trim());
    $("#emailM").val($(registro).find("td:eq(4)").text().trim());
    $("#fecha_nacimientoM").val($(registro).find("td:eq(5)").text().trim()); 
    $("#generoM").val($(registro).find("td:eq(6)").text().trim().toLowerCase()); 
    $("#paisM").val($(registro).find("td:eq(7)").text().trim());
    $("#ciudadM").val($(registro).find("td:eq(8)").text().trim());
    
    $('#modificarpacienteModal').modal('show');
}

/**
 * FUNCIÓN AJAX CENTRAL CORREGIDA CON EXTRACCIÓN Y PARSEO ROBUSTO
 */
function enviarAjax(datos) {
    $.ajax({
        async: true,
        url: window.location.href,
        type: "POST",
        contentType: false,
        data: datos,
        processData: false,
        cache: false,
        beforeSend: function () {},
        timeout: 10000,
        success: function (respuesta) {
            let respObj = {};

            try {
                console.log('Respuesta raw servidor:', respuesta);
                // Parseo directo del JSON
                respObj = JSON.parse(respuesta.trim()); 
                console.log('Respuesta JSON procesada:', respObj);

                // --- Lógica de procesamiento de DataTables (Consultar) ---
                if (respObj.resultado == "consultar") {
                    // 🛑 Solución al error "Cannot reinitialise DataTable"
                    // Si ya existe, la destruye antes de insertar el nuevo HTML
                    if ($.fn.DataTable.isDataTable('#tablaPacientes')) { 
                        $('#tablaPacientes').DataTable().destroy(); 
                    }
                    // Importante: El ID aquí debe ser el contenedor de la tabla (ej: <div>)
                    $("#tablapacientes").html(respObj.mensaje); 
                    inicializarDataTable(); 
                    return;
                }

                // 🛑 MANEJO ESPECÍFICO PARA ERRORES DE CÉDULA/DATOS
                if (respObj.resultado === 'cedula_existe' || respObj.resultado === 'cedula_invalida') {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Error de Datos', 
                        text: respObj.mensaje || "La cédula ingresada ya existe o es inválida."
                    });
                    return;
                }

                // MANEJO DE OTROS ERRORES GENERALES
                if (respObj.resultado === 'error') {
                    Swal.fire({ icon: 'error', title: 'Error', text: respObj.mensaje });
                    return;
                }

                // 🟢 ÉXITO (Registrar, Modificar, Eliminar)
                if (['registrar', 'modificar', 'eliminar'].includes(respObj.resultado)) {
                    Swal.fire({ 
                        icon: "success", 
                        title: (respObj.resultado === 'registrar' ? "Éxito" : (respObj.resultado === 'modificar' ? "Actualizado" : "Eliminado")), 
                        text: respObj.mensaje, 
                        timer: 2000, 
                        showConfirmButton: false 
                    });

                    // Cierre y reseteo de modales
                    if (respObj.resultado === 'registrar') {
                        try { $("#registropacienteModal").modal('hide'); } catch (e) {}
                        const form = document.getElementById('formularioRegistropaciente');
                        if (form) {
                            form.reset();
                            $(form).find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
                            $(form).find('.invalid-feedback').text('').hide();
                        }
                    }
                    if (respObj.resultado === 'modificar') {
                        try { $("#modificarpacienteModal").modal('hide'); } catch (e) {}
                        $('#formularioModificarpaciente').find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
                        $('#formularioModificarpaciente').find('.invalid-feedback').text('').hide();
                    }
                    
                    consultar(); // Recarga la tabla
                    return;
                }

                Swal.fire({ icon: 'info', title: 'Info', text: respObj.mensaje || 'Operación completada' });

            } catch (e) {
                console.error("Error al procesar la respuesta (JSON inválido): ", e);
                console.error("Respuesta completa del servidor:", respuesta);

                Swal.fire({ 
                    icon: "error", 
                    title: "Error de JSON", 
                    text: "Hubo un problema procesando la respuesta del servidor. Revisa la consola (F12) para ver la respuesta completa. Probablemente tu PHP está imprimiendo algo extra." 
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('AJAX error', textStatus, errorThrown);
            console.error('Response text:', jqXHR.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error AJAX de Conexión',
                text: 'No se pudo contactar al servidor. Revisa la consola para más detalles.'
            });
        }
    });
}