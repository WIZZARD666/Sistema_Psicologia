<?php

//esto nomas destruye la sesión del usuario
session_destroy();
//luego redirige al main
header("Location: ?page=main");
exit;

?>