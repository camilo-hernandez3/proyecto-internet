<?php
require_once "../models/balance.php";

session_start();
$var_session = $_SESSION['id_usuario'];


if(isset($_POST["range_dates"])){
	$balance = new Balance();

	$range= $_POST["range_dates"];

	echo json_encode($balance -> balance($range));

}


