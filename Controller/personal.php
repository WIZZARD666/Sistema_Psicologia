<?php

if (!is_file("./Model/Personal.php")) {
    echo "No se encontro modelo" . $pagina;
    exit;
}

// Incluir el archivo del modelo para que la clase esté disponible
require_once("./Model/Personal.php");

use Yahir\Compo\Personal as PersonalModelo;


if (is_file('./View/personal.php')) {

    if (!empty($_POST)) {
        $o = new PersonalModelo();
        // Evitar warning cuando 'accion' no viene en el POST
        $accion = $_POST['accion'] ?? '';

        if ($accion === 'consultar') {
            echo json_encode($o->consultar());
        } else if ($accion === 'eliminar') {
                $o->set_cedula($_POST['cedula']);
                echo json_encode($o->eliminar());
        } else {

            $o->set_cedula($_POST['cedula']?? '');
            $o->set_nombre($_POST['nombre']?? '');
            $o->set_apellido($_POST['apellido']?? '');
            $o->set_telefono($_POST['telefono']?? '');
            $o->set_direccion($_POST['direccion']?? '');
            $o->set_rol($_POST['rol']?? '');
            $o->set_password($_POST['password']?? '');

            if ($accion === 'registrar') {
                echo json_encode($o->registrar());
            }
            else if ($accion === 'modificar') {
                echo json_encode($o->modificar());
            }
        }

        exit;
    }


    require_once('./View/personal.php');
} else {
    echo 'No encontrado';
}

