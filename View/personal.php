<!DOCTYPE html>
<html lang="es">
<?php require_once('menu/menu.php'); ?>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal</title>
    <?php require_once("menu/head.php"); ?>
    <?php require_once("menu/menu.php"); ?>
    <link rel="stylesheet" href="css/pacientess.css">

</head>

<body class="bg-light font-primary text-dark">
    <div class="container-fluid">
        <div class="row">
            <!-- Menú lateral -->
            <?php require_once('menu/menu.php'); ?>
            <main role="main" class="col-md-3 col-md-6 col-md-9 col-md-12 ms-sm-auto col-lg-10 px-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4 ms-4 me-2">
                    <h2 class="fw-bold" style="color:var(--color5);">Personal</h2>
                    <div class=" d-flex align-items-center gap-3">
                        <a href='?pagina=profile' title="Mi Perfil" class="profile-icon-link d-none d-md-flex align-items-center justify-content-center">
                            <i class="bi bi-person" style="font-size: 32px; color: var(--color5);"></i>
                        </a>
                        <button type="button" class="btn btn-primary  d-flex align-items-center" data-bs-toggle="modal"
                            data-bs-target="#modalRegistro">
                            <i class="bi bi-plus-circle"></i>
                            <span class="d-none d-md-inline ms-2">Incluir Personal</span>
                        </button>

                      

                    </div>

                </div>
                <div class="table-responsive">
                    <table id="tablaPersonal" class=" table-responsive table table-hover table table-striped  align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="p-3">No.</th>
                                <th class="p-3">Cedula</th>
                                <th class="p-3">Nombre</th>
                                <th class="p-3">Apellido </th>
                                <th class="p-3">Telefono </th>
                                <th class="p-3">Dirección</th>
                                <th class="p-3">Rol</th>
                                <th class="p-3 d-none">Contraseña</th>
                                <th class="p-3">Modificar</th>
                                <th class="p-3">Eliminar</th>
                            </tr>
                        </thead>
                        <tbody id="tablaBody" class="text-blue-900">
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


        </main>
    </div>
    </div>


    </form>
    </div>
    </div>
    </div>
    </div>

    <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modalModificarLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalModificarLabel">Modificar Personal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form id="formularioModificarpaciente" action="" method="POST">
                <div class="modal-body">
                    <input type="text" id="accion" name="accion" hidden>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombreM" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombreM" name="nombre" required>
                            <div class="invalid-feedback">El nombre es obligatorio y solo debe contener letras.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="apellidoM" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellidoM" name="apellido" required>
                            <div class="invalid-feedback">El apellido es obligatorio y solo debe contener letras.</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cedulaM" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedulaM" name="cedula">
                            <div class="invalid-feedback">La cédula debe contener entre 6 y 10 dígitos.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="telefonoM" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefonoM" name="telefono">
                            <div class="invalid-feedback">El teléfono debe contener entre 7 y 12 dígitos.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="direccionM" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccionM" name="direccion">
                            <div class="invalid-feedback">La dirección es obligatoria.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="rolM" class="form-label">Rol</label>
                            <select class="form-select" id="rolM" name="rol">
                                <option value="">Seleccionar</option>
                                <option value="Secretaria">Secretaria</option>
                                <option value="Doctor">Doctor</option>
                            </select>
                            <div class="invalid-feedback">Debe seleccionar un Rol.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="passwordM" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="passwordM" name="password">
                            <div class="invalid-feedback">La contraseña es obligatoria para modificar.</div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="modificar" class="btn btn-primary">Modificar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="incluirPacienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="incluirPacienteModalLabel">Incluir Nuevo Personal</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">

                <form id="registro" action="" method="POST" novalidate> <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Juan" required>
                            <div class="invalid-feedback">El nombre es obligatorio y solo debe contener letras.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="apellido" class="form-label">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" placeholder="Ej: Pérez" required>
                            <div class="invalid-feedback">El apellido es obligatorio y solo debe contener letras.</div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="cedula" class="form-label">Cédula</label>
                            <input type="text" class="form-control" id="cedula" name="cedula" placeholder="Ej: 12235439" maxlength="10">
                            <div class="invalid-feedback">La cédula debe contener entre 6 y 10 dígitos.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Ej: 04145113078" maxlength="12">
                            <div class="invalid-feedback">El teléfono debe contener entre 7 y 12 dígitos.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Ej: Calle 123, Ciudad, País">
                            <div class="invalid-feedback">La dirección es obligatoria.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="rol" class="form-label">Rol</label>
                            <select class="form-select" id="rol" name="rol">
                                <option value="">Seleccionar</option>
                                <option value="Secretaria">Secretaria</option>
                                <option value="Doctor">Doctor</option>
                            </select>
                            <div class="invalid-feedback">Debe seleccionar un Rol.</div>
                        </div>
                        <div class="col-md-6">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Ingrese una contraseña" required>
                            <div class="invalid-feedback">La contraseña es obligatoria.</div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="guardara" value="guardara">Guardar Personal</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                </form>

            </div>
        </div>
    </div>
</div>
    
    
    

    <script src="js/Jquery.min.js"></script> 

<script src="js/bootstrap.bundle.js"></script>


<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<script src="js/formUtilss.js"></script>

<script src="js/Personall.js"></script>
<script src="js/main.js"></script>


</body>

</html>