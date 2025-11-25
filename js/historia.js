// --- Expresiones regulares para validaciones ---
const regex = {
    textoBasico: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\.,;:\-\(\)]{0,500}$/,
    textoLargo: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\.,;:\-\(\)]{0,1000}$/,
    trabajo: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\-]{0,100}$/,
    significativo: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\.,;:\-\(\)]{0,150}$/,
    soloLetras: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]*$/,
    email: /^([a-zA-Z0-9._%+-]+)@([a-zA-Z0-9.-]+)\.([a-zA-Z]{2,})$/,
    cedula: /^\d{6,10}$/,
    telefono: /^\d{7,12}$/
}; 

// --- Variables para el control de tiempo ---
let temporizadorFormulario = null;
// Tiempo límite del formulario: 1 minuto (60000 ms)
const TIEMPO_LIMITE_FORMULARIO = 6000000;

// --- Funciones de control de tiempo ---
function iniciarTemporizadorFormulario() {
    // Limpiar temporizador anterior si existe
    if (temporizadorFormulario) {
        clearTimeout(temporizadorFormulario);
    }
  // Configurar temporizador único (1 minuto)
  temporizadorFormulario = setTimeout(function() {
    cerrarFormularioPorTiempo();
  }, TIEMPO_LIMITE_FORMULARIO);
  console.log("Temporizador iniciado - Tiempo límite: " + TIEMPO_LIMITE_FORMULARIO + "ms");
}

function detenerTemporizadorFormulario() {
    if (temporizadorFormulario) {
        clearTimeout(temporizadorFormulario);
        temporizadorFormulario = null;
        console.log("Temporizador detenido");
    }
}

function cerrarFormularioPorTiempo() {
    // Cerrar el modal de registro
    $("#modalRegistro").modal("hide");
    
    // Limpiar el formulario
    $("#registroHistorial").get(0).reset();
    
    // Mostrar mensaje de tiempo excedido
    Swal.fire({
        icon: "warning",
        title: "Tiempo agotado",
        text: "Excedió el tiempo para llenar el formulario. Por favor, intente nuevamente.",
        timer: 4000,
        showConfirmButton: true,
        confirmButtonText: 'Entendido'
    });
    
    // Detener todos los temporizadores
    detenerTemporizadorFormulario();
  console.log("Formulario cerrado por exceder el tiempo límite de " + TIEMPO_LIMITE_FORMULARIO + "ms");
}

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
function validarTextoBasico(input, campoObligatorio = false) {
    const valor = input.value.trim();
    
    if (campoObligatorio && !valor) {
        mostrarError(input, 'Este campo es obligatorio.');
        return false;
    }
    
    if (valor && !regex.textoBasico.test(valor)) {
        mostrarError(input, 'Máximo 500 caracteres. Solo letras, números y signos de puntuación básicos.');
        return false;
    }
    
    if (valor || !campoObligatorio) {
        mostrarValido(input);
    } else {
        input.classList.remove('is-invalid', 'is-valid');
    }
    
    return true;
}

function validarTextoLargo(input, campoObligatorio = false) {
    const valor = input.value.trim();
    
    if (campoObligatorio && !valor) {
        mostrarError(input, 'Este campo es obligatorio.');
        return false;
    }
    
    if (valor && !regex.textoLargo.test(valor)) {
        mostrarError(input, 'Máximo 1000 caracteres. Solo letras, números y signos de puntuación básicos.');
        return false;
    }
    
    if (valor || !campoObligatorio) {
        mostrarValido(input);
    } else {
        input.classList.remove('is-invalid', 'is-valid');
    }
    
    return true;
}

function validarTrabajo(input) {
    const valor = input.value.trim();
    
    if (valor && !regex.trabajo.test(valor)) {
        mostrarError(input, 'Máximo 100 caracteres. Solo letras, espacios y guiones.');
        return false;
    }
    
    if (valor) {
        mostrarValido(input);
    } else {
        input.classList.remove('is-invalid', 'is-valid');
    }
    
    return true;
}

