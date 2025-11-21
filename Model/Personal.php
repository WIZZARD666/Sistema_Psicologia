<?php

namespace Yahir\Compo;

use Yahir\Compo\Conexion;
use PDO;
use Exception;

class Personal extends Conexion
{
    private $id;
    private $cedula;
    private $nombre;
    private $apellido;
    private $telefono;
    private $direccion;
    private $rol;
    private $password;

    // Setters (Dejo los Setters sin modificar, solo asignan el valor)
    public function set_cedula($valor)
    {
        $this->cedula = $valor;
    }
    public function set_nombre($valor)
    {
        $this->nombre = $valor;
    }
    public function set_apellido($valor)
    {
        $this->apellido = $valor;
    }
    public function set_telefono($valor)
    {
        $this->telefono = $valor;
    }
    public function set_direccion($valor)
    {
        $this->direccion = $valor;
    }
    public function set_rol($valor)
    {
        $this->rol = $valor;
    }
    public function set_password($valor)
    {
        $this->password = $valor;
    }

    // Getters
    public function get_cedula()
    {
        return $this->cedula;
    }
    public function get_nombre()
    {
        return $this->nombre;
    }
    public function get_apellido()
    {
        return $this->apellido;
    }
    public function get_telefono()
    {
        return $this->telefono;
    }
    public function get_direccion()
    {
        return $this->direccion;
    }
    public function get_rol()
    {
        return $this->rol;
    }
    public function get_password()
    {
        return $this->password;
    }

    // **********************************************
    // * FUNCIÓN DE VALIDACIÓN CON preg_match *
    // **********************************************

    /**
     * Valida todos los atributos antes de un registro o modificación.
     * @param bool $validar_password Si es true, la validación de la clave es obligatoria (ej: en 'registrar').
     * @throws Exception Si algún campo no cumple con el patrón.
     */
    private function validarAtributos(bool $validar_password = true)
    {
        // Caracteres permitidos en Nombres/Apellidos (letras, espacios, acentos, ñ)
        $patron_nombre = "/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]{2,50}$/";
        // Cédula: asumimos solo dígitos, entre 7 y 15 (ajustar si usa guiones o letras)
        $patron_cedula = "/^\d{7,15}$/";
        // Teléfono: asumimos dígitos, opcionalmente espacios o guiones, entre 8 y 15 caracteres
        $patron_telefono = "/^[\d\s-]{8,15}$/";
        // Dirección: permite letras, números, espacios, comas, puntos y guiones
        $patron_direccion = "/^[a-zA-Z0-9\s,.\-áéíóúÁÉÍÓÚñÑ]{5,100}$/";
        // Rol: asumimos solo letras minúsculas o mayúsculas
        $patron_rol = "/^[a-zA-Z]{3,20}$/";

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

        // 5. Validar DIRECCIÓN
        if (!preg_match($patron_direccion, $this->direccion)) {
            throw new Exception("La Dirección no tiene un formato válido (5-100 caracteres, sin caracteres especiales).");
        }

        // 6. Validar ROL
        if (!preg_match($patron_rol, $this->rol)) {
            throw new Exception("El Rol no tiene un formato válido (solo letras, 3-20 caracteres).");
        }

        // 7. Validar PASSWORD (Solo si $validar_password es true y el campo no está vacío)
        if ($validar_password && !empty($this->password)) {
            // Patrón de clave segura: Mínimo 8 caracteres, al menos 1 mayúscula, 1 minúscula, 1 número.
            $patron_clave = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[a-zA-Z\d\W]{8,}$/";

            if (!preg_match($patron_clave, $this->password)) {
                throw new Exception("La Clave debe tener al menos 8 caracteres, 1 mayúscula, 1 minúscula y 1 número.");
            }
        } elseif ($validar_password && empty($this->password)) {
            // Si $validar_password es true (en 'registrar'), la clave es obligatoria.
            throw new Exception("La clave es obligatoria para el registro.");
        }
    }


    /**
     * Obtiene nombre, apellido y rol de un empleado por su cédula.
     * @param string $cedula La cédula del empleado.
     * @return array|null Un array asociativo con los datos o null si no existe.
     */
    public function obtener_perfil_completo(string $cedula): ?array
    {
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Se recuperan todos los campos que el usuario puede ver/editar en su perfil.
            $consulta = $q->prepare("SELECT cedula, nombre, apellido, telefono, direccion, rol FROM personal WHERE cedula = :cedula LIMIT 1");
            $consulta->bindValue(':cedula', $cedula);
            $consulta->execute();

            return $consulta->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (Exception $e) {
            error_log("Error al obtener perfil completo: " . $e->getMessage());
            return null;
        }
    }


