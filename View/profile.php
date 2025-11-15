<?php
// Asegura que la sesión esté iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Validar la existencia de los datos del perfil en la sesión con el nombre 'datos_personal'
if (!isset($_SESSION['datos_personal'])) {
    // Redirige al login si no hay datos de sesión
    header('Location: ?pagina=login'); 
    exit;
}

// Obtener los datos del perfil de la sesión
// Asignamos a una variable local para facilitar su uso
$datos_perfil = $_SESSION['datos_personal'];

// Variables para un acceso más limpio y seguro (escapando HTML)
$nombre = htmlspecialchars($datos_perfil['nombre'] ?? '');
$apellido = htmlspecialchars($datos_perfil['apellido'] ?? '');
$direccion = htmlspecialchars($datos_perfil['direccion'] ?? 'N/A'); // Añadida Dirección
$cedula = htmlspecialchars($_SESSION['usu'] ?? 'N/A'); // Asumo que la cédula se guarda en $_SESSION['usu']
$telefono = htmlspecialchars($datos_perfil['telefono'] ?? 'N/A'); // Asumo que el teléfono se carga en 'datos_personal'
$rol = htmlspecialchars($datos_perfil['rol'] ?? 'N/A');
$nombre_completo = $nombre . ' ' . $apellido;

// Si el controlador dejó un mensaje de éxito/error, lo mostramos después de la recarga
$mensaje_servidor = $_SESSION['mensaje_servidor'] ?? null;
unset($_SESSION['mensaje_servidor']); // Limpiar la sesión inmediatamente
?>
<!doctype html>
<html lang="es">
<head>
    <title>Mi Perfil de Usuario</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php require_once("menu/head.php"); ?>
    <?php require_once("menu/menu.php"); ?>
    <link rel="stylesheet" href="css/profile.css">
    
    <!-- Incluye SweetAlert2 directamente en la vista para asegurar que funcione -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php require_once('menu/menu.php'); ?>
    
    <center>
        <div class="profile-main-content">
            <div class="profile-card">
                <div class="profile-avatar">
                    <i class="bi bi-person-circle fa-2x"></i> 
                </div>
                <h2 class="profile-title">
                    <?php echo $nombre_completo; ?>
                </h2>
                
                <hr>
                
                <div class="profile-data text-left">
                    
                    <label>Cédula:</label>
                    <div class="value mb-2"><?php echo $cedula; ?></div>
                    
                    <label>Teléfono:</label>
                    <div class="value mb-2"><?php echo $telefono; ?></div>

                    <label>Direccion:</label>
                    <div class="value mb-2"><?php echo $direccion; ?></div>
                    
                    <label>Rol:</label>
                    <div class="value mb-2"><?php echo $rol; ?></div>
                    
                </div>
                <div class="profile-actions">
                    <button type="button" class="btn btn-edit" data-bs-toggle="modal"
                        data-bs-target="#modalEditarPerfil">
                        <i class="bi bi-pencil"></i> Editar datos
                    </button>
                    <button type="button" class="btn btn-password" data-bs-toggle="modal"
                        data-bs-target="#modalCambiarContrasena">
                        <i class="bi bi-key"></i> Cambiar clave
                    </button>
                </div>
            </div>
        </div>
    </center>
    
    <!-- Modal Editar Perfil -->
    <div class="modal fade" id="modalEditarPerfil" tabindex="-1" aria-labelledby="modalEditarPerfilLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <!-- NOTA: La acción debe apuntar a tu controlador -->
            <form class="modal-content" method="POST" action="?pagina=profile"> 
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditarPerfilLabel">Editar Perfil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="accion" value="modificar_perfil"> 
                    <input type="hidden" name="cedula_actual" value="<?php echo $cedula; ?>"> 
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre_modificar" name="nombre_modificar"
                            value="<?php echo $nombre; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellido" class="form-label">Apellido</label>
                        <input type="text" class="form-control" id="apellido_modificar" name="apellido_modificar"
                            value="<?php echo $apellido; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono_modificar" name="telefono_modificar"
                            value="<?php echo $telefono; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <input type="text" class="form-control" id="direccion_modificar" name="direccion_modificar"
                            value="<?php echo htmlspecialchars($datos_perfil['direccion'] ?? ''); ?>" required>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-edit" id="modificar" name="modificar">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Cambiar Contraseña -->
    <div class="modal fade" id="modalCambiarContrasena" tabindex="-1" aria-labelledby="modalCambiarContrasenaLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <!-- NOTA: La acción debe apuntar a tu controlador -->
            <form class="modal-content" method="POST" action="?pagina=profile"> 
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCambiarContrasenaLabel">Cambiar Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="accion" value="cambiar_password"> 
                    <input type="hidden" name="cedula_clave" value="<?php echo $cedula; ?>"> 
                    
                    <div class="mb-3">
                        <label for="password_nueva" class="form-label">Nueva Contraseña</label>
                        <input type="password" class="form-control" id="password_nueva" name="password_nueva" required
                            placeholder="Mínimo 8 caracteres, 1 Mayús, 1 Minús, 1 Número.">
                        <small class="text-danger" style="display:none;"></small> <!-- Para errores JS -->
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmar" class="form-label">Confirmar Contraseña</label>
                        <input type="password" class="form-control" id="password_confirmar" name="password_confirmar" required>
                        <small class="text-danger" style="display:none;"></small> <!-- Para errores JS -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-password" name="cambiar_clave">Confirmar Cambio</button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Scripts al final del body -->
    <script src="js/Jquery.min.js"></script> 
    <script src="js/bootstrap.bundle.js"></script>
    
 
    <!-- Tu script de lógica de perfil -->
    <script src="js/profilel.js"></script>
</body>

</html>