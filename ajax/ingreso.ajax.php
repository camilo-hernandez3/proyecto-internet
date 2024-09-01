<?php
require_once "../models/ingreso.php";

session_start();
$var_session = $_SESSION['id_usuario'];

if(isset($_POST["productos"])){

	$ingreso = new Ingreso();

	$productos= $_POST["productos"];
	$total= $_POST["total"];
    $ingreso->store($var_session, $total, $productos);

}

if(isset($_POST["range_dates"])){
	$ingreso = new Ingreso();

	$range= $_POST["range_dates"];

	echo json_encode($ingreso -> ventasPorRango($range));

}
