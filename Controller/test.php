<?php

use Yahir\Compo\Test as TestModelo;

ob_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$model = new TestModelo();

// Manejar acciones AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax_action'])) {
    header('Content-Type: application/json');
    
    try {
        $response = [];
        
        switch ($_POST['ajax_action']) {
            case 'obtenerTests':
                $id_paciente = $_POST['id_paciente'] ?? '';
                if ($id_paciente === '' || $id_paciente === null) {
                    // Obtener todos los tests de todos los pacientes
                    $tests = $model->obtenerTodosLosTests();
                } else {
                    // Obtener solo los tests del paciente seleccionado
                    $tests = $model->obtenerTestsPorPaciente($id_paciente);
                }
                $response = ['success' => true, 'data' => $tests];
                break;
                
            case 'obtenerTest':
                $tipo = $_POST['tipo'] ?? '';
                $id = $_POST['id'] ?? 0;
                $test = $model->obtenerTest($tipo, $id);
                $response = ['success' => true, 'data' => $test];
                break;
                
            case 'obtenerDetallesTest':
                $tipo = $_POST['tipo'] ?? '';
                $id = $_POST['id'] ?? 0;
                $test = $model->obtenerTestConPaciente($tipo, $id);
                
                if ($test) {
                    $response = [
                        'success' => true,
                        'test' => $test,
                        'paciente' => $test['paciente']
                    ];
                    // Eliminar el paciente del array test para evitar duplicados
                    unset($test['paciente']);
                } else {
                    $response = ['success' => false, 'message' => 'Test no encontrado'];
                }
                break;
                
            case 'obtenerPacientes':
                $pacientes = $model->obtenerPacientesParaSelect();
                $response = ['success' => true, 'data' => $pacientes];
                break;
                
            case 'guardar_test':
                $id_paciente = (int)$_POST['id_paciente'] ?? 0;
                $tipo_test = $_POST['tipo_test'] ?? '';

                switch ($tipo_test) {
                    case 'poms':
                        $deporte = $_POST['deporte'] ?? '';
                        $respuestas = [];
                        for ($i = 1; $i <= 65; $i++) {
                            $respuestas[$i] = (int)($_POST['pregunta_'.$i] ?? 0);
                        }
                        $resultado = $model->crearTestPoms($id_paciente, $deporte, $respuestas);
                        $response = ($resultado['resultado'] == 'ok') ?
                            ['success' => true, 'message' => $resultado['mensaje']] :
                            ['success' => false, 'message' => $resultado['mensaje']];
                        break;

                    case 'confianza':
                        $respuestas = [];
                        for ($i = 1; $i <= 10; $i++) {
                            $respuestas[$i] = (int)($_POST['pregunta_'.$i] ?? 1);
                        }
                        $resultado = $model->crearTestConfianza($id_paciente, $respuestas);
                        $response = ($resultado['resultado'] == 'ok') ?
                            ['success' => true, 'message' => $resultado['mensaje']] :
                            ['success' => false, 'message' => $resultado['mensaje']];
                        break;

                    case 'importancia':
                        $parte1 = [];
                        $parte2 = [];
                        for ($i = 1; $i <= 17; $i++) {
                            $parte1[$i] = (int)($_POST['parte1_pregunta_'.$i] ?? 1);
                        }
                        for ($i = 18; $i <= 34; $i++) {
                            $parte2[$i] = (int)($_POST['parte2_pregunta_'.$i] ?? 1);
                        }
                        $resultado = $model->crearTestImportancia($id_paciente, $parte1, $parte2);
                        $response = ($resultado['resultado'] == 'ok') ?
                            ['success' => true, 'message' => $resultado['mensaje']] :
                            ['success' => false, 'message' => $resultado['mensaje']];
                        break;

                    default:
                        $response = ['success' => false, 'message' => 'Tipo de test no válido'];
                }

                
                break;

            case 'actualizar_test':
                $id = (int)$_POST['id_test'] ?? 0;
                $tipo_test = $_POST['tipo_test'] ?? '';
                $datos = ['fecha' => date('Y-m-d')];

                switch ($tipo_test) {
                    case 'poms':
                        $datos['deporte'] = $_POST['deporte'] ?? '';
                        $respuestas = [];
                        for ($i = 1; $i <= 65; $i++) {
                            $respuestas[$i] = (int)($_POST['pregunta_'.$i] ?? 0);
                        }
                        $datos['respuestas'] = $respuestas;
                        break;

                    case 'confianza':
                        $respuestas = [];
                        for ($i = 1; $i <= 10; $i++) {
                            $respuestas[$i] = (int)($_POST['pregunta_'.$i] ?? 1);
                        }
                        $datos['respuestas'] = $respuestas;
                        break;

                    case 'importancia':
                        $parte1 = [];
                        $parte2 = [];
                        for ($i = 1; $i <= 17; $i++) {
                            $parte1[$i] = (int)($_POST['parte1_pregunta_'.$i] ?? 1);
                        }
                        for ($i = 18; $i <= 34; $i++) {
                            $parte2[$i] = (int)($_POST['parte2_pregunta_'.$i] ?? 1);
                        }
                        $datos['parte1'] = $parte1;
                        $datos['parte2'] = $parte2;
                        break;

                    default:
                        $response = ['success' => false, 'message' => 'Tipo de test no válido'];
                        echo json_encode($response);
                        exit;
                }

                $success = $model->actualizarTest($tipo_test, $id, $datos);
                $response = $success ?
                    ['success' => true, 'message' => 'Test actualizado correctamente'] :
                    ['success' => false, 'message' => 'Error al actualizar test'];
                break;
                
            case 'eliminarTest':
                $tipo = $_POST['tipo'] ?? '';
                $id = $_POST['id'] ?? 0;
                
                $success = $model->eliminarTest($tipo, $id);
                $response = $success ? 
                    ['success' => true, 'message' => 'Test eliminado correctamente'] : 
                    ['success' => false, 'message' => 'Error al eliminar test'];
                break;
                
            default:
                $response = ['success' => false, 'message' => 'Acción no válida'];
        }
        
        echo json_encode($response);
        exit;
        
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        exit;
    }
}

// Obtener lista de pacientes para mostrar en la vista
$pacientes = $model->obtenerPacientesParaSelect();

// Mostrar mensajes de estado
$status = $_GET['status'] ?? '';
$error = $_GET['error'] ?? '';

// Incluir la vista
require_once BASE_PATH . 'View/test.php';

ob_end_flush();
?>