function validarSignificativo(input) {
    const valor = input.value.trim();
    
    if (valor && !regex.significativo.test(valor)) {
        mostrarError(input, 'Máximo 150 caracteres. Solo letras, números y signos de puntuación básicos.');
        return false;
    }
    
    if (valor) {
        mostrarValido(input);
    } else {
        input.classList.remove('is-invalid', 'is-valid');
    }
    
    return true;
}

function validarCompromiso(input) {
    const valor = input.value.trim();
    
    if (!valor) {
        mostrarError(input, 'Este campo es obligatorio.');
        return false;
    }
    
    const numero = parseInt(valor);
    if (isNaN(numero) || numero < 1 || numero > 10) {
        mostrarError(input, 'Debe ser un número entre 1 y 10.');
        return false;
    }
    
    mostrarValido(input);
    return true;
}

function validarSintomas() {
    const sintomasCheckboxes = document.querySelectorAll('input[name="sintoma[]"]:checked');
    const otrosSintomas = document.getElementById('otros_sintoma');
    
    // AMBOS SON OPCIONALES - solo validar formato si se ingresa algo
    if (otrosSintomas.value.trim()) {
        return validarTextoLargo(otrosSintomas, false);
    }
    
    return true;
}

// --- Inicialización ---
function consultar() {
  var datos = new FormData();
  datos.append("accion", "consultarPacientes");
  enviarAjax(datos);
}

document.addEventListener("DOMContentLoaded", function () {
  consultar();
  inicializarValidaciones();
 $('#buscarpaciente').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase().trim();

        // Itera sobre los hijos directos del contenedor (asumiendo que cada hijo es un paciente)
        // Opcionalmente, puedes buscar una clase específica si usas tarjetas, ej: .paciente-card
        $('#pacientesContainer > *').each(function() {
            const $item = $(this);
            
            // 🛑 CRUCIAL: Debes tener estas clases (.cedula, .nombre, .apellido) en algún
            // elemento dentro del DIV de cada paciente que inyecta tu PHP.
            // Si el texto de Cédula/Nombre/Apellido NO está dentro de un elemento con clase,
            // puedes usar el texto completo del contenedor: $item.text()
            
            // INTENTO 1: Buscar por clases específicas (Ideal si existe)
            const cedula = $item.find('.cedula').text() || '';
            const nombre = $item.find('.nombre').text() || '';
            const apellido = $item.find('.apellido').text() || '';
            
            let fullText = (cedula + ' ' + nombre + ' ' + apellido).toLowerCase();
            
            // INTENTO 2 (Alternativo): Si no hay clases específicas, busca en todo el texto del contenedor
            if (!fullText.trim()) {
                fullText = $item.text().toLowerCase();
            }

            // 3. Aplica el filtro (muestra si coincide, oculta si no)
            if (fullText.includes(searchTerm)) {
                $item.show(); // Muestra el elemento (tarjeta/div)
            } else {
                $item.hide(); // Oculta el elemento
            }
        });
    });
});

function inicializarControlTiempo() {
    // Evento cuando se abre el modal de registro
    $('#modalRegistro').on('show.bs.modal', function() {
        iniciarTemporizadorFormulario();
    });
    
    // Evento cuando se cierra el modal (por cualquier razón)
    $('#modalRegistro').on('hide.bs.modal', function() {
    detenerTemporizadorFormulario();
    });
    
    // Eventos para pausar el temporizador cuando el usuario escribe
  // Ya no se pausan/reanudan temporizadores con la interacción del usuario.
}

