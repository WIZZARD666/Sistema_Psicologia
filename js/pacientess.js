
document.addEventListener("DOMContentLoaded", function () {
  // Inicializar control de tiempo para formularios
 

  // --- VARIABLES Y ELEMENTOS PRINCIPALES ---
  const buscarpacienteInput = document.getElementById("buscarpaciente");
  const tablapacientesBody = document.getElementById("tablapacientes");
  const paginacionpacientesTop = document.getElementById("paginacionpacientesTop");
  const paginacionpacientesBottom = document.getElementById("paginacionpacientesBottom");
  let pacientesData = [];
  const pacientesPorPagina = 15;
  let paginaActual = 1;

  // --- Expresiones regulares para validaciones ---
  const regex = {
    nombre: /^[A-Z][a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s]*$/,
    cedula: /^\d{6,10}$/,
    telefono: /^\d{7,12}$/,
    email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  };

  // --- Funciones de validación ---
  function validarNombre(input) {
    const valor = input.value;
    const esValido = regex.nombre.test(valor);
    
    if (esValido) {
      mostrarValido(input);
      return true;
    } else {
      mostrarError(input, "Solo letras, primera letra mayúscula.");
      return false;
    }
  }

  function validarCedula(input) {
    const valor = input.value.replace(/\D/g, "").slice(0, 10);
    input.value = valor;
    const esValido = regex.cedula.test(valor);
    
    if (esValido) {
      mostrarValido(input);
      return true;
    } else {
      mostrarError(input, "Solo números (6-10 dígitos).");
      return false;
    }
  }

  function validarTelefono(input) {
    const valor = input.value.replace(/\D/g, "").slice(0, 12);
    input.value = valor;
    const esValido = regex.telefono.test(valor);
    
    if (esValido) {
      mostrarValido(input);
      return true;
    } else {
      mostrarError(input, "Solo números (7-12 dígitos).");
      return false;
    }
  }

  function validarFechaNacimiento(input) {
    input.setAttribute("max", "2011-12-31");
    const valor = input.value;
    
    if (!valor) {
      mostrarError(input, "Seleccione una fecha.");
      return false;
    } else if (valor > "2011-12-31") {
      mostrarError(input, "Debe ser 2011 o antes.");
      return false;
    } else {
      mostrarValido(input);
      return true;
    }
  }

  function validarEmail(input) {
    const valor = input.value;
    const esValido = regex.email.test(valor);
    
    if (esValido) {
      mostrarValido(input);
      return true;
    } else {
      mostrarError(input, "Ingrese un correo válido.");
      return false;
    }
  }

  // --- Helper para obtener pacientes desde el servidor ---
  function fetchPacientesFromServer(filtro = "") {
    return fetch("index.php?pagina=pacientes", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "ajax=1&filtro=" + encodeURIComponent(filtro),
    })
      .then((response) => response.json())
      .then((data) => data.pacientes || []);
  }

  // Exponer una función global para cargar pacientes desde otras vistas (p.ej. reportes)
  window.loadPacientesForReports = function (containerSelector, options = {}) {
    const container = document.querySelector(containerSelector);
    if (!container) return Promise.reject(new Error("Container not found: " + containerSelector));
    return fetchPacientesFromServer(options.filtro || "").then((pacientes) => {
      container.innerHTML = "";
      if (!pacientes || pacientes.length === 0) {
        container.innerHTML = "<div>No hay pacientes registrados.</div>";
        return pacientes;
      }
      // Render simple list by default; caller can customize via options.template
      if (typeof options.template === "function") {
        container.innerHTML = pacientes.map(options.template).join("");
      } else {
        const rows = pacientes.map((p) => `<div class=\"rp-item\">${p.id_paciente} - ${p.nombre} ${p.apellido} - ${p.cedula || '-'} </div>`);
        container.innerHTML = rows.join("");
      }
      return pacientes;
    });
  };

  // Si los elementos principales de la UI de pacientes no existen, no inicializamos la lógica completa
  if (!buscarpacienteInput || !tablapacientesBody || !paginacionpacientesTop || !paginacionpacientesBottom) {
    // Si existe un contenedor con id 'reportesPacientes', cargar automáticamente para facilitar la migración
    const reportContainer = document.getElementById('reportesPacientes');
    if (reportContainer) {
      window.loadPacientesForReports('#reportesPacientes').catch((err) => console.error('Error cargando pacientes para reportes:', err));
    }

    // Si sólo existe la tabla `tablapacientes` (por ejemplo en la vista de reportes), cargar y renderizar todas las filas sin paginación ni filtros
    if (tablapacientesBody) {
      fetchPacientesFromServer()
        .then((pacientes) => {
          if (!pacientes || pacientes.length === 0) {
            tablapacientesBody.innerHTML = '<tr><td colspan="6">No hay pacientes registrados.</td></tr>';
            return;
          }
          tablapacientesBody.innerHTML = "";
          pacientes.forEach((paciente) => {
            const tr = document.createElement('tr');
            const idTd = document.createElement('td'); idTd.textContent = paciente.id_paciente;
            const nombreTd = document.createElement('td'); nombreTd.textContent = paciente.nombre || '';
            const apellidoTd = document.createElement('td'); apellidoTd.textContent = paciente.apellido || '';
            const cedulaTd = document.createElement('td'); cedulaTd.textContent = paciente.cedula || '-';
            const telefonoTd = document.createElement('td'); telefonoTd.textContent = paciente.telefono || '-';
            const accionesTd = document.createElement('td');
            accionesTd.innerHTML = `
                <button class="btn btn-accion btn-editar btn-sm" data-id="${paciente.id_paciente}">
                    <i class="bi bi-pencil"></i> Editar
                </button>
                <button class="btn btn-accion btn-eliminar btn-sm" data-id="${paciente.id_paciente}">
                    <i class="bi bi-trash"></i> Eliminar
                </button>
                <button class="btn btn-accion btn-detalles btn-sm" data-id="${paciente.id_paciente}">
                    <i class="bi bi-eye"></i> Ver detalles
                </button>
            `;
            tr.appendChild(idTd);
            tr.appendChild(nombreTd);
            tr.appendChild(apellidoTd);
            tr.appendChild(cedulaTd);
            tr.appendChild(telefonoTd);
            tr.appendChild(accionesTd);
            tablapacientesBody.appendChild(tr);
          });
        })
        .catch((err) => {
          console.error('Error cargando pacientes para tabla:', err);
          tablapacientesBody.innerHTML = '<tr><td colspan="6">Error al cargar los pacientes.</td></tr>';
        });
    }

    return; // Salir, para evitar errores por elementos inexistentes
  }

  // --- Utilidades para validacion ---
  function mostrarError(input, mensaje) {
    let error = input.parentElement.querySelector(".invalid-feedback");
    if (!error) {
      error = document.createElement("div");
      error.className = "invalid-feedback d-block";
      input.parentElement.appendChild(error);
    }
    error.textContent = mensaje;
    input.classList.add("is-invalid");
    input.classList.remove("is-valid");
  }

  function mostrarValido(input) {
    let error = input.parentElement.querySelector(".invalid-feedback");
    if (error) error.textContent = "";
    input.classList.remove("is-invalid");
    input.classList.add("is-valid");
  }

  // --- Funciones para cargar los pacientes ---
  function cargarpacientes(pagina = 1, filtro = "") {
    fetch("index.php?pagina=pacientes", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: "ajax=1&filtro=" + encodeURIComponent(filtro),
    })
      .then((response) => response.json())
      .then((data) => {
        pacientesData = data.pacientes || [];
        paginaActual = 1;
        aplicarFiltroYPaginacion();
      })
      .catch((error) => {
        console.error("Error al cargar pacientes:", error);
        tablapacientesBody.innerHTML =
          '<tr><td colspan="6">Error al cargar los pacientes.</td></tr>';
        paginacionpacientesTop.innerHTML = "";
        paginacionpacientesBottom.innerHTML = "";
      });
  }

  // --- Funciones para actualizar la tabla ---
  function actualizarTabla(pacientes) {
    tablapacientesBody.innerHTML = "";
    if (pacientes.length > 0) {
      pacientes.forEach((paciente) => {
        const row = tablapacientesBody.insertRow();
        row.insertCell().textContent = paciente.id_paciente;
        row.insertCell().textContent = paciente.nombre;
        row.insertCell().textContent = paciente.apellido;
        row.insertCell().textContent = paciente.cedula || "-";
        row.insertCell().textContent = paciente.telefono || "-";
        const accionesCell = row.insertCell();
        accionesCell.innerHTML = `
          <button class="btn btn-accion btn-editar btn-sm" data-id="${paciente.id_paciente}">
            <i class="bi bi-pencil"></i> 
            <span class="d-none d-md-inline"> Editar</span>
          </button>
          <button class="btn btn-accion btn-eliminar btn-sm" data-id="${paciente.id_paciente}">
            <i class="bi bi-trash"></i> 
            <span class="d-none d-md-inline"> Eliminar</span>
          </button>
          <button class="btn btn-accion btn-detalles btn-sm" data-id="${paciente.id_paciente}">
            <i class="bi bi-eye"></i> 
            <span class="d-none d-md-inline"> Ver detalles</span>
          </button>
                `;
      });
    } else {
      const filtro = buscarpacienteInput.value;
      tablapacientesBody.innerHTML = `<tr><td colspan="6">${
        filtro
          ? "No se encontraron pacientes con ese filtro."
          : "No hay pacientes registrados."
      }</td></tr>`;
    }
  }
  // --- Funciones para paginación ---
  function generarPaginacion(totalpacientesFiltrados, pacientesPorPagina) {
    const totalPaginas = Math.ceil(
      totalpacientesFiltrados / pacientesPorPagina
    );
    paginacionpacientesTop.innerHTML = "";
    paginacionpacientesBottom.innerHTML = "";

    if (totalPaginas > 1) {
      for (let i = 1; i <= totalPaginas; i++) {
        const li = document.createElement("li");
        li.classList.add("page-item");
        if (i === paginaActual) {
          li.classList.add("active");
        }
        const a = document.createElement("a");
        a.classList.add("page-link");
        a.href = "#";
        a.textContent = i;
        a.dataset.page = i;
        a.addEventListener("click", handlePaginacionClick);
        li.appendChild(a);

        const liClone = li.cloneNode(true);
        liClone
          .querySelector("a")
          .addEventListener("click", handlePaginacionClick);

        paginacionpacientesTop.appendChild(li);
        paginacionpacientesBottom.appendChild(liClone);
      }
    }
  }

  function handlePaginacionClick(e) {
    e.preventDefault();
    const targetPage = parseInt(e.target.dataset.page);
    if (targetPage !== paginaActual) {
      paginaActual = targetPage;
      aplicarFiltroYPaginacion();
    }
  }

  function aplicarFiltroYPaginacion() {
    const filtro = buscarpacienteInput.value.toLowerCase();
    const pacientesFiltrados = pacientesData.filter(
      (paciente) =>
        paciente.nombre.toLowerCase().includes(filtro) ||
        paciente.apellido.toLowerCase().includes(filtro) ||
        (paciente.cedula && paciente.cedula.toLowerCase().includes(filtro)) ||
        (paciente.telefono && paciente.telefono.toLowerCase().includes(filtro))
    );

    const totalpacientesFiltrados = pacientesFiltrados.length;
    const inicio = (paginaActual - 1) * pacientesPorPagina;
    const fin = inicio + pacientesPorPagina;
    const pacientesPagina = pacientesFiltrados.slice(inicio, fin);

    actualizarTabla(pacientesPagina);
    generarPaginacion(totalpacientesFiltrados, pacientesPorPagina);
  }

  // --- EVENTOS PRINCIPALES ---
  cargarpacientes();

  buscarpacienteInput.addEventListener("input", function () {
    paginaActual = 1;
    aplicarFiltroYPaginacion();
  });

  // --- MODIFICAR PACIENTE: CARGA DATOS AL MODAL Y LIMPIA VALIDACIÓN ---

  // --- Validaciones para modificar ---
  // Helper para adjuntar validación a un input
  function attachValidation(id, handlerName) {
    const input = document.getElementById(id);
    if (!input) return;
    input.addEventListener('input', function() {
      input.dataset.touched = 'true';
      switch (handlerName) {
        case 'nombre':
          let val = input.value.replace(/[^a-zA-ZáéíóúüñÁÉÍÓÚÜÑ\s]/g, '');
          if (val.length > 0) val = val.charAt(0).toUpperCase() + val.slice(1);
          input.value = val;
          validarNombre(input);
          break;
        case 'cedula':
          validarCedula(input);
          break;
        case 'telefono':
          validarTelefono(input);
          break;
        case 'fecha':
          validarFechaNacimiento(input);
          break;
        case 'email':
          validarEmail(input);
          break;
        default:
          break;
      }
    });

    if (handlerName === 'fecha') {
      input.addEventListener('change', function() {
        input.dataset.touched = 'true';
        validarFechaNacimiento(input);
      });
    }
  }

  // Campos modificar
  ['modificar_nombre','modificar_apellido','modificar_ciudad','modificar_pais'].forEach(id => attachValidation(id, 'nombre'));
  ['modificar_cedula'].forEach(id => attachValidation(id, 'cedula'));
  ['modificar_telefono'].forEach(id => attachValidation(id, 'telefono'));
  ['modificar_fecha_nacimiento'].forEach(id => attachValidation(id, 'fecha'));
  ['modificar_email'].forEach(id => attachValidation(id, 'email'));

  // --- Validaciones para el envio ---
  const formModificar = document.getElementById("formularioModificarpaciente");
  if (formModificar) {
    formModificar.addEventListener("submit", function (e) {
      // Detener temporizador ya que el usuario está enviando el formulario
      detenerTemporizadorFormulario();

      let valido = true;
      const errorMessages = [];
      
      [
        "modificar_nombre",
        "modificar_apellido",
        "modificar_cedula",
        "modificar_telefono",
        "modificar_fecha_nacimiento",
        "modificar_ciudad",
        "modificar_pais",
      ].forEach((id) => {
        const input = document.getElementById(id);
        if (input && input.dataset.touched === "true") {
          if (!input.classList.contains("is-valid")) {
            mostrarError(input, "Corrija este campo.");
            valido = false;
            errorMessages.push(`Campo ${id.replace('modificar_', '')} no válido.`);
          }
        }
      });
      
      if (!valido) {
        e.preventDefault();
        // Reiniciar el temporizador si hay errores de validación
        iniciarTemporizadorFormulario();
        console.log('Errores en modificar:', errorMessages);
      }
    });
  }

  // --- Validaciones en tiempo real para añadir paciente ---
  // Campos añadir
  ['nombre','apellido','ciudad','pais'].forEach(id => attachValidation(id, 'nombre'));
  ['cedula'].forEach(id => attachValidation(id, 'cedula'));
  ['telefono'].forEach(id => attachValidation(id, 'telefono'));
  ['fecha_nacimiento'].forEach(id => attachValidation(id, 'fecha'));
  ['email'].forEach(id => attachValidation(id, 'email'));

  // --- Validación al enviar formulario de añadir paciente ---
  const formAñadir = document.getElementById("formularioRegistropaciente");
  if (formAñadir) {
    formAñadir.addEventListener("submit", function (e) {
      // Detener temporizador ya que el usuario está enviando el formulario
      detenerTemporizadorFormulario();

      let valido = true;
      const errorMessages = [];
      
      [
        "nombre",
        "apellido",
        "cedula",
        "telefono",
        "fecha_nacimiento",
        "ciudad",
        "pais",
        "email",
      ].forEach((id) => {
        const input = document.getElementById(id);
        if (input && !input.classList.contains("is-valid")) {
          mostrarError(input, "Corrija este campo.");
          valido = false;
          errorMessages.push(`Campo ${id} no válido.`);
        }
      });
      
      if (!valido) {
        e.preventDefault();
        // Reiniciar el temporizador si hay errores de validación
        iniciarTemporizadorFormulario();
        console.log('Errores en añadir:', errorMessages);
      }
    });
  }

  // --- Botón editar ---
  tablapacientesBody.addEventListener("click", function (e) {
    const btn = e.target.closest(".btn-editar");
    if (btn) {
      const pacienteId = btn.getAttribute("data-id");
      const paciente = pacientesData.find(
        (p) => String(p.id_paciente) === String(pacienteId)
      );
      if (paciente) {
        document.getElementById("modificar_id").value = paciente.id_paciente;
        document.getElementById("modificar_nombre").value =
          paciente.nombre || "";
        document.getElementById("modificar_apellido").value =
          paciente.apellido || "";
        document.getElementById("modificar_cedula").value =
          paciente.cedula || "";
        document.getElementById("modificar_telefono").value =
          paciente.telefono || "";
        document.getElementById("modificar_email").value = paciente.email || "";
        document.getElementById("modificar_fecha_nacimiento").value =
          paciente.fecha_nacimiento || "";
        document.getElementById("modificar_genero").value =
          paciente.genero || "";
        document.getElementById("modificar_ciudad").value =
          paciente.ciudad || "";
        document.getElementById("modificar_pais").value = paciente.pais || "";

       document.getElementById("preview-imagen-paciente-m").src =
          paciente.foto || "";
        
          if(paciente.foto){
          document.getElementById("preview-imagen-paciente-m").style.display =
            "block";
          document.getElementById("icono-imagen-paciente-m").style.display =
            "none";
        }else{
          document.getElementById("preview-imagen-paciente-m").style.display =
            "none";
          document.getElementById("icono-imagen-paciente-m").style.display =
            "flex";
        }
      }
      // Abre el modal manualmente
      const modal = new bootstrap.Modal(
        document.getElementById("modificarpacienteModal")
      );
      modal.show();
    }
    
  });

  // --- Botón detalles ---
  tablapacientesBody.addEventListener("click", function (e) {
    const btnDetalles = e.target.closest(".btn-detalles");
    if (btnDetalles) {
      const pacienteId = btnDetalles.getAttribute("data-id");
      const paciente = pacientesData.find(
        (p) => String(p.id_paciente) === String(pacienteId)
      );
      if (paciente) {
        // Llena el modal con los datos del paciente
        document.getElementById("detalles_nombre").textContent =
          paciente.nombre || "";
        document.getElementById("detalles_apellido").textContent =
          paciente.apellido || "";
        document.getElementById("detalles_cedula").textContent =
          paciente.cedula || "";
        document.getElementById("detalles_telefono").textContent =
          paciente.telefono || "";
        document.getElementById("detalles_fecha_nacimiento").textContent =
          paciente.fecha_nacimiento || "";
        document.getElementById("detalles_genero").textContent =
          paciente.genero || "";
        document.getElementById("detalles_ciudad").textContent =
          paciente.ciudad || "";
        document.getElementById("detalles_pais").textContent =
          paciente.pais || "";
        document.getElementById("detalles_email").textContent =
          paciente.email || "";

        // Mostrar foto si existe, sino mostrar icono
        const imgEl = document.getElementById("detalles_imagen");
        const iconEl = document.getElementById("detalles_icono");
        if (imgEl && iconEl) {
          if (paciente.foto && String(paciente.foto).trim() !== "") {
            imgEl.src = paciente.foto;
            imgEl.style.display = "block";
            iconEl.style.display = "none";
          } else {
            imgEl.src = "#";
            imgEl.style.display = "none";
            iconEl.style.display = "flex";
          }
        }
      }
      // Abre el modal de detalles
      const modalDetalles = new bootstrap.Modal(
        document.getElementById("detallesPacienteModal")
      );
      modalDetalles.show();
    }
  });
  // --- Botón eliminar ---
  tablapacientesBody.addEventListener("click", function (e) {
    const btnEliminar = e.target.closest(".btn-eliminar");
    if (btnEliminar) {
      const pacienteId = btnEliminar.getAttribute("data-id");
      const paciente = pacientesData.find(
        (p) => String(p.id_paciente) === String(pacienteId)
      );

      if (paciente) {
        Swal.fire({
          title: "¿Desea eliminar?",
          text: "Esta acción no se puede deshacer.",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Sí, eliminar",
          cancelButtonText: "Cancelar",
        }).then((result) => {
          if (result.isConfirmed) {
            let datos = {
              accion: "eliminarpaciente",
              id_paciente: paciente.id_paciente,
            };

            $.ajax({
              url: "",
              method: "POST",
              data: datos,
              success: function (response) {
                response = JSON.parse(response);
                if (response.success) {
                  pacientesData = pacientesData.filter(
                    (elemento) => elemento.id_paciente != paciente.id_paciente
                  );
                  actualizarTabla(pacientesData);
                  Swal.fire({
                    title: "Paciente eliminado correctamente",
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false,
                  });
                } else {
                  Swal.fire("Error", "Error al eliminar el paciente", "error");
                }
              },
            });
          }
        });
      }
    }
  });

 document
    .getElementById("foto")
    .addEventListener("change", function (e) {
      const file = e.target.files[0];
      const preview = document.getElementById("preview-imagen-paciente");
      const icono = document.getElementById("icono-imagen-paciente");
      const formatosPermitidos = ["image/jpeg", "image/png", "image/jpg"];
      const maxSize = 2 * 1024 * 1024;

      if (file) {
        if (!formatosPermitidos.includes(file.type)) {
          preview.src = "#";
          preview.style.display = "none";
          icono.style.display = "flex";
          e.target.value = "";
          Swal.fire({
            icon: "error",
            title: "Formato no permitido",
            text: "Solo se permiten imágenes JPG, PNG o JPEG.",
            timer: 5000,
            showConfirmButton: false,
          });
        } else if (file.size > maxSize) {
          preview.src = "#";
          preview.style.display = "none";
          icono.style.display = "flex";
          e.target.value = "";
          Swal.fire({
            icon: "error",
            title: "Archivo demasiado grande",
            text: "La imagen no debe superar los 2MB.",
            timer: 5000,
            showConfirmButton: false,
          });
        } else {
          const reader = new FileReader();
          reader.onload = function (evt) {
            preview.src = evt.target.result;
            preview.style.display = "block";
            icono.style.display = "none";
          };
          reader.readAsDataURL(file);
        }
      } else {
        preview.src = "#";
        preview.style.display = "none";
        icono.style.display = "flex";
      }
    });

  document.getElementById("foto-m").addEventListener("change", function (e) {
    const file = e.target.files[0];
    const preview = document.getElementById("preview-imagen-paciente-m");
    const icono = document.getElementById("icono-imagen-paciente-m");
    const formatosPermitidos = ["image/jpeg", "image/png", "image/jpg"];
    const maxSize = 2 * 1024 * 1024;

    if (file) {
      if (!formatosPermitidos.includes(file.type)) {
        preview.src = "#";
        preview.style.display = "none";
        icono.style.display = "flex";
        e.target.value = "";
        Swal.fire({
          icon: "error",
          title: "Formato no permitido",
          text: "Solo se permiten imágenes JPG, PNG o JPEG.",
          timer: 5000,
          showConfirmButton: false,
        });
      } else if (file.size > maxSize) {
        preview.src = "#";
        preview.style.display = "none";
        icono.style.display = "flex";
        e.target.value = "";
        Swal.fire({
          icon: "error",
          title: "Archivo demasiado grande",
          text: "La imagen no debe superar los 2MB.",
          timer: 5000,
          showConfirmButton: false,
        });
      } else {
        const reader = new FileReader();
        reader.onload = function (evt) {
          preview.src = evt.target.result;
          preview.style.display = "block";
          icono.style.display = "none";
        };
        reader.readAsDataURL(file);
      }
    } else {
      preview.src = "#";
      preview.style.display = "none";
      icono.style.display = "flex";
    }
  });
});
