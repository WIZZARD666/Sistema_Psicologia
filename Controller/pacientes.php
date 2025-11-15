<?php
use Yahir\Compo\Pacientes as PacientesModelo;
ob_start();
// Inicia sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
// Incluye el modelo de pacientes


// Devuelve todos los pacientes
function listarpaciente(){
    $paciente = new PacientesModelo();
    return $paciente->listarpaciente();
}

// Devuelve un paciente por ID
function obtenerpaciente($id){
    $paciente = new PacientesModelo();
    return $paciente->obtenerpaciente($id);
}

// Crea un paciente y su ubicación
function crearpaciente($nombre, $apellido, $cedula, $telefono, $fecha_nacimiento, $genero, $ciudad, $pais, $email, $foto){
    $paciente = new PacientesModelo();
    $id_ubicacion = $paciente->crearUbicacion($ciudad, $pais);
    $paciente->setNombre($nombre);
    $paciente->setapellido($apellido);
    $paciente->setcedula($cedula);
    $paciente->settelefono($telefono); 
    $paciente->setfecha_nacimiento($fecha_nacimiento);
    $paciente->setgenero($genero);
    $paciente->setIdUbicacion($id_ubicacion);
    $paciente->setemail($email);
    $paciente->setFoto($foto);
    return $paciente->crearpaciente(); 
}

// Actualiza un paciente y su ubicación
function actualizarpaciente($id, $nombre, $apellido, $cedula, $telefono, $fecha_nacimiento, $genero, $ciudad, $pais, $email,$foto){
    $paciente = new PacientesModelo();
    $paciente->setId($id);
    $id_ubicacion = $paciente->obtenerIdUbicacionPorPaciente($id);
    $paciente->actualizarUbicacion($id_ubicacion, $ciudad, $pais);
    $paciente->setNombre($nombre);
    $paciente->setapellido($apellido);
    $paciente->setcedula($cedula);
    $paciente->settelefono($telefono); 
    $paciente->setfecha_nacimiento($fecha_nacimiento);
    $paciente->setgenero($genero);
    $paciente->setemail($email);
    $paciente->setFoto($foto);
    return $paciente->actualizarpaciente(); 
}

// Elimina un paciente (solo de la tabla paciente)
function eliminarpaciente($id_paciente){
    $paciente = new PacientesModelo();
    return $paciente->eliminarpaciente($id_paciente);
}

// --- PROCESA FORMULARIO DE CREAR PACIENTE ---
if (isset($_POST['guardara'])) {
    // Valida campos requeridos y crea paciente
    $nombre = $_POST['nombre'] ?? null;
    $apellido = $_POST['apellido'] ?? null;
    $cedula = $_POST['cedula'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $genero = $_POST['genero'] ?? null;
    $ciudad = $_POST['ciudad'] ?? null;
    $pais = $_POST['pais'] ?? null;
    $email = $_POST['email'] ?? null;
    $foto_nombre = null;

     if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fotoTmp = $_FILES['foto']['tmp_name'];
        $fotoOriginal = $_FILES['foto']['name'];
        $extension = strtolower(pathinfo($fotoOriginal, PATHINFO_EXTENSION));
        $foto_nombre = $cedula . '.' . $extension;
        $directorio = BASE_PATH . 'img/usuarios/';
        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }
        $destino = $directorio . $foto_nombre;
        move_uploaded_file($fotoTmp, $destino);
    }

    if (!empty($nombre) && !empty($apellido) && !empty($email) && !empty($cedula)) {
        crearpaciente($nombre, $apellido, $cedula, $telefono, $fecha_nacimiento, $genero, $ciudad, $pais, $email, $foto_nombre);
    }
}

// --- PROCESA FORMULARIO DE ACTUALIZAR PACIENTE ---
if (isset($_POST['actualizar_paciente_submit'])) {
    $id_paciente = $_POST['id_paciente'] ?? null;
    if ($id_paciente) {
        $foto_nombre = '';
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $fotoTmp = $_FILES['foto']['tmp_name'];
            $fotoOriginal = $_FILES['foto']['name'];
            $extension = strtolower(pathinfo($fotoOriginal, PATHINFO_EXTENSION));
            $foto_nombre = $_POST['cedula'] . '.' . $extension;
            $directorio = BASE_PATH . 'img/usuarios/';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0777, true);
            }
            $destino = $directorio . $foto_nombre;
            move_uploaded_file($fotoTmp, $destino);
        }

        actualizarpaciente(
            $id_paciente,
            $_POST['nombre'] ?? null,
            $_POST['apellido'] ?? null,
            $_POST['cedula'] ?? null,
            $_POST['telefono'] ?? null,
            $_POST['fecha_nacimiento'] ?? null,
            $_POST['genero'] ?? null,
            $_POST['ciudad'] ?? null,
            $_POST['pais'] ?? null,
            $_POST['email'] ?? null,
            null,
            $foto_nombre
        );
    }
}

// --- PROCESA PETICIÓN AJAX PARA ELIMINAR PACIENTE ---
if (isset($_POST['accion']) && $_POST['accion'] === 'eliminarpaciente' && isset($_POST['id_paciente'])) {
    echo json_encode([
        "mensaje" => "EXITO",
        "success" => eliminarpaciente($_POST['id_paciente'])
    ]);
    exit;
}

// --- RESPUESTA AJAX PARA LISTAR PACIENTES CON FILTRO ---
if (isset($_POST['ajax']) && $_POST['ajax'] == 1) {
    header('Content-Type: application/json');
    $filtro = strtolower($_POST['filtro'] ?? '');
    $todosLosPacientes = listarpaciente();
    $pacientesFiltrados = $todosLosPacientes;
    if (!empty($filtro)) {
        $pacientesFiltrados = array_filter($todosLosPacientes, function ($paciente) use ($filtro) {
            return (stripos($paciente['nombre'], $filtro) !== false) ||
                (stripos($paciente['apellido'], $filtro) !== false) ||
                (isset($paciente['cedula']) && stripos($paciente['cedula'], $filtro) !== false) ||
                (isset($paciente['telefono']) && stripos($paciente['telefono'], $filtro) !== false);
        });
    }
    echo json_encode(['pacientes' => array_values($pacientesFiltrados)]);
    exit;
}

// --- CARGA LA VISTA PRINCIPAL DE PACIENTES ---
$pacientes = listarpaciente();
require_once BASE_PATH . 'View/pacientes.php';
ob_end_flush();
?>
