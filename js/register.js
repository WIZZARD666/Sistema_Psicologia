document.addEventListener("DOMContentLoaded", function () {
 
});

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
// REGISTRAR HISTORIAL


$("#f").on("submit", function (e) {
  e.preventDefault();


  var datos = new FormData();
  datos.append("accion", "incluir");
  datos.append("id", $("#id").val());
  datos.append("cedula", $("#cedula").val());
  datos.append("name", $("#name").val());
  datos.append("lastName", $("#lastName").val());
  datos.append("mail", $("#mail").val());
  datos.append("password", $("#password").val());
  datos.append("birthDate", $("#birthDate").val());
  datos.append("gender", $("#gender").val());
  datos.append("role", $("#role").val());

  enviarAjax(datos);
});

// MODIFICAR HISTORIAL
$("#modalEditarPerfil").on("submit", (e) => {
  e.preventDefault();

  var datos = new FormData();
  datos.append("accion", "modificar");
  datos.append("id", $("#id").val());
  datos.append("name", $("#nombre_modificar").val());
  datos.append("lastName", $("#apellido_modificar").val());
  datos.append("mail", $("#correo_modificar").val());
  datos.append("cedula", $("#cedula_modificar").val());
  datos.append("birthDate", $("#fecha_nacimiento_modificar").val());
  datos.append("gender", $("#genero_modificar").val());
  datos.append("password", $("#password_modificar").val());
  enviarAjax(datos);
});

// ACTUALIZAR CAMPOS MODIFICAR
function actualizarCampos(id, datos = false) {
  event.preventDefault();

  if (datos) {
    $("#nombre_modificar").val(datos.name);
    $("#apellido_modificar").val(datos.lastName);
    $("#cedula_modificar").val(datos.cedula);
    $("#email_modificar").val(datos.mail);
    $("#fecha_nacimiento_modificar").val(datos.birthDate);
    $("#genero_modificar").val(datos.gender);
    $("#password_modificar").val(datos.password);


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

// AJAX
function enviarAjax(datos) {
  $.ajax({
    async: true,
  url: "",
    type: "POST",
    contentType: false,
    data: datos,
    processData: false,
    cache: false,
    beforeSend: function () {},
    timeout: 10000,
    success: function (respuesta) {
      // console.log(respuesta)
      try {
        console.log('Respuesta raw servidor:', respuesta);
        let respObj = JSON.parse(respuesta);

        if (respObj.resultado == "incluir") {
          Swal.fire({
            icon: "success",
            title: "Éxito",
            text: respObj.mensaje,
            timer: 2000,
            showConfirmButton: false,
          });

          if (respObj.mensaje == "Se ha registrado correctamente") {
            try { if (typeof $ !== 'undefined' && $("#modalRegistro").length) { $("#modalRegistro").modal("hide"); } } catch(e){}
            try { if (document.getElementById('f')) { document.getElementById('f').reset(); } } catch(e){}
            try { if (typeof consultar === 'function') { consultar(); } } catch(e){}

            // 🔄 limpiar cache para asegurar refresco
            if ("caches" in window) {
              caches.keys().then((names) => {
                for (let name of names) caches.delete(name);
              });
            }
          } else if (respObj.resultado == "modificar") {
          Swal.fire({
            icon: "success",
            title: "Actualizado",
            text: respObj.mensaje,
            timer: 2000,
            showConfirmButton: false,
          });

          if (respObj.mensaje == "Datos del historia modificados") {
            $("#modalEditarPerfil").modal("hide");
            consultar();
          }
        }
        }
      }  catch (e) {
        console.error("Error al procesar la respuesta: ", e);
        console.error("Respuesta del servidor: ", respuesta);

        Swal.fire({
          icon: "error",
          title: "Error",
          text: "Hubo un problema procesando la respuesta del servidor.",
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

function mostrarMensaje(mensaje) {
  $("#modalContenido").html(mensaje);
  $("#modalMensajes").removeClass("hidden");
  $("#modalMensajes").addClass("flex");
}
