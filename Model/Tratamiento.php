<?php
namespace Yahir\Compo;
use Yahir\Compo\Conexion;
use PDO;
use PDOException;

/**
 * Clase Tratamiento
 * ... (resto de la descripción de la clase)
 */
class Tratamiento extends Conexion {
    // Propiedades privadas
    private $id_tratamiento;
    private $id_paciente;
    private $fecha_creacion;
    private $diagnostico_descripcion;
    private $tratamiento_tipo;
    private $estado_actual;
    private $observaciones;
    private $pdo;

    public function __construct() {
        if (Conexion::getConexion() == null) {
            Conexion::conectar();
        }
        $this->pdo = Conexion::getConexion();
    }

    // --- MÉTODOS DE VALIDACIÓN DE SEGURIDAD (PVP) ---

    /**
     * Valida si un campo de texto contiene caracteres seguros y tiene una longitud mínima.
     * Permite letras, números, espacios, tildes y puntuación extendida (.,;:-_¿?¡!()/&%#).
     * @param string $valor El valor del campo a validar.
     * @param int $min_longitud La longitud mínima requerida (por defecto 5).
     * @return array [bool $es_valido, string $mensaje_error]
     */
    private function validar_campo_texto($valor, $min_longitud = 5) {
        // Si el campo está vacío, es válido (se asume que no es estrictamente obligatorio)
        if (empty($valor)) {
            return ['es_valido' => true, 'mensaje_error' => ''];
        }

        // 1. Validación de longitud mínima
        if (mb_strlen($valor, 'UTF-8') < $min_longitud) {
            return ['es_valido' => false, 'mensaje_error' => "Debe tener al menos **$min_longitud** caracteres."];
        }

        // 2. Validación de patrón para evitar inyección (letras, números, puntuación común)
        // El modificador 'u' es crucial para soportar caracteres UTF-8.
        $patron = '/^[\p{L}\p{N}\s.,;:\-¿?¡!ñÑáéíóúÁÉÍÓÚ()\/&%#]+$/u';
        
        if (preg_match($patron, $valor)) {
            return ['es_valido' => true, 'mensaje_error' => ''];
        }

        return ['es_valido' => false, 'mensaje_error' => 'Contiene caracteres no permitidos. Evita símbolos como < > o código malicioso.'];
    }

    /**
     * Valida que un valor sea un entero positivo (usado para IDs).
     * @param mixed $valor El valor a validar.
     * @return bool True si es un entero positivo y mayor a cero.
     */
    private function validar_id_entero($valor) {
        // Asegura que sea un entero válido y mayor a cero
        return filter_var($valor, FILTER_VALIDATE_INT) !== false && $valor > 0;
    }

