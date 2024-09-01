<?php
require_once('db.php');
date_default_timezone_set('America/Bogota');


class MetodosPago extends Database
{


	public function index()
	{
		$query = $this->pdo->query('SELECT * from  metodos_pago');
		return $query->fetchAll();
	}
}