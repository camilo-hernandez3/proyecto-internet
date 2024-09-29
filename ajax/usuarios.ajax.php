<?php
require_once "../models/usuario.php";


if(isset($_POST["id_usuario"])){

	$user = new Usuario();
	$id_usuario= $_POST["id_usuario"];
	echo json_encode($user -> userById($id_usuario));

}

if(isset($_POST["all"])){

	$user = new Usuario();
	echo json_encode($user -> index());

}

if(isset($_POST["id_eliminar"])){

	$user = new Usuario();
	$id_eliminar= $_POST["id_eliminar"];
    
	echo json_encode($user -> destroy($id_eliminar));

}

if(isset($_POST["new_user"])){

	$user = new Usuario();
	$newUser= $_POST["new_user"];
	echo json_encode($user -> store($newUser));

}

if(isset($_POST["user_edit"])){

	$user = new Usuario();
	$user_edit= $_POST["user_edit"];
    
	echo json_encode($user -> editUser($user_edit));

}