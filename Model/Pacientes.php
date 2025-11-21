<?php

namespace Yahir\Compo;

use Yahir\Compo\Conexion;
use PDO;
use Exception;

// Asumo que tienes una tabla 'paciente' y una tabla 'ubicacion' en tu base de datos.
// La tabla 'ubicacion' debe tener campos 'id_ubicacion' (AUTO_INCREMENT), 'pais', y 'ciudad'.
// La tabla 'paciente' debe tener un campo 'id_ubicacion' como clave foránea.

class Pacientes extends Conexion
{
    // Propiedades del nuevo modelo Paciente
    private $pdo;
    private $id_paciente;
    private $nombre;
    private $apellido;
    private $cedula;
    private $telefono;
    private $fecha_nacimiento; // Nuevo
    private $genero; // Nuevo
    private $email; // Nuevo
    private $pais; // Usamos esto en lugar de $id_ubicacion para recibir el dato de la vista
    private $ciudad; // Usamos esto en lugar de $id_ubicacion para recibir el dato de la vista
    private $id_ubicacion; // Propiedad interna para el resultado del INSERT de ubicación

    // El constructor se usará para inicializar la conexión PDO
    public function __construct()
    {
        Conexion::conectar();
        $this->pdo = Conexion::getConexion();
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // --- Setters ---
    public function set_id_paciente($valor)
    {
        $this->id_paciente = $valor;
    }
    public function set_nombre($valor)
    {
        $this->nombre = $valor;
    }
    public function set_apellido($valor)
    {
        $this->apellido = $valor;
    }
    public function set_cedula($valor)
    {
        $this->cedula = $valor;
    }
    public function set_telefono($valor)
    {
        $this->telefono = $valor;
    }
    public function set_fecha_nacimiento($valor)
    {
        $this->fecha_nacimiento = $valor;
    }
    public function set_genero($valor)
    {
        $this->genero = $valor;
    }
    public function set_email($valor)
    {
        $this->email = $valor;
    }
    public function set_pais($valor)
    {
        $this->pais = $valor;
    }
    public function set_ciudad($valor)
    {
        $this->ciudad = $valor;
    }

    // --- Getters ---
    public function get_id_paciente()
    {
        return $this->id_paciente;
    }
    public function get_nombre()
    {
        return $this->nombre;
    }
    public function get_apellido()
    {
        return $this->apellido;
    }
    public function get_cedula()
    {
        return $this->cedula;
    }
    public function get_telefono()
    {
        return $this->telefono;
    }
    public function get_fecha_nacimiento()
    {
        return $this->fecha_nacimiento;
    }
    public function get_genero()
    {
        return $this->genero;
    }
    public function get_email()
    {
        return $this->email;
    }
    public function get_pais()
    {
        return $this->pais;
    }
    public function get_ciudad()
    {
        return $this->ciudad;
    }

    // **********************************************
    // * FUNCIÓN DE VALIDACIÓN CON preg_match *
    // **********************************************

    /**
     * Valida todos los atributos antes de un registro o modificación.
     * @throws Exception Si algún campo no cumple con el patrón.
     */
    private function validarAtributos()
    {
        // Patrones del modelo anterior
        $patron_nombre = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,50}$/";
        $patron_cedula = "/^\d{7,15}$/";
        $patron_telefono = "/^[\d\s-]{8,15}$/";

        // Nuevos patrones y ajustes
        $patron_genero = "/^(Masculino|Femenino|Otro)$/i";
        $patron_ubicacion = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s\d,.\-]{2,50}$/"; // Para país/ciudad

        // 1. Validar CÉDULA
        if (!preg_match($patron_cedula, $this->cedula)) {
            throw new Exception("La Cédula no tiene un formato válido (solo números, 7-15 dígitos).");
        }

        // 2. Validar NOMBRE
        if (!preg_match($patron_nombre, $this->nombre)) {
            throw new Exception("El Nombre no tiene un formato válido (solo letras, espacios, 2-50 caracteres).");
        }

        // 3. Validar APELLIDO
        if (!preg_match($patron_nombre, $this->apellido)) {
            throw new Exception("El Apellido no tiene un formato válido (solo letras, espacios, 2-50 caracteres).");
        }

        // 4. Validar TELÉFONO
        if (!preg_match($patron_telefono, $this->telefono)) {
            throw new Exception("El Teléfono no tiene un formato válido (8-15 caracteres, solo números y guiones).");
        }

