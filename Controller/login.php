<?php
// Aseguramos que la sesión esté iniciada ANTES de cualquier salida
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. INCLUSIÓN Y USAGE DE MODELOS
// Necesitamos ambos modelos. Asumimos que están en ./Model/Login.php y ./Model/Personal.php
if (!is_file("./Model/Login.php") || !is_file("./Model/Personal.php")) {
    exit("Error: No se encontró el modelo de Login y/o Personal.");
}

require_once("./Model/Login.php");
require_once("./Model/Personal.php"); // Necesario para obtener nombre y apellido

use Yahir\Compo\Login as Login;
use Yahir\Compo\Personal as PersonalModelo; // Usamos este modelo para obtener datos completos

// Variable para mensajes de error en la vista
$error = ''; 

// Asegura que la vista existe (la variable $pagina debe venir de index.php)
if(is_file("View/".$pagina.".php")){

    if(!empty($_POST)){
        
        if(isset($_POST['accion']) && $_POST['accion']=='entrar'){

            // Proteger índices POST y limpiar espacios en blanco (trim)
            $ced = trim($_POST['cedula'] ?? '');
            $pwd = trim($_POST['password'] ?? '');

            $o = new Login();
            $o->set_cedula($ced);
            $o->set_password($pwd); 
            
            // 2. Intentar autenticación (Modelo Login)
            $m = $o->existe(); 

            if($m['resultado']=='existe'){
                
                // --- LÓGICA CLAVE: BUSCAR NOMBRE Y APELLIDO ---
                $personalModel = new PersonalModelo();
                // Usamos la cédula ($m['usu']) para buscar nombre, apellido y rol
                $datos_personal = $personalModel->obtener_perfil_completo($m['usu']); 
                
                if ($datos_personal) {
                    
                    // Regenerar ID de sesión por seguridad después de login
                    session_regenerate_id(true); 

                    // 3. Crear la variable de sesión 'datos_personal'
                    // ESTO ES LO QUE PERMITE MOSTRAR EL NOMBRE Y APELLIDO
                    $_SESSION['datos_personal'] = [
                        'nombre' => $datos_personal['nombre'] ?? '',
                        'apellido' => $datos_personal['apellido'] ?? '',
                        'cedula' => $datos_personal['cedula'] ?? '',
                        'telefono' => $datos_personal['telefono'] ?? '',
                        'rol' => $datos_personal['rol'] ?? '' 
                    ];
                    
                    // Mantener las variables de sesión originales para compatibilidad
                    $nivel_rol = strtolower(trim($m['nivel'] ?? '')); 
                    
                    $_SESSION['nivel'] = $nivel_rol; // Rol (ej: doctor, secretaria)
                    $_SESSION['usu'] = $m['usu']; // Cedula
                    $_SESSION['id'] = $m['id'] ?? ""; // ID Personal/Cliente
                    
                    // Redirigir a la página principal.
                    header('Location: ?pagina=main');
                    die();
                } else {
                    // El usuario se autenticó, pero falló la carga de datos del perfil.
                    $error = 'Error crítico: No se pudieron cargar los datos de perfil después de la autenticación.';
                }
            }
            else
            {
                // El modelo devuelve 'mensaje' si hay un error de credenciales
                $error = $m['mensaje'] ?? 'Usuario o contraseña incorrectos';
                
                // Opcional: Log de depuración para fallos de login
                @file_put_contents(
                    __DIR__."/../tmp/login_debug.log", 
                    date('c')." - login fallido: ".json_encode(
                        array('cedula'=>$ced, 'error_msg' => $error, 'model'=>$m)
                    )."\n", 
                    FILE_APPEND
                );
            }
        }
    }
    
    // Si no es un POST de login, o si el login falló, cargar la vista
    require_once("View/".$pagina.".php"); 
}
else
{
    echo "Falta la vista";
}
?>
