<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Tratamientos Psicológicos</title>
    <?php require_once("menu/head.php"); ?>
    <?php require_once("menu/menu.php"); ?>
    <link rel="stylesheet" href="css/tratamiento.css">
</head>

<body>
    <main role="main" class="col-md-3 col-md-6 col-md-9 col-md-12 ms-sm-auto col-lg-10 px-4 main-content">
        <!-- Mostrar mensajes -->
        <?php if (isset($_SESSION['mensaje'])): ?>
            <div class="alert alert-<?= $_SESSION['mensaje']['tipo'] ?> alert-dismissible fade show" role="alert">
                <?= $_SESSION['mensaje']['texto'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['mensaje']); ?>
        <?php endif; ?>

        <div class="d-flex justify-content-between align-items-center mb-4 ms-4 me-2">
            <h2 class="fw-bold" style="color:var(--color5);">Tratamientos Psicológicos</h2>
            <div class="d-flex align-items-center gap-3">
                <a href='?pagina=profile' title="Mi Perfil" class="profile-icon-link d-none d-md-flex align-items-center justify-content-center">
                    <i class="bi bi-person" style="font-size: 32px; color: var(--color5);"></i>
                </a>
                <button type="button" class="btn btn-primary  d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#nuevoTratamientoModal">
                    <i class="bi bi-plus-circle"></i>
                    <span class="d-none d-md-inline ms-2">Incluir tratamiento</span>
                </button>
            </div>
        </div>

        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="buscarTratamiento" class="form-control" placeholder="Buscar por nombre, cédula, estado o fecha...">
            </div>
        </div>

        <div class="container-fluid mt-5">
            <div class="card shadow-sm mx-auto" style="max-width: 1000px;">
                <div class="card-header  modal-header text-white d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0"><i class="fas fa-heartbeat me-2"></i>TRATAMIENTOS PSICOLÓGICOS</h4>
                </div>
                <div class="card-body paciente-card">
                    <div class="table-responsive">
                        <table id="tablaTratamientos" class="table table-hover table table-striped  align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Paciente</th>
                                    <th>Cédula</th>
                                    <th>Fecha Inicio</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tratamientos as $tratamiento): ?>
                                    <tr data-id="<?= $tratamiento['id_tratamiento'] ?>">
                                        <td><?= htmlspecialchars($tratamiento['nombre'] . ' ' . $tratamiento['apellido']) ?></td>
                                        <td><?= htmlspecialchars($tratamiento['cedula']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($tratamiento['fecha_creacion'])) ?></td>
                                        <td>
                                            <?php
                                            $estadoClass = "badge-" . str_replace(' ', '_', strtolower($tratamiento['estado_actual']));
                                            $estadoText = ucwords(str_replace('_', ' ', $tratamiento['estado_actual']));
                                            ?>
                                            <span class="badge <?= $estadoClass ?>"><?= $estadoText ?></span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-accion btn-editar" data-id="${data}">
                                                <i class="fas fa-edit me-1"></i>Editar
                                            </button>
                                            <button class="btn btn-sm btn-accion btn-eliminar" data-id="${data}">
                                                <i class="fas fa-trash me-1"></i>Eliminar
                                            </button>
                                            <button class="btn btn-sm btn-accion btn-detalles" data-id="${data}">
                                                <i class="fas fa-info-circle me-1"></i>Detalles
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <a href="img/manual.pdf" target="_blank"

            id="help-button-fab"
            class="btn rounded-circle shadow-lg"
            data-bs-toggle="tooltip" data-bs-placement="left"
            title="Manual de Ayuda"> <i class="bi bi-question-circle-fill"></i>
        </a>

        <!-- Modal Nuevo Tratamiento -->
        <div class="modal fade" id="nuevoTratamientoModal" tabindex="-1" aria-labelledby="nuevoTratamientoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header modal-header text-white">
                        <h5 class="modal-title" id="nuevoTratamientoModalLabel">
                            <i class="fas fa-plus-circle me-2"></i>Nuevo Tratamiento
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formNuevoTratamiento" method="post">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="id_paciente" class="form-label">Paciente</label>
                                    <select class="form-select" id="id_paciente" name="id_paciente" required>
                                        <option value="">Seleccione un paciente</option>
                                        <?php foreach ($pacientes as $paciente): ?>
                                            <option value="<?= $paciente['id_paciente'] ?>">
                                                <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido'] . ' - ' . $paciente['cedula']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_creacion" class="form-label">Fecha de Creación *</label>
                                    <input type="date" class="form-control" id="fecha_creacion" name="fecha_creacion" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tratamiento_tipo" class="form-label">Tipo de Tratamiento *</label>
                                    <input type="text" class="form-control" id="tratamiento_tipo" name="tratamiento_tipo" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="estado_actual" class="form-label">Estado *</label>
                                    <select class="form-select" id="estado_actual" name="estado_actual" required>
                                        <option value="inicial">Fase Inicial</option>
                                        <option value="en_progreso">En Progreso</option>
                                        <option value="pausado">Pausado</option>
                                        <option value="seguimiento">Seguimiento</option>
                                        <option value="finalizado">Finalizado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="diagnostico_descripcion" class="form-label">Diagnóstico *</label>
                                <textarea class="form-control" id="diagnostico_descripcion" name="diagnostico_descripcion" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="observaciones" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </button>
                                <button type="submit" name="guardar_tratamiento" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Editar Tratamiento -->
        <div class="modal fade" id="editarTratamientoModal" tabindex="-1" aria-labelledby="editarTratamientoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header modal-header text-white">
                        <h5 class="modal-title" id="editarTratamientoModalLabel">
                            <i class="fas fa-edit me-2"></i>Editar Tratamiento
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEditarTratamiento" method="post">
                            <input type="hidden" id="id_tratamiento_editar" name="id_tratamiento">

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="id_paciente_editar" class="form-label">Paciente *</label>
                                    <select class="form-select" id="id_paciente_editar" name="id_paciente" required>
                                        <option value="">Seleccione un paciente</option>
                                        <?php foreach ($pacientes as $paciente): ?>
                                            <option value="<?= $paciente['id_paciente'] ?>">
                                                <?= htmlspecialchars($paciente['nombre'] . ' ' . $paciente['apellido'] . ' - ' . $paciente['cedula']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha_creacion_editar" class="form-label">Fecha de Creación *</label>
                                    <input type="date" class="form-control" id="fecha_creacion_editar" name="fecha_creacion" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="tratamiento_tipo_editar" class="form-label">Tipo de Tratamiento *</label>
                                    <input type="text" class="form-control" id="tratamiento_tipo_editar" name="tratamiento_tipo" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="estado_actual_editar" class="form-label">Estado *</label>
                                    <select class="form-select" id="estado_actual_editar" name="estado_actual" required>
                                        <option value="inicial">Fase Inicial</option>
                                        <option value="en_progreso">En Progreso</option>
                                        <option value="pausado">Pausado</option>
                                        <option value="seguimiento">Seguimiento</option>
                                        <option value="finalizado">Finalizado</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="diagnostico_descripcion_editar" class="form-label">Diagnóstico *</label>
                                <textarea class="form-control" id="diagnostico_descripcion_editar" name="diagnostico_descripcion" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="observaciones_editar" class="form-label">Observaciones</label>
                                <textarea class="form-control" id="observaciones_editar" name="observaciones" rows="3"></textarea>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>Cancelar
                                </button>
                                <button type="submit" name="actualizar_tratamiento" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Actualizar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Detalles Tratamiento -->
        <div class="modal fade" id="detallesTratamientoModal" tabindex="-1" aria-labelledby="detallesTratamientoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header modal-header text-white">
                        <h5 class="modal-title" id="detallesTratamientoModalLabel">
                            <i class="fas fa-info-circle me-2"></i>Detalles del Tratamiento
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-light">
                                    <div class="card-body text-center">
                                        <i class="fas fa-user fa-3x mb-3 text-primary"></i>
                                        <h5 id="detalleNombrePaciente"></h5>
                                        <p class="text-muted mb-1" id="detalleCedula"></p>
                                        <p class="text-muted" id="detalleFechaCreacion"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Estado del Tratamiento</h5>
                                        <p class="card-text">
                                            <span class="badge" id="detalleEstado"></span>
                                        </p>

                                        <h5 class="card-title mt-3">Tipo de Tratamiento</h5>
                                        <p class="card-text" id="detalleTipoTratamiento"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Diagnóstico Psicológico</h5>
                            </div>
                            <div class="card-body">
                                <p id="detalleDiagnostico"></p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Observaciones</h5>
                            </div>
                            <div class="card-body">
                                <p id="detalleObservaciones"></p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Confirmar Eliminación -->
        <div class="modal fade" id="confirmarEliminarModal" tabindex="-1" aria-labelledby="confirmarEliminarModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="confirmarEliminarModalLabel">
                            <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Está seguro que desea eliminar este tratamiento? Esta acción no se puede deshacer.</p>
                        <input type="hidden" id="id_tratamiento_eliminar">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-danger" id="btnConfirmarEliminar">
                            <i class="fas fa-trash me-1"></i>Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div>
    </div>

    <script src="js/Jquery.min.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/data.js"></script>
    <script src="js/tratamientos.js"></script>
    <script src="js/main.js">

    </script>
</body>

</html>