function inicializarValidaciones() {
  // --- Validaciones en tiempo real para formulario NUEVO ---
  // Helper para adjuntar validación simple
  function attachValidation(id, handler, obligatorio = false) {
    const input = document.getElementById(id);
    if (!input) return;
    input.addEventListener('input', function() {
      this.dataset.touched = 'true';
      handler(this, obligatorio);
    });
  }

  // Campos de texto largos (obligatorios)
  const camposTextoObligatorios = [
    'convivencia', 'relacion_mejorar', 'area_conflictiva',
    'rutina_sueno', 'personas_significativas', 'ayuda_terapia',
    'espera_terapia', 'duracion_terapia', 'importante_reflejar'
  ];
  camposTextoObligatorios.forEach(id => attachValidation(id, validarTextoLargo, true));

  // Otros síntomas (opcional)
  attachValidation('otros_sintoma', validarTextoLargo, false);

  // Campos especiales
  attachValidation('trabajar', validarTrabajo);
  attachValidation('significativo', validarSignificativo);
  attachValidation('compromiso_terapia', validarCompromiso);
    
  // Los checkboxes/radios/select no requieren validación en tiempo real aquí.

    // --- Validaciones en tiempo real para formulario MODIFICAR ---
    

    
    // Campos de texto modificar (obligatorios)
    const camposTextoObligatoriosModificar = [
        'convivencia_modificar', 'relacion_mejorar_modificar', 'area_conflictiva_modificar',
        'frecuencia_alcohol_modificar', 'frecuencia_fumar_modificar', 'frecuencia_sustancia_modificar',
        'rutina_sueno_modificar', 'tratamiento_recibido_modificar', 'finalizado_tratamiento_modificar',
        'personas_significativas_modificar', 'ayuda_terapia_modificar', 'espera_terapia_modificar',
        'duracion_terapia_modificar', 'importante_reflejar_modificar'
    ];
    
  camposTextoObligatoriosModificar.forEach(id => attachValidation(id, validarTextoLargo, true));
    
    // "Otros síntomas" modificar - OPCIONAL
  attachValidation('otros_sintoma_modificar', validarTextoLargo, false);
    
    // Campos especiales modificar
  attachValidation('trabajarM', validarTrabajo);
  attachValidation('significativoM', validarSignificativo);
  attachValidation('compromiso_terapia_modificar', validarCompromiso);
}

function validarSintomasModificar() {
    const otrosSintomas = document.getElementById('otros_sintoma_modificar');
    
    // OPCIONAL - solo validar formato si se ingresa algo
    if (otrosSintomas.value.trim()) {
        return validarTextoLargo(otrosSintomas, false);
    }
    
    return true;
}

// BOTONES MODAL
$("#botonCerrar").on("click", () => {
  $("#modalRegistro").modal("hide");
});

$("#modificarCerrar").on("click", () => {
  $("#modalModificar").modal("hide");
});

function imprimir() {
  console.log("presionaste");
}

