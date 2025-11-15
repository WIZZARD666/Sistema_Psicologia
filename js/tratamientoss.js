$(document).ready(function() {
    

    // --- Expresiones regulares para validaciones ---
    const regex = {
        tratamiento_tipo: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-]{5,50}$/, // 5-100 caracteres, letras, espacios, guiones
        diagnostico_descripcion: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\.,;:\-\(\)]{10,500}$/, // 10-500 caracteres, texto con puntuación
        observaciones: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\.,;:\-\(\)]{0,1000}$/ // 0-1000 caracteres, opcional
    };
 
    // --- Utilidades para validación ---
    function mostrarError(input, mensaje) {
        input.classList.add('is-invalid');
        input.classList.remove('is-valid');
        
        // Remover feedback anterior si existe
        const feedbackExistente = input.parentNode.querySelector('.invalid-feedback');
        if (feedbackExistente) {
            feedbackExistente.remove();
        }
        
        // Crear nuevo feedback
        const feedback = document.createElement('div');
        feedback.className = 'invalid-feedback d-block';
        feedback.textContent = mensaje;
        input.parentNode.appendChild(feedback);
    }

    function mostrarValido(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        
        // Remover feedback si existe
        const feedbackExistente = input.parentNode.querySelector('.invalid-feedback');
        if (feedbackExistente) {
            feedbackExistente.remove();
        }
    }

    // --- Funciones de validación específicas ---
    function validarTratamientoTipo(input) {
        const valor = input.value.trim();
        
        if (!valor) {
            mostrarError(input, 'El tipo de tratamiento es obligatorio.');
            return false;
        }
        
        if (!regex.tratamiento_tipo.test(valor)) {
            mostrarError(input, 'El tipo de tratamiento debe tener entre 5 y 50 caracteres (solo letras, espacios y guiones).');
            return false;
        }
        
        mostrarValido(input);
        return true;
    }

    function validarDiagnostico(input) {
        const valor = input.value.trim();
        
        if (!valor) {
            mostrarError(input, 'El diagnóstico es obligatorio.');
            return false;
        }
        
        if (!regex.diagnostico_descripcion.test(valor)) {
            mostrarError(input, 'El diagnóstico debe tener entre 10 y 500 caracteres. Puede incluir letras, números y signos de puntuación básicos.');
            return false;
        }
        
        mostrarValido(input);
        return true;
    }

    function validarObservaciones(input) {
        const valor = input.value.trim();
        
        // Las observaciones son opcionales, pero si se ingresan deben cumplir con el formato
        if (valor && !regex.observaciones.test(valor)) {
            mostrarError(input, 'Las observaciones no deben exceder los 1000 caracteres. Puede incluir letras, números y signos de puntuación básicos.');
            return false;
        }
        
        if (valor) {
            mostrarValido(input);
        } else {
            // Si está vacío, limpiar estados de validación
            input.classList.remove('is-invalid', 'is-valid');
            const feedbackExistente = input.parentNode.querySelector('.invalid-feedback');
            if (feedbackExistente) {
                feedbackExistente.remove();
            }
        }
        
        return true;
    }

    // --- Configuración de DataTable ---
    const tablaTratamientos = $('#tablaTratamientos').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
        }, 
        responsive: true,
        ajax: {
            url: '?pagina=tratamiento',
            type: 'POST',
            data: {
                ajax_action: 'listar_tratamientos'
            },
            dataSrc: 'data'
        },
        columns: [
            { 
                data: null,
                render: function(data, type, row) {
                    return row.nombre + ' ' + row.apellido;
                }
            },
            { data: 'cedula' },
            { 
                data: 'fecha_creacion',
                render: function(data, type, row) {
                    return formatDate(data);
                }
            },
            { 
                data: 'estado_actual',
                render: function(data, type, row) {
                    const estadoClass = "badge-" + data;
                    const estadoText = data.replace('_', ' ');
                    return `<span class="badge ${estadoClass}">${estadoText.charAt(0).toUpperCase() + estadoText.slice(1)}</span>`;
                }
            },
            {
                data: 'id_tratamiento',
                render: function(data, type, row) {
                    return `
                    <button class="btn btn-sm btn-warning btn-editar" data-id="${data}">
                            <i class="fas fa-edit me-1"></i>Editar
                        </button>
                        <button class="btn btn-sm btn-danger btn-eliminar" data-id="${data}">
                            <i class="fas fa-trash me-1"></i>Eliminar
                        </button>
                        <button class="btn btn-sm btn-info btn-detalles" data-id="${data}">
                            <i class="fas fa-info-circle me-1"></i>Detalles
                        </button>
                    `;
                },
                orderable: false
            }
        ]
    });
    
    // Establecer fecha actual por defecto en el formulario nuevo
    const today = new Date().toISOString().split('T')[0];
    $('#fecha_creacion').val(today);
    
    // --- Validaciones en tiempo real para formulario NUEVO ---
    // Helper para asignar validaciones y reducir duplicación
    function attachValidation(selector, handler, obligatorio = true) {
        $(selector).on('input change blur', function() {
            this.dataset.touched = 'true';
            handler(this);
        });
    }

    // Tipo de tratamiento
    attachValidation('#tratamiento_tipo', validarTratamientoTipo);
    // Diagnóstico
    attachValidation('#diagnostico_descripcion', validarDiagnostico);
    // Observaciones
    attachValidation('#observaciones', validarObservaciones);
    
    // --- Validaciones en tiempo real para formulario EDITAR ---
    attachValidation('#tratamiento_tipo_editar', validarTratamientoTipo);
    attachValidation('#diagnostico_descripcion_editar', validarDiagnostico);
    attachValidation('#observaciones_editar', validarObservaciones);
    
    // Manejar búsqueda en tiempo real
    $('#buscarTratamiento').on('keyup', function() {
        tablaTratamientos.search(this.value).draw();
    });
    
    $('#btnBuscar').on('click', function() {
        tablaTratamientos.search($('#buscarTratamiento').val()).draw();
    });
    
    // Función para mostrar errores de validación (mantenida para compatibilidad con el backend)
    function mostrarErrores(errors) {
        // Limpiar errores previos
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Mostrar nuevos errores
        for (const field in errors) {
            const input = $(`#${field}`);
            input.addClass('is-invalid');
            input.after(`<div class="invalid-feedback">${errors[field]}</div>`);
        }
    }
    
    // --- Validación al enviar formulario NUEVO tratamiento ---
    $('#formNuevoTratamiento').on('submit', function(e) {
        e.preventDefault();
        

        let valido = true;
        const errorMessages = [];
        
        // Validar campos requeridos
        const tratamientoTipoValido = validarTratamientoTipo(document.getElementById('tratamiento_tipo'));
        const diagnosticoValido = validarDiagnostico(document.getElementById('diagnostico_descripcion'));
        const observacionesValido = validarObservaciones(document.getElementById('observaciones'));
        
        if (!tratamientoTipoValido) {
            valido = false;
            errorMessages.push('Tipo de tratamiento: ' + ($('#tratamiento_tipo').parent().find('.invalid-feedback').text() || 'Campo inválido'));
        }
        
        if (!diagnosticoValido) {
            valido = false;
            errorMessages.push('Diagnóstico: ' + ($('#diagnostico_descripcion').parent().find('.invalid-feedback').text() || 'Campo inválido'));
        }
        
        if (!observacionesValido) {
            valido = false;
            errorMessages.push('Observaciones: ' + ($('#observaciones').parent().find('.invalid-feedback').text() || 'Campo inválido'));
        }
        
        if (!valido) {
            // Mostrar mensaje de error general
            Swal.fire({
                title: 'Error de validación',
                html: '<strong>Por favor corrige lo siguiente:</strong><ul>' + 
                      errorMessages.map(m => '<li>' + m + '</li>').join('') + '</ul>',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
            
            // Reiniciar temporizador si hay errores de validación
            iniciarTemporizadorFormulario();
            
            // Hacer scroll al primer error
            $('.is-invalid').first().get(0)?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            
            return;
        }
        
        // Si todo es válido, proceder con el envío AJAX
        const formData = new FormData(this);
        formData.append('ajax_action', 'crear_tratamiento');
        
        // Mostrar loading
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Guardando...');
        
        $.ajax({
            url: '?pagina=tratamiento',
            method: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false, 
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Tratamiento creado correctamente',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#nuevoTratamientoModal').modal('hide');
                    // Recargar la tabla
                    tablaTratamientos.ajax.reload(null, false);
                    // Resetear formulario
                    $('#formNuevoTratamiento')[0].reset();
                    $('#fecha_creacion').val(today);
                    // Limpiar estados de validación
                    $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
                    $('.invalid-feedback').remove();
                } else {
                    if (response.errors) {
                        mostrarErrores(response.errors);
                        // Reiniciar temporizador si hay errores del servidor
                        iniciarTemporizadorFormulario();
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo crear el tratamiento', 'error');
                        // Reiniciar temporizador si hay error general
                        iniciarTemporizadorFormulario();
                    }
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'Error al crear el tratamiento: ' + error, 'error');
                // Reiniciar temporizador si hay error de conexión
                iniciarTemporizadorFormulario();
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Guardar');
            }
        });
    });
    
    // --- Validación al enviar formulario EDITAR tratamiento ---
    $('#formEditarTratamiento').on('submit', function(e) {
        e.preventDefault();
        
        

        let valido = true;
        const errorMessages = [];
        
        // Validar campos requeridos
        const tratamientoTipoValido = validarTratamientoTipo(document.getElementById('tratamiento_tipo_editar'));
        const diagnosticoValido = validarDiagnostico(document.getElementById('diagnostico_descripcion_editar'));
        const observacionesValido = validarObservaciones(document.getElementById('observaciones_editar'));
        
        if (!tratamientoTipoValido) {
            valido = false;
            errorMessages.push('Tipo de tratamiento: ' + ($('#tratamiento_tipo_editar').parent().find('.invalid-feedback').text() || 'Campo inválido'));
        }
        
        if (!diagnosticoValido) {
            valido = false;
            errorMessages.push('Diagnóstico: ' + ($('#diagnostico_descripcion_editar').parent().find('.invalid-feedback').text() || 'Campo inválido'));
        }
        
        if (!observacionesValido) {
            valido = false;
            errorMessages.push('Observaciones: ' + ($('#observaciones_editar').parent().find('.invalid-feedback').text() || 'Campo inválido'));
        }
        
        if (!valido) {
            // Mostrar mensaje de error general
            Swal.fire({
                title: 'Error de validación',
                html: '<strong>Por favor corrige lo siguiente:</strong><ul>' + 
                      errorMessages.map(m => '<li>' + m + '</li>').join('') + '</ul>',
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
            
            // Reiniciar temporizador si hay errores de validación
            iniciarTemporizadorFormulario();
            
            // Hacer scroll al primer error
            $('.is-invalid').first().get(0)?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            
            return;
        }
        
        // Si todo es válido, proceder con el envío AJAX
        const formData = new FormData(this);
        formData.append('ajax_action', 'actualizar_tratamiento');
        
        // Mostrar loading
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Actualizando...');
        
        $.ajax({
            url: '?pagina=tratamiento',
            method: 'POST',
            dataType: 'json',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Tratamiento actualizado correctamente',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    $('#editarTratamientoModal').modal('hide');
                    // Recargar la tabla
                    tablaTratamientos.ajax.reload(null, false);
                } else {
                    if (response.errors) {
                        mostrarErrores(response.errors);
                        // Reiniciar temporizador si hay errores del servidor
                        iniciarTemporizadorFormulario();
                    } else {
                        Swal.fire('Error', response.message || 'No se pudo actualizar el tratamiento', 'error');
                        // Reiniciar temporizador si hay error general
                        iniciarTemporizadorFormulario();
                    }
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'Error al actualizar el tratamiento: ' + error, 'error');
                // Reiniciar temporizador si hay error de conexión
                iniciarTemporizadorFormulario();
            },
            complete: function() {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save me-1"></i>Actualizar');
            }
        });
    });
    
    // Manejar clic en botón Editar
    $(document).on('click', '.btn-editar', function() {
        const idTratamiento = $(this).data('id');
        
        $.ajax({
            url: '?pagina=tratamiento',
            method: 'POST',
            dataType: 'json',
            data: {
                ajax_action: 'obtener_tratamiento',
                id: idTratamiento
            },
            success: function(response) {
                if (response.success) {
                    const tratamiento = response.data;
                    
                    $('#id_tratamiento_editar').val(tratamiento.id_tratamiento);
                    $('#id_paciente_editar').val(tratamiento.id_paciente);
                    $('#fecha_creacion_editar').val(tratamiento.fecha_creacion);
                    $('#tratamiento_tipo_editar').val(tratamiento.tratamiento_tipo);
                    $('#estado_actual_editar').val(tratamiento.estado_actual);
                    $('#diagnostico_descripcion_editar').val(tratamiento.diagnostico_descripcion);
                    $('#observaciones_editar').val(tratamiento.observaciones);
                    
                    // Limpiar estados de validación al cargar datos
                    $('#tratamiento_tipo_editar, #diagnostico_descripcion_editar, #observaciones_editar')
                        .removeClass('is-valid is-invalid')
                        .next('.invalid-feedback').remove();
                    
                    $('#editarTratamientoModal').modal('show');
                } else {
                    Swal.fire('Error', 'No se pudo cargar el tratamiento', 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'Error al obtener datos del tratamiento: ' + error, 'error');
            }
        });
    });
    
    // Manejar clic en botón Detalles
    $(document).on('click', '.btn-detalles', function() {
        const idTratamiento = $(this).data('id');
        
        $.ajax({
            url: '?pagina=tratamiento',
            method: 'POST',
            dataType: 'json',
            data: {
                ajax_action: 'obtener_tratamiento',
                id: idTratamiento
            },
            success: function(response) {
                if (response.success) {
                    const tratamiento = response.data;
                    
                    // Mostrar datos básicos
                    $('#detalleNombrePaciente').text(tratamiento.nombre + ' ' + tratamiento.apellido);
                    $('#detalleCedula').text('Cédula: ' + tratamiento.cedula);
                    $('#detalleFechaCreacion').text('Fecha inicio: ' + formatDate(tratamiento.fecha_creacion));
                    
                    // Mostrar estado con el badge correspondiente
                    const estadoClass = "badge-" + tratamiento.estado_actual;
                    const estadoText = tratamiento.estado_actual.replace('_', ' ');
                    $('#detalleEstado').text(estadoText.charAt(0).toUpperCase() + estadoText.slice(1))
                        .removeClass().addClass('badge ' + estadoClass);
                    
                    // Mostrar otros datos
                    $('#detalleTipoTratamiento').text(tratamiento.tratamiento_tipo);
                    $('#detalleDiagnostico').text(tratamiento.diagnostico_descripcion || 'No especificado');
                    $('#detalleObservaciones').text(tratamiento.observaciones || 'No hay observaciones');
                    
                    $('#detallesTratamientoModal').modal('show');
                } else {
                    Swal.fire('Error', 'No se pudo cargar el tratamiento', 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'Error al obtener datos del tratamiento: ' + error, 'error');
            }
        });
    });
    
    // Manejar clic en botón Eliminar
    $(document).on('click', '.btn-eliminar', function() {
        const idTratamiento = $(this).data('id');
        $('#id_tratamiento_eliminar').val(idTratamiento);
        $('#confirmarEliminarModal').modal('show');
    });
    
    // Confirmar eliminación
    $('#btnConfirmarEliminar').on('click', function() {
        const idTratamiento = $('#id_tratamiento_eliminar').val();
        
        $.ajax({
            url: '?pagina=tratamiento',
            method: 'POST',
            dataType: 'json',
            data: {
                ajax_action: 'eliminar_tratamiento',
                id: idTratamiento
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: 'Éxito',
                        text: 'Tratamiento eliminado correctamente',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    // Recargar la tabla
                    tablaTratamientos.ajax.reload(null, false);
                } else {
                    Swal.fire('Error', response.message || 'No se pudo eliminar el tratamiento', 'error');
                }
                $('#confirmarEliminarModal').modal('hide');
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'Error al eliminar el tratamiento: ' + error, 'error');
                $('#confirmarEliminarModal').modal('hide');
            }
        });
    });
    
    // Formatear fecha para mostrar
    function formatDate(dateString) {
        const date = new Date(dateString);
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }
    
    // Limpiar formulario cuando se cierra el modal
    $('#nuevoTratamientoModal').on('hidden.bs.modal', function() {
        $('#formNuevoTratamiento')[0].reset();
        $('#fecha_creacion').val(today);
        $('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        $('.invalid-feedback').remove();
        // Detener temporizador al cerrar manualmente
    });
    
    // Limpiar errores cuando se cierra el modal de edición
    $('#editarTratamientoModal').on('hidden.bs.modal', function() {
        $('.is-invalid, .is-valid').removeClass('is-invalid is-valid');
        $('.invalid-feedback').remove();
        // Detener temporizador al cerrar manualmente
    });
});