<?php
require('../fpdf/fpdf.php');
require('../models/venta.php');

$id = $_GET['id_usuario'];
$fecha_inicio = $_GET['fecha_inicio'];
$fecha_final = $_GET['fecha_final'];
$venta = new Venta();
$ventas_rango = $venta->ventasPorRangoDetalle($id, $fecha_inicio, $fecha_final);


class pdf extends FPDF
{
	public function header()
	{
		

	
		$this->SetFillColor(57, 38, 107);
		$this->Rect(0, 0, 220, 50, 'F');
		$this->SetY(20);
		$this->SetFont('Arial', 'B', 30);
		$this->SetTextColor(255, 255, 255);
		$this->Write(-20, 'Tienda del soldado GSECO');
		$this->Ln();
		$this->Write(45, 'VENTA POR RANGO');
		$this->Ln();
		$this->SetFont('Arial', 'I', 20);
		$this->Image('../img/logo.png', 150, 2, 60, 50);
		$this->Ln();
		$this->SetFont('Arial', 'I', 10);
		$this->Ln();
		$this->Write(90, 'jose.jairo.fuentes@gmail.com');
		$this->Ln();
		$this->Write(-80, '123-456-7890');
		$this->SetY(60);
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




$fpdf->SetFillColor(0, 0, 0);
$fpdf->SetY(60);
$fpdf->SetX(10);
$fpdf->Write(5, 'Fecha:');
$fpdf->SetX(25);
$fechaFormateada = date("d/m/Y H:i");
$fpdf->Write(5, $fechaFormateada);
$fpdf->Ln();
$fpdf->Ln();

/* $fpdf->Write(5, 'Venta realizada por: ');
$nombreCompleto = ucwords($order->nombre_completo);
$fpdf->Write(5, $nombreCompleto);
$fpdf->Ln();
$fpdf->Ln(); */
$fpdf->SetFont('Arial', '', 9);
$fpdf->SetTextColor(255, 255, 255);
$fpdf->SetFillColor(57, 38, 107);
$fpdf->Cell(60, 10, 'NOMBRE COMPLETO', 0, 0, 'C', 1);
$fpdf->Cell(60, 10, 'PRODUCTO', 0, 0, 'C', 1);
$fpdf->Cell(40, 10, 'CANTIDAD', 0, 0, 'C', 1);
$fpdf->Cell(30, 10, 'SUBTOTAL', 0, 0, 'C', 1);
$fpdf->Ln();

$fpdf->SetTextColor(0, 0, 0);
$fpdf->SetFillColor(255, 255, 255);
$total = 0;
foreach ($ventas_rango as $v) {
	
	if ($fpdf->GetY() > 250) { // Ajusta este valor según la posición en la que quieras que se agregue una nueva página
	   $fpdf->AddPage(); // Agregar una nueva página
	   
	}
	
	$fpdf->Cell(60, 10, $v->nombres.' '.$v->apellidos, 0, 0, 'C', 1);
	$fpdf->Cell(60, 10, $v->nombre, 0, 0, 'C', 1);
	$fpdf->Cell(30, 10, $v->cantidad_total, 0, 0, 'C', 1); 
	$fpdf->Cell(40, 10,'$ '.  number_format($v->subtotal, 0, '', '.'), 0, 0, 'C', 1);
	$total = $total + $v->subtotal;
	
	$fpdf->Ln();


}
$fpdf->Ln();
$fpdf->SetFont('Arial', 'B', 16);
$fpdf->SetX(100);
$fpdf->Write(0, 'TOTAL: ');
$totalEnPesos = number_format($total, 0, '', '.');
$fpdf->SetFont('Arial', '', 16);
$fpdf->SetX(150);
$fpdf->Write(0, "$" . $totalEnPesos); 

/* $fpdf->Ln();
$fpdf->Ln();
$fpdf->SetFont('Arial', 'B', 16);
$fpdf->SetX(150);
$fpdf->Write(0, 'TOTAL: ');
$totalEnPesos = number_format($order->total, 0, '', '.');
$fpdf->SetFont('Arial', '', 16);
$fpdf->Write(0, "$" . $totalEnPesos); */
$fpdf->OutPut();
