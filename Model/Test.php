<?php

namespace Yahir\Compo;

use Yahir\Compo\Conexion;
use Exception;
use PDO;

// Modelo orientado a objetos para manejar los tests psicológicos de pacientes
class Test extends Conexion
{
    // --- Atributos privados para encapsular los datos de un test ---
    private $id;
    private $id_paciente;
    private $fecha;
    private $deporte;
    private $respuestas;
    private $parte1;
    private $parte2;
    private $pdo;

    // Constructor: asegura la conexión PDO
    public function __construct()
    {
        if (Conexion::getConexion() == null) {
            Conexion::conectar();
        }
        $this->pdo = Conexion::getConexion();
    }

    // --- Getters y Setters para cada atributo ---
    public function getId()
    {
        return $this->id;
    }
    public function getIdPaciente()
    {
        return $this->id_paciente;
    }
    public function getFecha()
    {
        return $this->fecha;
    }
    public function getDeporte()
    {
        return $this->deporte;
    }
    public function getRespuestas()
    {
        return $this->respuestas;
    }
    public function getParte1()
    {
        return $this->parte1;
    }
    public function getParte2()
    {
        return $this->parte2;
    }
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }
    public function setIdPaciente($id_paciente)
    {
        $this->id_paciente = $id_paciente;
        return $this;
    }
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;
        return $this;
    }
    public function setDeporte($deporte)
    {
        $this->deporte = $deporte;
        return $this;
    }
    public function setRespuestas($respuestas)
    {
        $this->respuestas = $respuestas;
        return $this;
    }
    public function setParte1($parte1)
    {
        $this->parte1 = $parte1;
        return $this;
    }
    public function setParte2($parte2)
    {
        $this->parte2 = $parte2;
        return $this;
    }

    // --- Métodos públicos CRUD y consultas principales ---
    // Devuelve todos los tests de un paciente agrupados por tipo
    public function obtenerTestsPorPaciente($id_paciente)
    {
        $tests = [];
        $tests['poms'] = $this->obtenerTestsPorTipo('poms', $id_paciente);
        $tests['confianza'] = $this->obtenerTestsPorTipo('confianza', $id_paciente);
        $tests['importancia'] = $this->obtenerTestsPorTipo('importancia', $id_paciente);
        return $tests;
    }

    // Crea un test POMS para un paciente
    public function crearTestPoms($id_paciente, $deporte, $respuestas)
    {
        $r = array();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $this->pdo->beginTransaction();
            $fecha = date('Y-m-d');
            // 1. Guardar el test POMS
            $respuestasFormateadas = [];
            foreach ($respuestas as $key => $value) {
                $respuestasFormateadas[$key] = (int)$value;
            }
            $stmt = $this->pdo->prepare("INSERT INTO test_poms (id_paciente, fecha, deporte, respuestas) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id_paciente, $fecha, $deporte, json_encode($respuestasFormateadas, JSON_PRETTY_PRINT)]);
            $id_test_poms = $this->pdo->lastInsertId();
            // 2. Insertar en paciente_test
            $ins = $this->pdo->prepare("INSERT INTO paciente_test (id_paciente, id_test_poms, fecha) VALUES (?, ?, ?)");
            $ins->execute([$id_paciente, $id_test_poms, $fecha]);
            $this->pdo->commit();
            $r['resultado'] = 'ok';
            $r['mensaje'] = 'Test incluido perfectamente';
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // --- Crear test Confianza ---
    public function crearTestConfianza($id_paciente, $respuestas)
    {
        $r = array();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $this->pdo->beginTransaction();
            $fecha = date('Y-m-d');
            // 1. Guardar el test de Confianza
            $respuestasFormateadas = [];
            foreach ($respuestas as $key => $value) {
                $respuestasFormateadas[$key] = (int)$value;
            }
            $stmt = $this->pdo->prepare("INSERT INTO test_confianza (id_paciente, fecha, respuestas) VALUES (?, ?, ?)");
            $stmt->execute([$id_paciente, $fecha, json_encode($respuestasFormateadas, JSON_PRETTY_PRINT)]);
            $id_test_confianza = $this->pdo->lastInsertId();
            // 2. Insertar en paciente_test
            $ins = $this->pdo->prepare("INSERT INTO paciente_test (id_paciente, id_test_confianza, fecha) VALUES (?, ?, ?)");
            $ins->execute([$id_paciente, $id_test_confianza, $fecha]);
            $this->pdo->commit();
            $r['resultado'] = 'ok';
            $r['mensaje'] = 'Test incluido perfectamente';
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // --- Crear test Importancia ---
    public function crearTestImportancia($id_paciente, $parte1, $parte2)
    {
        $r = array();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $this->pdo->beginTransaction();
            $fecha = date('Y-m-d');
            // 1. Guardar el test de Importancia
            $parte1Formateada = [];
            $parte2Formateada = [];
            foreach ($parte1 as $key => $value) $parte1Formateada[$key] = (int)$value;
            foreach ($parte2 as $key => $value) $parte2Formateada[$key] = (int)$value;
            $stmt = $this->pdo->prepare("INSERT INTO test_importancia (id_paciente, fecha, parte1, parte2) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $id_paciente,
                $fecha,
                json_encode($parte1Formateada, JSON_PRETTY_PRINT),
                json_encode($parte2Formateada, JSON_PRETTY_PRINT)
            ]);
            $id_test_importancia = $this->pdo->lastInsertId();
            // 2. Insertar en paciente_test
            $ins = $this->pdo->prepare("INSERT INTO paciente_test (id_paciente, id_test_importancia, fecha) VALUES (?, ?, ?)");
            $ins->execute([$id_paciente, $id_test_importancia, $fecha]);
            $this->pdo->commit();
            $r['resultado'] = 'ok';
            $r['mensaje'] = 'Test incluido perfectamente';
        } catch (Exception $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    // --------------------------------------------------------------------
    // Las demás funciones (obtener, actualizar, eliminar, etc.) se mantienen
    // exactamente igual, no necesitan cambios para la transacción.
    // --------------------------------------------------------------------
    // Devuelve un test específico por tipo e id
    public function obtenerTest($tipo, $id)
    {
        $tabla = "test_" . strtolower($tipo);
        $id_field = $this->getTestIdField($tipo);
        $stmt = $this->pdo->prepare("SELECT * FROM $tabla WHERE $id_field = ?");
        $stmt->execute([$id]);
        $test = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($test) {
            $this->mapearDatosTest($test, $tipo);
            return $this->getDatosTest();
        }
        return null;
    }

    // Devuelve un test y los datos del paciente asociado
    public function obtenerTestConPaciente($tipo, $id)
    {
        $tabla = "test_" . strtolower($tipo);
        $id_field = $this->getTestIdField($tipo);
        $sql = "SELECT t.*, p.nombre, p.apellido, p.cedula, p.telefono, p.fecha_nacimiento, p.genero FROM $tabla t JOIN paciente p ON t.id_paciente = p.id_paciente WHERE t.$id_field = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $test = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($test) {
            $this->mapearDatosTest($test, $tipo);
            $datosTest = $this->getDatosTest();
            $datosTest['paciente'] = $this->obtenerInfoPaciente($this->getIdPaciente());
            return $datosTest;
        }
        return null;
    }

    // Actualiza un test según tipo e id
    public function actualizarTest($tipo, $id, $datos)
    {
        $tabla = "test_" . strtolower($tipo);
        $id_field = $this->getTestIdField($tipo);
        $campos = [];
        $valores = [];
        foreach ($datos as $campo => $valor) {
            $campos[] = "$campo = ?";
            $valores[] = is_array($valor) ? json_encode($valor) : $valor;
        }
        $valores[] = $id;
        $sql = "UPDATE $tabla SET " . implode(', ', $campos) . " WHERE $id_field = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($valores);
    }

    // Elimina un test según tipo e id
    public function eliminarTest($tipo, $id)
    {
        $tabla = "test_" . strtolower($tipo);
        $id_field = $this->getTestIdField($tipo);
        $stmt = $this->pdo->prepare("DELETE FROM $tabla WHERE $id_field = ?");
        return $stmt->execute([$id]);
    }

    // Devuelve lista de pacientes para selects
    public function obtenerPacientesParaSelect()
    {
        $stmt = $this->pdo->query("SELECT id_paciente, nombre, apellido FROM paciente ORDER BY apellido, nombre");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- Métodos privados de apoyo ---
    // Devuelve todos los tests de un tipo para un paciente
    private function obtenerTestsPorTipo($tipo, $id_paciente)
    {
        $tabla = "test_" . strtolower($tipo);
        $id_field = $this->getTestIdField($tipo);
        $stmt = $this->pdo->prepare("SELECT t.*, p.nombre, p.apellido FROM $tabla t JOIN paciente p ON t.id_paciente = p.id_paciente WHERE t.id_paciente = ?");
        $stmt->execute([$id_paciente]);
        $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tests as &$test) {
            if (isset($test['respuestas'])) $test['respuestas'] = json_decode($test['respuestas'], true);
            if (isset($test['parte1'])) $test['parte1'] = json_decode($test['parte1'], true);
            if (isset($test['parte2'])) $test['parte2'] = json_decode($test['parte2'], true);
            $test['nombre_paciente'] = $test['apellido'] . ', ' . $test['nombre'];
            $test['id'] = $test[$id_field]; // Para compatibilidad con frontend
        }
        return $tests;
    }

    // Devuelve todos los tests de todos los pacientes agrupados por tipo
    public function obtenerTodosLosTests()
    {
        $tests = [];
        $tests['poms'] = $this->obtenerTestsPorTipoTodos('poms');
        $tests['confianza'] = $this->obtenerTestsPorTipoTodos('confianza');
        $tests['importancia'] = $this->obtenerTestsPorTipoTodos('importancia');
        return $tests;
    }

    // Devuelve todos los tests de un tipo para todos los pacientes
    private function obtenerTestsPorTipoTodos($tipo)
    {
        $tabla = "test_" . strtolower($tipo);
        $id_field = $this->getTestIdField($tipo);
        $stmt = $this->pdo->query("SELECT t.*, p.nombre, p.apellido FROM $tabla t JOIN paciente p ON t.id_paciente = p.id_paciente");
        $tests = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tests as &$test) {
            if (isset($test['respuestas'])) $test['respuestas'] = json_decode($test['respuestas'], true);
            if (isset($test['parte1'])) $test['parte1'] = json_decode($test['parte1'], true);
            if (isset($test['parte2'])) $test['parte2'] = json_decode($test['parte2'], true);
            $test['nombre_paciente'] = $test['apellido'] . ', ' . $test['nombre'];
            $test['id'] = $test[$id_field];
        }
        return $tests;
    }

    // Devuelve los datos de un paciente por id
    private function obtenerInfoPaciente($id_paciente)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM paciente WHERE id_paciente = ?");
        $stmt->execute([$id_paciente]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Asigna los datos de un test a los atributos del objeto según tipo
    private function mapearDatosTest($datos, $tipo)
    {
        $id_field = $this->getTestIdField($tipo);
        $this->setId($datos[$id_field])
            ->setIdPaciente($datos['id_paciente'])
            ->setFecha($datos['fecha']);
        switch ($tipo) {
            case 'poms':
                $this->setDeporte($datos['deporte'] ?? null)
                    ->setRespuestas(isset($datos['respuestas']) ? json_decode($datos['respuestas'], true) : []);
                break;
            case 'confianza':
                $this->setRespuestas(isset($datos['respuestas']) ? json_decode($datos['respuestas'], true) : []);
                break;
            case 'importancia':
                $this->setParte1(isset($datos['parte1']) ? json_decode($datos['parte1'], true) : [])
                    ->setParte2(isset($datos['parte2']) ? json_decode($datos['parte2'], true) : []);
                break;
        }
    }

    // Devuelve los datos del test en un array asociativo
    private function getDatosTest()
    {
        return [
            'id' => $this->getId(),
            'id_paciente' => $this->getIdPaciente(),
            'fecha' => $this->getFecha(),
            'deporte' => $this->getDeporte(),
            'respuestas' => $this->getRespuestas(),
            'parte1' => $this->getParte1(),
            'parte2' => $this->getParte2()
        ];
    }

    // Devuelve el nombre del campo id según el tipo de test
    private function getTestIdField($tipo)
    {
        switch (strtolower($tipo)) {
            case 'poms':
                return 'id_test_poms';
            case 'confianza':
                return 'id_test_confianza';
            case 'importancia':
                return 'id_test_importancia';
            default:
                return 'id';
        }
    }
}
