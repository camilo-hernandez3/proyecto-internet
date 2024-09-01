<?php
require('../fpdf/fpdf.php');
require('../models/compra.php');
$nw = new Compras();
$id = $_GET['id_compra'];


class pdf extends FPDF
{
	public function header()
	{
		$nw = new Compras();
		$id = $_GET['id_compra'];
		
		$order = $nw->getCompra($id);
		$this->SetFillColor(57, 38, 107);
		$this->Rect(0, 0, 220, 50, 'F');
		$this->SetY(20);
		$this->SetFont('Arial', 'B', 30);
		$this->SetTextColor(255, 255, 255);
		$this->Write(-20, 'Tienda del soldado GSECO');
		$this->Ln();
		$this->Write(45, 'FACTURA DE COMPRA');
		$this->Ln();
		$this->SetFont('Arial', 'I', 20);
		$this->Write(-25, '#GSECO-');
		$this->Write(-25, $order->id_ingreso);
		$this->Image('../img/logo.png', 150, 2, 60, 50);
		$this->Ln();
		$this->SetFont('Arial', 'I', 10);
		$this->Ln();
		$this->Write(90, 'jose.jairo.fuentes@gmail.com');
		$this->Ln();
		$this->Write(-80, '123-456-7890');
	}

	public function footer()
	{
		$this->SetFillColor(57, 38, 107);
		$this->Rect(0, 260, 220, 20, 'F');
	}
}

$fpdf = new pdf('P', 'mm', 'letter', true);
$fpdf->AddPage('portrait', 'letter');
$fpdf->SetMargins(10, 30, 20, 20);
$fpdf->SetFont('Arial', 'I', 12);
$fpdf->SetTextColor(0, 0, 0);

$order = $nw->getCompra($id);

$order_details = $nw->getCompraDetails($id);

$fpdf->SetFillColor(0, 0, 0);
$fpdf->SetY(60);
$fpdf->SetX(10);
$fpdf->Write(5, 'Fecha:');
$fpdf->SetX(25);
$fechaFormateada = date("d/m/Y H:i", strtotime($order->fecha));
$fpdf->Write(5, $fechaFormateada);
$fpdf->Ln();

$fpdf->Write(5, 'Compra realizada por: ');
$nombreCompleto = ucwords($order->nombre_completo);
$fpdf->Write(5, $nombreCompleto);
$fpdf->Ln();
$fpdf->Ln();
$fpdf->SetFont('Arial', '', 9);
$fpdf->SetTextColor(255, 255, 255);
$fpdf->SetFillColor(57, 38, 107);
$fpdf->Cell(60, 10, 'PRODUCTO', 0, 0, 'C', 1);
$fpdf->Cell(60, 10, 'VALOR UNITARIO', 0, 0, 'C', 1);
$fpdf->Cell(40, 10, 'CANTIDAD', 0, 0, 'C', 1);
$fpdf->Cell(30, 10, 'TOTAL DE VENTA', 0, 0, 'C', 1);
$fpdf->Ln();

$fpdf->SetTextColor(0, 0, 0);
$fpdf->SetFillColor(255, 255, 255);
foreach ($order_details as $detail) {
	$precio = number_format($detail->precio, 0, '', '.');
	$cantidad = number_format($detail->cantidad, 0, '', '.');
	$total = number_format($detail->cantidad * $detail->precio, 0, '', '.');
	$fpdf->Cell(60, 10, $detail->nombre, 0, 0, 'C', 1);
	$fpdf->Cell(60, 10, "$" . $precio, 0, 0, 'C', 1);
	$fpdf->Cell(40, 10, $cantidad, 0, 0, 'C', 1);
	$fpdf->Cell(30, 10, "$" . $total, 0, 0, 'C', 1);
	/* 	$fpdf->Cell(60, 10, $detail->product_name, 0, 0, 'C', 1);
	$fpdf->Cell(60, 10, $detail->description, 0, 0, 'C', 1);
	$fpdf->Cell(20, 10, $detail->unit_price, 0, 0, 'C', 1);
	$fpdf->Cell(20, 10, $detail->quantity, 0, 0, 'C', 1);
	$fpdf->Cell(30, 10, $detail->uni_price * $detail->quantity, 0, 0, 'C', 1); */
	$fpdf->Ln();
}
$fpdf->Ln();
$fpdf->Ln();
$fpdf->SetFont('Arial', 'B', 16);
$fpdf->SetX(150);
$fpdf->Write(0, 'TOTAL: ');
$totalEnPesos = number_format($order->total, 0, '', '.');
$fpdf->SetFont('Arial', '', 16);
$fpdf->Write(0, "$" . $totalEnPesos);
$fpdf->OutPut();
