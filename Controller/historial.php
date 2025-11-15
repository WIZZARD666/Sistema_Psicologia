<?php
use Yahir\Compo\Historial as HistorialModelo;
use Yahir\Compo\Pacientes as PacientesModelo;
ob_start();

// Inicia sesión si no está iniciada
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// --- Cargar pacientes para el select ---
$pacientes = [];
try {
    $pacienteObj = new PacientesModelo();
    $pacientes = $pacienteObj->listarpaciente(); // Debe devolver un array con id_paciente y nombre
} catch (Exception $e) {
    $pacientes = [];
}

if (is_file('./View/historial.php')) {

    if (!empty($_POST)) {
        $o = new HistorialModelo();
        $accion = $_POST['accion'];

        // ========== ELIMINAR ==========
        if ($accion === 'eliminar') {
            $o->set_id($_POST['id']);
            echo json_encode($o->eliminar());
            exit;
        }

        // ========== CONSULTAR ==========
        if ($accion === 'consultarPacientes') {
            echo json_encode($o->consultarPacientes());
            exit;
        }
        if ($accion === 'consultar') {
            $o->set_id($_POST['id']);
            echo json_encode($o->consultarHistorial());
            exit;
        }
        if ($accion === 'consultarDetalles') {
            $o->set_id($_POST['id']);
            echo json_encode($o->consultarDetalles());
            exit;
        }

        // ========== REGISTRAR / MODIFICAR ==========
        $o->set_id(!empty($_POST['id']) ? $_POST['id'] : '');
        $o->set_id_paciente($_POST['id_paciente'] ?? '');
        $o->set_sintomas($_POST['sintomas'] ?? '');
        $o->set_otrosintomas($_POST['otrosintomas'] ?? '');
        $o->set_convives($_POST['convives'] ?? '');
        $o->set_cambiar($_POST['cambiar'] ?? '');
        $o->set_conflicto($_POST['conflicto'] ?? '');
        $o->set_trabajar($_POST['trabajar'] ?? '');
        $o->set_alcohol($_POST['alcohol'] ?? '');
        $o->set_alcofrecuencia($_POST['alcofrecuencia'] ?? '');
        $o->set_fumas($_POST['fumas'] ?? '');
        $o->set_fumafrecuencia($_POST['fumafrecuencia'] ?? '');
        $o->set_consumir($_POST['consumir'] ?? '');
        $o->set_consufrecuencia($_POST['consufrecuencia'] ?? '');
        $o->set_rutina($_POST['rutina'] ?? '');
        $o->set_acudir($_POST['acudir'] ?? '');
        $o->set_tratamiento($_POST['tratamiento'] ?? '');
        $o->set_finalizar($_POST['finalizar'] ?? '');
        $o->set_significativo($_POST['significativo'] ?? '');
        $o->set_PersonaSigni($_POST['PersonaSigni'] ?? '');
        $o->set_PodriaAyudar($_POST['PodriaAyudar'] ?? '');
        $o->set_ConseguirTerapia($_POST['ConseguirTerapia'] ?? '');
        $o->set_compromiso($_POST['compromiso'] ?? '');
        $o->set_TiempoDurara($_POST['TiempoDurara'] ?? '');
        $o->set_considerar($_POST['considerar'] ?? '');

        if ($accion === 'registrar') {
            $o->set_id('');
            echo json_encode($o->registrar());
            exit;
        }

        if ($accion === 'modificar') {
            echo json_encode($o->modificar());
            exit;
        }
    }

    require_once('./View/historial.php');
} else {
    echo 'No encontrado';
}

ob_end_flush();
