<?php
require_once(__DIR__ . '/../vendor/autoload.php');
use Yahir\Compo\Reportes as ReportesModelo;

// Si $pagina no viene definido (por acceso directo al controlador),
// le damos un valor por defecto
if (!isset($pagina)) {
    $pagina = "reportes";
}

//  Si llega el formulario (POST), generamos el PDF directamente
if (!empty($_POST)) {
    $accion = $_POST['accion'] ?? '';
    $idPaciente = $_POST['id_paciente'] ?? '';
    $mes = $_POST['mes'] ?? '';

    if ($accion && $idPaciente && $mes) {
        $o = new ReportesModelo();
        $o->set_id_paciente($idPaciente);
        $o->set_mes($mes);

        if ($accion === 'citas') {
            $o->reportesCitas();
            exit;
        } elseif ($accion === 'tests') {
            $o->reportesTests();
            exit;
        } else {
            echo "⚠️ Acción desconocida.";
            exit;
        }
    } else {
        echo "⚠️ Faltan datos obligatorios.";
        exit;
    }
}

//  Si se está accediendo desde index.php (para mostrar la vista)
if (is_file("View/" . $pagina . ".php")) {
    require_once("View/" . $pagina . ".php");
} else {
    echo "página en construcción";
}


