<?php
require_once "../models/compra.php";

session_start();
$var_session = $_SESSION['id_usuario'];


if(isset($_POST["id_factura"])){
	
    $compra = new Compras();
    $id_factura = $_POST["id_factura"];

    echo json_encode($compra->changeStatusFactura($id_factura));
}

if(isset($_POST["all"])){
	
    $compra = new Compras();
    echo json_encode($compra->facturas($var_session));
}
