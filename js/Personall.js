function consultar() {
    var datos = new FormData();
    datos.append('accion', 'consultar');
    enviarAjax(datos);
}

function inicializarDataTable() {
    // 2. Inicializar la nueva instancia
    window.personalTable = $('#tablaPersonal').DataTable({
        language: { url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' },
        responsive: true
    });
}

// =========================================================================================
// INICIALIZACIÓN Y VALIDACIONES
// =========================================================================================
const REGISTRO_RULES = {
    nombre: { 
        validator: (v) => FormUtils.isAlphaSpace(v), 
        message: 'El nombre es obligatorio y solo debe contener letras y espacios.' 
    },
    apellido: { 
        validator: (v) => FormUtils.isAlphaSpace(v), 
        message: 'El apellido es obligatorio y solo debe contener letras y espacios.' 
    },
    cedula: { 
        validator: (v) => FormUtils.isCedula(v), 
        message: 'La cédula debe contener entre 6 y 10 dígitos.' 
    },
    telefono: { 
        validator: (v) => FormUtils.isPhone(v), 
        message: 'El teléfono debe contener entre 7 y 12 dígitos.' 
    },
    direccion: { 
        validator: (v) => FormUtils.isNotEmpty(v), 
        message: 'La dirección es obligatoria.' 
    },
    rol: { 
        validator: (v) => FormUtils.isNotEmpty(v), 
        message: 'Debe seleccionar un rol.' 
    },
    password: { 
        validator: (v) => FormUtils.isNotEmpty(v), 
        message: 'La contraseña es obligatoria.' 
    }
};

const MODIFICAR_RULES = {
    nombreM: { 
        validator: (v) => FormUtils.isAlphaSpace(v), 
        message: 'El nombre es obligatorio y solo debe contener letras y espacios.' 
    },
    apellidoM: { 
        validator: (v) => FormUtils.isAlphaSpace(v), 
        message: 'El apellido es obligatorio y solo debe contener letras y espacios.' 
    },
    cedulaM: { 
        validator: (v) => FormUtils.isCedula(v), 
        message: 'La cédula debe contener entre 6 y 10 dígitos.' 
    },
    telefonoM: { 
        validator: (v) => FormUtils.isPhone(v), 
        message: 'El teléfono debe contener entre 7 y 12 dígitos.' 
    },
    direccionM: { 
        validator: (v) => FormUtils.isNotEmpty(v), 
        message: 'La dirección es obligatoria.' 
    },
    rolM: { 
        validator: (v) => FormUtils.isNotEmpty(v), 
        message: 'Debe seleccionar un rol.' 
    },
    passwordM: { 
        validator: (v) => FormUtils.isNotEmpty(v), 
        message: 'La contraseña es obligatoria para modificar.' 
    }
};


$(document).ready(function () {
    consultar();
    
    window.personalTable = null; 
    
    // Inicializar validaciones en tiempo real
    FormUtils.attachRealtimeValidation('#registro', REGISTRO_RULES);
    FormUtils.attachRealtimeValidation('#formularioModificarpaciente', MODIFICAR_RULES);
});


// =========================================================================================
// SUBMIT DE FORMULARIOS (CONTROLADO POR VALIDACIÓN)
// =========================================================================================

$("#registro").on("submit", function (e) {
    e.preventDefault();

    // 🛑 Paso clave: VALIDAR ANTES DE ENVIAR AJAX
    if (!FormUtils.validateForm('#registro', REGISTRO_RULES)) {
        Swal.fire({
            icon: 'warning',
            title: 'Validación',
            text: 'Por favor, corrige los campos marcados en rojo.',
            showConfirmButton: false,
            timer: 3000
        });
        return; // Detiene el proceso si la validación falla
    }
    
    // Si la validación pasa, procede con AJAX
    var datos = new FormData();
    datos.append('accion', 'registrar');
    datos.append('cedula', $("#cedula").val());
    datos.append('nombre', $("#nombre").val());
    datos.append('apellido', $("#apellido").val());
    datos.append('telefono', $("#telefono").val());
    datos.append('direccion', $("#direccion").val());
    datos.append('rol', $("#rol").val());
    datos.append('password', $("#password").val());
    enviarAjax(datos);
});

$('#modificar').on("click", function () {
    // 🛑 Paso clave: VALIDAR ANTES DE ENVIAR AJAX
    if (!FormUtils.validateForm('#formularioModificarpaciente', MODIFICAR_RULES)) {
        Swal.fire({
            icon: 'warning',
            title: 'Validación',
            text: 'Por favor, corrige los campos marcados en rojo.',
            showConfirmButton: false,
            timer: 3000
        });
        return; // Detiene el proceso si la validación falla
    }
    
    // Si la validación pasa, procede con AJAX
    var datos = new FormData();
    datos.append('accion', 'modificar');
    datos.append('cedula', $("#cedulaM").val());
    datos.append('nombre', $("#nombreM").val());
    datos.append('apellido', $("#apellidoM").val());
    datos.append('telefono', $("#telefonoM").val());
    datos.append('direccion', $("#direccionM").val());
    datos.append('rol', $("#rolM").val());
    datos.append('password', $("#passwordM").val());
    enviarAjax(datos);
});

// ... (resto de funciones: eliminarCliente, actualizarCampos, enviarAjax)
// Asegúrate de que las funciones auxiliares de cerrar modal sigan funcionando, aunque no son estrictamente necesarias aquí.

$('#botonCerrar').on('click', () => {
    try { $("#modalRegistro").modal('hide'); } catch (e) { /* ignore */ }
});

$('#modificarCerrar').on('click', () => {
    try { $("#modalModificar").modal('hide'); } catch (e) { /* ignore */ }
});

function imprimir() {
    console.log("presionaste");
}

function eliminarCliente(linea, event) {
    event.preventDefault();
    const registro = $(linea).closest('tr');
    const cedula = $(registro).find("td:eq(1)").text().trim();

    // Confirmación con SweetAlert
    Swal.fire({
        title: '¿Eliminar personal?',
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
    
    $("#cedulaM").val($(registro).find("td:eq(1)").text().trim());
    $("#nombreM").val($(registro).find("td:eq(2)").text().trim());
    $("#apellidoM").val($(registro).find("td:eq(3)").text().trim());
    $("#telefonoM").val($(registro).find("td:eq(4)").text().trim());
    $("#direccionM").val($(registro).find("td:eq(5)").text().trim());
    $("#rolM").val($(registro).find("td:eq(6)").text().trim());
    $("#passwordM").val($(registro));

    
}

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
            try {
                console.log('Respuesta raw servidor:', respuesta);
                
                let respObj = JSON.parse(respuesta.trim()); 

                if (respObj.resultado == "consultar") {
                    if ($.fn.DataTable.isDataTable('#tablaPersonal')) {
                        $('#tablaPersonal').DataTable().destroy(); 
                    }
                    $("#tablaBody").html(respObj.mensaje); 
                    inicializarDataTable(); 
                    return;
                }

                if (respObj.resultado === 'error') {
                    Swal.fire({ icon: 'error', title: 'Error', text: respObj.mensaje });
                    return;
                }

                if (['registrar', 'modificar', 'eliminar'].includes(respObj.resultado)) {
                    Swal.fire({ 
                        icon: "success", 
                        title: (respObj.resultado === 'registrar' ? "Éxito" : (respObj.resultado === 'modificar' ? "Actualizado" : "Eliminado")), 
                        text: respObj.mensaje, 
                        timer: 2000, 
                        showConfirmButton: false 
                    });

                    // Cerrar modales y resetear
                    if (respObj.resultado === 'registrar') {
                        try { $("#modalRegistro").modal('hide'); } catch (e) {}
                        try { 
                            if (document.getElementById('registro')) {
                                document.getElementById('registro').reset();
                                // Limpiar clases de validación después del reset
                                $('#registro').find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
                                $('#registro').find('.invalid-feedback').text('').hide();
                            }
                        } catch (e) {}
                    }
                    if (respObj.resultado === 'modificar') {
                        try { $("#modalModificar").modal('hide'); } catch (e) {}
                        // Limpiar clases de validación al cerrar modal de modificar
                        $('#formularioModificarpaciente').find('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
                        $('#formularioModificarpaciente').find('.invalid-feedback').text('').hide();
                    }
                    
                    consultar(); 
                    return;
                }

                Swal.fire({ icon: 'info', title: 'Info', text: respObj.mensaje || 'Operación completada' });

            } catch (e) {
                console.error("Error al procesar la respuesta (JSON inválido): ", e);
                console.error("Respuesta completa del servidor:", respuesta);

                Swal.fire({ 
                    icon: "error", 
                    title: "Error", 
                    text: "Hubo un problema procesando la respuesta del servidor. Revisa la consola para el error de JSON." 
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('AJAX error', textStatus, errorThrown);
            console.error('Response text:', jqXHR.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error AJAX',
                text: 'Comprueba la consola para más detalles.'
            });
        }
    });
}