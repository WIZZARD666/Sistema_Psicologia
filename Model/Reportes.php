<?php

namespace Yahir\Compo;

// Es crucial que el autoload se cargue primero para que las clases de Dompdf sean reconocidas.
require_once(__DIR__ . '/../vendor/autoload.php');

use Yahir\Compo\Conexion;
use PDO;
use Exception;
// Aseguramos que las clases Dompdf y Dompdf\Options estén correctamente importadas
use Dompdf\Dompdf;
use Dompdf\Options;

class Reportes extends Conexion {

    private $id_paciente;
    private $mes;
    private $pdo;

    public function __construct(){
        // Llamada a conectar para establecer la conexión a la base de datos
        Conexion::conectar();
        $this->pdo = Conexion::getConexion();
        // VITAL: Asegura que PDO lanza excepciones (Exception) en caso de errores de consulta.
        if ($this->pdo) {
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    // Setters
    function set_id_paciente($valor) {
        $this->id_paciente = $valor;
    }

    function set_mes($valor) {
        $this->mes = $valor;
    }

    // Getters
    function get_id_paciente() {
        return $this->id_paciente;
    }

    function get_mes() {
        return $this->mes;
    }

    
     // Genera un reporte PDF de las citas de un paciente en un mes específico.
    
    function reportesCitas() {
        // Usamos $this->pdo establecido en el constructor.
        $q = $this->pdo;

        // Validamos y separamos el año y el mes del valor en formato 'YYYY-MM'
        if (preg_match('/^(\d{4})-(\d{2})$/', $this->mes, $matches)) {
            $ano = $matches[1];
            $mes = $matches[2];
        } else {
            throw new Exception("Formato de mes inválido. Se espera 'YYYY-MM'.");
        }

        $nombrePaciente = "Paciente Desconocido";

        try {
            // 1. Obtener datos del paciente
            $resultado = $q->prepare("SELECT nombre, apellido FROM paciente WHERE id_paciente = :id_paciente");
            $resultado->bindValue(':id_paciente', $this->id_paciente);
            $resultado->execute();
            $pacienteData = $resultado->fetch(PDO::FETCH_ASSOC);
            if ($pacienteData) {
                // Sanear el nombre del paciente para la salida HTML/PDF
                $nombrePaciente = htmlspecialchars($pacienteData['nombre'] . " " . $pacienteData['apellido']);
            }
        } catch (Exception $e) {
            error_log("Error al buscar paciente para reporte de citas: " . $e->getMessage());
        }

        try {
            // 2. Obtener datos de las citas
            $resultado2 = $q->prepare("SELECT DATE(start) AS fecha_cita, TIME(start) AS hora_cita, title, descripcion 
                                         FROM cita 
                                         WHERE id_paciente = :id_paciente 
                                         AND YEAR(start) = :ano 
                                         AND MONTH(start) = :mes 
                                         ORDER BY start ASC");
            $resultado2->bindValue(':id_paciente', $this->id_paciente);
            $resultado2->bindValue(':ano', $ano);
            $resultado2->bindValue(':mes', $mes);
            $resultado2->execute();

            $fila = $resultado2->fetchAll(PDO::FETCH_ASSOC);

            // 3. Generación del HTML para el PDF
            $html = '
            <html>
            <head>
                <style>
                    /* Usamos DejaVu Sans para mejor soporte de caracteres especiales como acentos y ñ */
                    body { font-family: DejaVu Sans, Arial, sans-serif; margin: 20px; color: #333; }
                    .report-table {
                        width: 100%;
                        border-collapse: collapse; 
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                    }
                    .report-table thead th {
                        background-color: #75A5B8; 
                        color: white;
                        padding: 14px 15px;
                        text-align: center;
                        text-transform: uppercase;
                        font-size: 0.9em;
                        letter-spacing: 0.5px;
                        font-weight: 600;
                    }
                    .report-table tbody td {
                        padding: 12px 15px;
                        border-bottom: 1px solid #e0e0e0;
                        font-size: 14px;
                        text-align: center; 
                    }
                    h1 { color: #2c3e50; text-align: center; margin-bottom: 5px; }
                    .report-header { font-size: 14px; color: #555; text-align: center; margin-bottom: 25px;}
                </style>
            </head>
            <body>
                <h1>REPORTE DE CITAS</h1>
                <div class="report-header">
                    Paciente: <strong>' . $nombrePaciente . '</strong> | 
                    Periodo: <strong>' . $mes . ' / ' . $ano . '</strong>
                </div>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>FECHA</th>
                            <th>HORA</th>
                            <th>TÍTULO</th>
                            <th>DESCRIPCIÓN</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            if ($fila) {
                $i = 0;
                foreach ($fila as $f) {
                    $color = ($i % 2 == 0) ? '#ffffff' : '#f9f9f9';
                    $html .= "<tr style='background-color:" . $color . ";'>";
                    // Sanear la salida de datos de la base de datos
                    $html .= "<td>" . htmlspecialchars($f['fecha_cita']) . "</td>";
                    $html .= "<td>" . htmlspecialchars($f['hora_cita']) . "</td>";
                    $html .= "<td>" . htmlspecialchars($f['title']) . "</td>";
                    $html .= "<td>" . htmlspecialchars($f['descripcion']) . "</td>";
                    $html .= "</tr>";
                    $i++;
                }
            } else {
                $html .= '<tr><td colspan="4" style="text-align:center; padding: 20px; color: #ff5c5c;">No se encontraron citas para este paciente en el periodo seleccionado.</td></tr>';
            }

            $html .= "
                    </tbody>
                </table>
            </body>
            </html>
            ";

            // 4. Configuración y generación del PDF
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true); 
            
            $pdf = new Dompdf($options);
            $pdf->setPaper("A4", "portrait");
            
            // Ya no usamos utf8_decode() porque Dompdf puede manejar UTF-8 si se configura bien la fuente en el HTML.
            $pdf->loadHtml($html, 'UTF-8'); 
            $pdf->render();

            $filename = 'ReporteCitas_' . $this->id_paciente . '_' . $ano . $mes . '.pdf';
            $pdf->stream($filename, array("Attachment" => false));

        } catch (Exception $e) {
            error_log("Error al generar PDF de citas: " . $e->getMessage());
            echo "Error: No se pudo generar el reporte. Consulte el registro de errores.";
        }
    }

    /**
     * Genera un reporte PDF de los tests realizados por un paciente en un mes específico.
     */
    function reportesTests() {
        // Usamos $this->pdo establecido en el constructor.
        $q = $this->pdo;

        // Validamos y separamos el año y el mes del valor en formato 'YYYY-MM'
        if (preg_match('/^(\d{4})-(\d{2})$/', $this->mes, $matches)) {
            $ano = $matches[1];
            $mes = $matches[2];
        } else {
            throw new Exception("Formato de mes inválido. Se espera 'YYYY-MM'.");
        }

        $nombrePaciente = "Paciente Desconocido";

        try {
            // 1. Obtener datos del paciente
            $resultado = $q->prepare("SELECT nombre, apellido FROM paciente WHERE id_paciente = :id_paciente");
            $resultado->bindValue(':id_paciente', $this->id_paciente);
            $resultado->execute();
            $pacienteData = $resultado->fetch(PDO::FETCH_ASSOC);
            if ($pacienteData) {
                // Sanear el nombre del paciente para la salida HTML/PDF
                $nombrePaciente = htmlspecialchars($pacienteData['nombre'] . " " . $pacienteData['apellido']);
            }
        } catch (Exception $e) {
            error_log("Error al buscar paciente para reporte de tests: " . $e->getMessage());
        }

        try {
            // 2. Obtener datos de los tests
            $resultado2 = $q->prepare("
                SELECT pt.fecha,
                        CASE 
                            WHEN pt.id_test_poms IS NOT NULL THEN 'POMS'
                            WHEN pt.id_test_confianza IS NOT NULL THEN 'Confianza'
                            WHEN pt.id_test_importancia IS NOT NULL THEN 'Importancia'
                        END AS tipo_test
                FROM paciente_test pt
                WHERE pt.id_paciente = :id_paciente 
                AND YEAR(pt.fecha) = :ano 
                AND MONTH(pt.fecha) = :mes 
                ORDER BY pt.fecha ASC
            ");
            $resultado2->bindValue(':id_paciente', $this->id_paciente);
            $resultado2->bindValue(':ano', $ano);
            $resultado2->bindValue(':mes', $mes);
            $resultado2->execute();

            $fila = $resultado2->fetchAll(PDO::FETCH_ASSOC);

            // 3. Generación del HTML para el PDF
            $html = '
            <html>
            <head>
                <style>
                    /* Usamos DejaVu Sans para mejor soporte de caracteres especiales como acentos y ñ */
                    body { font-family: DejaVu Sans, Arial, sans-serif; margin: 20px; color: #333; }
                    .report-table {
                        width: 100%;
                        border-collapse: collapse; 
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                    }
                    .report-table thead th {
                        background-color: #75A5B8; 
                        color: white;
                        padding: 14px 15px;
                        text-align: center;
                        text-transform: uppercase;
                        font-size: 0.9em;
                        font-weight: 600;
                    }
                    .report-table tbody td {
                        padding: 12px 15px;
                        border-bottom: 1px solid #e0e0e0;
                        font-size: 14px;
                        text-align: center; 
                    }
                    h1 { color: #2c3e50; text-align: center; margin-bottom: 5px; }
                    .report-header { font-size: 14px; color: #555; text-align: center; margin-bottom: 25px;}
                    .total-summary {
                        margin-top: 20px;
                        padding: 15px;
                        background-color: #e9ffe9; 
                        border: 1px solid #28a745;
                        font-size: 16px;
                        text-align: right;
                        font-weight: bold;
                    }
                </style>
            </head>
            <body>
                <h1>REPORTE DE TESTS</h1>
                <div class="report-header">
                    Paciente: <strong>' . $nombrePaciente . '</strong> | 
                    Periodo: <strong>' . $mes . ' / ' . $ano . '</strong>
                </div>
                <table class="report-table">
                    <thead>
                        <tr>
                            <th>FECHA</th>
                            <th>TIPO DE TEST</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            $totalTests = count($fila);

            if ($fila) {
                $i = 0;
                foreach ($fila as $f) {
                    $color = ($i % 2 == 0) ? '#ffffff' : '#f9f9f9';
                    $html .= "<tr style='background-color:" . $color . ";'>";
                    // Sanear la salida de datos de la base de datos
                    $html .= "<td>" . htmlspecialchars($f['fecha']) . "</td>";
                    $html .= "<td>" . htmlspecialchars($f['tipo_test']) . "</td>";
                    $html .= "</tr>";
                    $i++;
                }
            } else {
                $html .= '<tr><td colspan="2" style="text-align:center; padding: 20px; color: #ff5c5c;">No se encontraron tests para este paciente en el mes seleccionado.</td></tr>';
            }

            $html .= "
                    </tbody>
                </table>
                <div class='total-summary'>
                    TOTAL DE TESTS REALIZADOS EN EL MES: " . $totalTests . "
                </div>
            </body>
            </html>
            ";
            
            // 4. Configuración y generación del PDF
            $options = new Options(); 
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true); 

            $pdf = new Dompdf($options);
            $pdf->setPaper("A4", "portrait");
            $pdf->loadHtml($html, 'UTF-8');
            $pdf->render();

            $filename = 'ReporteTests_' . $this->id_paciente . '_' . $ano . $mes . '.pdf';
            $pdf->stream($filename, array("Attachment" => false));

        } catch (Exception $e) {
            error_log("Error al generar PDF de tests: " . $e->getMessage());
            echo "Error: No se pudo generar el reporte de tests. Consulte el registro de errores.";
        }
    }
}