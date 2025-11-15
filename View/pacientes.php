<!doctype html>
<html lang="es">

<head>
    <title>pacientes</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php require_once("menu/head.php"); ?>
    <?php require_once("menu/menu.php"); ?>
    <link rel="stylesheet" href="css/pacientess.css">

</head>

<body>
    <main role="main" class="col-md-3 col-md-6 col-md-9 col-md-12 ms-sm-auto col-lg-10 px-4 main-content">

        <div class="d-flex justify-content-between align-items-center mb-4 ms-4 me-2">
            <h2 class="fw-bold" style="color:var(--color5);">Pacientes</h2>

            <div class="d-flex align-items-center gap-2">

                <a href='?pagina=profile' title="Mi Perfil" class="profile-icon-link d-none d-md-flex align-items-center justify-content-center">
                    <i class="bi bi-person" style="font-size: 32px; color: var(--color5);"></i>
                </a>

                <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#registropacienteModal" title="Añadir nuevo paciente">
                    <i class="bi bi-plus-circle"></i>
                    <span class="d-none d-md-inline ms-2">Incluir paciente</span>
                </button>

            </div>
        </div>

        <div class="mb-4">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" id="buscarpaciente" class="form-control" placeholder="Buscar pacientes...">
            </div>
        </div>

        <!-- paginación de pacientes (arriba) -->
        <nav aria-label="Paginación de pacientes (arriba)">
            <ul class="pagination justify-content-center" id="paginacionpacientesTop"></ul>
        </nav>
        <div class=" row card shadow-sm rounded-4 mb-4">
            <div class="table-responsive shadow p-1 ">
                <table class="table table-sm table-hover align-middle mb-0 ">
                    <thead class="table-primary">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cédula</th>
                            <th>Teléfono</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>

                    <!-- llama los datos de los pacientes -->
                    <tbody id="tablapacientes"></tbody>

                </table>
            </div>
        </div>

        <!-- Paginación de pacientes (abajo) -->
        <nav aria-label="Paginación de pacientes (abajo)">
            <ul class="pagination justify-content-center" id="paginacionpacientesBottom"></ul>
        </nav>

        <a href="img/manual.pdf" target="_blank"
        
            id="help-button-fab"
            class="btn rounded-circle shadow-lg"
            data-bs-toggle="tooltip" data-bs-placement="left"
            title="Manual de Ayuda"> <i class="bi bi-question-circle-fill"></i>
        </a>

        <!-- Modal registro Paciente -->
        <div class="modal fade" id="registropacienteModal" tabindex="-1" aria-labelledby="registropacienteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="registropacienteModalLabel">Añadir Nuevo paciente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <form id="formularioRegistropaciente" action="?pagina=pacientes" method="POST">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Juan" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ej: Pérez" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="cedula" class="form-label">Cédula</label>
                                    <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Ej: 12235439" maxlength="10">
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Ej: 04145113078" maxlength="12">
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Gmail</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Ej: juanperez@gmail.com">
                                </div>

                                <div class="col-md-6">
                                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" max="2011-12-31" value="2011-12-31">
                                </div>
                            </div>
                            <div class="row mb-3">

                                <div class="col-md-6">
                                    <label for="genero" class="form-label">Género</label>
                                    <select class="form-select" id="genero" name="genero">
                                        <option value="">Seleccionar</option>
                                        <option value="masculino">Masculino</option>
                                        <option value="femenino">Femenino</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">

                                <div class="col-md-6">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" placeholder="Ej: Caracas" id="ciudad" name="ciudad">
                                </div>

                                <div class="col-md-6">
                                    <label for="pais" class="form-label">País</label>
                                    <input type="text" class="form-control" placeholder="Ej: Venezuela" id="pais" name="pais">
                                </div>

                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="" class="form-label">Foto del paciente</label>
                                    <label id="label-imagen-paciente" for="foto">
                                        <div id="icono-imagen-paciente">
                                            <i class="bi bi-image"></i>
                                            <span style="font-size: 1em;">Seleccionar imagen</span>
                                        </div>
                                        <img id="preview-imagen-paciente" src="#" alt="Vista previa" style="display: none;" />
                                    </label>
                                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                                    <small class="form-text text-muted">Formatos permitidos: JPG, PNG, JPEG. Tamaño máximo recomendado: 2MB.</small>
                                </div>
                            </div>

                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="guardara" value="guardara">Guardar paciente</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                    </form>

                </div>
            </div>
        </div>

        <!-- Modal Modificar Paciente -->
        <div class="modal fade" id="modificarpacienteModal" tabindex="-1" aria-labelledby="modificarpacienteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modificarpacienteModalLabel">Modificar paciente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formularioModificarpaciente" action="?pagina=pacientes" method="POST">
                            <input type="hidden" id="modificar_id" name="id_paciente">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="modificar_nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control" id="modificar_nombre" name="nombre" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="modificar_apellido" class="form-label">Apellido</label>
                                    <input type="text" class="form-control" id="modificar_apellido" name="apellido" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="modificar_cedula" class="form-label">Cédula</label>
                                    <input type="text" class="form-control" id="modificar_cedula" name="cedula">
                                </div>
                                <div class="col-md-6">
                                    <label for="modificar_telefono" class="form-label">Teléfono</label>
                                    <input type="tel" class="form-control" id="modificar_telefono" name="telefono">
                                </div>
                                <div class="col-md-6">
                                    <label for="modificar_email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="modificar_email" name="email">
                                </div>
                                <div class="col-md-6">
                                    <label for="modificar_fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" id="modificar_fecha_nacimiento" name="fecha_nacimiento">
                                </div>
                            </div>
                            <div class="row mb-3">

                                <div class="col-md-6">
                                    <label for="modificar_genero" class="form-label">Género</label>
                                    <select class="form-select" id="modificar_genero" name="genero">
                                        <option value="">Seleccionar</option>
                                        <option value="masculino">Masculino</option>
                                        <option value="femenino">Femenino</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="modificar_ciudad" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="modificar_ciudad" name="ciudad">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="modificar_pais" class="form-label">País</label>
                                    <input type="text" class="form-control" id="modificar_pais" name="pais">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="" class="form-label">Foto del paciente</label>
                                    <label id="label-imagen-paciente-m" class="label-imagen-paciente" for="foto-m">
                                        <div id="icono-imagen-paciente-m" class="icono-imagen-paciente">
                                            <i class="bi bi-image"></i>
                                            <span style="font-size: 1em;">Seleccionar imagen</span>
                                        </div>
                                        <img id="preview-imagen-paciente-m" class="preview-imagen-paciente" src="#" alt="Vista previa" style="display: none;" />
                                    </label>
                                    <input type="file" class="form-control" id="foto-m" name="foto" accept="image/*" hidden>
                                    <small class="form-text text-muted">Formatos permitidos: JPG, PNG, JPEG. Tamaño máximo recomendado: 2MB.</small>
                                </div>
                            </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="actualizar_paciente_submit">Guardar Cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>

                    </form>


                </div>
            </div>
        </div>

        <!-- Modal Detalles -->
        <div class="modal fade" id="detallesPacienteModal" tabindex="-1" aria-labelledby="detallesPacienteModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header">
                        <h4 class="modal-title fw-bold" id="detallesPacienteModalLabel">
                            <i class="bi bi-person-badge me-2"></i>Detalles del Paciente
                        </h4>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body px-4 py-3">
                        <div class="row g-4">
                            <div class="col-md-4 text-center">
                                <div class="bg-light rounded-circle mx-auto mb-3" style="width:120px;height:120px;display:flex;align-items:center;justify-content:center;position:relative;">
                                    <!-- Imagen del paciente (se muestra si existe paciente.foto) -->
                                    <img id="detalles_imagen" src="#" alt="Foto paciente" style="width:120px;height:120px;object-fit:cover;border-radius:50%;display:none;" />
                                    <!-- Icono por defecto (se muestra si no hay foto) -->
                                    <div id="detalles_icono" style="display:flex;align-items:center;justify-content:center;font-size:5rem;color:#0d6efd;">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                </div>
                                <h5 class="mb-0" id="detalles_nombre" style="font-weight: bold;"></h5>
                                <div class="text-muted" id="detalles_apellido"></div>
                                <span class="badge bg-secondary mt-2" id="detalles_genero"></span>
                            </div>

                            <div class="col-md-8">
                                <div class="row mb-2">
                                    <div class="col-6 mb-2">
                                        <i class="bi bi-credit-card-2-front me-2"></i>
                                        <strong>Cédula:</strong>
                                        <span class="ms-1" id="detalles_cedula"></span>
                                    </div>

                                    <div class="col-6 mb-2">
                                        <i class="bi bi-telephone me-2"></i>
                                        <strong>Teléfono:</strong>
                                        <span class="ms-1" id="detalles_telefono"></span>
                                    </div>

                                    <div class="col-6 mb-2">
                                        <i class="bi bi-calendar-date me-2"></i>
                                        <strong>Fecha de nacimiento:</strong>
                                        <span class="ms-1" id="detalles_fecha_nacimiento"></span>
                                    </div>

                                    <div class="col-6 mb-2">
                                        <i class="bi bi-envelope-at me-2"></i>
                                        <strong>Email:</strong>
                                        <span class="ms-1" id="detalles_email"></span>
                                    </div>



                                    <div class="col-6 mb-2">
                                        <i class="bi bi-building me-2"></i>
                                        <strong>Ciudad:</strong>
                                        <span class="ms-1" id="detalles_ciudad"></span>
                                    </div>

                                    <div class="col-6 mb-2">
                                        <i class="bi bi-globe-americas me-2"></i>
                                        <strong>País:</strong>
                                        <span class="ms-1" id="detalles_pais"></span>
                                    </div>

                                </div>

                                <hr>

                                <div class="alert alert-info d-flex align-items-center mb-0" role="alert" style="font-size: 1rem;">
                                    <i class="bi bi-info-circle-fill me-2"></i>
                                    Todos los datos son confidenciales y solo visibles para personal autorizado.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div>
    </div>

    <script>
    
</script>
    <script src="js/pacientess.js"></script>
    <script src="js/main.js"></script>
    <script src="js/Jquery.min.js"></script>


    <script src="js/bootstrap.bundle.js"></script>


</body>

</html>