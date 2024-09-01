<?php
require_once "../models/usuario.php";


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