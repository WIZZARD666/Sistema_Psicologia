<!doctype html>
<html lang="es">
<head>
    <title>Bienvenido - Sistema Universidad</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <?php require_once("menu/head.php"); ?>
    <?php require_once("menu/menu.php"); ?>
    <link rel="stylesheet" href="css/main.css">
    
</head>
<body>
    
    <main role="main" class="col-md-9 ms-sm-auto col-lg-10 px-4 main-content">
        
        <div class="d-flex justify-content-between align-items-center mb-4 ms-4 me-2">
            <h2 class="fw-bold" style="color:var(--color5);">Inicio</h2>
            <div class="d-flex align-items-center gap-3">
                <a href='?pagina=profile' title="Mi Perfil" 
                   class="profile-icon-link d-none d-md-flex align-items-center justify-content-center p-2 rounded-circle hover:bg-light transition-colors">
                    <i class="bi bi-person" style="font-size: 32px; color: var(--color5);"></i>
                </a>
            </div>
        </div>
        
        <div id="contentWrapper">
            
            <div class="skeleton-container" id="skeletonLoader" style="display:block;">
                <div class="skeleton-emoji"></div>
                
                <div class="skeleton-title"></div>
                
                <div class="skeleton-role-container">
                    <div class="skeleton-role-icon"></div>
                    <div class="skeleton-role-text"></div>
                </div>
                
                <div class="skeleton-message"></div>
                
                <div class="skeleton-buttons">
                    <div class="skeleton-button"></div>
                    <div class="skeleton-button"></div>
                </div>
            </div>


            <div class="text-center" id="mainContent" style="display:none;">
                
                <div class="greeting-emoji" id="greeting-emoji">🧠</div>
                
                <div class="greeting-title" id="greeting-title">
                    <?php
                    // --- ZONA DE MODIFICACIÓN 1: Saludo personalizado con nombre y apellido ---
                    if (isset($_SESSION['datos_personal']) && is_array($_SESSION['datos_personal'])) {
                        $datos = $_SESSION['datos_personal'];
                        $nombre_completo = htmlspecialchars($datos['nombre'] . ' ' . $datos['apellido']);
                        
                        // Usamos color4 para resaltar el nombre
                        echo "¡Hola, <span style='color:var(--color4)'>" . $nombre_completo . "</span>!";
                    } else {
                        echo "¡Bienvenido!";
                    }
                    ?>
                </div>
                
                <div class="role-container">
                    <?php
                    // --- ZONA DE MODIFICACIÓN 2: Mensaje de bienvenida con el rol ---
                    if (isset($_SESSION['datos_personal']) && is_array($_SESSION['datos_personal'])) {
                        $rol = htmlspecialchars($_SESSION['datos_personal']['rol']);
                        
                        // Nuevo formato: Icono + Rol + Mensaje de saludo
                        echo "<i class='bi bi-person-badge role-icon fs-2'></i>";
                        echo "<span>Rol: <strong>$rol</strong>.</span>";
                        echo "<span>¡Nos alegra verte de nuevo!</span>";

                    } else {
                        // Mensaje por defecto si no hay datos de personal
                        echo "Accede a tu cuenta para ver tus datos de rol y opciones.";
                    }
                    ?>
                </div>
                
                <div class="motivational-message-box mb-4" id="motivational-message-container">
                    </div>

                <div class="quick-actions">
                    <button class="quick-action-btn" onclick="showMotivation()"><i class="bi bi-lightbulb"></i> Mensaje motivacional</button>
                    <button class="quick-action-btn" id="openGameBtn" onclick="openMiniGame()">
                        <i class="bi bi-controller"></i> Mini juego Psico
                    </button>
                </div>
            </div>
            
        </div>
        <div id="miniGameModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:9999; align-items:center; justify-content:center;">
            <div id="miniGameContent" style="background:#fff; border-radius:18px; max-width:350px; width:90%; padding:2rem 1.5rem; box-shadow:0 10px 30px rgba(0,0,0,0.25); position:relative; margin:auto;">
                </div>
        </div>
        
        <div class="main-footer mt-4">
            <span>© <?php echo date('Y'); ?> Sistema Psicología | @copyright</span>
        </div>

        <a href="img/manual.pdf" target="_blank"
            id="help-button-fab"
            class="btn rounded-circle shadow-lg"
            data-bs-toggle="tooltip" data-bs-placement="left"
            title="Manual de Ayuda"> <i class="bi bi-question-circle-fill"></i>
        </a>
    </main>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/Jquery.min.js"></script>
    
    <script src="js/main.js"></script>
</body>
</html>
