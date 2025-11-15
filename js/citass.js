// Declara la instancia global del calendario
var calendar;

// Nota: Se ha eliminado la funcionalidad de temporizador/timeout que cerraba los modales
// porque interfería con el envío de formularios. El comportamiento ahora vuelve a
// depender únicamente de las acciones del usuario (abrir/cerrar/enviar modales).

document.addEventListener("DOMContentLoaded", function() {
    // Control de tiempo eliminado: el envío depende solo de la acción del usuario

    // Eliminar handlers viejos de jQuery que disparaban confirm
    if (window.jQuery) {
        jQuery(document).off('click', '.fc-event');
    }

    // Modales
    const modalVerEl = document.getElementById("ModalVisualizar");
    const modalVer = modalVerEl ? (bootstrap.Modal.getOrCreateInstance ?
        bootstrap.Modal.getOrCreateInstance(modalVerEl) :
        new bootstrap.Modal(modalVerEl)) : null;

    const modalGuardarEl = document.getElementById("GuardarModal");
    const guardarModal = modalGuardarEl ? (bootstrap.Modal.getOrCreateInstance ?
        bootstrap.Modal.getOrCreateInstance(modalGuardarEl) :
        new bootstrap.Modal(modalGuardarEl)) : null;

    // Utilidades
    function setField(id_cita, value) {
        const el = document.getElementById(id_cita);
        if (!el) return;
        if ('value' in el) el.value = value ?? '';
        else el.textContent = value ?? '';
    }

    function formatDateTime(d) {
        const dt = new Date(d);
        const y = dt.getFullYear();
        const m = String(dt.getMonth() + 1).padStart(2, '0');
        const day = String(dt.getDate()).padStart(2, '0');
        const hh = String(dt.getHours()).padStart(2, '0');
        const mm = String(dt.getMinutes()).padStart(2, '0');
        return `${y}-${m}-${day}T${hh}:${mm}`;
    }

    // Inicializar FullCalendar
    var calendarEl = document.getElementById('calendar');
    calendar = new FullCalendar.Calendar(calendarEl, {
        themeSystem: 'bootstrap5',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        locale: 'es',
        buttonText: {
            today: 'Hoy',
            month: 'Mes',
            week: 'Semana',
            day: 'Día',
            list: 'Lista'
        },
        titleFormat: {
            year: 'numeric',
            month: 'long'
        }, // "agosto de 2025"
        initialDate: new Date().toISOString().slice(0, 10), // Fecha actual automática
        navLinks: true,
        selectable: true,
        selectMirror: true,
        editable: false,
        eventStartEditable: false,
        eventDurationEditable: false,
        dayMaxEvents: true,

        // Cargar eventos desde el servidor
        events: async function(fetchInfo, successCallback, failureCallback) {
            try {
                const response = await fetch('Controller/cita.php?ajax=1');
                const data = await response.json();
                successCallback(Array.isArray(data) ? data : []);
                console.log('eventos cargados');
            } catch (e) {
                failureCallback(e);
            }
        },

        // Evitar que eventos con "url" se abran como links
        eventDidMount: function(arg) {
            const a = arg.el.tagName === 'A' ? arg.el : arg.el.querySelector('a');
            if (a) a.removeAttribute('href');
        },

        // Selección de rango -> abrir modal de crear
        select: function(info) {
            if (!guardarModal) return;
            // Limpiar el formulario para evitar valores residuales ("cache")
            try {
                const f = document.getElementById('formEvento');
                if (f) f.reset();
            } catch (e) {}
            setField("start", formatDateTime(info.start));
            setField("end", formatDateTime(info.end));
            guardarModal.show();
        },

       eventClick: function(info) {
    if (info.jsEvent) {
        info.jsEvent.preventDefault();
        info.jsEvent.stopPropagation();
        if (info.jsEvent.stopImmediatePropagation) {
            info.jsEvent.stopImmediatePropagation();
        }
    }

    if (info.event.url) {
        try { info.event.setProp('url', null); } catch (_) {}
    }

    if (!modalVer) {
        console.error('No se encontró #ModalVisualizar o falta bootstrap.bundle.min.js');
        return;
    }

    // ✅ Mostrar nombre completo en subtítulo
    const nombre = info.event.extendedProps?.nombre || "";
    const apellido = info.event.extendedProps?.apellido || "";
    const nombreCompleto = (nombre || apellido) ? `${nombre} ${apellido}`.trim() : "Paciente no especificado";
    document.getElementById("nombrePacienteVisualizar").textContent = nombreCompleto;

    // ✅ Cargar datos al formulario
    setField("idVisualizar", info.event.id);
    setField("titleVisualizar", info.event.title || "");
    setField("descripcionVisualizar", info.event.extendedProps?.descripcion || "");
    setField("colorVisualizar", info.event.backgroundColor || info.event.extendedProps?.color || "");
    setField("textoColorVisualizar", info.event.textColor || info.event.extendedProps?.textColor || "");
    setField("startVisualizar", info.event.start ? formatDateTime(info.event.start) : "");
    setField("endVisualizar", info.event.end ? formatDateTime(info.event.end) : "");

    modalVer.show();
}

    });

    calendar.render();

    // Guardar nueva cita (desde modal GuardarModal)
    const formEvento = document.getElementById("formEvento");
    if (formEvento) {
        formEvento.addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Envío normal del formulario (sin temporizador)

            const formData = new FormData(formEvento);
            const datos = {
                ...Object.fromEntries(formData.entries()),
                guardar_cita: true
            };

            $.ajax({
                url: "Controller/cita.php",
                type: "POST",
                data: datos,
                success: function(response) {
                    // El controlador devuelve JSON: {resultado: 'ok'|'existe'|'error', mensaje: '...'}
                    var res = response;
                    if (typeof res === 'string') {
                        try { res = JSON.parse(res); } catch(e) { res = {resultado: 'error', mensaje: 'Respuesta inválida'}; }
                    }

                    if (res && res.resultado === 'ok') {
                        // limpiar formulario al guardar correctamente
                        try { const f = document.getElementById('formEvento'); if (f) f.reset(); } catch(e){}
                        if (modalGuardarEl) bootstrap.Modal.getInstance(modalGuardarEl).hide();
                        calendar.refetchEvents();
                        Swal.fire({
                            icon: "success",
                            title: "Éxito",
                            text: res.mensaje || "Cita guardada correctamente",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else if (res && res.resultado === 'existe') {
                        // No cerrar el modal; mostrar mensaje de conflicto
                        Swal.fire({
                            icon: "error",
                            title: "No se pudo enviar",
                            text: res.mensaje || "Ya existe una cita a esa hora."
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: (res && res.mensaje) ? res.mensaje : "Error al guardar la cita"
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Error al guardar la cita"
                    });
                    // Error en envío: el usuario puede reintentar
                }
            });
        });
    }

    // Editar cita (desde modal Visualizar)
    const formVisualizar = document.getElementById("formVisualizar");
    if (formVisualizar) {
        formVisualizar.addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Envío normal del formulario (sin temporizador)

            const formData = new FormData(formVisualizar);
            const datos = {
                ...Object.fromEntries(formData.entries()),
                actualizar_cita: true
            };

            $.ajax({
                url: "Controller/cita.php",
                type: "POST",
                data: datos,
                success: function(response) {
                    var res = response;
                    if (typeof res === 'string') {
                        try { res = JSON.parse(res); } catch(e) { res = {resultado: 'error', mensaje: 'Respuesta inválida'}; }
                    }

                    if (res && res.resultado === 'ok') {
                        if (modalVer) modalVer.hide();
                        calendar.refetchEvents();
                        Swal.fire({
                            icon: "success",
                            title: "Éxito",
                            text: res.mensaje || "Cita actualizada correctamente",
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else if (res && res.resultado === 'existe') {
                        // No cerrar modal, mostrar conflicto
                        Swal.fire({
                            icon: "error",
                            title: "No se pudo enviar",
                            text: res.mensaje || "Ya existe una cita a esa hora."
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: (res && res.mensaje) ? res.mensaje : "Error al actualizar la cita"
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Error al actualizar la cita"
                    });
                    // Error en actualización: el usuario puede reintentar
                }
            });
        });
    }

    // Eliminar cita
    const btnEliminar = document.getElementById("btnEliminar");
    if (btnEliminar) {
        btnEliminar.addEventListener("click", function() {
            const id = document.getElementById("idVisualizar").value;
            if (!id) {
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "No se encontró el ID de la cita."
                });
                return;
            }
            
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¿Seguro que quieres eliminar esta cita?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: "#ff5c5c",
                cancelButtonColor: "#75A5B8",
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Acción de eliminación iniciada

                    $.ajax({
                        url: "Controller/cita.php",
                        type: "POST",
                        data: {
                            eliminar_cita: true,
                            id_cita: id
                        },
                        success: function() {
                            modalVer.hide();
                            calendar.refetchEvents();
                            Swal.fire({
                                icon: "success",
                                title: "Eliminado",
                                text: "Cita eliminada correctamente",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: "error",
                                title: "Error",
                                text: "Error al eliminar la cita"
                            });
                        }
                    });
                }
            });
        });
    }
});