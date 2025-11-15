<?php
namespace Yahir\Compo;

use Yahir\Compo\Conexion;
use PDO;
use Exception;

class Login extends Conexion {

    private $cedula;
    private $password;

    function set_cedula($valor) { 
        $this->cedula = $valor; 
    }
    function set_password($valor) { 
        $this->password = $valor; 
    }

    /**
     * Verifica la existencia y credenciales de un usuario.
     * @return array Array con resultado ('existe'/'noexiste'/'error'), nivel, usu y id.
     */
    function existe() {
        Conexion::conectar();
        $q = Conexion::getConexion();
        $q->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            // Se selecciona el ID, la cédula, el rol y la contraseña.
            $p = $q->prepare("SELECT id_personal, cedula, rol, password FROM personal WHERE cedula = :cedula LIMIT 1");
            $p->bindParam(':cedula', $this->cedula);
            $p->execute();

            $fila = $p->fetch(PDO::FETCH_ASSOC);

            if ($fila) {
                // Se eliminan espacios en blanco de las contraseñas
                $pwd_ingresada = trim($this->password);
                $pwd_almacenada = trim($fila['password'] ?? '');

                // ⚠️ Verificación de contraseña en texto plano (PELIGROSO)
                if ($pwd_ingresada === $pwd_almacenada) {
                    
                    // Autenticación Exitosa
                    return [
                        'resultado' => 'existe',
                        'nivel' => $fila['rol'] ?? '',
                        'usu' => $fila['cedula'] ?? '', // Cédula del usuario
                        'id' => $fila['id_personal'] ?? '' // ID de la tabla
                    ];
                } else {
                    // Contraseña Incorrecta
                    return [
                        'resultado' => 'noexiste',
                        'mensaje' => 'Usuario y/o Contraseña Incorrecta'
                    ];
                }
            } else {
                // Usuario no encontrado
                return [
                    'resultado' => 'noexiste',
                    'mensaje' => 'Usuario no encontrado'
                ];
            }
        } catch (Exception $e) {
            // Error de Base de Datos
            error_log("Error en Login::existe(): " . $e->getMessage());
            return [
                'resultado' => 'error',
                'mensaje' => 'Error en el servidor de base de datos'
            ];
        }
    }
}



