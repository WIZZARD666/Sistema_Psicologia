<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pacientes</title>
    <?php require_once("menu/head.php"); ?>
    <link rel="stylesheet" href="css/pacientes.css"> 
</head>

<body class="bg-light font-primary text-dark">

    <div class="container-fluid">
        <div class="row">
            <?php require_once('menu/menu.php'); ?>

            <main role="main" class="col-md-3 col-md-6 col-md-9 col-md-12 ms-sm-auto col-lg-10 px-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4 ms-4 me-2">
                    <h2 class="fw-bold" style="color:var(--color5);">Pacientes</h2> <div class=" d-flex align-items-center gap-3">
                        <a href='?pagina=profile' title="Mi Perfil"
                            class="profile-icon-link d-none d-md-flex align-items-center justify-content-center">
                            <i class="bi bi-person" style="font-size: 32px; color: var(--color5);"></i>
                        </a>
                        <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                            data-bs-target="#registropacienteModal" title="Añadir nuevo paciente"> <i class="bi bi-plus-circle"></i>
                            <span class="d-none d-md-inline ms-2">Incluir Paciente</span> </button>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="buscarpaciente" class="form-control" placeholder="Buscar pacientes...">
                    </div>
                </div>

                <nav aria-label="Paginación de pacientes (arriba)">
                    <ul class="pagination justify-content-center" id="paginacionpacientesTop"></ul>
                </nav>

                <div class=" row card shadow-sm rounded-4 mb-4">
                    <div class="table-responsive shadow p-1 ">
                        <table id="tablaPacientes" class="table table-sm table-hover align-middle mb-0"> <thead class="table-primary">
                                <tr>
                                    <th class="p-3">Cédula</th>
                                    <th class="p-3">Nombre</th>
                                    <th class="p-3">Apellido</th>
                                    <th class="p-3">Teléfono</th>
                                    <th class="p-3">Email</th>
                                    <th class="p-3">F. Nacimiento</th> 
                                    <th class="p-3">Género</th>
                                    <th class="p-3">País</th>
                                    <th class="p-3">Ciudad</th>
                                    <th class="p-3">Modificar</th>
                                    <th class="p-3">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id="tablapacientes" class="text-blue-900"> </tbody>
                        </table>
                    </div>
                </div>

                <nav aria-label="Paginación de pacientes (abajo)">
                    <ul class="pagination justify-content-center" id="paginacionpacientesBottom"></ul>
                </nav>

                <a href="img/manual.pdf" target="_blank" id="help-button-fab" class="btn rounded-circle shadow-lg"
                    data-bs-toggle="tooltip" data-bs-placement="left" title="Manual de Ayuda">
                    <i class="bi bi-question-circle-fill"></i>
                </a>
            </main>
        </div>
    </div>


    <div class="modal fade" id="registropacienteModal" tabindex="-1" aria-labelledby="registropacienteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registropacienteModalLabel">Incluir Nuevo Paciente</h5> <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">

                    <form id="formularioRegistropaciente" action="?pagina=pacientes" method="POST" novalidate>
                        <input type="hidden" name="accion" value="registrar"> 
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cedula" class="form-label">Cédula</label>
                                <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Ej: 12235439" maxlength="10">
                                <div class="invalid-feedback">La cédula debe contener entre 6 y 10 dígitos.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Juan" required>
                                <div class="invalid-feedback">El nombre es obligatorio y solo debe contener letras.</div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="apellido" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ej: Pérez" required>
                                <div class="invalid-feedback">El apellido es obligatorio y solo debe contener letras.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Ej: 04145113078" maxlength="12">
                                <div class="invalid-feedback">El teléfono debe contener entre 7 y 12 dígitos.</div>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label> <input type="email" class="form-control" id="email" name="email" placeholder="Ej: paciente@mail.com">
                            </div>
                            <div class="col-md-6">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label> <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="genero" class="form-label">Género</label> <select class="form-select" id="genero" name="genero">
                                    <option value="">Seleccionar</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="pais" class="form-label">País</label> <input type="text" class="form-control" id="pais" name="pais" placeholder="Ej: Venezuela">
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ciudad" class="form-label">Ciudad</label> <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ej: Caracas">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="guardara" value="guardara">Guardar Paciente</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modificarpacienteModal" tabindex="-1" aria-labelledby="modificarpacienteModalLabel" aria-hidden="true"> <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modificarpacienteModalLabel">Modificar Paciente</h5> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                
                <form id="formularioModificarpaciente" action="?pagina=pacientes" method="POST"> <div class="modal-body">
                        <input type="hidden" name="accion" value="modificar">
                        <input type="hidden" id="modificar_id" name="id_paciente">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cedulaM" class="form-label">Cédula</label>
                                <input type="text" class="form-control" id="cedulaM" name="cedula">
                                <div class="invalid-feedback">La cédula es obligatoria.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="nombreM" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombreM" name="nombre" required>
                                <div class="invalid-feedback">El nombre es obligatorio.</div>
                            </div>
                           
                        </div>

                        <div class="row mb-3">
                             <div class="col-md-6">
                                <label for="apellidoM" class="form-label">Apellido</label>
                                <input type="text" class="form-control" id="apellidoM" name="apellido" required>
                                <div class="invalid-feedback">El apellido es obligatorio.</div>
                            </div>
                            <div class="col-md-6">
                                <label for="telefonoM" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefonoM" name="telefono">
                                <div class="invalid-feedback">El teléfono es obligatorio.</div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="emailM" class="form-label">Email</label>
                                <input type="email" class="form-control" id="emailM" name="email">

                            </div>
                            <div class="col-md-6">
                                <label for="fecha_nacimientoM" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimientoM" name="fecha_nacimiento">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="generoM" class="form-label">Género</label> <select class="form-select" id="generoM" name="genero">
                                    <option value="">Seleccionar</option>
                                    <option value="masculino">Masculino</option>
                                    <option value="femenino">Femenino</option>
                                    <option value="otro">Otro</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="paisM" class="form-label">País</label> <input type="text" class="form-control" id="paisM" name="pais">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="ciudadM" class="form-label">Ciudad</label> <input type="text" class="form-control" id="ciudadM" name="ciudad">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" id="modificar" class="btn btn-primary">Guardar Cambios</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="js/Jquery.min.js"></script>
    <script src="js/bootstrap.bundle.js"></script>
    <script src="js/datatables.min.js"></script>
    <script src="js/data.js"></script>
    <script src="js/formUtilss.js"></script>
    <script src="js/pacientes.js"></script> 
    <script src="js/main.js"></script>


</body>

</html>