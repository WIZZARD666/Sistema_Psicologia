// =======================================================
// === VALIDACIONES Y MENSAJES DE PERFIL ===
// =======================================================

// Expresiones Regulares
const telefonoRegex = /^[\d\s-]{8,15}$/;
const nombreApellidoRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,50}$/;
const claveRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

// Mensajes
const TELEFONO_ERROR_MSG = "El teléfono no tiene un formato válido (8-15 caracteres).";
const NOMBRE_ERROR_MSG = "Solo letras y espacios (2-50 caracteres).";
const CLAVE_ERROR_MSG = "Mínimo 8 caracteres, debe incluir Mayús, Minús y Números.";


// =======================================================
// === FUNCIÓN CENTRAL AJAX CON SWEETALERT2 BONITO ===
// =======================================================

function enviarAjaxPerfil(datos, modalId, tipoAccion) {
    Swal.fire({
        title: "Procesando...",
        text: "Por favor, espere un momento.",
        didOpen: () => Swal.showLoading(),
        allowOutsideClick: false,
        allowEscapeKey: false
    });

    $.ajax({
        async: true,
        url: window.location.href,
        type: "POST",
        data: datos,
        contentType: false,
        processData: false,
        cache: false,
        dataType: 'json',
        timeout: 15000,
        success: function (respuesta) {
            $(modalId).modal('hide'); // Cierra el modal
            Swal.close();

            if (respuesta.status === 'success') {
                // Mensaje de éxito bonito según tipo de acción
                let titulo = tipoAccion === 'modificar_perfil' 
                    ? 'Perfil modificado' 
                    : 'Contraseña actualizada';

                let texto = tipoAccion === 'modificar_perfil'
                    ? 'Tus datos fueron actualizados correctamente.'
                    : 'Tu contraseña ha sido cambiada con éxito.';

                Swal.fire({
                    icon: "success",
                    title: titulo,
                    text: texto,
                    confirmButtonText: 'Aceptar',
                    confirmButtonColor: '#75A5B8',
                    background: '#fefefe',
                    color: '#333',
                    showClass: {
                        popup: 'animate__animated animate__fadeInDown'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutUp'
                    }
                }).then(() => window.location.reload());

            } else {
                // Error controlado desde PHP
                Swal.fire({
                    icon: "error",
                    title: "Error al Guardar",
                    text: respuesta.message || "Ocurrió un error inesperado.",
                    confirmButtonColor: '#dc3545'
                });
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error('AJAX error', textStatus, errorThrown, jqXHR.responseText);
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error de Conexión',
                text: 'Hubo un problema al comunicarse con el servidor. Intente de nuevo.',
                confirmButtonColor: '#dc3545'
            });
        }
    });
}


// =======================================================
// === VALIDACIONES Y ENVÍO DE FORMULARIOS ===
// =======================================================

$(document).ready(function(){

    // Helpers de error
    function mostrarError(input, msg) {
        let errorElement = input.closest('.mb-3').find('small.text-danger');
        if (errorElement.length === 0) {
            input.after(`<small class="text-danger">${msg}</small>`);
            errorElement = input.closest('.mb-3').find('small.text-danger');
        }
        errorElement.text(msg).show();
    }

    function ocultarError(input) {
        input.closest('.mb-3').find('small.text-danger').hide().text('');
    }

    function validarCampo(input, regex, msg) {
        const valor = input.val().trim();
        if (valor === "" && input.prop('required')) {
            mostrarError(input, "Este campo es obligatorio.");
            return false;
        } else if (valor !== "" && !regex.test(valor)) {
            mostrarError(input, msg);
            return false;
        } else {
            ocultarError(input);
            return true;
        }
    }

    // =======================================================
    // === FORMULARIO DE PERFIL ===
    // =======================================================
    const formPerfil = $('#modalEditarPerfil form');
    const nombreInput = $('#nombre_modificar');
    const apellidoInput = $('#apellido_modificar');
    const telefonoInput = $('#telefono_modificar');

    nombreInput.on('keyup blur', () => validarCampo(nombreInput, nombreApellidoRegex, NOMBRE_ERROR_MSG));
    apellidoInput.on('keyup blur', () => validarCampo(apellidoInput, nombreApellidoRegex, NOMBRE_ERROR_MSG));
    telefonoInput.on('keyup blur', () => validarCampo(telefonoInput, telefonoRegex, TELEFONO_ERROR_MSG));

    formPerfil.on('submit', function(e) {
        e.preventDefault();

        const nombreValido = validarCampo(nombreInput, nombreApellidoRegex, NOMBRE_ERROR_MSG);
        const apellidoValido = validarCampo(apellidoInput, nombreApellidoRegex, NOMBRE_ERROR_MSG);
        const telefonoValido = validarCampo(telefonoInput, telefonoRegex, TELEFONO_ERROR_MSG);

        if (nombreValido && apellidoValido && telefonoValido) {
            const datos = new FormData(this);
            enviarAjaxPerfil(datos, '#modalEditarPerfil', 'modificar_perfil');
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Error de Validación',
                text: 'Corrige los campos marcados antes de guardar.'
            });
        }
    });


    // =======================================================
    // === FORMULARIO DE CONTRASEÑA ===
    // =======================================================
    const formClave = $('#modalCambiarContrasena form');
    const passwordNueva = $('#password_nueva');
    const passwordConfirmar = $('#password_confirmar');

    function validarClaveNueva() {
        const valor = passwordNueva.val();
        let esValido = true;

        if (valor === "") {
            mostrarError(passwordNueva, "La contraseña es obligatoria.");
            esValido = false;
        } else if (!claveRegex.test(valor)) {
            mostrarError(passwordNueva, CLAVE_ERROR_MSG);
            esValido = false;
        } else {
            ocultarError(passwordNueva);
        }

        if (passwordConfirmar.val() !== "") validarClaveConfirmar();
        return esValido;
    }

    function validarClaveConfirmar() {
        const nueva = passwordNueva.val();
        const confirmar = passwordConfirmar.val();
        let esValido = true;

        if (confirmar === "") {
            mostrarError(passwordConfirmar, "Debe confirmar la contraseña.");
            esValido = false;
        } else if (nueva !== confirmar) {
            mostrarError(passwordConfirmar, "Las contraseñas no coinciden.");
            esValido = false;
        } else {
            ocultarError(passwordConfirmar);
        }

        return esValido;
    }

    passwordNueva.on('keyup blur', validarClaveNueva);
    passwordConfirmar.on('keyup blur', validarClaveConfirmar);

    formClave.on('submit', function(e) {
        e.preventDefault();

        const claveNuevaValida = validarClaveNueva();
        const claveConfirmarValida = validarClaveConfirmar();

        if (claveNuevaValida && claveConfirmarValida) {
            const datos = new FormData(this);
            enviarAjaxPerfil(datos, '#modalCambiarContrasena', 'cambiar_password');
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Error de Validación',
                text: 'Corrige los campos de contraseña antes de continuar.'
            });
        }
    });

});
