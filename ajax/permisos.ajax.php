<?php
require_once "../models/permisos.php";


session_start();
$var_session = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];


if(isset($_POST["list_permisos"])){

	$user = new permisos();
	echo json_encode($user -> index());

}

if(isset($_POST["new_equipo"])){

	$user = new Permisos();
	$newUser= $_POST["new_equipo"];
	echo json_encode($user -> store($newUser));

}


