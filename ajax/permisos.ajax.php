<?php
require_once "../models/permisos.php";


session_start();
$var_session = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];



if(isset($_POST["id_equipo"])){

	$user = new Permisos();
	$id_usuario= $_POST["id_equipo"];
	echo json_encode($user -> show($id_usuario));

}

if(isset($_POST["list_permisos"])){

	$user = new Permisos();
	echo json_encode($user -> index());

}
if(isset($_POST["id_eliminar"])){

	$user = new Permisos();
	$id_eliminar= $_POST["id_eliminar"];
	echo json_encode($user -> destroy($id_eliminar));

}

if(isset($_POST["new_equipo"])){

	$user = new Permisos();
	$newUser= $_POST["new_equipo"];
	echo json_encode($user -> store($newUser));
}

if(isset($_POST["equipo_edit"])){
	
	$user = new Permisos();
	$newUser= $_POST["equipo_edit"];
	echo json_encode($user -> EditEquipo($newUser));
}





