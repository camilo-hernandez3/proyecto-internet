<?php
require_once "../models/permisos.php";
require_once "../models/roles.php";


session_start();
$var_session = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];



if(isset($_POST["id_eliminar"])){

	$user = new Roles();
	$id_usuario= $_POST["id_eliminar"];
	echo json_encode($user -> destroy($id_usuario));

}


if(isset($_POST["idrol"])){

	$user = new Roles();
	$id_usuario= $_POST["idrol"];
	echo json_encode($user -> show($id_usuario));

}

if(isset($_POST["roles"])){

	$user = new Roles();
	echo json_encode($user -> roles());

}

if(isset($_POST["new_equipo"])){

	$user = new Roles();
	$newUser= $_POST["new_equipo"];
	echo json_encode($user -> store($newUser));
}

if(isset($_POST["equipo_edit"])){
	
	$user = new Roles();
	$newUser= $_POST["equipo_edit"];
	echo json_encode($user -> EditEquipo($newUser));
}





