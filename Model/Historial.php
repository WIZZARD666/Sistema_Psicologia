<?php namespace Yahir\Compo;

use Yahir\Compo\Conexion;
use PDO;
use Exception;

class Historial extends Conexion {
    // Propiedades
    private $id;
    private $id_paciente; // <-- Agregado
    private $sintomas;
    private $otrosintomas;
    private $convives;
    private $cambiar;
    private $conflicto;
    private $trabajar;
    private $alcohol;
    private $alcofrecuencia;
    private $fumas;
    private $fumafrecuencia;
    private $consumir;
    private $consufrecuencia;
    private $rutina;
    private $acudir;
    private $tratamiento;
    private $finalizar;
    private $significativo;
    private $PersonaSigni;
    private $PodriaAyudar;
    private $ConseguirTerapia;
    private $compromiso;
    private $TiempoDurara;
    private $considerar;

    // Setters y Getters (Mantenidos sin cambios por brevedad)

    // Setters
    public function set_id($valor) { $this->id = $valor; }
    public function set_id_paciente($valor) { $this->id_paciente = $valor; }
    public function set_sintomas($valor) { $this->sintomas = $valor; }
    public function set_otrosintomas($valor) { $this->otrosintomas = $valor; }
    public function set_convives($valor) { $this->convives = $valor; }
    public function set_cambiar($valor) { $this->cambiar = $valor; }
    public function set_conflicto($valor) { $this->conflicto = $valor; }
    public function set_trabajar($valor) { $this->trabajar = $valor; }
    public function set_alcohol($valor) { $this->alcohol = $valor; }
    public function set_alcofrecuencia($valor) { $this->alcofrecuencia = $valor; }
    public function set_fumas($valor) { $this->fumas = $valor; }
    public function set_fumafrecuencia($valor) { $this->fumafrecuencia = $valor; }
    public function set_consumir($valor) { $this->consumir = $valor; }
    public function set_consufrecuencia($valor) { $this->consufrecuencia = $valor; }
    public function set_rutina($valor) { $this->rutina = $valor; }
    public function set_acudir($valor) { $this->acudir = $valor; }
    public function set_tratamiento($valor) { $this->tratamiento = $valor; }
    public function set_finalizar($valor) { $this->finalizar = $valor; }
    public function set_significativo($valor) { $this->significativo = $valor; }
    public function set_PersonaSigni($valor) { $this->PersonaSigni = $valor; }
    public function set_PodriaAyudar($valor) { $this->PodriaAyudar = $valor; }
    public function set_ConseguirTerapia($valor) { $this->ConseguirTerapia = $valor; }
    public function set_compromiso($valor) { $this->compromiso = $valor; }
    public function set_TiempoDurara($valor) { $this->TiempoDurara = $valor; }
    public function set_considerar($valor) { $this->considerar = $valor; }
    // Getters
    public function get_id() { return $this->id; }
    public function get_id_paciente() { return $this->id_paciente; }
    public function get_sintomas() { return $this->sintomas; }
    public function get_otrosintomas() { return $this->otrosintomas; }
    public function get_convives() { return $this->convives; }
    public function get_cambiar() { return $this->cambiar; }
    public function get_conflicto() { return $this->conflicto; }
    public function get_trabajar() { return $this->trabajar; }
    public function get_alcohol() { return $this->alcohol; }
    public function get_alcofrecuencia() { return $this->alcofrecuencia; }
    public function get_fumas() { return $this->fumas; }
    public function get_fumafrecuencia() { return $this->fumafrecuencia; }
    public function get_consumir() { return $this->consumir; }
    public function get_consufrecuencia() { return $this->consufrecuencia; }
    public function get_rutina() { return $this->rutina; }
    public function get_acudir() { return $this->acudir; }
    public function get_tratamiento() { return $this->tratamiento; }
    public function get_finalizar() { return $this->finalizar; }
    public function get_significativo() { return $this->significativo; }
    public function get_PersonaSigni() { return $this->PersonaSigni; }
    public function get_PodriaAyudar() { return $this->PodriaAyudar; }
    public function get_ConseguirTerapia() { return $this->ConseguirTerapia; }
    public function get_compromiso() { return $this->compromiso; }
    public function get_TiempoDurara() { return $this->TiempoDurara; }
    public function get_considerar() { return $this->considerar; }