// --- Validación al enviar formulario NUEVO historial ---
$("#registroHistorial").on("submit", function (e) {
  e.preventDefault();

  // Detener el temporizador ya que el usuario está enviando el formulario
  detenerTemporizadorFormulario();

  let valido = true;
  const errorMessages = [];

  // Validar campos obligatorios básicos
  const camposObligatorios = [
    'convivencia', 'relacion_mejorar', 'area_conflictiva',
    'rutina_sueno', 'personas_significativas', 'ayuda_terapia',
    'espera_terapia', 'duracion_terapia', 'importante_reflejar'
  ];

  camposObligatorios.forEach(id => {
    const input = document.getElementById(id);
    if (input && !validarTextoLargo(input, true)) {
      valido = false;
      errorMessages.push(`${input.previousElementSibling?.textContent || id}: Campo obligatorio no válido`);
    }
  });

  // Validar síntomas (TOTALMENTE OPCIONAL - solo validar formato si se ingresa algo)
  if (!validarSintomas()) {
    valido = false;
    errorMessages.push('Síntomas: Formato incorrecto en otros síntomas');
  }

  // Validar compromiso
  const compromisoInput = document.getElementById('compromiso_terapia');
  if (compromisoInput && !validarCompromiso(compromisoInput)) {
    valido = false;
    errorMessages.push('Compromiso: Debe ser un número entre 1 y 10');
  }

  if (!valido) {
    Swal.fire({
      title: 'Error de validación',
      html: '<strong>Por favor corrige lo siguiente:</strong><ul>' + 
            errorMessages.map(m => '<li>' + m + '</li>').join('') + '</ul>',
      icon: 'error',
      confirmButtonText: 'Entendido'
    });
    
    // Reiniciar el temporizador si hay errores de validación
    iniciarTemporizadorFormulario();
    return;
  }

  // Si todo es válido, proceder con el envío
  let sintomas = $('input[name="sintoma[]"]:checked').map(function() {
    return this.value;
  }).get();

  var datos = new FormData();
  datos.append("accion", "registrar");
  datos.append("id_paciente", $("#id_paciente").val());
  datos.append("sintomas", sintomas);
  datos.append("otrosintomas", $("#otros_sintoma").val());
  datos.append("convives", $("#convivencia").val());
  datos.append("cambiar", $("#relacion_mejorar").val());
  datos.append("conflicto", $("#area_conflictiva").val());
  datos.append("trabajar", $("#trabajar").val());
  datos.append("alcohol", $("input[name='alcohol']:checked").val());
  datos.append("alcofrecuencia", $("#frecuencia_alcohol").val());
  datos.append("fumas", $("input[name='fumar']:checked").val());
  datos.append("fumafrecuencia", $("#frecuencia_fumar").val());
  datos.append("consumir", $("input[name='sustancia']:checked").val());
  datos.append("consufrecuencia", $("#frecuencia_sustancia").val());
  datos.append("rutina", $("#rutina_sueno").val());
  datos.append("acudir", $("input[name='acudido']:checked").val());
  datos.append("tratamiento", $("#tratamiento_recibido").val());
  datos.append("finalizar", $("#finalizado_tratamiento").val());
  datos.append("significativo", $("#significativo").val());
  datos.append("PersonaSigni", $("#personas_significativas").val());
  datos.append("PodriaAyudar", $("#ayuda_terapia").val());
  datos.append("ConseguirTerapia", $("#espera_terapia").val());
  datos.append("compromiso", $("#compromiso_terapia").val());
  datos.append("TiempoDurara", $("#duracion_terapia").val());
  datos.append("considerar", $("#importante_reflejar").val());
  enviarAjax(datos);
});