    /**
     * Modifica SOLO la contraseña del empleado.
     * @return array Resultado de la operación.
     */
    public function cambiar_password()
    {
        $respuesta = array();

        // La clave es OBLIGATORIA para esta acción (parametro true)
        try {
            // Patrón de clave segura: Mínimo 8 caracteres, al menos 1 mayúscula, 1 minúscula, 1 número.
            // Uso el patrón exacto que definiste para la validación principal.
            $patron_clave = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[a-zA-Z\d\W]{8,}$/";

            if (empty($this->password)) {
                throw new Exception("La clave es obligatoria.");
            }
            if (!preg_match($patron_clave, $this->password)) {
                throw new Exception("La nueva clave no cumple los requisitos de seguridad.");
            }
            // Aquí puedes añadir: (if ($this->password == $this->password_anterior) { throw ... })
            // si tu clase tiene un atributo para la clave anterior.

        } catch (Exception $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }

        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($this->existe($this->cedula)) {
            try {
                // Actualiza SOLO la contraseña
                $qp = $q->prepare("UPDATE personal SET password = :password WHERE cedula = :cedula");
                $qp->bindParam(':password', $this->password);
                $qp->bindParam(':cedula', $this->cedula);

                $qp->execute();

                $respuesta['resultado'] = 'cambiar_password';
                $respuesta['mensaje'] = "Contraseña actualizada con éxito.";
            } catch (Exception $e) {
                $respuesta['resultado'] = 'error';
                $respuesta['mensaje'] = $e->getMessage();
            }
        } else {
            $respuesta['resultado'] = 'error';
            $respuesta['mensaje'] = "El usuario no existe.";
        }

        return $respuesta;
    }