    /**
     * Valida si un campo de texto contiene caracteres seguros.
     * Permite letras, números, espacios, tildes, comas, puntos, guiones, y signos de interrogación/admiración.
     * @param string $valor El valor del campo a validar.
     * @return bool True si es válido, False si no lo es.
     */
    private function validar_campo($valor) {
        // Expresión regular que permite:
        // - Letras mayúsculas y minúsculas (a-zA-Z)
        // - Números (0-9)
        // - Espacios (\s)
        // - Tildes y la ñ/Ñ (áéíóúÁÉÍÓÚñÑ)
        // - Puntuación común (.,;:\-?¿!¡())
        // - El modificador 'u' es crucial para soportar caracteres UTF-8 (tildes, ñ).
        $patron = '/^[\p{L}\p{N}\s.,;:\-¿?¡!ñÑáéíóúÁÉÍÓÚ()]*$/u';
        
        // El campo puede ser opcionalmente vacío, si tiene datos, valida.
        if (empty($valor) || preg_match($patron, $valor)) {
            return true;
        }
        return false;
    }

    // FUNCIONES PRINCIPALES
    public function registrar() {
        $respuesta = array();
        
        // --- INICIO DE VALIDACIONES PVP (Front-End en el Back-End) ---
        // Se valida solo si el campo no está vacío. Si está vacío, se asume que no es obligatorio
        // o se confía en la validación de la base de datos (NOT NULL), pero se previene inyección.

        // Campos de texto largos que deben ser validados
        $campos_a_validar = [
            'otrosintomas' => $this->otrosintomas,
            'convives' => $this->convives,
            'cambiar' => $this->cambiar,
            'conflicto' => $this->conflicto,
            'trabajar' => $this->trabajar,
            'alcofrecuencia' => $this->alcofrecuencia,
            'fumafrecuencia' => $this->fumafrecuencia,
            'consufrecuencia' => $this->consufrecuencia,
            'rutina' => $this->rutina,
            'acudir' => $this->acudir,
            'tratamiento' => $this->tratamiento,
            'finalizar' => $this->finalizar,
            'significativo' => $this->significativo,
            'PersonaSigni' => $this->PersonaSigni,
            'PodriaAyudar' => $this->PodriaAyudar,
            'ConseguirTerapia' => $this->ConseguirTerapia,
            'compromiso' => $this->compromiso,
            'TiempoDurara' => $this->TiempoDurara,
            'considerar' => $this->considerar,
            // NOTA: 'sintomas' no se valida aquí con el patrón general
            // ya que suele ser un campo de selección múltiple (JSON o CSV)
            // que se valida por su formato específico antes de ser seteado.
        ];

        foreach ($campos_a_validar as $nombre_campo => $valor_campo) {
            // Solo se valida si el campo tiene algún valor
            if (!empty($valor_campo) && !$this->validar_campo($valor_campo)) {
                $respuesta['resultado'] = 'error';
                $respuesta['mensaje'] = "El campo **" . $nombre_campo . "** contiene caracteres no permitidos. Solo se permiten letras, números y puntuación básica.";
                return $respuesta;
            }
        }
        // --- FIN DE VALIDACIONES PVP ---

        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!$this->existe($this->id)) {
            try {
                $qp = $q->prepare("INSERT INTO historia (
                    id_paciente, sintomas, otrosintomas, convives, cambiar, conflicto, trabajar,
                    alcohol, alcofrecuencia, fumas, fumafrecuencia, consumir, consufrecuencia,
                    rutina, acudir, tratamiento, finalizar, significativo, PersonaSigni,
                    PodriaAyudar, ConseguirTerapia, compromiso, TiempoDurara, considerar
                ) VALUES (
                    :id_paciente, :sintomas, :otrosintomas, :convives, :cambiar, :conflicto, :trabajar,
                    :alcohol, :alcofrecuencia, :fumas, :fumafrecuencia, :consumir, :consufrecuencia,
                    :rutina, :acudir, :tratamiento, :finalizar, :significativo, :PersonaSigni,
                    :PodriaAyudar, :ConseguirTerapia, :compromiso, :TiempoDurara, :considerar
                )");
                
                // Binding de parámetros
                $qp->bindParam(':id_paciente', $this->id_paciente);
                $qp->bindParam(':sintomas', $this->sintomas);
                $qp->bindParam(':otrosintomas', $this->otrosintomas);
                $qp->bindParam(':convives', $this->convives);
                $qp->bindParam(':cambiar', $this->cambiar);
                $qp->bindParam(':conflicto', $this->conflicto);
                $qp->bindParam(':trabajar', $this->trabajar);
                $qp->bindParam(':alcohol', $this->alcohol);
                $qp->bindParam(':alcofrecuencia', $this->alcofrecuencia);
                $qp->bindParam(':fumas', $this->fumas);
                $qp->bindParam(':fumafrecuencia', $this->fumafrecuencia);
                $qp->bindParam(':consumir', $this->consumir);
                $qp->bindParam(':consufrecuencia', $this->consufrecuencia);
                $qp->bindParam(':rutina', $this->rutina);
                $qp->bindParam(':acudir', $this->acudir);
                $qp->bindParam(':tratamiento', $this->tratamiento);
                $qp->bindParam(':finalizar', $this->finalizar);
                $qp->bindParam(':significativo', $this->significativo);
                $qp->bindParam(':PersonaSigni', $this->PersonaSigni);
                $qp->bindParam(':PodriaAyudar', $this->PodriaAyudar);
                $qp->bindParam(':ConseguirTerapia', $this->ConseguirTerapia);
                $qp->bindParam(':compromiso', $this->compromiso);
                $qp->bindParam(':TiempoDurara', $this->TiempoDurara);
                $qp->bindParam(':considerar', $this->considerar);
                
                $qp->execute();
                $respuesta['resultado'] = 'registrar';
                $respuesta['mensaje'] = "Datos del historial registrado";
            } catch (Exception $e) {
                $respuesta['resultado'] = 'error';
                $respuesta['mensaje'] = $e->getMessage();
            }
        } else {
            $respuesta['resultado'] = 'registrar';
            $respuesta['mensaje'] = "Ya existe un historia con este ID.";
        }
        return $respuesta;
    }
    
    // El resto de tus funciones (modificar, eliminar, existe, consultarPacientes, consultarHistorial)
    // se mantienen igual, pero considera aplicar también las validaciones en `modificar()`.

    public function modificar() {
        $respuesta = array();
        
        // --- INICIO DE VALIDACIONES PVP para modificar() ---
        // Reutilizamos el array de campos a validar
        $campos_a_validar = [
            'otrosintomas' => $this->otrosintomas,
            'convives' => $this->convives,
            'cambiar' => $this->cambiar,
            'conflicto' => $this->conflicto,
            'trabajar' => $this->trabajar,
            'alcofrecuencia' => $this->alcofrecuencia,
            'fumafrecuencia' => $this->fumafrecuencia,
            'consufrecuencia' => $this->consufrecuencia,
            'rutina' => $this->rutina,
            'acudir' => $this->acudir,
            'tratamiento' => $this->tratamiento,
            'finalizar' => $this->finalizar,
            'significativo' => $this->significativo,
            'PersonaSigni' => $this->PersonaSigni,
            'PodriaAyudar' => $this->PodriaAyudar,
            'ConseguirTerapia' => $this->ConseguirTerapia,
            'compromiso' => $this->compromiso,
            'TiempoDurara' => $this->TiempoDurara,
            'considerar' => $this->considerar,
        ];

        foreach ($campos_a_validar as $nombre_campo => $valor_campo) {
            if (!empty($valor_campo) && !$this->validar_campo($valor_campo)) {
                $respuesta['resultado'] = 'error';
                $respuesta['mensaje'] = "El campo **" . $nombre_campo . "** contiene caracteres no permitidos en la modificación. Solo se permiten letras, números y puntuación básica.";
                return $respuesta;
            }
        }
        // --- FIN DE VALIDACIONES PVP para modificar() ---

        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($this->existe($this->id)) {
            try {
                $qp = $q->prepare("UPDATE historia SET sintomas = :sintomas, otrosintomas = :otrosintomas, convives = :convives, cambiar = :cambiar, conflicto = :conflicto, trabajar = :trabajar, alcohol = :alcohol, alcofrecuencia = :alcofrecuencia, fumas = :fumas, fumafrecuencia = :fumafrecuencia, consumir = :consumir, consufrecuencia = :consufrecuencia, rutina = :rutina, acudir = :acudir, tratamiento = :tratamiento, finalizar = :finalizar, significativo = :significativo, PersonaSigni = :PersonaSigni, PodriaAyudar = :PodriaAyudar, ConseguirTerapia = :ConseguirTerapia, compromiso = :compromiso, TiempoDurara = :TiempoDurara, considerar = :considerar WHERE id_historia = :id");
                $qp->bindParam(':id', $this->id);
                $qp->bindParam(':sintomas', $this->sintomas);
                $qp->bindParam(':otrosintomas', $this->otrosintomas);
                $qp->bindParam(':convives', $this->convives);
                $qp->bindParam(':cambiar', $this->cambiar);
                $qp->bindParam(':conflicto', $this->conflicto);
                $qp->bindParam(':trabajar', $this->trabajar);
                $qp->bindParam(':alcohol', $this->alcohol);
                $qp->bindParam(':alcofrecuencia', $this->alcofrecuencia);
                $qp->bindParam(':fumas', $this->fumas);
                $qp->bindParam(':fumafrecuencia', $this->fumafrecuencia);
                $qp->bindParam(':consumir', $this->consumir);
                $qp->bindParam(':consufrecuencia', $this->consufrecuencia);
                $qp->bindParam(':rutina', $this->rutina);
                $qp->bindParam(':acudir', $this->acudir);
                $qp->bindParam(':tratamiento', $this->tratamiento);
                $qp->bindParam(':finalizar', $this->finalizar);
                $qp->bindParam(':significativo', $this->significativo);
                $qp->bindParam(':PersonaSigni', $this->PersonaSigni);
                $qp->bindParam(':PodriaAyudar', $this->PodriaAyudar);
                $qp->bindParam(':ConseguirTerapia', $this->ConseguirTerapia);
                $qp->bindParam(':compromiso', $this->compromiso);
                $qp->bindParam(':TiempoDurara', $this->TiempoDurara);
                $qp->bindParam(':considerar', $this->considerar);
                $qp->execute();
                $respuesta['resultado'] = 'modificar';
                $respuesta['mensaje'] = "Datos del historia modificados";
            } catch (Exception $e) {
                $respuesta['resultado'] = 'error';
                $respuesta['mensaje'] = $e->getMessage();
            }
        } else {
            $respuesta['resultado'] = 'modificar';
            $respuesta['mensaje'] = "Este historia no está registrado";
        }
        return $respuesta;
    }

    public function eliminar() {
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $respuesta = array();
        if ($this->existe($this->id)) {
            try {
                $qp = $q->prepare("DELETE FROM historia WHERE id_historia = :id");
                $qp->bindParam(':id', $this->id);
                $qp->execute();
                $respuesta['resultado'] = 'eliminar';
                $respuesta['mensaje'] = 'historia eliminado';
            } catch (Exception $e) {
                $respuesta['resultado'] = 'error';
                $respuesta['mensaje'] = $e->getMessage();
            }
        } else {
            $respuesta['resultado'] = 'eliminar';
            $respuesta['mensaje'] = 'El historia no existe';
        }
        return $respuesta;
    }
    private function existe($id) {
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $consulta = $q->prepare("SELECT * FROM historia WHERE id_historia = :id");
            $consulta->bindValue(':id', $id);
            $consulta->execute();
            $fila = $consulta->fetchAll(PDO::FETCH_BOTH);
            if ($fila) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }
    public function consultarPacientes() {
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $respuesta = array();
        try {
            $consulta = $q->query("SELECT h.id_historia, p.id_paciente, p.nombre, p.apellido, p.cedula, p.telefono, p.email, p.fecha_nacimiento FROM `paciente` as p INNER JOIN historia as h ON p.id_paciente = h.id_paciente");
            // echo json_encode($consulta);
            // exit;
            if ($consulta) {
                $mostrar = '';
                foreach ($consulta as $paciente) {

                    $mostrar.= '
                        <div class="col-12 col-md-6 mb-4 paciente-card">
                            <div class="card" style="width: 100%;">
                                <div class="card-body">
                                    <h5 class="card-title">' . htmlspecialchars($paciente['nombre']) . ' ' . htmlspecialchars($paciente['apellido']) . '</h5>
                                    <p class="card-text">Cédula: ' . htmlspecialchars($paciente['cedula']) . '</p>
                                    <p class="card-text">Teléfono: ' . htmlspecialchars($paciente['telefono']) . '</p>
                                    <p class="card-text">Email: ' . htmlspecialchars($paciente['email']) . '</p>
                                    <p class="card-text">Fecha Nac.: ' . htmlspecialchars($paciente['fecha_nacimiento']) . '</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button class="btn btn-sm bi bi-eye btn-accion btn-detalles" onclick="actualizarCampos(' . $paciente["id_historia"] . ')">
                                            Ver Detalles
                                        </button>
                                        <button class="btn btn-sm btn-accion btn-editar" onclick="actualizarCampos(' . $paciente["id_historia"] . ')">
                                            <i class="bi bi-pencil-square"></i> Modificar
                                        </button>
                                        <button class="btn btn-sm btn-accion btn-eliminar" onclick="eliminarhistoria(' . $paciente["id_historia"] . ')">
                                            <i class="bi bi-trash3-fill eliminar-icono-tarjeta"></i> Eliminar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';
                }
                $respuesta['resultado'] = 'consultarPacientes';
                $respuesta['mensaje'] = $mostrar;
            } else {
                $respuesta['resultado'] = 'consultarPacientes';
                $respuesta['mensaje'] = '';
            }
        } catch (Exception $e) {
            $respuesta['resultado'] = 'error';
            $respuesta['mensaje'] = $e->getMessage();
        }
        return $respuesta;
    }

    public function consultarHistorial()
    {
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $respuesta = array();
        try {
            $consulta = $q->prepare("SELECT 
                p.nombre, 
                p.apellido, 
                p.cedula, 
                p.telefono, 
                p.fecha_nacimiento, 
                p.genero, 
                p.email ,
                h.* 
                FROM historia as h
                INNER JOIN paciente as p ON h.id_paciente = p.id_paciente
                WHERE h.id_historia = :id
            ");
            $consulta->bindValue(':id', $this->id);
            $consulta->execute();

            if ($consulta) {
                $historial = $consulta->fetch(PDO::FETCH_ASSOC);
                $historial['sintomas'] = !empty($historial['sintomas']) ? (is_array($historial['sintomas']) ? $historial['sintomas'] : explode(',', $historial['sintomas'])) : [];
                
                $respuesta['resultado'] = 'consultarHistorial';
                $respuesta['mensaje'] = $historial;
            } else {
                $respuesta['resultado'] = 'consultarHistorial';
                $respuesta['mensaje'] = '';
            }
        } catch (Exception $e) {
            $respuesta['resultado'] = 'error';
            $respuesta['mensaje'] = $e->getMessage();
        }
        return $respuesta;
    }

    public function consultarDetalles()
    {
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $respuesta = array();
        try {
            $consulta = $q->prepare("SELECT 
                p.nombre, 
                p.apellido, 
                p.cedula, 
                p.telefono, 
                p.fecha_nacimiento, 
                p.genero, 
                p.email ,
                h.* 
                FROM historia as h
                INNER JOIN paciente as p ON h.id_paciente = p.id_paciente
                WHERE h.id_historia = :id
            ");
            $consulta->bindValue(':id', $this->id);
            $consulta->execute();

            if ($consulta) {
                $historial = $consulta->fetch(PDO::FETCH_ASSOC);
                $historial['sintomas'] = !empty($historial['sintomas']) ? (is_array($historial['sintomas']) ? $historial['sintomas'] : explode(',', $historial['sintomas'])) : [];
                
                $respuesta['resultado'] = 'consultarDetalles';
                $respuesta['mensaje'] = $historial;
            } else {
                $respuesta['resultado'] = 'consultarDetalles';
                $respuesta['mensaje'] = '';
            }
        } catch (Exception $e) {
            $respuesta['resultado'] = 'error';
            $respuesta['mensaje'] = $e->getMessage();
        }
        return $respuesta;
    }
}