// --- Validación al enviar formulario MODIFICAR historial ---
$("#modificarHistorial").on("submit", (e) => {
  e.preventDefault();

  let valido = true;
  const errorMessages = [];


  // Validar campos obligatorios del historial
  const camposObligatoriosModificar = [
    'convivencia_modificar', 'relacion_mejorar_modificar', 'area_conflictiva_modificar',
    'rutina_sueno_modificar', 'personas_significativas_modificar', 'ayuda_terapia_modificar',
    'espera_terapia_modificar', 'duracion_terapia_modificar', 'importante_reflejar_modificar'
  ];

  camposObligatoriosModificar.forEach(id => {
    const input = document.getElementById(id);
    if (input && !validarTextoLargo(input, true)) {
      valido = false;
      errorMessages.push(`${input.previousElementSibling?.textContent || id}: Campo obligatorio no válido`);
    }
  });

  // Validar síntomas modificar (TOTALMENTE OPCIONAL - solo validar formato si se ingresa algo)
  if (!validarSintomasModificar()) {
    valido = false;
    errorMessages.push('Síntomas: Formato incorrecto en otros síntomas');
  }

  // Validar compromiso modificar
  const compromisoModificarInput = document.getElementById('compromiso_terapia_modificar');
  if (compromisoModificarInput && !validarCompromiso(compromisoModificarInput)) {
    valido = false;
    errorMessages.push('Compromiso: Debe ser un número entre 1 y 10');
  }

  if (!valido) {
    Swal.fire({
      title: 'Error de validación',
      html: '<strong>Por favor corrige lo siguiente:</strong><ul>' + 
            errorMessages.map(m => '<li>' + m + '</li>').join('') + '</ul>',
      icon: 'error',
      confirmButtonText: 'Entendido'
    });
    return;
  }

  // Si todo es válido, proceder con el envío
  let sintomas = $('input[name="sintoma_modificar[]"]:checked')
    .map(function () {
      return this.value;
    })
    .get();

  var datos = new FormData();
  datos.append("accion", "modificar");
  datos.append("id", $("#id_historia_modificar").val());
  datos.append("id_paciente", $("#id_paciente_modificar").val());
  datos.append("sintomas", sintomas);
  datos.append("otrosintomas", $("#otros_sintoma_modificar").val());
  datos.append("convives", $("#convivencia_modificar").val());
  datos.append("cambiar", $("#relacion_mejorar_modificar").val());
  datos.append("conflicto", $("#area_conflictiva_modificar").val());
  datos.append("trabajar", $("#trabajarM").val());
  datos.append("alcohol", $("input[name='alcohol_modificar']:checked").val());
  datos.append("alcofrecuencia", $("#frecuencia_alcohol_modificar").val());
  datos.append("fumas", $("input[name='fumar_modificar']:checked").val());
  datos.append("fumafrecuencia", $("#frecuencia_fumar_modificar").val());
  datos.append(
    "consumir",
    $("input[name='sustancia_modificar']:checked").val()
  );
  datos.append("consufrecuencia", $("#frecuencia_sustancia_modificar").val());
  datos.append("rutina", $("#rutina_sueno_modificar").val());
  datos.append("acudir", $("input[name='acudido_modificar']:checked").val());
  datos.append("tratamiento", $("#tratamiento_recibido_modificar").val());
  datos.append("finalizar", $("#finalizado_tratamiento_modificar").val());
  datos.append("significativo", $("#significativoM").val());
  datos.append("PersonaSigni", $("#personas_significativas_modificar").val());
  datos.append("PodriaAyudar", $("#ayuda_terapia_modificar").val());
  datos.append("ConseguirTerapia", $("#espera_terapia_modificar").val());
  datos.append("compromiso", $("#compromiso_terapia_modificar").val());
  datos.append("TiempoDurara", $("#duracion_terapia_modificar").val());
  datos.append("considerar", $("#importante_reflejar_modificar").val());
  enviarAjax(datos);
});

// ELIMINAR HISTORIAL
function eliminarhistoria(id) {
  var datos = new FormData();
  datos.append("accion", "eliminar");
  datos.append("id", id);
  enviarAjax(datos);
}

