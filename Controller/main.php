<?php
require_once("Model/login.php");
if (session_status() == PHP_SESSION_NONE)
{ session_start(); }

//importa la vista de la página
if (is_file("View/".$pagina.".php"))
{ require_once("View/".$pagina.".php"); }
else
{ echo "page not found"; }

?>