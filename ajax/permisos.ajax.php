<?php
require_once "../models/permisos.php";


session_start();
$var_session = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];


if(isset($_POST["list_permisos"])){

	$user = new permisos();
	echo json_encode($user -> index());

}