// ACTUALIZAR CAMPOS MODIFICAR
function actualizarCampos(id, datos = false) {
  event.preventDefault();

  if (datos) {
    // Limpiar estados de validación antes de cargar nuevos datos
    $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
    $('.invalid-feedback').remove();

    $("#id_paciente_modificar").val(datos.id_paciente);
    $("#id_historia_modificar").val(datos.id_historia);

    $("#nombre_modificar").val(datos.nombre);
    $("#apellido_modificar").val(datos.apellido);
    $("#cedula_modificar").val(datos.cedula);
    $("#telefono_modificar").val(datos.telefono);
    $("#email_modificar").val(datos.email);
    $("#fecha_nacimiento_modificar").val(datos.fecha_nacimiento);
    $(`input[name='genero_modificar'][value='${datos.genero}']`).prop(
      'checked', true
    );
    
    // Limpiar checkboxes de síntomas primero
    $('input[name="sintoma_modificar[]"]').prop('checked', false);
    
    // Marcar los síntomas existentes
    if (datos.sintomas && Array.isArray(datos.sintomas)) {
      datos.sintomas.forEach(sintoma => {
        $(`input[name='sintoma_modificar[]'][value='${sintoma}']`).prop(
          "checked",
          true
        );
      });
    }

    $("#otros_sintoma_modificar").val(datos.otrosintomas);
    $("#convivencia_modificar").val(datos.convives);
    $("#relacion_mejorar_modificar").val(datos.cambiar);
    $("#area_conflictiva_modificar").val(datos.conflicto);
    $("#trabajarM").val(datos.trabajar);

    $(`input[name='alcohol_modificar'][value='${datos.alcohol}']`).prop(
      "checked",
      true
    );
    $("#frecuencia_alcohol_modificar").val(datos.alcofrecuencia);
    
    $(`input[name='fumar_modificar'][value='${datos.fumas}']`).prop(
      "checked",
      true
    );
    $("#frecuencia_fumar_modificar").val(datos.fumafrecuencia);

    $(`input[name='sustancia_modificar'][value='${datos.consumir}']`).prop(
      "checked",
      true
    );
    $("#frecuencia_sustancia_modificar").val(datos.consufrecuencia);

    $("#rutina_sueno_modificar").val(datos.rutina);

    $(`input[name='acudido_modificar'][value='${datos.acudir}']`).prop(
      "checked",
      true
    );
    
    $("#tratamiento_recibido_modificar").val(datos.tratamiento);
    $("#finalizado_tratamiento_modificar").val(datos.finalizar);
    $("#significativoM").val(datos.significativo);
    $("#personas_significativas_modificar").val(datos.PersonaSigni);
    $("#ayuda_terapia_modificar").val(datos.PodriaAyudar);
    $("#espera_terapia_modificar").val(datos.ConseguirTerapia);
    $("#compromiso_terapia_modificar").val(datos.compromiso);
    $("#duracion_terapia_modificar").val(datos.TiempoDurara);
    $("#importante_reflejar_modificar").val(datos.considerar);

    var modal = new bootstrap.Modal(document.getElementById("modalModificar"));
    modal.show();
  } else {
    if (id) {
      var datos = new FormData();
      datos.append("accion", "consultar");
      datos.append("id", id);
      enviarAjax(datos);
    } else {
      console.error("Falta id");
    }
  }
}

function mostrarDetalles(id, datos = false){
  if (datos) {
    var modal = new bootstrap.Modal(
      document.getElementById("verDetallesModal")
    );
    $('#verDetallesModal').find("#nombre_detalle").val(datos.nombre);
    $('#verDetallesModal').find("#apellido_detalle").val(datos.apellido);
    $('#verDetallesModal').find("#cedula_detalle").val(datos.cedula);
    $('#verDetallesModal').find("#telefono_detalle").val(datos.telefono);
    $('#verDetallesModal').find("#email_detalle").val(datos.email);
    $('#verDetallesModal')
      .find("#fecha_nacimiento_detalle")
      .val(datos.fecha_nacimiento);

    var html = "";

      try {
        html += '<h5 class="mt-3">Datos del Historial</h5>';
        html += '<ul class="list-group">';
        if (datos.sintomas && datos.sintomas.length > 0) {
          html +=
            '<li class="list-group-item"><b>Síntomas:</b> ' +
            datos.sintomas.join(", ") +
            "</li>";
        }
        if (datos.acudir) {
          html +=
            '<li class="list-group-item"><b>Tratamiento anterior:</b> ' +
            datos.acudir +
            "</li>";
        }
        if (datos.tratamiento) {
          html +=
            '<li class="list-group-item"><b>Tratamiento recibido:</b> ' +
            datos.tratamiento +
            "</li>";
        }
        if (datos.considerar) {
          html +=
            '<li class="list-group-item"><b>Motivo de consulta:</b> ' +
            datos.considerar +
            "</li>";
        }
        if (datos.ConseguirTerapia) {
          html +=
            '<li class="list-group-item"><b>Expectativas:</b> ' +
            datos.ConseguirTerapia +
            "</li>";
        }
        html += "</ul>";
      } catch (e) {
        html =
          '<div class="alert alert-warning">No se pudieron cargar los datos del historial.</div>';
      }
    $("#verDetallesModal").find("#historial_detalles_extra").html(html);

    
    modal.show();
  } else {
    if (id) {
      var datos = new FormData();
      datos.append("accion", "consultarDetalles");
      datos.append("id", id);
      enviarAjax(datos);
    } else {
      console.error("Falta id");
    }
  }
}

