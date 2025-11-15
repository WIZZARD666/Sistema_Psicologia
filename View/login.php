<!doctype html>
<html lang="es">
<head>
    <title>Iniciar sesión</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
     <?php require_once("menu/head.php"); ?>
    <link rel="stylesheet" href="css/loginn.css">
</head>
<body>
    <main>
        <form action="" method="POST" id="f">
            <input type="hidden" name="accion" id="accion" value="" />
            <div class="login-container">
                <h2 class="text-center">Iniciar sesión</h2>
                <div class="mb-3">
                    <label for="cedula" class="form-label">Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" name="cedula" id="cedula" placeholder="Ingresa tu cédula">
                        <div><small id="scedula" class="text-danger"></small></div>
                    </div>



                </div>
                <div class="mb-2">
                    <label for="clave" class="form-label">Contraseña</label>
                    <span class="change-link" onclick="document.getElementById('clave').focus()">Cambiar</span>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" name="password" id="clave" placeholder="Ingresa tu contraseña">
                        <div><small id="sclave" class="text-danger"></small></div>
                        <span class="input-group-text" style="cursor:pointer" onclick="togglePassword()"><i class="bi bi-eye" id="eye"></i></span>
                    </div>
                </div>
                <button type="button" name="entrar" id="entrar" class="btn btn-yellow">Iniciar sesión</button>
                
            </div>
        </form>
    </main>
    <!-- Div oculto para mensajes desde el servidor (se mostrará vía modal) -->
    <div id="mensajes" style="display:none">
        <?php if(!empty($error)) echo $error; ?>
    </div>
    <!-- Modal para mensajes (oculto por defecto, replicando comportamiento de entrada.js) -->
    <div class="modal fade" id="mostrarmodal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mensaje</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="contenidodemodal"></div>
            </div>
        </div>
    </div>
    <!-- Dependencias necesarias para la validación y modal -->
    <script src="js/Jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/login.js"></script>

    <script>
        function togglePassword() {
            const input = document.getElementById('clave');
            const icon = document.getElementById('eye');
            if (!input) return;
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
