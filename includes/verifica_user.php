<?php 
include_once "comum.php";
		
if ( is_session_started() === FALSE ) {
    session_start();
}

error_log("LOGIN");

if(!isset($_SESSION["id_usuario"]) || !isset($_SESSION["nome_usuario"])) 
{ 
    header("Location: login.php"); 
    exit; 
}
?>