// AJAX
function enviarAjax(datos) {
  // 🧠 Detectar si la acción es de eliminación
  let accion = "";
  if (datos instanceof FormData) {
    accion = datos.get("accion") || "";
  }

  // ✅ Si es eliminar, pedimos confirmación antes de enviar
  if (accion === "eliminar") {
    Swal.fire({
      title: "¿Estás seguro?",
      text: "Esta acción eliminará el historial de forma permanente.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#ff5c5c",
      cancelButtonColor: " #75A5B8",
      confirmButtonText: "Sí, eliminar",
      cancelButtonText: "No, cancelar"
    }).then((result) => {
      if (result.isConfirmed) {
        // Si confirma, ejecuta la eliminación
        ejecutarAjax(datos);
      } else {
        // Si cancela, muestra aviso y no hace nada
        Swal.fire({
          icon: "info",
          title: "Cancelado",
          text: "La eliminación fue cancelada.",
          timer: 1500,
          showConfirmButton: false
        });
      }
    });
  } else {
    // Para cualquier otra acción (registrar, modificar, consultar, etc.)
    ejecutarAjax(datos);
  }
}

// 💡 Función interna que ejecuta el AJAX real
function ejecutarAjax(datos) {
  $.ajax({
    async: true,
    url: "",
    type: "POST",
    contentType: false,
    data: datos,
    processData: false,
    cache: false,
    timeout: 10000,
    success: function (respuesta) {
      try {
        let respObj = JSON.parse(respuesta);

        if (respObj.resultado == "consultarPacientes") {
          $("#pacientesContainer").html(respObj.mensaje);

        } else if (respObj.resultado == "consultarHistorial") {
          actualizarCampos(null, respObj.mensaje);

        } else if (respObj.resultado == "consultarDetalles") {
          mostrarDetalles(null, respObj.mensaje);

        } else if (respObj.resultado == "registrar") {
          Swal.fire({
            icon: "success",
            title: "Éxito",
            text: respObj.mensaje,
            timer: 2000,
            showConfirmButton: false
          });

          if (respObj.mensaje == "Datos del historial registrado") {
            $("#modalRegistro").modal("hide");
            $("#registroHistorial").get(0).reset();
            consultar();

            if ("caches" in window) {
              caches.keys().then((names) => {
                for (let name of names) caches.delete(name);
              });
            }
          }

        } else if (respObj.resultado == "modificar") {
          Swal.fire({
            icon: "success",
            title: "Actualizado",
            text: respObj.mensaje,
            timer: 2000,
            showConfirmButton: false
          });

          if (respObj.mensaje == "Datos del historia modificados") {
            $("#modalModificar").modal("hide");
            consultar();
          }

        } else if (respObj.resultado == "eliminar") {
          Swal.fire({
            icon: "success",
            title: "Eliminado",
            text: respObj.mensaje,
            timer: 2000,
            showConfirmButton: false
          });
          consultar();
        }

      } catch (e) {
        console.error("Error al procesar la respuesta: ", e);
        console.error("Respuesta del servidor: ", respuesta);

        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Hubo un problema procesando la respuesta del servidor."
        });
      }
    }
  });
}

function mostrarMensaje(mensaje) {
  $("#modalContenido").html(mensaje);
  $("#modalMensajes").removeClass("hidden");
  $("#modalMensajes").addClass("flex");
}

// Limpiar validaciones al cerrar modales
$('#modalRegistro').on('hidden.bs.modal', function() {
    $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
    $('.invalid-feedback').remove();
    detenerTemporizadorFormulario();
  // temporizadores adicionales removidos en refactor
});

$('#modalModificar').on('hidden.bs.modal', function() {
    $('.is-valid, .is-invalid').removeClass('is-valid is-invalid');
    $('.invalid-feedback').remove();
});