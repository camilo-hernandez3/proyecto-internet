<?php
require_once "../models/equipos.php";


session_start();
$var_session = $_SESSION['id_usuario'];
$rol = $_SESSION['rol'];


if(isset($_POST["id_equipo"])){

	$user = new Equipo();
	$id_usuario= $_POST["id_equipo"];
	echo json_encode($user -> show($id_usuario));

}


if(isset($_POST["equipo_edit"])){

	$user = new Equipo();
	$user_edit= $_POST["equipo_edit"];
    
	echo json_encode($user -> EditEquipo($user_edit));

}



if(isset($_POST["all"])){

	$equipo = new Equipo();
	$response = [
		'rol' => $rol,
		'data' => $equipo -> index($var_session)
	];

	echo json_encode($response);

}

if(isset($_POST["list_dispositivos"])){

	$equipo = new Equipo();
	echo json_encode($equipo -> ListadoEquipos());

}

if(isset($_POST["id_eliminar"])){

	$user = new Equipo();
	$id_eliminar= $_POST["id_eliminar"];
    
	echo json_encode($user -> destroy($id_eliminar));

}

if(isset($_POST["new_equipo"])){

	$user = new Equipo();
	$newUser= $_POST["new_equipo"];
	echo json_encode($user -> store($newUser));

}

if(isset($_POST["historial"])){

	$equipo = new Equipo();
	$id_equipo= $_POST["id_equipo"];
	echo json_encode($equipo -> historial($id_equipo));

}