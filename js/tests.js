$(document).ready(function () {
  // Variables globales
  let pacienteSeleccionado = null;
  let testSeleccionado = null;
  let pacientesCache = {};
  const preguntas_confianza = [
    "",
    "Su capacidad para ejecutar las destrezas de su deporte o ejercicio",
    "Su capacidad para tomar decisiones fundamentales durante la competición",
    "Su capacidad para concentrarse",
    "Su capacidad para actuar bajo presión",
    "Su capacidad para ejecutar una estrategia satisfactoriamente",
    "Su capacidad para emplear el esfuerzo necesario para lograr el éxito",
    "Su capacidad para controlar sus emociones durante la competición",
    "Su capacidad para relacionarse satisfactoriamente con sus entrenadores",
    "Su capacidad para reaccionar cuando anda retrasado o está teniendo una mala actuación",
    "Su entrenamiento o preparación física",
  ];

  const preguntas_importancia = [
    "",
    "Mantengo mi concentración en el juego o en el partido",
    "Me siento bien con mi peso",
    "Veo que mis compañeros me animan",
    "Creo en las capacidades de mi entrenador",
    "Veo jugar un buen partido",
    "Juego en un ambiente que me gusta",
    "Domino nuevas habilidades",
    "Demuestro que soy mejor que los otros",
    "Consigo mentalizarme",
    "Me siento bien con mi aspecto",
    "Sé que cuento con el apoyo de los demás",
    "Sé que mi entrenador va a tomar decisiones acertadas",
    "Veo a otro futbolista jugar",
    "Mejoro en alguna acción técnica",
    "Sé que estoy mentalmente preparado",
    "Me siento a gusto con mi cuerpo",
    "Sé que los demás creen en mí",
    "Sé que el entrenador es un buen líder",
    "Observo como un compañero juega bien",
    "Me siento cómodo en el campo.",
    "Mejoró mis habilidades técnicas.",
    "Sé que técnicamente soy mejor que mis rivales",
    "Me mantengo centrado en lo que tengo que hacer",
    "Creo en las decisiones de mi entrenador",
    "Veo a un amigo jugar bien",
    "Me gusta el ambiente en el que juego",
    "Aumento el número de acciones técnicas que puedo realizar",
    "Demuestro que soy mejor que el rival",
    "Me preparo física y mentalmente.",
    "Siento que mi entrenador actúa como un líder",
    "Desarrollo nuevas habilidades técnicas y mejoro",
    "Demuestro que soy uno de los mejores",
    "Creo que puedo esforzarme al máximo",
    "El publico me anima",
  ];

  const preguntas_poms = [
    "",
    "Amigable",
    "Tenso",
    "Enfadado",
    "Agotado",
    "Infeliz",
    "Lucido",
    "Vivaz",
    "Confuso",
    "Arrepentido",
    "Ambicioso",
    "Apatico",
    "Irritado",
    "Considerado",
    "Triste",
    "Activo",
    "Desbordado",
    "Malhumorado",
    "Caido",
    "Energizado",
    "Con pánico",
    "Desesperanzado",
    "Relajado",
    "Torpe",
    "Malicioso",
    "Sorpresivo",
    "Intranquilo",
    "Inquieto",
    "Sin concentración",
    "Delgado",
    "Colaborador",
    "Molesto",
    "Desanimado",
    "Resentido",
    "Nervioso",
    "Solo",
    "Indichado",
    "Aturdido",
    "Alegre",
    "Amargado",
    "Adormilado",
    "Ansioso",
    "Luchador",
    "De buen humor",
    "Deprimido",
    "Desesperado",
    "Desprolijo",
    "Culpable",
    "Desamparado",
    "Cansado",
    "Desorientado",
    "Lista",
    "Engañado",
    "Furioso",
    "Eficiente",
    "Contento",
    "Dinámico",
    "Enormizado",
    "Desvalorizado",
    "Olvidadizo",
    "Despreocupado",
    "Aterrorizado",
    "Estable",
    "Vigoroso",
    "Inseguro",
    "Abatido",
  ];

  // Cargar todos los tests al iniciar
  cargarTests("");

  // Cargar pacientes en caché para obtener nombres rápidamente
  function cargarPacientesCache() {
    $.ajax({
      url: "",
      type: "POST",
      data: { ajax_action: "obtenerPacientes" },
      success: function (response) {
        if (response.success) {
          response.data.forEach(function (p) {
            pacientesCache[p.id_paciente] = p.apellido + ", " + p.nombre;
          });
        }
      },
    });
  }
  cargarPacientesCache();

  // Cargar tests cuando se selecciona un paciente
  $("#selectPaciente").change(function () {
    pacienteSeleccionado = $(this).val();
    // Si el select está vacío, mostrar todos los tests
    if (!pacienteSeleccionado) {
      cargarTests("");
    } else {
      cargarTests(pacienteSeleccionado);
    }
  });

  // Filtrar tests por tipo
  $("#filtroTest").change(function () {
    cargarTests(pacienteSeleccionado);
  });

  // Cargar formulario cuando se selecciona tipo de test
  $("#nuevoTestTipo").change(function () {
    const tipo = $(this).val();
    if (tipo) {
      cargarFormularioTest(tipo, "nuevo");
    } else {
      $("#formularioTestContainer").html("");
    }

  });

  // Manejar envío de nuevo test
  $("#formNuevoTest").submit(function (e) {
    e.preventDefault();
    guardarTest("nuevo");
  });

  // Manejar envío de test editado
  $("#formEditarTest").submit(function (e) {
    e.preventDefault();
    guardarTest("editar");
  });

  // Función para cargar los tests de un paciente o todos
  function cargarTests(idPaciente) {
    // Destruir DataTable antes de recargar datos para evitar errores de reinicialización
    if ($.fn.DataTable.isDataTable(".table")) {
      $(".table").DataTable().destroy();
    }
    $.ajax({
      url: "",
      type: "POST",
      data: {
        ajax_action: "obtenerTests",
        id_paciente: idPaciente,
      },
      success: function (response) {
        if (response.success) {
          mostrarTests(response.data);
        } else {
          mostrarError("Error al cargar tests");
        }
      },
      error: function () {
        mostrarError("Error de conexión");
      },
    });
  }

  // Función para mostrar los tests en la tabla con DataTable
  function mostrarTests(tests) {
    // Elimina el filtrado por tipo y paciente
    let html = "";
    let contador = 1;

    function obtenerNombrePaciente(idPaciente) {
      if (pacientesCache[idPaciente]) return pacientesCache[idPaciente];
      // Buscar en todos los tests por idPaciente y usar nombre_paciente si está disponible
      for (const tipo of ["poms", "confianza", "importancia"]) {
        if (tests[tipo] && Array.isArray(tests[tipo])) {
          const encontrado = tests[tipo].find(
            (t) => t.id_paciente == idPaciente && t.nombre_paciente
          );
          if (encontrado) {
            return encontrado.nombre_paciente;
          }
        }
      }
      // Si no se encuentra, retorna vacío
      return "";
    }

    // Mostrar todos los tests de todos los tipos
    if (tests.poms && tests.poms.length > 0) {
      tests.poms.forEach((test) => {
        html += crearFilaTest(
          contador++,
          test.id_paciente,
          "POMS",
          test.fecha,
          test.id,
          "poms",
          obtenerNombrePaciente(test.id_paciente)
        );
      });
    }
    if (tests.confianza && tests.confianza.length > 0) {
      tests.confianza.forEach((test) => {
        html += crearFilaTest(
          contador++,
          test.id_paciente,
          "Confianza",
          test.fecha,
          test.id,
          "confianza",
          obtenerNombrePaciente(test.id_paciente)
        );
      });
    }
    if (tests.importancia && tests.importancia.length > 0) {
      tests.importancia.forEach((test) => {
        html += crearFilaTest(
          contador++,
          test.id_paciente,
          "Importancia",
          test.fecha,
          test.id,
          "importancia",
          obtenerNombrePaciente(test.id_paciente)
        );
      });
    }

    if (html === "") {
      html = '<tr><td colspan="5">No se encontraron tests</td></tr>';
    }

    $("#tablaTests").html(html);

    // Inicializar o reinicializar DataTable
    if ($.fn.DataTable.isDataTable(".table")) {
      $(".table").DataTable().destroy();
    }
    $(".table").DataTable({
      language: {
        url: "js/es-ES.json",
      },
      order: [[0, "asc"]],
      dom: '<"d-flex flex-wrap justify-content-between align-items-center mb-2"lp>rt',
      // Quita el scroll interno y muestra más filas por página
      pageLength: 10, // Puedes aumentar este valor si tienes muchos registros
      lengthMenu: [
        [10, 25, 50, 100, -1],
        [10, 25, 50, 100, "Todos"],
      ],
      scrollY: false,
      scrollCollapse: false,
      initComplete: function () {
        // Estilos para los selects de DataTable
        $(".dataTables_length select").addClass("form-select").css({
          "border-radius": "20px",
          padding: "0.375rem 1.75rem 0.375rem 0.75rem",
          "font-size": "1rem",
          "box-shadow": "0 2px 8px rgba(0,0,0,0.07)",
        });
        // Oculta el buscador interno de DataTable
        $(".dataTables_filter").hide();
        // Opcional: estilos para la paginación
        $(".dataTables_paginate").addClass("mt-0 mb-2");
      },
    });

    // Si el buscador externo no tiene evento, lo agregamos (solo una vez)
    if (!$("#buscarTest").data("dt-attached")) {
      $("#buscarTest").on("input", function () {
        $(".table").DataTable().search(this.value).draw();
      });
      $("#buscarTest").data("dt-attached", true);
    }
  }

  // Modifica para aceptar nombrePaciente como parámetro
  function crearFilaTest(
    contador,
    idPaciente,
    tipo,
    fecha,
    idTest,
    tipoTest,
    nombrePaciente
  ) {
    return `
            <tr>
                <td>${contador}</td>
                <td>${nombrePaciente}</td>
                <td>${tipo}</td>
                <td>${fecha}</td>
                <td>
                    <button class="btn btn-sm btn-accion btn-detalles" data-tipo="${tipoTest}" data-id="${idTest}" title="Ver detalles">
                        <i class="bi bi-eye-fill"></i> Detalles
                    </button>
                    <button class="btn btn-sm btn-accion btn-editar" data-tipo="${tipoTest}" data-id="${idTest}" title="Editar">
                        <i class="bi bi-pencil-fill"></i> Modificar
                    </button>
                    <button class="btn btn-sm btn-accion btn-eliminar" data-tipo="${tipoTest}" data-id="${idTest}" title="Eliminar">
                        <i class="bi bi-trash-fill"></i> Eliminar
                    </button>
                </td>
            </tr>
        `;
  }

  // Delegación de eventos para botones dinámicos
  $(document).on("click", ".btn-detalles", function () {
    const tipo = $(this).data("tipo");
    const id = $(this).data("id");
    mostrarDetallesTest(tipo, id);
  });

  $(document).on("click", ".btn-editar", function () {
    const tipo = $(this).data("tipo");
    const id = $(this).data("id");
    editarTest(tipo, id);
  });

  $(document).on("click", ".btn-eliminar", function () {
    const tipo = $(this).data("tipo");
    const id = $(this).data("id");
    eliminarTest(tipo, id);
  });

  // Función para mostrar detalles del test
  function mostrarDetallesTest(tipo, id) {
    $.ajax({
      url: "",
      type: "POST",
      data: {
        ajax_action: "obtenerDetallesTest",
        tipo: tipo,
        id: id,
      },
      success: function (response) {
        // console.log(response)
        if (response.success) {
          mostrarModalDetalles(response.test, response.paciente, tipo);
        } else {
          mostrarError("Error al cargar detalles del test");
        }
      },
      error: function () {
        mostrarError("Error de conexión");
      },
    });
  }

  // Función para mostrar modal con detalles
  function mostrarModalDetalles(test, paciente, tipoTest) {
    // Crear contenido del modal según el tipo de test
    let contenidoTest = "";
    let respuestas = test.respuestas || {};

    switch (tipoTest) {
      case "poms":
        contenidoTest = crearContenidoPOMS(respuestas);
        break;
      case "confianza":
        contenidoTest = crearContenidoConfianza(respuestas);
        break;
      case "importancia":
        contenidoTest = crearContenidoImportancia(test);
        break;
      default:
        contenidoTest = "<p>No se pudo cargar la información del test</p>";
    }

    // Crear modal
    const modalHTML = `
            <div class="modal fade" id="detallesTestModal" tabindex="-1" aria-labelledby="detallesTestModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header" >
                            <h5 class="modal-header" id="detallesTestModalLabel">Detalles del Test</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>Información del Paciente</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Nombre:</strong> ${
                                          paciente.nombre
                                        } ${paciente.apellido}</li>
                                        <li class="list-group-item"><strong>Cédula:</strong> ${
                                          paciente.cedula
                                        }</li>
                                        <li class="list-group-item"><strong>Teléfono:</strong> ${
                                          paciente.telefono
                                        }</li>
                                        <li class="list-group-item"><strong>Fecha Nacimiento:</strong> ${
                                          paciente.fecha_nacimiento
                                        }</li>
                                        <li class="list-group-item"><strong>Género:</strong> ${
                                          paciente.genero
                                        }</li>
                                        <li class="list-group-item"><strong>Edad:</strong> ${calcularEdad(
                                          paciente.fecha_nacimiento
                                        )}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <h5>Información del Test</h5>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item"><strong>Tipo:</strong> ${tipoTest.toUpperCase()}</li>
                                        <li class="list-group-item"><strong>Fecha:</strong> ${
                                          test.fecha
                                        }</li>
                                        ${
                                          tipoTest === "poms"
                                            ? `<li class="list-group-item"><strong>Deporte:</strong> ${
                                                test.deporte ||
                                                "No especificado"
                                              }</li>`
                                            : ""
                                        }
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="test-details-container">
                                <h5>Resultados del Test</h5>
                                ${contenidoTest}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

    // Agregar modal al DOM y mostrarlo
    $("body").append(modalHTML);
    const modal = new bootstrap.Modal(
      document.getElementById("detallesTestModal")
    );
    modal.show();

    // Eliminar el modal cuando se cierre
    $("#detallesTestModal").on("hidden.bs.modal", function () {
      $(this).remove();
    });
  }

// Función para crear contenido POMS con carrusel (10 preguntas por slide)
function crearContenidoPOMS(respuestas) {
    let html = `
        <h5 class="test-header">Perfil de Estados de Ánimo (POMS)</h5>
        <p>Respuestas proporcionadas por el paciente:</p>
        <div class="test-questions-container">
            <div id="detallesPOMSCarousel" class="carousel slide" data-bs-ride="false">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#detallesPOMSCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    `;

    // Calcular número de slides (10 preguntas por slide)
    const totalSlides = Math.ceil(65 / 10);
    
    // Agregar indicadores
    for (let i = 1; i < totalSlides; i++) {
        html += `<button type="button" data-bs-target="#detallesPOMSCarousel" data-bs-slide-to="${i}" aria-label="Slide ${i+1}"></button>`;
    }

    html += `</div><div class="carousel-inner">`;

    // Agregar preguntas en slides
    for (let slide = 0; slide < totalSlides; slide++) {
        html += `<div class="carousel-item ${slide === 0 ? 'active' : ''}"><div class="table-responsive"><table class="table table-bordered"><tbody>`;
        
        for (let i = 1; i <= 10; i++) {
            const preguntaIndex = slide * 10 + i;
            if (preguntaIndex > 65) break;
            
            const valor = respuestas[preguntaIndex] !== undefined ? respuestas[preguntaIndex] : null;
            let textoRespuesta = "No respondida";

            if (valor !== null) {
                switch (valor) {
                    case 0: textoRespuesta = "Nada"; break;
                    case 1: textoRespuesta = "Un poco"; break;
                    case 2: textoRespuesta = "Moderadamente"; break;
                    case 3: textoRespuesta = "Bastante"; break;
                    case 4: textoRespuesta = "Muchísimo"; break;
                    default: textoRespuesta = `Valor no reconocido (${valor})`;
                }
            }

            html += `
                <tr>
                    <td class="fw-bold" style="width: 60%">${preguntaIndex}) ${preguntas_poms[preguntaIndex]}</td>
                    <td style="width: 40%">${textoRespuesta}</td>
                </tr>
            `;
        }
        
        html += `</tbody></table></div></div>`;
    }

    html += `
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#detallesPOMSCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#detallesPOMSCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    `;

    return html;
}

// Función para crear contenido Confianza con carrusel (10 preguntas por slide)
function crearContenidoConfianza(respuestas) {
    let html = `
        <h5 class="test-header">Test de Confianza</h5>
        <p>Respuestas proporcionadas por el paciente:</p>
        <div class="test-questions-container">
            <div id="detallesConfianzaCarousel" class="carousel slide" data-bs-ride="false">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#detallesConfianzaCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    `;

    // Calcular número de slides (10 preguntas por slide)
    const totalSlides = Math.ceil((preguntas_confianza.length - 1) / 10);
    
    // Agregar indicadores
    for (let i = 1; i < totalSlides; i++) {
        html += `<button type="button" data-bs-target="#detallesConfianzaCarousel" data-bs-slide-to="${i}" aria-label="Slide ${i+1}"></button>`;
    }

    html += `</div><div class="carousel-inner">`;

    // Agregar preguntas en slides
    for (let slide = 0; slide < totalSlides; slide++) {
        html += `<div class="carousel-item ${slide === 0 ? 'active' : ''}"><div class="table-responsive"><table class="table table-bordered"><tbody>`;
        
        for (let i = 1; i <= 10; i++) {
            const preguntaIndex = slide * 10 + i;
            if (preguntaIndex >= preguntas_confianza.length) break;
            
            const valor = respuestas[preguntaIndex] !== undefined ? respuestas[preguntaIndex] : null;
            let textoRespuesta = "No respondida";

            if (valor !== null) {
                switch (valor) {
                    case 1: textoRespuesta = "Poca confianza"; break;
                    case 2: textoRespuesta = "Regular"; break;
                    case 3: textoRespuesta = "Exceso de confianza"; break;
                    default: textoRespuesta = `Valor no reconocido (${valor})`;
                }
            }

            html += `
                <tr>
                    <td class="fw-bold" style="width: 60%">${preguntaIndex}) ${preguntas_confianza[preguntaIndex]}</td>
                    <td style="width: 40%">${textoRespuesta}</td>
                </tr>
            `;
        }
        
        html += `</tbody></table></div></div>`;
    }

    html += `
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#detallesConfianzaCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#detallesConfianzaCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    `;

    return html;
}

// Función para crear contenido Importancia con carrusel (10 preguntas por slide)
function crearContenidoImportancia(test) {
    const parte1 = test.parte1 || {};
    const parte2 = test.parte2 || {};

    let html = `
        <h5 class="test-header">Test de Importancia</h5>
        <p>Respuestas proporcionadas por el paciente:</p>
        <div class="test-questions-container">
            <div id="detallesImportanciaCarousel" class="carousel slide" data-bs-ride="false">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#detallesImportanciaCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    `;

    // Calcular número de slides (10 preguntas por slide para cada parte)
    const totalSlidesParte1 = Math.ceil(17 / 10);
    const totalSlidesParte2 = Math.ceil((preguntas_importancia.length - 18) / 10);
    const totalSlides = totalSlidesParte1 + totalSlidesParte2;
    
    // Agregar indicadores
    for (let i = 1; i < totalSlides; i++) {
        html += `<button type="button" data-bs-target="#detallesImportanciaCarousel" data-bs-slide-to="${i}" aria-label="Slide ${i+1}"></button>`;
    }

    html += `</div><div class="carousel-inner">`;

    // Parte 1 (preguntas 1-17)
    for (let slide = 0; slide < totalSlidesParte1; slide++) {
        html += `
            <div class="carousel-item ${slide === 0 ? 'active' : ''}">
                <h6 class="text-center mb-3">Parte 1</h6>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Pregunta</th>
                                <th>Respuesta</th>
                            </tr>
                        </thead>
                        <tbody>
        `;
        
        for (let i = 1; i <= 10; i++) {
            const preguntaIndex = slide * 10 + i;
            if (preguntaIndex > 17) break;
            
            const valor = parte1[preguntaIndex] !== undefined ? parte1[preguntaIndex] : null;
            
            html += `
                <tr>
                    <td class="text-start fw-bold">${preguntaIndex}) ${preguntas_importancia[preguntaIndex]}</td>
                    <td class="fs-5">${valor !== null ? valor : "No respondida"}</td>
                </tr>
            `;
        }
        
        html += `</tbody></table></div></div>`;
    }

    // Parte 2 (preguntas 18-34)
    for (let slide = 0; slide < totalSlidesParte2; slide++) {
        html += `
            <div class="carousel-item">
                <h6 class="text-center mb-3">Parte 2</h6>
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>Pregunta</th>
                                <th>Respuesta</th>
                            </tr>
                        </thead>
                        <tbody>
        `;
        
        for (let i = 1; i <= 10; i++) {
            const preguntaIndex = 18 + slide * 10 + i - 1;
            if (preguntaIndex >= preguntas_importancia.length) break;
            
            const valor = parte2[preguntaIndex] !== undefined ? parte2[preguntaIndex] : null;
            
            html += `
                <tr>
                    <td class="text-start fw-bold">${preguntaIndex}) ${preguntas_importancia[preguntaIndex]}</td>
                    <td class="fs-5">${valor !== null ? valor : "No respondida"}</td>
                </tr>
            `;
        }
        
        html += `</tbody></table></div></div>`;
    }

    html += `
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#detallesImportanciaCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#detallesImportanciaCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Siguiente</span>
            </button>
        </div>
    `;

    return html;
}

  // Función para editar un test
  function editarTest(tipo, id) {
    $.ajax({
      url: "",
      type: "POST",
      data: {
        ajax_action: "obtenerTest",
        tipo: tipo,
        id: id,
      },
      success: function (response) {
        if (response.success) {
          testSeleccionado = response.data;
          $("#editarTestId").val(id);
          $("#editarTestTipo").val(tipo);

          // Cargar formulario de edición con los datos actuales
          cargarFormularioTest(tipo, "editar", response.data);

          // Mostrar modal de edición
          const modal = new bootstrap.Modal(
            document.getElementById("editarTestModal")
          );
          modal.show();
        } else {
          mostrarError("Error al cargar test para edición");
        }
      },
      error: function () {
        mostrarError("Error de conexión");
      },
    });
  }

  // Función para cargar formulario de test
  function cargarFormularioTest(tipo, accion, datos = null) {
    let html = "";
    $(`#formularioTestContainer`).html("");
    $(`#formularioEditarTestContainer`).html("");
    const containerId =
      accion === "nuevo"
        ? "formularioTestContainer"
        : "formularioEditarTestContainer";

    switch (tipo) {
      case "poms":
        html = crearFormularioPOMS(datos);
        break;
      case "confianza":
        html = crearFormularioConfianza(datos);
        break;
      case "importancia":
        html = crearFormularioImportancia(datos);
        break;
      default:
        html = "<p>Seleccione un tipo de test válido</p>";
    }

    $(`#${containerId}`).html(html);
  }

  // Funciones para crear formularios específicos de cada test
  function crearFormularioPOMS(datos) {
    let deporte = datos ? datos.deporte : "";
    let respuestas = datos && datos.respuestas ? datos.respuestas : {};

    let html = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="deporte" class="form-label">Deporte</label>
                    <input type="text" class="form-control" id="deporte" name="deporte" value="${deporte}" required>
                </div>
            </div>
            <div class="test-container">
                <h5 class="test-header">Perfil de Estados de Ánimo (POMS)</h5>
                <p>Por favor, indique cómo se ha sentido durante la última semana incluyendo hoy, marcando la opción que mejor describa su estado.</p>
                <div class="test-questions-container">
                <div id="formularioTest" class="carousel slide">
                    <div class="carousel-indicators">
                        <button
                        type="button"
                        data-bs-target="#formularioTest"
                        data-bs-slide-to="0"
                        class="active"
                        aria-current="true"
                        aria-label="Slide 1"
                        ></button>

        `;

    let div = Math.ceil((preguntas_poms.length - 1) / 5);

    for (let i = 1; i < div; i++) {
      html += `
              <button
              type="button"
              data-bs-target="#formularioTest"
              data-bs-slide-to="${i}"
              aria-label="Slide ${i + 1}"
              ></button>
          `;
    }
    html += `</div>
              <div class="carousel-inner">
      `;

    let pregunta = 1;
    for (let i = 1; i <= div; i++) {
      html += `  
          <div class="carousel-item ${i == 1 ? "active" : ""}">
          `;
      // Preguntas POMS (1-65)
      for (let j = 1; j <= 5 && pregunta < preguntas_poms.length; j++) {
        const valorActual =
          respuestas[pregunta] !== undefined ? respuestas[pregunta] : 0;

        html += `
                <div class="test-question">
                    <p class="fw-bold">${pregunta}) ${
          preguntas_poms[pregunta]
        }:</p>
                    <div class="test-options-group">
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="pregunta_${pregunta}" id="p${pregunta}_0" value="0" ${
          valorActual === 0 ? "checked" : ""
        }>
                            <label class="form-check-label" for="p${pregunta}_0">Nada</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="pregunta_${pregunta}" id="p${pregunta}_1" value="1" ${
          valorActual === 1 ? "checked" : ""
        }>
                            <label class="form-check-label" for="p${pregunta}_1">Un poco</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="pregunta_${pregunta}" id="p${pregunta}_2" value="2" ${
          valorActual === 2 ? "checked" : ""
        }>
                            <label class="form-check-label" for="p${pregunta}_2">Moderadamente</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="pregunta_${pregunta}" id="p${pregunta}_3" value="3" ${
          valorActual === 3 ? "checked" : ""
        }>
                            <label class="form-check-label" for="p${pregunta}_3">Bastante</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="pregunta_${pregunta}" id="p${pregunta}_4" value="4" ${
          valorActual === 4 ? "checked" : ""
        }>
                            <label class="form-check-label" for="p${pregunta}_4">Muchisimo</label>
                        </div>
                    </div>
                </div>
            `;
        pregunta++;
      }

      html += `</div>`;
    }

    html += `</div>
      <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#formularioTest"
        data-bs-slide="prev"
      >
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#formularioTest"
        data-bs-slide="next"
      >
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
      </button>
      </div>
    `;

    html += `</div></div>`;
    return html;
  }

  function crearFormularioConfianza(datos) {
    let respuestas = datos && datos.respuestas ? datos.respuestas : {};
    let html = `
            <div class="test-container">
                <h5 class="test-header">Test de Confianza</h5>
                <p>Por favor, indique su grado de acuerdo con cada una de las siguientes afirmaciones.</p>
                <p>¿Qué grado de confianza tiene respecto a...?</p>
                <div class="test-questions-container">
                <div id="formularioTest" class="carousel slide">
                    <div class="carousel-indicators">
                        <button
                        type="button"
                        data-bs-target="#formularioTest"
                        data-bs-slide-to="0"
                        class="active"
                        aria-current="true"
                        aria-label="Slide 1"
                        ></button>
        `;

    let div = Math.ceil((preguntas_confianza.length - 1) / 5);

    for (let i = 1; i < div; i++) {
      html += `
              <button
              type="button"
              data-bs-target="#formularioTest"
              data-bs-slide-to="${i}"
              aria-label="Slide ${i + 1}"
              ></button>
          `;
    }
    html += `</div>
              <div class="carousel-inner">
      `;

    let pregunta = 1;
    for (let i = 1; i <= div; i++) {
      html += `  
          <div class="carousel-item ${i == 1 ? "active" : ""}">
          `;

      for (let j = 1; j <= 5 && pregunta < preguntas_confianza.length; j++) {
        const valorActual =
          respuestas[pregunta] !== undefined ? respuestas[pregunta] : 1;

        html += `
                <div class="test-question">
                    <p class="fw-bold">${pregunta}) ${
          preguntas_confianza[pregunta]
        }:</p>
                    <div class="test-options-group">
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="pregunta_${pregunta}" id="c${pregunta}_1" value="1" ${
          valorActual === 1 ? "checked" : ""
        }>
                            <label class="form-check-label" for="c${pregunta}_1">Poca confianza</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="pregunta_${pregunta}" id="c${pregunta}_2" value="2" ${
          valorActual === 2 ? "checked" : ""
        }>
                            <label class="form-check-label" for="c${pregunta}_2">Regular</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="pregunta_${pregunta}" id="c${pregunta}_3" value="3" ${
          valorActual === 3 ? "checked" : ""
        }>
                            <label class="form-check-label" for="c${pregunta}_3">Exceso de confianza</label>
                        </div>
                    </div>
                </div>
            `;
        pregunta++;
      }

      html += `</div>`;
    }

    html += `</div>
      <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#formularioTest"
        data-bs-slide="prev"
      >
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#formularioTest"
        data-bs-slide="next"
      >
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
      </button>
      </div>
    `;

    html += `</div></div>`;
    return html;
  }

  function crearFormularioImportancia(datos) {
    let parte1 = datos && datos.parte1 ? datos.parte1 : {};
    let parte2 = datos && datos.parte2 ? datos.parte2 : {};

    let html = `
            <div class="test-container">
                <h5 class="test-header">Test de Importancia</h5>
                <p>Por favor, califique cada uno de los siguientes aspectos según su importancia.</p>
                <div class="test-questions-container">
                <div id="formularioTest" class="carousel slide">
                    <div class="carousel-indicators">
                        <button
                        type="button"
                        data-bs-target="#formularioTest"
                        data-bs-slide-to="0"
                        class="active"
                        aria-current="true"
                        aria-label="Slide 1"
                        ></button>
        `;

    let div1 = Math.ceil(17 / 5);
    let div2 = Math.ceil((preguntas_importancia.length - 17 - 1) / 5);

    for (let i = 1; i < div1 + div2; i++) {
      html += `
              <button
              type="button"
              data-bs-target="#formularioTest"
              data-bs-slide-to="${i}"
              aria-label="Slide ${i + 1}"
              ></button>
          `;
    }
    html += `</div>
              <div class="carousel-inner">
      `;

    let pregunta = 1;
    for (let i = 1; i <= div1; i++) {
      html += `  
          <div class="carousel-item ${i == 1 ? "active" : ""}">
                    <h3 class="text-center">Parte 1</h3>
          `;
      // Preguntas Importancia Parte 1 (1-17)
      for (let j = 1; j <= 5 && pregunta < 18; j++) {
        const valorActual =
          parte1[pregunta] !== undefined ? parte1[pregunta] : 1;
        html += `
                <div class="test-question">
                    <p class="fw-bold">${pregunta}) ${
          preguntas_importancia[pregunta]
        }</p>
                    <div class="test-options-group">
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="parte1_pregunta_${pregunta}" id="parte1_${pregunta}_1" value="1" ${
          valorActual == 1 ? "checked" : ""
        }>
                            <label class="form-check-label" for="parte1_${pregunta}_1">1 - Muy poco importante</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="parte1_pregunta_${pregunta}" id="parte1_${pregunta}_2" value="2" ${
          valorActual == 2 ? "checked" : ""
        }>
                            <label class="form-check-label" for="parte1_${pregunta}_2">2</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="parte1_pregunta_${pregunta}" id="parte1_${pregunta}_3" value="3" ${
          valorActual == 3 ? "checked" : ""
        }>
                            <label class="form-check-label" for="parte1_${pregunta}_3">3</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="parte1_pregunta_${pregunta}" id="parte1_${pregunta}_4" value="4" ${
          valorActual == 4 ? "checked" : ""
        }>
                            <label class="form-check-label" for="parte1_${pregunta}_4">4</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="parte1_pregunta_${pregunta}" id="parte1_${pregunta}_5" value="5" ${
          valorActual == 5 ? "checked" : ""
        }>
                            <label class="form-check-label" for="parte1_${pregunta}_5">5</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="parte1_pregunta_${pregunta}" id="parte1_${pregunta}_6" value="6" ${
          valorActual == 6 ? "checked" : ""
        }>
                            <label class="form-check-label" for="parte1_${pregunta}_6">6 - Extremadamente importante</label>
                        </div>
                    </div>
                </div>
            `;
        pregunta++;
      }

      html += `</div>`;
    }
    for (let i = 1; i <= div2; i++) {
      html += `  
          <div class="carousel-item">
                    <h3 class="text-center">Parte 2</h3>
          `;
      // Preguntas Importancia Parte 2 (18-34)
      for (let j = 1; j <= 5 && pregunta < preguntas_importancia.length; j++) {
        const valorActual =
          parte1[pregunta] !== undefined ? parte1[pregunta] : 1;
        html += `
                <div class="test-question">
                    <p class="fw-bold">${pregunta}) ${
          preguntas_importancia[pregunta]
        }</p>
                    <div class="test-options-group">
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="parte1_pregunta_${pregunta}" id="parte1_${pregunta}_1" value="1" ${
          valorActual == 1 ? "checked" : ""
        }>
                            <label class="form-check-label" for="parte1_${pregunta}_1">1 - Muy poco importante</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="parte1_pregunta_${pregunta}" id="parte1_${pregunta}_2" value="2" ${
          valorActual == 2 ? "checked" : ""
        }>
                            <label class="form-check-label" for="parte1_${pregunta}_2">2</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="parte1_pregunta_${pregunta}" id="parte1_${pregunta}_3" value="3" ${
          valorActual == 3 ? "checked" : ""
        }>
                            <label class="form-check-label" for="parte1_${pregunta}_3">3</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="parte1_pregunta_${pregunta}" id="parte1_${pregunta}_4" value="4" ${
          valorActual == 4 ? "checked" : ""
        }>
                            <label class="form-check-label" for="parte1_${pregunta}_4">4</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="parte1_pregunta_${pregunta}" id="parte1_${pregunta}_5" value="5" ${
          valorActual == 5 ? "checked" : ""
        }>
                            <label class="form-check-label" for="parte1_${pregunta}_5">5</label>
                        </div>
                        <div class="form-check test-option">
                            <input class="form-check-input" type="radio" name="parte1_pregunta_${pregunta}" id="parte1_${pregunta}_6" value="6" ${
          valorActual == 6 ? "checked" : ""
        }>
                            <label class="form-check-label" for="parte1_${pregunta}_6">6 - Extremadamente importante</label>
                        </div>
                    </div>
                </div>
            `;
        pregunta++;
      }

      html += `</div>`;
    }

    html += `</div>
      <button
        class="carousel-control-prev"
        type="button"
        data-bs-target="#formularioTest"
        data-bs-slide="prev"
      >
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button
        class="carousel-control-next"
        type="button"
        data-bs-target="#formularioTest"
        data-bs-slide="next"
      >
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
      </button>
      </div>
    `;

    html += `</div></div>`;

    return html;
  }

  // Función para guardar test (nuevo o edición)
  function guardarTest(accion) {
    const formId = accion === "nuevo" ? "formNuevoTest" : "formEditarTest";
    const formData = $(`#${formId}`).serializeArray();
    const tipoTest = $(`#${formId} [name="tipo_test"]`).val();

    $.ajax({
      url: "",
      type: "POST",
      data: {
        ajax_action: accion === "nuevo" ? "guardar_test" : "actualizar_test",
        ...Object.fromEntries(formData.map((item) => [item.name, item.value])),
      },
      success: function (response) {
        // console.log(response)

        if (response.success) {
          mostrarExito(response.message);

          // Cerrar modal
          if (accion === "nuevo") {
            $("#nuevoTestModal").modal("hide");
          } else {
            $("#editarTestModal").modal("hide");
          }

          // Recargar todos los tests y actualizar DataTable
          cargarTests(pacienteSeleccionado || "");
        } else {
          mostrarError(response.message);
        }
      },
      error: function (err) {
        mostrarError("Error de conexión");
      },
    });
  }

  // Función para eliminar un test
  function eliminarTest(tipo, id) {
    if (
      confirm(
        "¿Está seguro que desea eliminar este test? Esta acción no se puede deshacer."
      )
    ) {
      $.ajax({
        url: "",
        type: "POST",
        data: {
          ajax_action: "eliminarTest",
          tipo: tipo,
          id: id,
        },
        success: function (response) {
          if (response.success) {
            mostrarExito(response.message);
            // Recargar todos los tests y actualizar DataTable
            cargarTests(pacienteSeleccionado || "");
          } else {
            mostrarError(response.message);
          }
        },
        error: function () {
          mostrarError("Error de conexión");
        },
      });
    }
  }

  // Funciones auxiliares para mostrar mensajes
  function mostrarExito(mensaje) {
    // Elimina alertas previas
    $(".alert").remove();
    const alertHTML = `
            <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-4" role="alert" style="z-index: 2000; min-width:300px; max-width:90vw;">
                <strong>Éxito:</strong> ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        `;
    $("body").append(alertHTML);
    setTimeout(() => $(".alert").alert("close"), 3000);
  }

  function mostrarError(mensaje) {
    // Elimina alertas previas
    $(".alert").remove();
    const alertHTML = `
            <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-4" role="alert" style="z-index: 2000; min-width:300px; max-width:90vw;">
                <strong>Error:</strong> ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        `;
    $("body").append(alertHTML);
    setTimeout(() => $(".alert").alert("close"), 3000);
  }

  // Limpiar el formulario de registro cada vez que se abre el modal de "Nuevo Test"
  $("#nuevoTestModal").on("show.bs.modal", function () {
    $("#formNuevoTest")[0].reset();
    $("#formularioTestContainer").html("");
  });

  // Funcion para calcular la edad con la fecha
  function calcularEdad(fechaNacimiento) {
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();
    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
      edad--;
    }
    return edad;
  }

});