    /**
     * Ejecuta todas las validaciones PVP para los datos de un tratamiento.
     * @param array $data Array asociativo con los datos a validar.
     * @return array Array con el resultado de la validación. ['status' => 'ok'|'error', 'mensaje' => '...']
     */
    private function ejecutarValidacionesTratamiento($data) {
        // 1. Validar id_paciente (obligatorio y debe ser un entero válido)
        if (!isset($data['id_paciente']) || !$this->validar_id_entero($data['id_paciente'])) {
            return ['status' => 'error', 'mensaje' => 'El ID del paciente es obligatorio y debe ser un número entero válido.'];
        }

        // 2. Validar campos de texto largos (con mínimo 5 caracteres)
        $campos_texto_a_validar = [
            'diagnostico_descripcion' => $data['diagnostico_descripcion'] ?? '',
            'tratamiento_tipo' => $data['tratamiento_tipo'] ?? '',
            'estado_actual' => $data['estado_actual'] ?? '',
            'observaciones' => $data['observaciones'] ?? '',
        ];

        // Recorrer y validar los campos de texto
        foreach ($campos_texto_a_validar as $nombre_campo => $valor_campo) {
            // Establecemos un mínimo de 5 caracteres para todos los campos de texto.
            $resultado_validacion = $this->validar_campo_texto($valor_campo, 5);
            
            // Solo verificamos si tiene datos; si es un campo que NO debe ir vacío, se pone un $min_longitud mayor a 0.
            // Para 'diagnostico_descripcion' y 'tratamiento_tipo', podemos forzar un mínimo:
            $min_obligatorio = ($nombre_campo == 'diagnostico_descripcion' || $nombre_campo == 'tratamiento_tipo') ? 5 : 0;
            
            if ($min_obligatorio > 0 && empty($valor_campo)) {
                 return ['status' => 'error', 'mensaje' => "El campo **" . $nombre_campo . "** es obligatorio y no puede estar vacío."];
            }

            if (!$resultado_validacion['es_valido']) {
                return ['status' => 'error', 'mensaje' => "Error en el campo **" . $nombre_campo . "**: " . $resultado_validacion['mensaje_error']];
            }
        }
        
        // 3. Validación de fecha_creacion (solo verificación de formato básico YYYY-MM-DD si está presente)
        if (isset($data['fecha_creacion']) && !empty($data['fecha_creacion'])) {
             // Este patrón es básico, asume que se usa el formato estándar de MySQL (YYYY-MM-DD).
             if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['fecha_creacion'])) {
                return ['status' => 'error', 'mensaje' => 'El formato de **fecha_creacion** no es válido (debe ser YYYY-MM-DD).'];
             }
        }

        return ['status' => 'ok', 'mensaje' => 'Datos válidos.'];
    }

    // --- MÉTODOS CRUD (Con la integración de la validación) ---

    // Los métodos listarTratamientos, obtenerTratamiento, buscarTratamientos se mantienen iguales, ya que son de lectura.

    public function listarTratamientos() {
        try {
            $sql = "SELECT t.*, p.nombre, p.apellido, p.cedula 
                    FROM tratamientos t 
                    JOIN paciente p ON t.id_paciente = p.id_paciente 
                    ORDER BY t.fecha_creacion DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al listar tratamientos: " . $e->getMessage());
            return [];
        }
    }

    public function obtenerTratamiento($id) {
        try {
            $sql = "SELECT t.*, p.nombre, p.apellido, p.cedula 
                    FROM tratamientos t 
                    JOIN paciente p ON t.id_paciente = p.id_paciente 
                    WHERE t.id_tratamiento = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al obtener tratamiento: " . $e->getMessage());
            return false;
        }
    }

    public function buscarTratamientos($termino) {
        try {
            $sql = "SELECT t.*, p.nombre, p.apellido, p.cedula 
                    FROM tratamientos t 
                    JOIN paciente p ON t.id_paciente = p.id_paciente 
                    WHERE p.nombre LIKE ? OR p.apellido LIKE ? OR p.cedula LIKE ? 
                    OR t.estado_actual LIKE ? OR t.fecha_creacion LIKE ?
                    ORDER BY t.fecha_creacion DESC";
            $stmt = $this->pdo->prepare($sql);
            $termino = "%$termino%";
            $stmt->execute([$termino, $termino, $termino, $termino, $termino]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error al buscar tratamientos: " . $e->getMessage());
            return [];
        }
    }

    public function crearTratamiento($data) {
        // --- INICIO DE VALIDACIONES PVP (Seguridad Estupenda) ---
        $validacion = $this->ejecutarValidacionesTratamiento($data);
        if ($validacion['status'] == 'error') {
            // Retorna un error de validación en lugar de procesar la base de datos
            return $validacion['mensaje']; 
        }
        // --- FIN DE VALIDACIONES PVP ---
        
        try {
            $sql = "INSERT INTO tratamientos 
                    (id_paciente, fecha_creacion, diagnostico_descripcion, tratamiento_tipo, estado_actual, observaciones) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            
            $stmt->execute([
                $data['id_paciente'],
                $data['fecha_creacion'],
                $data['diagnostico_descripcion'],
                $data['tratamiento_tipo'],
                $data['estado_actual'],
                $data['observaciones'] ?? ''
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Error al crear tratamiento: " . $e->getMessage());
            // Se asume que el ID de paciente existe por restricción de clave foránea.
            return "Error al crear tratamiento: " . $e->getMessage();
        }
    }

    public function actualizarTratamiento($id, $data) {
        // --- INICIO DE VALIDACIONES PVP (Seguridad Estupenda) ---
        // Validamos el ID del tratamiento a actualizar
        if (!$this->validar_id_entero($id)) {
            return "Error: ID de tratamiento a actualizar no es válido.";
        }
        
        $validacion = $this->ejecutarValidacionesTratamiento($data);
        if ($validacion['status'] == 'error') {
            // Retorna un error de validación
            return $validacion['mensaje']; 
        }
        // --- FIN DE VALIDACIONES PVP ---

        try {
            $sql = "UPDATE tratamientos SET 
                    id_paciente = ?, 
                    fecha_creacion = ?, 
                    diagnostico_descripcion = ?, 
                    tratamiento_tipo = ?, 
                    estado_actual = ?, 
                    observaciones = ? 
                    WHERE id_tratamiento = ?";
            $stmt = $this->pdo->prepare($sql);
            
            return $stmt->execute([
                $data['id_paciente'],
                $data['fecha_creacion'],
                $data['diagnostico_descripcion'],
                $data['tratamiento_tipo'],
                $data['estado_actual'],
                $data['observaciones'] ?? '',
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar tratamiento: " . $e->getMessage());
            return "Error al actualizar tratamiento: " . $e->getMessage();
        }
    }

    public function eliminarTratamiento($id) {
         // Validación de ID (PVP)
         if (!$this->validar_id_entero($id)) {
            return false; // No es un ID válido, no se ejecuta la consulta
        }
        
        try {
            $sql = "DELETE FROM tratamientos WHERE id_tratamiento = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error al eliminar tratamiento: " . $e->getMessage());
            return false;
        }
    }
}
?>