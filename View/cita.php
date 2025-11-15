<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <link rel="stylesheet" href="css/custom.css">
  <title>cita</title>
  <?php require_once("menu/head.php"); ?>
  <?php require_once("menu/menu.php"); ?>
  <link rel="stylesheet" href="css/citas.css">

</head>

<body>

   <main role="main" class="col-md-3 col-md-6 col-md-9 col-md-12 ms-sm-auto col-lg-10 px-4 main-content">

    <div class="container-fluid mt-4">
      <div class="row justify-content-center">
        <div class="col-12">
          <div id="calendar" class="w-100"></div>
        </div>
      </div>
    </div>

    <!-- Modal Crear / Guardar Cita -->
    <div class="modal fade" id="GuardarModal" tabindex="-1" aria-labelledby="GuardarModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <form id="formEvento" method="POST" action="?pagina=cita">
            <div class="modal-header">
              <h5 class="modal-title" id="GuardarModalLabel">Nueva Cita</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
              <?php
              use Yahir\Compo\Cita as CitaModelo;
              use Yahir\Compo\Pacientes as PacientesModelo;
              $pacienteModel = new PacientesModelo();
              $pacientes = $pacienteModel->listarpaciente();
              ?>
              <div class="mb-3">
                <label for="id_paciente" class="form-label">Paciente</label>
                <select class="form-control" id="id_paciente" name="id_paciente" required>
                  <option value="">Seleccione un paciente</option>
                  <?php foreach ($pacientes as $p): ?>
                    <option value="<?php echo htmlspecialchars($p['id_paciente']); ?>">
                      <?php echo htmlspecialchars($p['nombre'] . ' ' . $p['apellido']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="Título del evento">
              </div>

              <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción">
              </div>

              <div class="row g-2">
                <div class="col">
                  <label for="color" class="form-label">Color Fondo</label>
                  <input type="color" class="form-control form-control-color" id="color" name="color" value="#6610f2">
                </div>
                <div class="col">
                  <label for="textoColor" class="form-label">Color Texto</label>
                  <input type="color" class="form-control form-control-color" id="textoColor" name="textColor" value="#0d6efd">
                </div>
              </div>

              <div class="row g-2 mt-2">
                <div class="col">
                  <label for="start" class="form-label">Inicio</label>
                  <input type="datetime-local" class="form-control" id="start" name="start">
                </div>
                <div class="col">
                  <label for="end" class="form-label">Fin</label>
                  <input type="datetime-local" class="form-control" id="end" name="end">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" id="btnGuardarCita" name="guardar_cita" class="btn btn-primary">
                <i class="bi bi-save-fill"></i> Guardar nueva cita
              </button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                <i class="bi bi-x-lg"></i> Cerrar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <a href="img/manual.pdf" target="_blank"
            id="help-button-fab"
            class="btn rounded-circle shadow-lg"
            data-bs-toggle="tooltip" data-bs-placement="left"
            title="Manual de Ayuda"> <i class="bi bi-question-circle-fill"></i>
        </a>


<!-- Modal Visualizar / Editar / Eliminar -->
<div class="modal fade" id="ModalVisualizar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <form id="formVisualizar">
        <div class="modal-header d-flex flex-column align-items-start">
          <h5 class="modal-title w-100 text-center">Detalles de la cita</h5>
          <!-- 👇 Nombre del paciente mostrado arriba -->
          <small id="nombrePacienteVisualizar" 
       class="text-center w-100 mb-2 fs-6 fw-bold text-dark">
    </small>
          <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>

        <div class="modal-body">
          <input type="hidden" id="idVisualizar" name="id">

          <!-- ✅ Campo de título conservado -->
          <div class="mb-2">
            <label class="form-label">Título</label>
            <input type="text" id="titleVisualizar" name="title" class="form-control">
          </div>

          <div class="mb-2">
            <label class="form-label">Descripción</label>
            <textarea id="descripcionVisualizar" name="descripcion" class="form-control"></textarea>
          </div>

          <div class="row g-2">
            <div class="col">
              <label class="form-label">Inicio</label>
              <input type="datetime-local" id="startVisualizar" name="start" class="form-control">
            </div>
            <div class="col">
              <label class="form-label">Fin</label>
              <input type="datetime-local" id="endVisualizar" name="end" class="form-control">
            </div>
          </div>

          <div class="row g-2 mt-2">
            <div class="col">
              <label class="form-label">Color Fondo</label>
              <input id="colorVisualizar" name="color" type="color" class="form-control form-control-color">
            </div>
            <div class="col">
              <label class="form-label">Color Texto</label>
              <input id="textoColorVisualizar" name="textColor" type="color" class="form-control form-control-color">
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-accion btn-primary" name="actualizar_cita">
            <i class="bi bi-pencil"></i> Guardar Cambios
          </button>
          <button type="button" id="btnEliminar" class="btn btn-accion btn-eliminar" name="eliminar_cita">
            <i class="bi bi-trash"></i> Eliminar
          </button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-lg"></i>Cerrar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

  </main>

  <footer>
  </footer>
  
  <script src="js/Jquery.min.js"></script>
  <script src="js/citass.js"></script>
  <script src="js/main.js"></script>
  <script src="js/bootstrap.bundle.js"></script>
  <script src='js/index.global.min.js'></script>
  <script src='js/index.global.js'></script>

</body>

</html>