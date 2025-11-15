<?php

// 1. INICIAR SESIÓN (NECESARIO)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2️⃣ Verificar autenticación
if (!isset($_SESSION['usu'])) {
    header("Location: ?pagina=login");
    exit();
}

// 3️⃣ Incluir el modelo Personal
if (!is_file("./Model/Personal.php")) {
    exit("Error: No se encontró el modelo Personal.");
}

require_once("./Model/Personal.php");
use Yahir\Compo\Personal;

// 4️⃣ Crear instancia del modelo y preparar variables
$personal_model = new Personal();
$cedula_usuario = $_SESSION['usu'];
$response = ['status' => 'error', 'message' => 'Acción no válida o no especificada.'];

// ====================================================
// 🔹 SECCIÓN DE PETICIONES AJAX (POST)
// ====================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    // ======================================
    // 🧩 MODIFICAR PERFIL
    // ======================================
    if ($accion === 'modificar_perfil') {
        $datos_actuales = $_SESSION['datos_personal'] ?? [];
        $rol_actual = $datos_actuales['rol'] ?? '';
        $clave_actual = ''; // placeholder para mantener campo seguro

        $personal_model->set_cedula($_POST['cedula_actual'] ?? '');
        $personal_model->set_nombre($_POST['nombre_modificar'] ?? '');
        $personal_model->set_apellido($_POST['apellido_modificar'] ?? '');
        $personal_model->set_telefono($_POST['telefono_modificar'] ?? '');
        $personal_model->set_direccion($_POST['direccion_modificar'] ?? '');
        $personal_model->set_rol($rol_actual);
        $personal_model->set_password($clave_actual);

        $resultado = $personal_model->modificar();

        if (isset($resultado['resultado']) && $resultado['resultado'] === 'modificar') {
            // Actualizar sesión con nuevos datos
            $datos_actualizados = $personal_model->obtener_perfil_completo($cedula_usuario);
            if ($datos_actualizados) {
                $_SESSION['datos_personal'] = $datos_actualizados;
            }

            $response = [
                'status' => 'success',
                'message' => 'Perfil actualizado correctamente.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => $resultado['mensaje'] ?? 'Error al actualizar el perfil.'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // ======================================
    // 🔑 CAMBIAR CONTRASEÑA
    // ======================================
    elseif ($accion === 'cambiar_password') {
        $password_nueva = $_POST['password_nueva'] ?? '';

        $personal_model->set_cedula($_POST['cedula_clave'] ?? '');
        $personal_model->set_password($password_nueva);

        $resultado = $personal_model->cambiar_password();

        if (isset($resultado['resultado']) && $resultado['resultado'] === 'cambiar_password') {
            $response = [
                'status' => 'success',
                'message' => 'Contraseña cambiada con éxito.'
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => $resultado['mensaje'] ?? 'Error al cambiar la contraseña.'
            ];
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }

    // ======================================
    // 🚫 Acción no reconocida
    // ======================================
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

// ====================================================
// 🔹 CARGA NORMAL DE PERFIL (GET)
// ====================================================

// Obtener los datos más recientes del usuario
$datos_perfil = $personal_model->obtener_perfil_completo($cedula_usuario);

if (!$datos_perfil) {
    header("Location: ?pagina=login");
    exit;
}

// Actualizar datos en sesión
$_SESSION['datos_personal'] = $datos_perfil;

// Variables limpias para la vista
$nombre = htmlspecialchars($datos_perfil['nombre'] ?? '');
$apellido = htmlspecialchars($datos_perfil['apellido'] ?? '');
$cedula = htmlspecialchars($datos_perfil['cedula'] ?? 'N/A');
$telefono = htmlspecialchars($datos_perfil['telefono'] ?? 'N/A');
$direccion = htmlspecialchars($datos_perfil['direccion'] ?? 'N/A');
$rol = htmlspecialchars($datos_perfil['rol'] ?? 'N/A');
$nombre_completo = trim("$nombre $apellido");

// ====================================================
// 🔹 CARGAR VISTA
// ====================================================
if (is_file("View/profile.php")) {
    require_once("View/profile.php");
} else {
    echo "page not found";
}