<?php

// 1. Verificar la existencia del nuevo Modelo Paciente
if (!is_file("./Model/Pacientes.php")) {
    echo "No se encontró el modelo Pacientes.";
    exit;
}

// Incluir el archivo del modelo para que la clase esté disponible
require_once("./Model/Pacientes.php");

// Usar el alias del nuevo modelo Pacientes
use Yahir\Compo\Pacientes as PacientesModelo;

function listarpaciente(){
    $paciente = new PacientesModelo();
    return $paciente->listarpaciente();
}

$ruta_vista = './View/pacientes.php';

if (is_file($ruta_vista)) {

    // Si hay datos POST, significa que se está intentando ejecutar una acción CRUD
    if (!empty($_POST)) {
        $o = new PacientesModelo();
        // Evitar warning cuando 'accion' no viene en el POST
        $accion = $_POST['accion'] ?? '';

        if ($accion === 'consultar') {
            // Acción CONSULTAR: Solo se llama al método consultar()
            echo json_encode($o->consultar());
        } 
        else if ($accion === 'eliminar') {
            // Acción ELIMINAR: Solo requiere la cédula
            $o->set_cedula($_POST['cedula'] ?? '');
            echo json_encode($o->eliminar());
        } 
        else {
            // Acciones REGISTRAR o MODIFICAR: Requieren todos los atributos

            // 3. Asignar los nuevos atributos al modelo
            $o->set_cedula($_POST['cedula'] ?? '');
            $o->set_nombre($_POST['nombre'] ?? '');
            $o->set_apellido($_POST['apellido'] ?? '');
            $o->set_telefono($_POST['telefono'] ?? '');
            $o->set_email($_POST['email'] ?? '');
            $o->set_fecha_nacimiento($_POST['fecha_nacimiento'] ?? '');
            $o->set_genero($_POST['genero'] ?? '');
            $o->set_pais($_POST['pais'] ?? ''); // Nuevo campo de ubicación
            $o->set_ciudad($_POST['ciudad'] ?? ''); // Nuevo campo de ubicación

            // Nota: El id_paciente se establece solo si se modifica (a menudo no se pasa en el POST, 
            // pero se usa la cédula como identificador principal).

            if ($accion === 'registrar') {
                echo json_encode($o->registrar());
            }
            else if ($accion === 'modificar') {
                echo json_encode($o->modificar());
            }
        }

        exit; // Detiene la ejecución después de manejar la petición AJAX
    }


        require_once('./View/pacientes.php');

} else {
    echo 'Vista de Pacientes no encontrada:';
}