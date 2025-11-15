<?php

use Yahir\Compo\Register as RegisterModelo;

if (is_file('./View/register.php')) {

    if (!empty($_POST)) {
        $o = new RegisterModelo();
        $accion = $_POST['accion'] ?? '';

        // ========== REGISTRAR / MODIFICAR ==========
        $o->set_id(!empty($_POST['id']) ? $_POST['id'] : '');
        $o->set_cedula($_POST['cedula'] ?? '');
        $o->set_name($_POST['name'] ?? ''); 
        $o->set_lastName($_POST['lastName'] ?? '');
        $o->set_mail($_POST['mail'] ?? '');
        $o->set_password($_POST['password'] ?? '');
        $o->set_birthDate($_POST['birthDate'] ?? '');
        $o->set_gender($_POST['gender'] ?? '');
        $o->set_role($_POST['role'] ?? '');

        if ($accion === 'incluir') {
            $o->set_id('');
            echo json_encode($o->incluir());
            exit;
        }
    
         if ($accion === 'modificar') {
            echo json_encode($o->modificar());
            exit;
        }
    }


    require_once('./View/register.php');
} else {
    echo 'No encontrado';
}

?>
