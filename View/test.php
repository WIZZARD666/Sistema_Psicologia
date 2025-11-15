<!doctype html>
<html lang="es">

<head>
    <title>Tests Psicológicos</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php require_once("menu/head.php"); ?>
    <?php require_once("menu/menu.php"); ?>
    <link rel="stylesheet" href="css/test.css">

</head>

<body>

    <!-- Contenido principal -->
    <main role="main" class="col-md-3 col-md-6 col-md-9 col-md-12 ms-sm-auto col-lg-10 px-4 main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 ms-4 me-2">
            <h2 class="fw-bold" style="color:var(--color5);">Tests Psicológicos</h2>
            <div class="d-flex align-items-center gap-3">
                <a href='?pagina=profile' title="Mi Perfil" class="profile-icon-link d-none d-md-flex align-items-center justify-content-center">
                    <i class="bi bi-person" style="font-size: 32px; color: var(--color5);"></i>
                </a>
                <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#nuevoTestModal">
                    <i class="bi bi-plus-circle "></i>
                    <span class="d-none d-md-inline ms-2">Incluir Test</span>
                </button>
            </div>
        </div>

        <!-- Filtros y selección de paciente -->
        <!-- Eliminado el bloque de selects de filtrado -->
        <!-- Lista de tests -->
        <!-- Buscador arriba de la tabla, igual al de pacientes -->
        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text" style="background: var(--color1); border: none; color: var(--color5); font-size: 1.2rem; border-radius: 20px 0 0 20px;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="buscarTest" class="form-control" placeholder="Buscar tests..." style="border-radius: 0 20px 20px 0;">
            </div>
        </div>
        <div class="card shadow-sm rounded-4 mb-4">
            <div class="card-header ">
                <h5 class="mb-10">Tests Registrados</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Paciente</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="tablaTests">
                        <tr>
                            <td colspan="5">Cargando tests...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <a href="img/manual.pdf" target="_blank"

            id="help-button-fab"
            class="btn rounded-circle shadow-lg"
            data-bs-toggle="tooltip" data-bs-placement="left"
            title="Manual de Ayuda"> <i class="bi bi-question-circle-fill"></i>
        </a>

        <!-- Modal para nuevo test -->
        <div class="modal fade" id="nuevoTestModal" tabindex="-1" aria-labelledby="nuevoTestModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="nuevoTestModalLabel">Nuevo Test</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formNuevoTest">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nuevoTestPaciente" class="form-label">Paciente</label>
                                    <select class="form-select" id="nuevoTestPaciente" name="id_paciente" required>
                                        <option value="">-- Seleccione un paciente --</option>
                                        <?php foreach ($pacientes as $paciente): ?>
                                            <option value="<?= $paciente['id_paciente'] ?>">
                                                <?= htmlspecialchars($paciente['apellido'] . ', ' . $paciente['nombre']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="nuevoTestTipo" class="form-label">Tipo de Test</label>
                                    <select class="form-select" id="nuevoTestTipo" name="tipo_test" required>
                                        <option value="">-- Seleccione un tipo --</option>
                                        <option value="poms">POMS</option>
                                        <option value="confianza">Confianza</option>
                                        <option value="importancia">Importancia</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Contenedor dinámico para el formulario del test seleccionado -->
                            <div id="formularioTestContainer"></div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary" name="guardar_test">Guardar Test</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para editar test -->
        <div class="modal fade" id="editarTestModal" tabindex="-1" aria-labelledby="editarTestModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editarTestModalLabel">Editar Test</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEditarTest">
                            <input type="hidden" id="editarTestId" name="id_test">
                            <input type="hidden" id="editarTestTipo" name="tipo_test">

                            <!-- Contenedor dinámico para el formulario del test -->
                            <div id="formularioEditarTestContainer"></div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary" name="actualizar_test">Actualizar Test</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div>
    </div>

    <!-- Scripts -->
    <script src="js/Jquery.min.js"></script>
    <script src="js/bootstrap.bundle.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="js/testsss.js"></script>
    <script src="js/main.js"></script>
</body>

</html>