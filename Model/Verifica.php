<?php
class Verifica{
	function leesesion(){
		$r = array();
		if(empty($_SESSION)){
		  session_start();
		}
	  	if(isset($_SESSION['nivel'])){
			$r['nivel'] = $_SESSION['nivel'];
			$r['id'] = $_SESSION['id'];
			$r['usu'] = $_SESSION['usu'];
		}	  
		else{
		    $r['nivel'] = "";
			$r['id'] = "";
			$r['usu'] = "";
		}
		return $r;
	}
	function destruyesesion(){
		session_start();
		session_destroy();
		header("Location: . ");
	}
}
?>