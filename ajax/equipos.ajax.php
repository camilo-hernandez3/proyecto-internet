<?php
require_once "../models/equipos.php";


if(isset($_POST["all"])){

	$equipo = new Equipo();
	echo json_encode($equipo -> index());

}

if(isset($_POST["list_dispositivos"])){

	$equipo = new Equipo();
	echo json_encode($equipo -> ListadoEquipos());

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