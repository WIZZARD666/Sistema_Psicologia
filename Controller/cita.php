<?php

use Yahir\Compo\Cita as CitaModelo;

ob_start();

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__) . '/');
}
// ...resto del código...

require_once BASE_PATH . 'vendor/autoload.php';

function listarcita(){
    $cita = new CitaModelo();
    return $cita->listarcita();
}
function obtenercita($id_cita){
    $cita = new CitaModelo();
    return $cita->obtenercita($id_cita);
}
function crearcita($id_paciente, $title, $descripcion, $color, $textColor, $start, $end){
    $cita = new CitaModelo();
    $cita->setid_paciente($id_paciente); // Faltaba esta línea
    $cita->settitle($title);
    $cita->setdescripcion($descripcion);
    $cita->setcolor($color);
    $cita->settextColor($textColor);
    $cita->setstart($start);
    $cita->setend($end);
    // Retornar el resultado del modelo para que el controlador pueda actuar (mostrar alertas, etc.)
    return $cita->crearcita(); 
}
function actualizarcita($id_cita, $title, $descripcion, $color, $textColor, $start, $end){
    $cita = new CitaModelo();
    $cita->setid_cita($id_cita);
    $cita->settitle($title);
    $cita->setdescripcion($descripcion);
    $cita->setcolor($color);
    $cita->settextColor($textColor);
    $cita->setstart($start);
    $cita->setend($end);
    return $cita->actualizarcita();
}
function eliminarcita($id_cita){
    $cita = new CitaModelo();
    $cita->eliminarcita($id_cita);
}
// Para la vista: obtener todos los pacientes
// function obtenerPacientesParaSelect(){
//     $paciente = new pacienteModulo();
//     return $paciente->listarpaciente();
// }

if (isset($_POST['guardar_cita'])) {
    $resultado = crearcita(
        $_POST['id_paciente'],
        $_POST['title'],
        $_POST['descripcion'],
        $_POST['color'],
        $_POST['textColor'],
        $_POST['start'],
        $_POST['end']
    );
    // Devolver JSON para que el frontend (js/citas.js) muestre los mensajes con SweetAlert
    header('Content-Type: application/json; charset=utf-8');
    if (is_array($resultado) && isset($resultado['resultado'])) {
        echo json_encode($resultado);
    } else {
        echo json_encode(['resultado' => 'error', 'mensaje' => 'Respuesta inesperada del servidor.']);
    }
    exit;
}
if (isset($_POST['actualizar_cita'])) {
    $resultado = actualizarcita(
        $_POST['id'],
        $_POST['title'],
        $_POST['descripcion'],
        $_POST['color'],
        $_POST['textColor'],
        $_POST['start'],
        $_POST['end']
    );

    header('Content-Type: application/json; charset=utf-8');
    if (is_array($resultado) && isset($resultado['resultado'])) {
        echo json_encode($resultado);
    } else {
        echo json_encode(['resultado' => 'ok', 'mensaje' => 'Cita actualizada correctamente.']);
    }
    exit;
}

// ==================================================
// ELIMINAR CITA
// ==================================================
if (isset($_POST['eliminar_cita'])) {
    eliminarcita($_POST['id_cita']);
    exit;
}

// ==================================================
// LISTAR CITAS (AJAX para FullCalendar)
// ==================================================
if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
    $citas = listarcita();

    foreach ($citas as &$cita) {
        $cita['id'] = $cita['id_cita']; // FullCalendar usa 'id'
        $cita['start'] = str_replace(' ', 'T', $cita['start']);
        $cita['end']   = str_replace(' ', 'T', $cita['end']);
    }

    header('Content-Type: application/json');
    echo json_encode($citas);
    exit;
}

require_once BASE_PATH . 'View/cita.php';

ob_end_flush();
?>