        // 5. Validar EMAIL
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("El Email no es válido.");
        }

        // 6. Validar GÉNERO
        if (!preg_match($patron_genero, $this->genero)) {
            throw new Exception("El Género no es válido.");
        }

        // 7. Validar FECHA DE NACIMIENTO (formato YYYY-MM-DD y que sea una fecha válida)
        $d = \DateTime::createFromFormat('Y-m-d', $this->fecha_nacimiento);
        if (!$d || $d->format('Y-m-d') !== $this->fecha_nacimiento || $d > new \DateTime()) {
            throw new Exception("La Fecha de Nacimiento no es válida o es una fecha futura.");
        }
    }


    /**
     * Comprueba la existencia de un paciente por cédula.
     * @param string $cedula La cédula a verificar.
     * @return bool
     */

    public function listarpaciente()
    {
        // Preparamos una consulta SQL para seleccionar todos los campos (*) de la tabla 'paciente'.
        // $this->pdo->query() es adecuado para consultas que no necesitan parámetros.
        $stmt = $this->pdo->query("SELECT p.*, u.ciudad, u.pais
        FROM paciente p
        LEFT JOIN ubicacion u ON p.id_ubicacion = u.id_ubicacion
        ");
        // fetchAll(PDO::FETCH_ASSOC) recupera todas las filas del resultado de la consulta
        // como un array asociativo, donde las claves son los nombres de las columnas.
        $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($pacientes as &$paciente) {
            if (!empty($paciente['foto'])) {
                $paciente['foto'] = 'img/usuarios/' . $paciente['foto'];
            } else {
                $paciente['foto'] = '';
            }
        }
        unset($paciente);

        return $pacientes;
    }

    private function existe($cedula)
    {
        try {
            $consulta = $this->pdo->prepare("SELECT id_paciente FROM paciente WHERE cedula = :cedula");
            $consulta->bindValue(':cedula', $cedula);
            $consulta->execute();
            return $consulta->fetch(PDO::FETCH_ASSOC) !== false;
        } catch (Exception $e) {
            error_log("Error en existe (Paciente): " . $e->getMessage());
            return false;
        }
    }


    /**
     * Registra un nuevo paciente y su ubicación en una transacción.
     * @return array Resultado de la operación.
     */
    public function registrar()
    {
        try {
            $this->validarAtributos();
        } catch (Exception $e) {
            return ["resultado" => "error", "mensaje" => $e->getMessage()];
        }

        if ($this->existe($this->cedula)) {
            return ["resultado" => "cedula_existe", "mensaje" => "La cédula ya está registrada."];
        }

        try {
            $this->pdo->beginTransaction();

            // INSERT UBICACION
            $sqlUb = "INSERT INTO ubicacion (pais, ciudad) VALUES (:pais, :ciudad)";
            $qp = $this->pdo->prepare($sqlUb);
            $qp->execute([
                ":pais" => $this->pais,
                ":ciudad" => $this->ciudad
            ]);

            $this->id_ubicacion = $this->pdo->lastInsertId();

            if (!$this->id_ubicacion || $this->id_ubicacion == "0") {
                throw new Exception("Error: La tabla ubicación no tiene un AUTO_INCREMENT válido.");
            }

            // INSERT PACIENTE
            $sqlPac = "INSERT INTO paciente 
                (cedula, nombre, apellido, telefono, fecha_nacimiento, genero, email, id_ubicacion)
                VALUES (:cedula, :nombre, :apellido, :telefono, :fecha, :genero, :email, :idU)";

            $qp2 = $this->pdo->prepare($sqlPac);
            $qp2->execute([
                ":cedula" => $this->cedula,
                ":nombre" => $this->nombre,
                ":apellido" => $this->apellido,
                ":telefono" => $this->telefono,
                ":fecha" => $this->fecha_nacimiento,
                ":genero" => $this->genero,
                ":email" => $this->email,
                ":idU" => $this->id_ubicacion
            ]);

            $this->pdo->commit();
            return ["resultado" => "registrar", "mensaje" => "Registro exitoso"];
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return ["resultado" => "error", "mensaje" => $e->getMessage()];
        }
    }



    /**
     * Modifica los datos del paciente y su ubicación.
     * @return array Resultado de la operación.
     */
    public function modificar()
    {
        // 1. Ejecutar la validación antes de continuar
        try {
            $this->validarAtributos();
        } catch (Exception $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }

        $respuesta = array();

        // Primero, obtener el id_ubicacion actual del paciente
        $consulta = $this->pdo->prepare("SELECT id_ubicacion FROM paciente WHERE cedula = :cedula");
        $consulta->bindParam(':cedula', $this->cedula);
        $consulta->execute();
        $id_ubicacion_actual = $consulta->fetchColumn();

        if (!$id_ubicacion_actual) {
            return ['resultado' => 'error', 'mensaje' => "El paciente con esta cédula no existe."];
        }

        $this->pdo->beginTransaction(); // 👈 INICIA LA TRANSACCIÓN

        try {
            // ----------------------------------------------------
            // PASO 1: ACTUALIZAR LA UBICACIÓN
            // ----------------------------------------------------
            $qp_ubicacion = $this->pdo->prepare("UPDATE ubicacion SET pais = :pais, ciudad = :ciudad WHERE id_ubicacion = :id_ubicacion");
            $qp_ubicacion->bindParam(':pais', $this->pais);
            $qp_ubicacion->bindParam(':ciudad', $this->ciudad);
            $qp_ubicacion->bindParam(':id_ubicacion', $id_ubicacion_actual);
            $qp_ubicacion->execute();

            // ----------------------------------------------------
            // PASO 2: ACTUALIZAR EL PACIENTE
            // ----------------------------------------------------
            $sql_paciente = "UPDATE paciente SET 
                             nombre = :nombre, apellido = :apellido, telefono = :telefono, 
                             fecha_nacimiento = :fecha_nacimiento, genero = :genero, email = :email 
                             WHERE cedula = :cedula";

            $qp_paciente = $this->pdo->prepare($sql_paciente);

            $qp_paciente->bindParam(':cedula', $this->cedula);
            $qp_paciente->bindParam(':nombre', $this->nombre);
            $qp_paciente->bindParam(':apellido', $this->apellido);
            $qp_paciente->bindParam(':telefono', $this->telefono);
            $qp_paciente->bindParam(':fecha_nacimiento', $this->fecha_nacimiento);
            $qp_paciente->bindParam(':genero', $this->genero);
            $qp_paciente->bindParam(':email', $this->email);

            $qp_paciente->execute();

            $this->pdo->commit(); // 👈 CONFIRMA LA TRANSACCIÓN

            $respuesta['resultado'] = 'modificar';
            $respuesta['mensaje'] = "Datos de paciente y ubicación modificados";
        } catch (Exception $e) {
            $this->pdo->rollBack(); // 👈 DESHACE LA TRANSACCIÓN
            error_log("Error de modificación (Paciente/Ubicación): " . $e->getMessage());
            $respuesta['resultado'] = 'error';
            $respuesta['mensaje'] = "Error al modificar: " . $e->getMessage();
        }

        return $respuesta;
    }


    /**
     * Elimina el paciente (y potencialmente la ubicación, si no es usada por nadie más).
     * NOTA: Aquí solo eliminamos el paciente. La eliminación de la ubicación depende de la lógica de tu BD 
     * (e.g., ON DELETE CASCADE en la clave foránea o un borrado explícito).
     * @return array Resultado de la operación.
     */
    public function eliminar()
    {
        $patron_cedula = "/^\d{7,15}$/";

        if (!preg_match($patron_cedula, $this->cedula)) {
            return ['resultado' => 'error', 'mensaje' => "Formato de Cédula de entrada no válido."];
        }

        $respuesta = array();

        $this->pdo->beginTransaction(); // 👈 INICIA LA TRANSACCIÓN

        try {
            // 1. Obtener el ID de ubicación antes de borrar el paciente
            $consulta = $this->pdo->prepare("SELECT id_ubicacion FROM paciente WHERE cedula = :cedula");
            $consulta->bindParam(':cedula', $this->cedula);
            $consulta->execute();
            $id_ubicacion_a_eliminar = $consulta->fetchColumn();

            if (!$id_ubicacion_a_eliminar) {
                $this->pdo->rollBack();
                return ['resultado' => 'eliminar', 'mensaje' => 'El paciente no existe'];
            }

            // 2. Eliminar el paciente
            $qp = $this->pdo->prepare("DELETE FROM paciente WHERE cedula = :cedula");
            $qp->bindParam(':cedula', $this->cedula);
            $qp->execute();

            // 3. Opcional: Eliminar la ubicación (solo si nadie más la usa)
            // Esto es más complejo y generalmente se maneja con triggers o lógica de la BD. 
            // Para mantener la simplicidad, solo borraremos el paciente.
            // Si la clave foránea tiene ON DELETE CASCADE, esto borrará la ubicación también.
            // Si quieres borrar la ubicación manualmente y no la usa nadie más:
            /*
            $qp_ubicacion = $this->pdo->prepare("DELETE FROM ubicacion WHERE id_ubicacion = :id_ubicacion AND NOT EXISTS (SELECT 1 FROM paciente WHERE id_ubicacion = :id_ubicacion)");
            $qp_ubicacion->bindParam(':id_ubicacion', $id_ubicacion_a_eliminar);
            $qp_ubicacion->execute();
            */

            $this->pdo->commit(); // 👈 CONFIRMA LA TRANSACCIÓN

            $respuesta['resultado'] = 'eliminar';
            $respuesta['mensaje'] = 'Paciente Eliminado';
        } catch (Exception $e) {
            $this->pdo->rollBack(); // 👈 DESHACE LA TRANSACCIÓN
            error_log("Error de eliminación (Paciente): " . $e->getMessage());
            $respuesta['resultado'] = 'error';
            $respuesta['mensaje'] = "Error al eliminar: " . $e->getMessage();
        }

        return $respuesta;
    }


    /**
     * Consulta y devuelve filas de pacientes, incluyendo datos de ubicación.
     * @return array Resultado de la operación con las filas formateadas.
     */
    public function consultar()
    {
        $respuesta = array();
        try {
            // Usamos un JOIN para obtener los datos de ubicación
            $sql = "SELECT p.*, u.pais, u.ciudad 
                    FROM paciente p
                    JOIN ubicacion u ON p.id_ubicacion = u.id_ubicacion
                    ORDER BY p.nombre";

            $consulta = $this->pdo->query($sql);

            $mostrar = '';
            if ($consulta) {
                foreach ($consulta->fetchAll(PDO::FETCH_ASSOC) as $item) {
                    // La columna 'id_paciente' (item[0] si fuera FETCH_NUM) se oculta usualmente
                    // Usamos FETCH_ASSOC para mayor claridad en el código
                    $mostrar .= "<tr class='odd:bg-blue-200 even:bg-gray-50'>";

                    $mostrar .= "<td class='p-3'>" . htmlspecialchars($item['cedula']) . "</td>";
                    $mostrar .= "<td class='p-3'>" . htmlspecialchars($item['nombre']) . "</td>";
                    $mostrar .= "<td class='p-3'>" . htmlspecialchars($item['apellido']) . "</td>";
                    $mostrar .= "<td class='p-3'>" . htmlspecialchars($item['telefono']) . "</td>";
                    $mostrar .= "<td class='p-3'>" . htmlspecialchars($item['email']) . "</td>";
                    $mostrar .= "<td class='p-3'>" . htmlspecialchars($item['fecha_nacimiento']) . "</td>";
                    $mostrar .= "<td class='p-3'>" . htmlspecialchars($item['genero']) . "</td>";
                    // Nuevos campos de ubicación
                    $mostrar .= "<td class='p-3'>" . htmlspecialchars($item['pais']) . "</td>";
                    $mostrar .= "<td class='p-3'>" . htmlspecialchars($item['ciudad']) . "</td>";

                    // Columna de ACCIONES
                    $mostrar .= "<td class='p-2'>
                        <a onclick='actualizarCampos(this, event)' href='#' 
                            class='btn btn-sm btn-editar' 
                            data-bs-toggle='modal' 
                            data-bs-target='#modalModificar'>
                            <i class='fa-solid fa-pencil fa-lg'></i>
                        </a>
                    </td>";
                    $mostrar .= "<td class='p-2'>
                        <a onclick='eliminarPaciente(this, event)' href='#' 
                            class='btn btn-sm btn-eliminar' 
                            title='Eliminar Paciente'>
                            <i class='fa-solid fa-trash fa-lg'></i>
                        </a>
                    </td>";
                    $mostrar .= "</tr>";
                }

                $respuesta['resultado'] = 'consultar';
                $respuesta['mensaje'] = $mostrar;
            } else {
                $respuesta['resultado'] = 'consultar';
                $respuesta['mensaje'] = ''; // No hay resultados
            }
        } catch (Exception $e) {
            error_log("Error de consulta (Paciente): " . $e->getMessage());
            $respuesta['resultado'] = 'error';
            $respuesta['mensaje'] = $e->getMessage();
        }
        return $respuesta;
    }

    // El método obtener_perfil_completo() del modelo Personal no aplica para este caso
    // ni el cambiar_password(), por lo que han sido omitidos.
}
