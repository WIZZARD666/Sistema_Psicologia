<!DOCTYPE html>
<html lang="es">
<?php require_once('menu/menu.php'); ?>

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Pacientes</title>
    <?php require_once("menu/head.php"); ?>
    <?php require_once("menu/menu.php"); ?>
    <link rel="stylesheet" href="css/historia.css">
</head>

<body class="bg-light font-primary text-dark">
    <div class="container-fluid">
        <div class="row">
            <!-- Menú lateral -->
            <?php require_once('menu/menu.php'); ?>
            <main role="main" class="col-md-3 col-md-6 col-md-9 col-md-12 ms-sm-auto col-lg-10 px-4 main-content">
                <div class="d-flex justify-content-between align-items-center mb-4 ms-4 me-2">
                    <h2 class="fw-bold" style="color:var(--color5);">Historial de Pacientes</h2>
                    <div class="d-flex align-items-center gap-3">
                        <a href='?pagina=profile' title="Mi Perfil" class="profile-icon-link d-none d-md-flex align-items-center justify-content-center">
                            <i class="bi bi-person" style="font-size: 32px; color: var(--color5);"></i>
                        </a>
                        <button type="button" class="btn btn-primary d-flex align-items-center" data-bs-toggle="modal"
                            data-bs-target="#modalRegistro">
                            <i class="bi bi-plus-circle "></i>
                            <span class="d-none d-md-inline ms-2">Incluir Historia</span>
                        </button>

                    </div>

                </div>
                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input type="text" id="buscarpaciente" class="form-control" placeholder="Buscar pacientes...">
                    </div>
                </div>
                <div class="cont row" id="pacientesContainer"></div>
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

    <!-- Modal Registro -->
    <div class="modal fade" id="modalRegistro" tabindex="-1" aria-labelledby="incluirPacienteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="incluirPacienteModalLabel">Incluir Nuevo Paciente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <?php if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger"><?php echo $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></div>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['success'])): ?>
                        <div class="alert alert-success"><?php echo $_SESSION['success'];
                                                            unset($_SESSION['success']); ?></div>
                    <?php endif; ?>

                    <form id="registroHistorial" action="" method="POST">
                        <header class="mb-4 text-center">
                            <h2>Historia de Vida</h2>
                        </header>

                        <section class="mb-3">
                            <h2 class="h5">Datos Personales</h2>
                            <div class="mb-3">
                                <label for="id_paciente" class="form-label">Seleccionar Paciente:</label>
                                <select class="form-control" id="id_paciente" name="id_paciente" required>
                                    <option value="" selected hidden>Seleccione un paciente</option>
                                    <?php foreach ($pacientes as $p): ?>
                                        <option value="<?php echo $p['id_paciente']; ?>">
                                            <?php echo htmlspecialchars($p['cedula'] . ' - ' . $p['nombre'] . ' ' . $p['apellido']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </section>

                        <div class="alert alert-info" role="alert">
                            <b>A continuación, rellena aquellos apartados/preguntas que consideres necesarios para el tratamiento</b>
                        </div>

                        <section class="mb-3">
                            <h3 class="h5">Análisis funcional</h3>
                            <p><b>Del siguiente listado, ¿Qué sensación presentas actualmente?:</b></p>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="tension" name="sintoma[]" value="Tensión">
                                        <label class="form-check-label" for="tension">Tensión</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="taquicardia" name="sintoma[]" value="Taquicardia">
                                        <label class="form-check-label" for="taquicardia">Taquicardia</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="fracaso" name="sintoma[]" value="Fracaso">
                                        <label class="form-check-label" for="fracaso">Fracaso</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="presion_pecho" name="sintoma[]" value="Presión en el pecho">
                                        <label class="form-check-label" for="presion_pecho">Presión en el pecho</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="ansiedad" name="sintoma[]" value="Ansiedad">
                                        <label class="form-check-label" for="ansiedad">Ansiedad</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="presion" name="sintoma[]" value="Presión">
                                        <label class="form-check-label" for="presion">Presión</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="celos" name="sintoma[]" value="Celos">
                                        <label class="form-check-label" for="celos">Celos</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="problemas_pareja" name="sintoma[]" value="Problemas de pareja">
                                        <label class="form-check-label" for="problemas_pareja">Problemas de pareja</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="flojedad" name="sintoma[]" value="Flojedad">
                                        <label class="form-check-label" for="flojedad">Flojedad</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="irritabilidad" name="sintoma[]" value="Irritabilidad">
                                        <label class="form-check-label" for="irritabilidad">Irritabilidad</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="miedo" name="sintoma[]" value="Miedo">
                                        <label class="form-check-label" for="miedo">Miedo</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="dificultades_sexuales" name="sintoma[]" value="Dificultades sexuales">
                                        <label class="form-check-label" for="dificultades_sexuales">Dificultades sexuales</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="sudor" name="sintoma[]" value="Sudor">
                                        <label class="form-check-label" for="sudor">Sudor</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="culpa" name="sintoma[]" value="Culpa">
                                        <label class="form-check-label" for="culpa">Culpa</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="desconfianza" name="sintoma[]" value="Desconfianza">
                                        <label class="form-check-label" for="desconfianza">Desconfianza</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 mt-3">
                                <label for="otros_sintoma" class="form-label">Otros síntomas:</label>
                                <textarea class="form-control" id="otros_sintoma" name="otros_sintoma" rows="3"></textarea>
                            </div>
                        </section>

                        <section class="mb-3">
                            <h2 class="h5">Organismo</h2>

                            <div class="mb-3">
                                <label for="convivencia" class="form-label">¿Con quién convives actualmente?</label>
                                <textarea class="form-control" id="convivencia" name="convivencia" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="relacion_mejorar" class="form-label">¿Cambiarías/mejorarías tu relación con alguno de ellos? ¿Por qué?</label>
                                <textarea class="form-control" id="relacion_mejorar" name="relacion_mejorar" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="area_conflictiva" class="form-label">¿Destacarías alguna área conflictiva en tu relación con tu familia/pareja que quisieras trabajar en terapia?</label>
                                <textarea class="form-control" id="area_conflictiva" name="area_conflictiva" rows="3"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="trabajar" class="form-label">Trabajar</label>
                                <input type="text" name="trabajar" id="trabajar" class="form-control" maxlength="100">
                            </div>

                            <h3 class="h5">Hábitos y estilo de vida</h3>

                            <div class="mb-3">
                                <label class="form-label">¿Consumes alcohol?</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="alcohol_si" name="alcohol" value="si">
                                    <label class="form-check-label" for="alcohol_si">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="alcohol_no" name="alcohol" value="no" checked>
                                    <label class="form-check-label" for="alcohol_no">No</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="frecuencia_alcohol" class="form-label">¿Con qué frecuencia y cuánta cantidad?</label>
                                <textarea class="form-control" id="frecuencia_alcohol" name="frecuencia_alcohol" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">¿Fumas o vapeas?</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="fumar_si" name="fumar" value="si">
                                    <label class="form-check-label" for="fumar_si">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="fumar_no" name="fumar" value="no" checked>
                                    <label class="form-check-label" for="fumar_no">No</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="frecuencia_fumar" class="form-label">¿Con qué frecuencia y cuánta cantidad?</label>
                                <textarea class="form-control" id="frecuencia_fumar" name="frecuencia_fumar" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">¿Consumes algún otro tipo de sustancia?</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="sustancia_si" name="sustancia" value="si">
                                    <label class="form-check-label" for="sustancia_si">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="sustancia_no" name="sustancia" value="no" checked>
                                    <label class="form-check-label" for="sustancia_no">No</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="frecuencia_sustancia" class="form-label">Indica cuál y con qué frecuencia</label>
                                <textarea class="form-control" id="frecuencia_sustancia" name="frecuencia_sustancia" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="rutina_sueno" class="form-label">Explica brevemente tu rutina de sueño (tiempo, calidad y si haces siesta...)</label>
                                <textarea class="form-control" id="rutina_sueno" name="rutina_sueno" rows="3"></textarea>
                            </div>

                            <h3 class="h5">Tratamientos anteriores</h3>
                            <div class="mb-3">
                                <label class="form-label">¿Has acudido al psicólogo o psiquiatría anteriormente? ¿Qué tipo de tratamiento recibió?</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="no_acudido" name="acudido" value="no" checked>
                                    <label class="form-check-label" for="no_acudido">No</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="psicologo" name="acudido" value="psicólogo">
                                    <label class="form-check-label" for="psicologo">Psicólogo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="psiquiatra" name="acudido" value="psiquiatra">
                                    <label class="form-check-label" for="psiquiatra">Psiquiatra</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="otro_acudido" name="acudido" value="otro">
                                    <label class="form-check-label" for="otro_acudido">Otro</label>
                                </div>
                                <textarea class="form-control mt-2" id="tratamiento_recibido" name="tratamiento_recibido" rows="2" placeholder="Especifica el tratamiento recibido"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="finalizado_tratamiento" class="form-label">¿Finalizaste el tratamiento? En caso negativo, ¿por qué razón?</label>
                                <textarea class="form-control" id="finalizado_tratamiento" name="finalizado_tratamiento" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="significativo" class="form-label">Significativo</label>
                                <input type="text" name="significativo" id="significativo" class="form-control"
                                    maxlength="150">
                            </div>

                            <h3 class="h5">Preguntas relativas</h3>
                            <div class="mb-3">
                                <label for="personas_significativas" class="form-label">¿Cuáles son las personas más significativas de tu vida actualmente?</label>
                                <textarea class="form-control" id="personas_significativas" name="personas_significativas" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="ayuda_terapia" class="form-label">¿Cuál o cuáles crees que podrían ayudarte durante tu terapia?</label>
                                <textarea class="form-control" id="ayuda_terapia" name="ayuda_terapia" rows="3"></textarea>
                            </div>

                            <h3 class="h5">Motivación y compromiso</h3>
                            <div class="mb-3">
                                <label for="espera_terapia" class="form-label">¿Qué esperas conseguir cuando finalice la terapia?</label>
                                <textarea class="form-control" id="espera_terapia" name="espera_terapia" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="compromiso_terapia" class="form-label">Del 1 al 10 ¿Cuál es tu compromiso hacia la terapia?</label>
                                <input type="number" class="form-control" id="compromiso_terapia" name="compromiso_terapia" min="1" max="10">
                            </div>

                            <div class="mb-3">
                                <label for="duracion_terapia" class="form-label">¿Cuánto tiempo crees que durará la terapia?</label>
                                <textarea class="form-control" id="duracion_terapia" name="duracion_terapia" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="importante_reflejar" class="form-label">¿Hay algo que no haya aparecido aquí pero consideras importante reflejar para abordar de manera adecuada el tratamiento? En caso afirmativo, indícalo</label>
                                <textarea class="form-control" id="importante_reflejar" name="importante_reflejar" rows="3"></textarea>
                            </div>
                        </section>

                        <div class="modal-footer">
                            <input type="submit" value="Guardar Pacientes" id="guardar" name="guardar" class="btn btn-primary">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Modificar -->
    <div class="modal fade" id="modalModificar" tabindex="-1" aria-labelledby="modificarPacienteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modificarPacienteModalLabel">Detalles del Paciente</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body py-4">
                    <form action="" method="POST" id="modificarHistorial">
                        <input type="hidden" id="id_paciente_modificar" name="id_paciente_modificar">
                        <input type="hidden" id="id_historia_modificar" name="id_historia_modificar">

                        <section class="mb-3">
                            <h2 class="h5">Datos Personales</h2>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre_modificar" class="form-label">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre_modificar" name="nombre_modificar" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="apellido_modificar" class="form-label">Apellido:</label>
                                        <input type="text" class="form-control" id="apellido_modificar" name="apellido_modificar" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="cedula_modificar" class="form-label">Cédula:</label>
                                        <input type="text" class="form-control" id="cedula_modificar" name="cedula_modificar" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefono_modificar" class="form-label">Teléfono:</label>
                                        <input type="text" class="form-control" id="telefono_modificar" name="telefono_modificar" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email_modificar" class="form-label">Email:</label>
                                        <input type="email" class="form-control" id="email_modificar" name="email_modificar" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="fecha_nacimiento_modificar" class="form-label">Fecha Nacimiento:</label>
                                        <input type="date" class="form-control" id="fecha_nacimiento_modificar" name="fecha_nacimiento_modificar" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Género:</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="genero_modificar" id="masculino_modificar" value="masculino">
                                    <label class="form-check-label" for="masculino_modificar">Masculino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="genero_modificar" id="femenino_modificar" value="femenino">
                                    <label class="form-check-label" for="femenino_modificar">Femenino</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="genero_modificar" id="otro_modificar" value="otro">
                                    <label class="form-check-label" for="otro_modificar">Otro</label>
                                </div>
                            </div>
                        </section>

                        <div class="alert alert-info" role="alert">
                            <b>A continuación, rellena aquellos apartados/preguntas que consideres necesarios para el tratamiento</b>
                        </div>

                        <section class="mb-3">
                            <h3 class="h5">Análisis funcional</h3>
                            <p><b>Del siguiente listado, ¿Qué sensación presentas actualmente?:</b></p>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="tension_modificar" name="sintoma_modificar[]" value="Tensión">
                                        <label class="form-check-label" for="tension_modificar">Tensión</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="taquicardia_modificar" name="sintoma_modificar[]" value="Taquicardia">
                                        <label class="form-check-label" for="taquicardia_modificar">Taquicardia</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="fracaso_modificar" name="sintoma_modificar[]" value="Fracaso">
                                        <label class="form-check-label" for="fracaso_modificar">Fracaso</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="presion_pecho_modificar" name="sintoma_modificar[]" value="Presión en el pecho">
                                        <label class="form-check-label" for="presion_pecho_modificar">Presión en el pecho</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="ansiedad_modificar" name="sintoma_modificar[]" value="Ansiedad">
                                        <label class="form-check-label" for="ansiedad_modificar">Ansiedad</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="presion_modificar" name="sintoma_modificar[]" value="Presión">
                                        <label class="form-check-label" for="presion_modificar">Presión</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="celos_modificar" name="sintoma_modificar[]" value="Celos">
                                        <label class="form-check-label" for="celos_modificar">Celos</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="problemas_pareja_modificar" name="sintoma_modificar[]" value="Problemas de pareja">
                                        <label class="form-check-label" for="problemas_pareja_modificar">Problemas de pareja</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="flojedad_modificar" name="sintoma_modificar[]" value="Flojedad">
                                        <label class="form-check-label" for="flojedad_modificar">Flojedad</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="irritabilidad_modificar" name="sintoma_modificar[]" value="Irritabilidad">
                                        <label class="form-check-label" for="irritabilidad_modificar">Irritabilidad</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="miedo_modificar" name="sintoma_modificar[]" value="Miedo">
                                        <label class="form-check-label" for="miedo_modificar">Miedo</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="dificultades_sexuales_modificar" name="sintoma_modificar[]" value="Dificultades sexuales">
                                        <label class="form-check-label" for="dificultades_sexuales_modificar">Dificultades sexuales</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="sudor_modificar" name="sintoma_modificar[]" value="Sudor">
                                        <label class="form-check-label" for="sudor_modificar">Sudor</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="culpa_modificar" name="sintoma_modificar[]" value="Culpa">
                                        <label class="form-check-label" for="culpa_modificar">Culpa</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="desconfianza_modificar" name="sintoma_modificar[]" value="Desconfianza">
                                        <label class="form-check-label" for="desconfianza_modificar">Desconfianza</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 mt-3">
                                <label for="otros_sintoma_modificar" class="form-label">Otros síntomas:</label>
                                <textarea class="form-control" id="otros_sintoma_modificar" name="otros_sintoma_modificar" rows="3"></textarea>
                            </div>
                        </section>

                        <section class="mb-3">
                            <h2 class="h5">Organismo</h2>

                            <div class="mb-3">
                                <label for="convivencia_modificar" class="form-label">¿Con quién convives actualmente?</label>
                                <textarea class="form-control" id="convivencia_modificar" name="convivencia_modificar" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="relacion_mejorar_modificar" class="form-label">¿Cambiarías/mejorarías tu relación con alguno de ellos? ¿Por qué?</label>
                                <textarea class="form-control" id="relacion_mejorar_modificar" name="relacion_mejorar_modificar" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="area_conflictiva_modificar" class="form-label">¿Destacarías alguna área conflictiva en tu relación con tu familia/pareja que quisieras trabajar en terapia?</label>
                                <textarea class="form-control" id="area_conflictiva_modificar" name="area_conflictiva_modificar" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="trabajar" class="form-label">Trabajar</label>
                                <input type="text" name="trabajar" id="trabajarM" class="form-control" maxlength="100">
                            </div>

                            <h3 class="h5">Hábitos y estilo de vida</h3>

                            <div class="mb-3">
                                <label class="form-label">¿Consumes alcohol?</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="alcohol_si_modificar" name="alcohol_modificar" value="si">
                                    <label class="form-check-label" for="alcohol_si_modificar">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="alcohol_no_modificar" name="alcohol_modificar" value="no" checked>
                                    <label class="form-check-label" for="alcohol_no_modificar">No</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="frecuencia_alcohol_modificar" class="form-label">¿Con qué frecuencia y cuánta cantidad?</label>
                                <textarea class="form-control" id="frecuencia_alcohol_modificar" name="frecuencia_alcohol_modificar" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">¿Fumas o vapeas?</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="fumar_si_modificar" name="fumar_modificar" value="si">
                                    <label class="form-check-label" for="fumar_si_modificar">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="fumar_no_modificar" name="fumar_modificar" value="no" checked>
                                    <label class="form-check-label" for="fumar_no_modificar">No</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="frecuencia_fumar_modificar" class="form-label">¿Con qué frecuencia y cuánta cantidad?</label>
                                <textarea class="form-control" id="frecuencia_fumar_modificar" name="frecuencia_fumar_modificar" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">¿Consumes algún otro tipo de sustancia?</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="sustancia_si_modificar" name="sustancia_modificar" value="si">
                                    <label class="form-check-label" for="sustancia_si_modificar">Sí</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="sustancia_no_modificar" name="sustancia_modificar" value="no" checked>
                                    <label class="form-check-label" for="sustancia_no_modificar">No</label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="frecuencia_sustancia_modificar" class="form-label">Indica cuál y con qué frecuencia</label>
                                <textarea class="form-control" id="frecuencia_sustancia_modificar" name="frecuencia_sustancia_modificar" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="rutina_sueno_modificar" class="form-label">Explica brevemente tu rutina de sueño (tiempo, calidad y si haces siesta...)</label>
                                <textarea class="form-control" id="rutina_sueno_modificar" name="rutina_sueno_modificar" rows="3"></textarea>
                            </div>

                            <h3 class="h5">Tratamientos anteriores</h3>
                            <div class="mb-3">
                                <label class="form-label">¿Has acudido al psicólogo o psiquiatría anteriormente? ¿Qué tipo de tratamiento recibió?</label><br>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="no_acudido_modificar" name="acudido_modificar" value="no" checked>
                                    <label class="form-check-label" for="no_acudido_modificar">No</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="psicologo_modificar" name="acudido_modificar" value="psicólogo">
                                    <label class="form-check-label" for="psicologo_modificar">Psicólogo</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="psiquiatra_modificar" name="acudido_modificar" value="psiquiatra">
                                    <label class="form-check-label" for="psiquiatra_modificar">Psiquiatra</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input type="radio" class="form-check-input" id="otro_acudido_modificar" name="acudido_modificar" value="otro">
                                    <label class="form-check-label" for="otro_acudido_modificar">Otro</label>
                                </div>
                                <textarea class="form-control mt-2" id="tratamiento_recibido_modificar" name="tratamiento_recibido_modificar" rows="2" placeholder="Especifica el tratamiento recibido"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="finalizado_tratamiento_modificar" class="form-label">¿Finalizaste el tratamiento? En caso negativo, ¿por qué razón?</label>
                                <textarea class="form-control" id="finalizado_tratamiento_modificar" name="finalizado_tratamiento_modificar" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="significativo" class="form-label">Significativo</label>
                                <input type="text" name="significativo" id="significativoM" class="form-control"
                                    maxlength="150">
                            </div>

                            <h3 class="h5">Preguntas relativas</h3>
                            <div class="mb-3">
                                <label for="personas_significativas_modificar" class="form-label">¿Cuáles son las personas más significativas de tu vida actualmente?</label>
                                <textarea class="form-control" id="personas_significativas_modificar" name="personas_significativas_modificar" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="ayuda_terapia_modificar" class="form-label">¿Cuál o cuáles crees que podrían ayudarte durante tu terapia?</label>
                                <textarea class="form-control" id="ayuda_terapia_modificar" name="ayuda_terapia_modificar" rows="3"></textarea>
                            </div>

                            <h3 class="h5">Motivación y compromiso</h3>
                            <div class="mb-3">
                                <label for="espera_terapia_modificar" class="form-label">¿Qué esperas conseguir cuando finalice la terapia?</label>
                                <textarea class="form-control" id="espera_terapia_modificar" name="espera_terapia_modificar" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="compromiso_terapia_modificar" class="form-label">Del 1 al 10 ¿Cuál es tu compromiso hacia la terapia?</label>
                                <input type="number" class="form-control" id="compromiso_terapia_modificar" name="compromiso_terapia_modificar" min="1" max="10">
                            </div>

                            <div class="mb-3">
                                <label for="duracion_terapia_modificar" class="form-label">¿Cuánto tiempo crees que durará la terapia?</label>
                                <textarea class="form-control" id="duracion_terapia_modificar" name="duracion_terapia_modificar" rows="2"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="importante_reflejar_modificar" class="form-label">¿Hay algo que no haya aparecido aquí pero consideras importante reflejar para abordar de manera adecuada el tratamiento? En caso afirmativo, indícalo</label>
                                <textarea class="form-control" id="importante_reflejar_modificar" name="importante_reflejar_modificar" rows="3"></textarea>
                            </div>
                        </section>

                        <div class="modal-footer">
                            <input type="submit" value="Guardar Cambios" id="guardar_modificacion" name="guardar_modificacion" class="btn btn-primary">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        </div>

                        <div class="modal fade" id="verDetallesModal" tabindex="-1" role="dialog" aria-labelledby="verDetallesModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="verDetallesModalLabel">Detalles del Paciente</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form id="formularioVerDetalles">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="nombre_detalle">Nombre:</label>
                                                        <input type="text" class="form-control" id="nombre_detalle" name="nombre_detalle" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="apellido_detalle">Apellido:</label>
                                                        <input type="text" class="form-control" id="apellido_detalle" name="apellido_detalle" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="cedula_detalle">Cédula:</label>
                                                        <input type="text" class="form-control" id="cedula_detalle" name="cedula_detalle" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="telefono_detalle">Teléfono:</label>
                                                        <input type="text" class="form-control" id="telefono_detalle" name="telefono_detalle" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="email_detalle">Email:</label>
                                                        <input type="text" class="form-control" id="email_detalle" name="email_detalle" readonly>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="fecha_nacimiento_detalle">Fecha Nacimiento:</label>
                                                        <input type="text" class="form-control" id="fecha_nacimiento_detalle" name="fecha_nacimiento_detalle" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <hr>
                                            <div id="historial_detalles_extra">
                                                <!-- Aquí se mostrarán los datos del historial -->
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script src="js/bootstrap.bundle.js"></script>
                        <script src="js/Jquery.min.js"></script>
                        <script src="js/historia.js"></script>
                        <script src="js/main.js"></script>
                        <script>
                        </script>

</body>

</html>