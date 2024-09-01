<?php
require('../fpdf/fpdf.php');
require('../models/usuario.php');
$nw = new Usuario();
$id = 1;
class pdf extends FPDF
{
	public function header()
	{
		$this->SetFillColor(2, 178,236);
		$this->Rect(0,0, 220, 50, 'F');
		$this->SetY(25);
		$this->SetFont('Arial', 'B', 30);
		$this->SetTextColor(255,255,255);
		$this->Write(5, 'Reporte de usuarios');
		$this->Image('../img/logo.png', 160, 15,30,30);

	}

	public function footer()
	{
		$this->SetFillColor(253, 135,39);
		$this->Rect(0, 250, 220, 50, 'F');
		$this->SetY(-20);
		$this->SetFont('Arial', '', 12);
		$this->SetTextColor(255,255,255);
		$this->SetX(120);
		$this->Write(5, 'Tienda del soldado GSECO');
		$this->Ln();
		$this->SetX(120);
		$this->Write(5, 'jose.jairo.fuentes@gmail.com');
		$this->SetX(120);
		$this->Ln();
		$this->SetX(120);
		$this->Write(5, '+(503)7889-8787');
	}
}

$fpdf = new pdf('P','mm','letter',true);
$fpdf->AddPage('portrait', 'letter');
$fpdf->SetMargins(10,30,20,20);
$fpdf->SetFont('Arial', '', 12);
$fpdf->SetTextColor(0,0,0);
$users = $nw->index();



$fpdf->SetY(70);
$fpdf->SetFont('Arial', '', 9);
$fpdf->SetTextColor(255,255,255);
$fpdf->SetFillColor(79,78,77);
$fpdf->Cell(30, 10, 'NOMBRES', 0, 0, 'C', 1);
$fpdf->Cell(30, 10, 'APELLIDOS', 0, 0, 'C', 1);
$fpdf->Cell(60, 10, 'NUM DOCUMENTO', 0, 0, 'C', 1);
$fpdf->Cell(60, 10, 'EMAIL', 0, 0, 'C', 1);
$fpdf->Ln();

$fpdf->SetTextColor(0,0,0);
$fpdf->SetFillColor(255,255,255);
foreach($users as $user)
{
	$fpdf->Cell(30, 10, $user->nombres, 0, 0, 'C', 1);
	$fpdf->Cell(30, 10, $user->apellidos, 0, 0, 'C', 1);
	$fpdf->Cell(60, 10, $user->num_documento, 0, 0, 'C', 1);
	$fpdf->Cell(60, 10, $user->email, 0, 0, 'C', 1);

	$fpdf->Ln();
}
$fpdf->OutPut();