    // Comprueba existencia por cédula (sin cambios mayores)
    private function existe($cedula)
    {
        // Se puede añadir aquí una validación simple de $cedula si el método es público, 
        // pero como es privado y se llama desde registrar/modificar, confiamos en la validación principal.

        $respuesta = array();
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $consulta = $q->prepare("SELECT * FROM personal WHERE cedula = :cedula");
            $consulta->bindValue(':cedula', $cedula);
            $consulta->execute();
            $fila = $consulta->fetchAll(PDO::FETCH_BOTH);
            if ($fila) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            $respuesta['mensaje'] = $e->getMessage();
            $respuesta['resultado'] = "error";
        }
        // Si hay error en la base de datos, lo más seguro es asumir que no existe y dejar que la operación falle.
        return false;
    }


    // Registrar
    public function registrar()
    {
        // 1. Ejecutar la validación antes de continuar
        try {
            // Aquí la clave es OBLIGATORIA (parametro true)
            $this->validarAtributos(true);
        } catch (Exception $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }

        $respuesta = array();
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!$this->existe($this->cedula)) {
            try {
                // Se ha quitado password_hash() para almacenar la clave en texto plano.
                // RECUERDA: Esto es inseguro y solo debe ser temporal.
                $qp = $q->prepare("INSERT INTO personal (cedula, nombre, apellido, telefono, direccion, rol, password) VALUES (:cedula, :nombre, :apellido, :telefono, :direccion, :rol, :password)");

                $qp->bindParam(':cedula', $this->cedula);
                $qp->bindParam(':nombre', $this->nombre);
                $qp->bindParam(':apellido', $this->apellido);
                $qp->bindParam(':telefono', $this->telefono);
                $qp->bindParam(':direccion', $this->direccion);
                $qp->bindParam(':rol', $this->rol);
                $qp->bindParam(':password', $this->password); // Almacenando la clave sin hash

                $qp->execute();
                $respuesta['resultado'] = 'registrar';
                $respuesta['mensaje'] = "Empleado registrado";
            } catch (Exception $e) {
                $respuesta['resultado'] = 'error';
                $respuesta['mensaje'] = $e->getMessage();
            }
        } else {
            // 🛑 ¡CORRECCIÓN CRÍTICA AQUÍ!
            // Si el empleado ya existe, devolvemos 'cedula_existe' para que el JS active el SweetAlert de error.
            $respuesta['resultado'] = 'cedula_existe';
            $respuesta['mensaje'] = "Ya existe un empleado con esta cedula.";
        }
        return $respuesta;
    }

    // Modificar
    public function modificar()
    {
        // 1. Ejecutar la validación antes de continuar
        try {
            // La clave es OPCIONAL (parametro false). Solo se valida si se proporciona.
            $this->validarAtributos(false);
        } catch (Exception $e) {
            return ['resultado' => 'error', 'mensaje' => $e->getMessage()];
        }

        $respuesta = array();
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($this->existe($this->cedula)) {
            try {
                if (!empty($this->password)) {
                    // Si se proporciona clave, actualiza con la clave en texto plano.
                    $qp = $q->prepare("UPDATE personal SET nombre = :nombre, apellido = :apellido, telefono = :telefono, direccion = :direccion, rol = :rol, password = :password WHERE cedula = :cedula");

                    $qp->bindParam(':password', $this->password); // Almacenando la clave sin hash
                } else {
                    // Si NO se proporciona clave, solo actualiza otros campos
                    $qp = $q->prepare("UPDATE personal SET nombre = :nombre, apellido = :apellido, telefono = :telefono, direccion = :direccion, rol = :rol WHERE cedula = :cedula");
                }

                $qp->bindParam(':cedula', $this->cedula);
                $qp->bindParam(':nombre', $this->nombre);
                $qp->bindParam(':apellido', $this->apellido);
                $qp->bindParam(':telefono', $this->telefono);
                $qp->bindParam(':direccion', $this->direccion);
                $qp->bindParam(':rol', $this->rol);
                $qp->bindParam(':password', $this->password); // Almacenando la clave sin hash

                $qp->execute();
                $respuesta['resultado'] = 'modificar';
                $respuesta['mensaje'] = "Datos de empleado modificados";
            } catch (Exception $e) {
                $respuesta['resultado'] = 'error';
                $respuesta['mensaje'] = $e->getMessage();
            }
        } else {
            $respuesta['resultado'] = 'modificar';
            $respuesta['mensaje'] = "Esta cedula no está registrada";
        }

        return $respuesta;
    }

    // Eliminar
    public function eliminar()
    {
        // Solo necesitamos validar que la cédula sea un campo válido antes de consultar la BD
        // El patrón de cédula debe ser el mismo usado en validarAtributos.
        $patron_cedula = "/^\d{7,15}$/";

        if (!preg_match($patron_cedula, $this->cedula)) {
            return ['resultado' => 'error', 'mensaje' => "Formato de Cédula de entrada no válido."];
        }

        $respuesta = array();
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($this->existe($this->cedula)) {
            try {
                $qp = $q->prepare("DELETE FROM personal WHERE cedula = :cedula");
                $qp->bindParam(':cedula', $this->cedula);
                $qp->execute();
                $respuesta['resultado'] = 'eliminar';
                $respuesta['mensaje'] = 'Empleado Eliminado';
            } catch (Exception $e) {
                $respuesta['resultado'] = 'error';
                $respuesta['mensaje'] = $e->getMessage();
            }
        } else {
            $respuesta['resultado'] = 'eliminar';
            $respuesta['mensaje'] = 'El empleado no existe';
        }

        return $respuesta;
    }

    

    // Consultar y devolver filas formateadas para la tabla (sin validación de entrada, es solo lectura)
    public function consultar()
    {
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $respuesta = array();
        try {
            $consulta = $q->query("SELECT * FROM personal");
            if ($consulta) {
                $mostrar = '';
                foreach ($consulta as $item) {
                    $mostrar .= "<tr class='odd:bg-blue-200 even:bg-gray-50'>";

                    for ($i = 0; $i < 8; $i++) {

                        if ($i === 7) {
                            $mostrar .= "<td class='p-3 d-none'></td>";
                        } else {
                            $mostrar .= "<td class='p-3'>" . htmlspecialchars($item[$i]) . "</td>";
                        }
                    }
                    $mostrar .= "<td class='p-2'>
                <a onclick='actualizarCampos(this, event)' href='#' 
                    class='btn btn-sm btn-editar' 
                    data-bs-toggle='modal' 
                    data-bs-target='#modalModificar'>
                    <i class='fa-solid fa-pencil fa-lg'></i>
                </a>
             </td>";
                    $mostrar .= "<td class='p-2'>
                <a onclick='eliminarCliente(this, event)' href='#' 
                    class='btn btn-sm btn-eliminar' 
                    title='Eliminar Personal'>
                    <i class='fa-solid fa-trash fa-lg'></i>
                </a>
             </td>";
                    $mostrar .= "</tr>";
                }
                $respuesta['resultado'] = 'consultar';
                $respuesta['mensaje'] = $mostrar;
            } else {
                $respuesta['resultado'] = 'consultar';
                $respuesta['mensaje'] = '';
            }
        } catch (Exception $e) {
            $respuesta['resultado'] = 'error';
            $respuesta['mensaje'] = $e->getMessage();
        }
        return $respuesta;
    }
}
