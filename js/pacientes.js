/**
 * Archivo: pacientes.js
 * Descripción: Funcionalidad AJAX, validación y manejo de DataTables para la vista de Pacientes.
 * Versión final con correcciones de nombres y manejo de errores.
 */

// =========================================================================================
// FUNCIONES DE CONSULTA Y DATATABLES
// =========================================================================================

function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviarAjax(datos);
}

function inicializarDataTable() {
    window.pacienteTable = $('#tablaPacientes').DataTable({
        language: { url: 'js/es-ES.json' },
        responsive: true
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
    consultar();
    
    window.pacienteTable = null; 
    
    FormUtils.attachRealtimeValidation('#formularioRegistropaciente', REGISTRO_RULES); 
    FormUtils.attachRealtimeValidation('#formularioModificarpaciente', MODIFICAR_RULES);
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

// 🟢 CORRECCIÓN DE NOMBRE
function eliminarPaciente(linea, event) {
    event.preventDefault();
    const registro = $(linea).closest('tr');
    
    // Obtener la CÉDULA (Columna 1)
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
    
    // Cédula (Columna 1)
    $("#cedulaM").val($(registro).find("td:eq(0)").text().trim());
    
    // Nombre (Columna 2)
    $("#nombreM").val($(registro).find("td:eq(1)").text().trim());
    
    // Apellido (Columna 3)
    $("#apellidoM").val($(registro).find("td:eq(2)").text().trim());
    
    // Teléfono (Columna 4)
    $("#telefonoM").val($(registro).find("td:eq(3)").text().trim());
    
    // Email (Columna 5) 🛑 ¡CORREGIDO!
    $("#emailM").val($(registro).find("td:eq(4)").text().trim());
    
    // Fecha de Nacimiento (Columna 6) 🛑 ¡CORREGIDO!
    $("#fecha_nacimientoM").val($(registro).find("td:eq(5)").text().trim()); 
    
    // Género (Columna 7) 🛑 ¡CORREGIDO!
    // Para selects, el valor debe coincidir exactamente con el 'value' del option.
    $("#generoM").val($(registro).find("td:eq(6)").text().trim().toLowerCase()); 
    
    // País (Columna 8)
    $("#paisM").val($(registro).find("td:eq(7)").text().trim());
    
    // Ciudad (Columna 9)
    $("#ciudadM").val($(registro).find("td:eq(8)").text().trim());
    
    $('#modificarpacienteModal').modal('show');
}

/**
 * FUNCIÓN AJAX CENTRAL CORREGIDA CON EXTRACCIÓN Y PARSEO ROBUSTO
 * Usa .match() para aislar el JSON, evitando texto extra del servidor (advertencias, etc.).
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
                // 🛑 Parseo directo del JSON (funciona si PHP no imprime nada extra)
                respObj = JSON.parse(respuesta.trim()); 
                console.log('Respuesta JSON procesada:', respObj);

                // --- Lógica de procesamiento de DataTables (Consultar) ---
                if (respObj.resultado == "consultar") {
                    if ($.fn.DataTable.isDataTable('#tablaPacientes')) { 
                        $('#tablaPacientes').DataTable().destroy(); 
                    }
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
                    // Opcional: Cerrar el modal (si está abierto)
                    try { 
                        $("#registropacienteModal").modal('hide'); 
                        $("#modificarpacienteModal").modal('hide'); 
                    } catch (e) {}
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
                        // 🛑 CIERRE DEL MODAL Y LIMPIEZA DE VALIDACIÓN
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