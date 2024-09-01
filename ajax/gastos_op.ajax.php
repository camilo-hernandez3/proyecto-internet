<?php
require_once "../models/gastos.php";

session_start();
$var_session = $_SESSION['id_usuario'];


if(isset($_POST["no_operacionales"])){

	$gastos = new Gastos();

	$range= $_POST["range_dates"];

	echo json_encode($gastos -> gastosPorCategoria(1,$range));

}

if(isset($_POST["operacionales"])){

	$gastos = new Gastos();

	$range= $_POST["range_dates"];

	echo json_encode($gastos -> gastosPorCategoria(2,$range));

}


