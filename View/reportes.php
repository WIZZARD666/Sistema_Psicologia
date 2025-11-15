<!doctype html>
<html lang="es">

<head>
    <title>Reportes</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php require_once("menu/head.php"); ?>
    <?php require_once("menu/menu.php"); ?>
    <link rel="stylesheet" href="css/reportes.css"> 

</head>

<body>
    <main role="main" class="col-md-3 col-md-6 col-md-9 col-md-12 ms-sm-auto col-lg-10 px-4 main-content">
        <div class="d-flex justify-content-between align-items-center mb-4 ms-4 me-2">
            <h2 class="fw-bold" style="color:var(--color5);">Gestión De Reportes</h2>
        </div>

        <div id="contentWrapper">
            
            <div id="mainContent" style="display: none;">
                <form method="post" id="f" autocomplete="off" target="_blank" action="">
                    <input type="hidden" name="accion" id="accion">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="id_paciente" class="form-label">Selecciona Paciente</label>
                            <select class="form-select" id="id_paciente" name="id_paciente">
                                <option value="">-- Seleccione un paciente --</option>
                                <?php
                                use Yahir\Compo\Pacientes as PacientesModelo;
                                $pacienteModel = new PacientesModelo();
                                $pacientes = $pacienteModel->listarpaciente();
                                foreach ($pacientes as $p): ?>
                                    <option value="<?php echo htmlspecialchars($p['id_paciente']); ?>">
                                        <?php echo htmlspecialchars($p['nombre'] . ' ' . $p['apellido']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="mes" class="form-label">Seleccionar Mes</label>
                            <input type="month" id="mes" name="mes" class="form-control" value="<?php echo date('Y-m'); ?>">
                            <div id="errorMes" class="text-danger" style="display: none;">Seleccione un mes</div>
                        </div>
                    </div>

                    <div class="row mb-3 text-center">
                        <h4 class="fw-bold" style="color:var(--color5);">Seleccionar Tipo de Reporte</h4>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="card text-center seleccionable" data-value="citas" style="cursor: pointer; border: 2px solid transparent;">
                                <div class="card-body">
                                    <i class="bi bi-calendar-check" style="font-size: 2rem; color: var(--color5);"></i>
                                    <h5 class="card-title mt-2">Citas</h5>
                                    <p class="text-muted">Detalles de citas del paciente.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card text-center seleccionable" data-value="tests" style="cursor: pointer; border: 2px solid transparent;">
                                <div class="card-body">
                                    <i class="bi bi-clipboard-check" style="font-size: 2rem; color: var(--color5);"></i>
                                    <h5 class="card-title mt-2">Tests</h5>
                                    <p class="text-muted">Historial de tests realizados al paciente.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-primary btn-sm" id="proceso">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i>
                            Generar PDF
                        </button>
                    </div>
                </form>
            </div>
            </div>

        <a href="img/manual.pdf" target="_blank"

            id="help-button-fab"
            class="btn rounded-circle shadow-lg"
            data-bs-toggle="tooltip" data-bs-placement="left"
            title="Manual de Ayuda"> <i class="bi bi-question-circle-fill"></i>
        </a>
        </main>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script> 
    <script src="js/reportess.js"></script>
</body>
</html>

