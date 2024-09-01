<?php

require_once('db.php');
date_default_timezone_set('America/Bogota');

class Venta extends Database
{
	

	public function ventas($id)
	{
		$rol = $_SESSION['rol'];

		if($rol === 2){
			$query = $this->pdo->query('SELECT sum(total) as total_diario FROM venta WHERE Usuario_id_usuario =' . $id);
			return $query->fetch();
		}else{
			$query = $this->pdo->query('SELECT sum(total) as total_diario FROM venta');
			return $query->fetch();
		}
	}

	public function store($idUsuario, $total, $productos)
	{

		$query = $this->pdo->prepare('INSERT INTO venta (Usuario_id_usuario, total, fecha) VALUES (:usuario_id, :total, :fecha)');


		$query->bindParam(':usuario_id', $idUsuario);
		$query->bindParam(':total', $total);
		$query->bindParam(':fecha', date('Y-m-d H:i:s'));
		
		$query->execute();


		if ($query->rowCount() > 0) {
			echo "Se guardo.";
			$id_venta = $this->pdo->lastInsertId();
			$productos = json_decode($productos);
			foreach ($productos as $articulo) {
				$this->insertDetalleVenta($id_venta, $articulo->id_articulo, $articulo->cantidad, $articulo->precio_venta, $articulo->metodo_pago_id);
				$this->discountProduct($articulo->id_articulo, $articulo->cantidad);
			}
		} else {

			echo "Error en la inserción.";
		}
	}

	public function discountProduct($id_articulo, $cantidad)
	{

		$query = $this->pdo->prepare("UPDATE articulo SET stock = stock - $cantidad WHERE id_articulo = $id_articulo");
		$query->execute();
	}



	public function insertDetalleVenta($id_venta, $id_articulo, $cantidad, $precio, $metodo_pago_id)
	{

		$query = $this->pdo->prepare('INSERT INTO detalle_venta (Venta_id_venta, Articulo_id_articulo, cantidad, precio, metodo_pago) VALUES (:id_venta, :id_articulo, :cantidad, :precio, :metodo_pago)');


		$query->bindParam(':id_venta', $id_venta);
		$query->bindParam(':id_articulo', $id_articulo);
		$query->bindParam(':cantidad', $cantidad);
		$query->bindParam(':precio', $precio);
		$query->bindParam(':metodo_pago', $metodo_pago_id);
		$query->execute();


		if ($query->rowCount() > 0) {
			echo "Se guardó correctamente";
		} else {

			echo "Error en la inserción.";
		}
	}



	public function ultimaVenta($id)
	{
		$rol = $_SESSION['rol'];
		$currentDate = date('Y-m-d');

		if($rol === 2){
			/* $query = $this->pdo->query('SELECT * FROM venta WHERE Usuario_id_usuario ='.$id.' order by fecha desc limit 1'); */
			/* $query = $this->pdo->query("SELECT SUM(total) as total_diario FROM venta WHERE Usuario_id_usuario = {$id} AND DATE(fecha) = '{$currentDate}'"); */
			$query = $this->pdo->query("SELECT SUM(total) as total_diario, 
			(SELECT total FROM venta WHERE Usuario_id_usuario = {$id} AND DATE(fecha) = '{$currentDate}' ORDER BY fecha DESC LIMIT 1) as ultima_venta,
			(SELECT SUM(total) FROM venta  WHERE Usuario_id_usuario = {$id}  AND DATE(fecha) BETWEEN DATE_SUB('{$currentDate}', INTERVAL 30 DAY) AND '{$currentDate}') as total_ultimo_mes FROM venta WHERE Usuario_id_usuario = {$id} AND DATE(fecha) = '{$currentDate}'");
			return $query->fetch();
			
		}else{
			/* $query = $this->pdo->query('SELECT * FROM venta WHERE Usuario_id_usuario ='.$id.' order by fecha desc limit 1'); */
			/* $query = $this->pdo->query("SELECT SUM(total) as total_diario FROM venta WHERE Usuario_id_usuario = {$id} AND DATE(fecha) = '{$currentDate}'"); */
			$query = $this->pdo->query("SELECT SUM(total) as total_diario, 
			(SELECT total FROM venta WHERE DATE(fecha) = '{$currentDate}' ORDER BY fecha DESC LIMIT 1) as ultima_venta,
			(SELECT SUM(total) FROM venta  WHERE DATE(fecha) BETWEEN DATE_SUB('{$currentDate}', INTERVAL 30 DAY) AND '{$currentDate}') as total_ultimo_mes FROM venta WHERE DATE(fecha) = '{$currentDate}'");
			return $query->fetch();
			
		}
	}

	public function facturas($id_usuario)
	{
		$currentDate = date('Y-m-d');
		$rol = $_SESSION['rol'];

		if($rol === 2){
			$query = $this->pdo->query("SELECT venta.id_venta,  DATE_FORMAT(venta.fecha, '%d/%m/%Y %H:%i') AS fecha_venta, GROUP_CONCAT(articulo.nombre SEPARATOR ', ') AS nombres_productos, ROUND(venta.total) AS total, metodos_pago.nombre as tipo_pago, GROUP_CONCAT(metodos_pago.nombre SEPARATOR ', ') AS metodos_pagos
				FROM venta 
				JOIN detalle_venta ON venta.id_venta = detalle_venta.Venta_id_venta 
				JOIN metodos_pago ON metodos_pago.id_metodo_pago = detalle_venta.metodo_pago
				JOIN articulo ON detalle_venta.Articulo_id_articulo = articulo.id_articulo
				WHERE venta.Usuario_id_usuario = {$id_usuario} AND DATE(venta.fecha) = '{$currentDate}'
				GROUP BY venta.id_venta");
			return $query->fetchAll();
			
		}else{
			$query = $this->pdo->query("SELECT venta.id_venta,  DATE_FORMAT(venta.fecha, '%d/%m/%Y %H:%i') AS fecha_venta, GROUP_CONCAT(articulo.nombre SEPARATOR ', ') AS nombres_productos, ROUND(venta.total) AS total, metodos_pago.nombre as tipo_pago
				FROM venta 
				JOIN detalle_venta ON venta.id_venta = detalle_venta.Venta_id_venta 
				JOIN metodos_pago ON metodos_pago.id_metodo_pago = detalle_venta.metodo_pago
				JOIN articulo ON detalle_venta.Articulo_id_articulo = articulo.id_articulo
				WHERE DATE(venta.fecha) = '{$currentDate}'
				GROUP BY venta.id_venta");
			return $query->fetchAll();

		}

	}

	public function transacciones($id_usuario)
	{

		$currentDate = date('Y-m-d');
		/* $query = $this->pdo->query("SELECT  COALESCE(SUM(total), 0) as total_diario FROM venta WHERE Usuario_id_usuario = {$id_usuario} AND DATE(fecha) = '{$currentDate}' GROUP BY tipo_pago"); */
		$rol = $_SESSION['rol'];

		if($rol === 2){
			$query = $this->pdo->query("SELECT
			COALESCE(SUM(CASE WHEN dv.metodo_pago = 2 THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS nequi,
			COALESCE(SUM(CASE WHEN dv.metodo_pago = 1 THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS efectivo,	
			COALESCE(SUM(CASE WHEN dv.metodo_pago = 3 THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS daviplata,	
			COALESCE(SUM(CASE WHEN dv.metodo_pago = 4 THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS otros,	
			
			mp.nombre
			FROM venta v INNER JOIN detalle_venta dv ON v.id_venta = dv.Venta_id_venta 
			INNER JOIN metodos_pago mp ON mp.id_metodo_pago = dv.metodo_pago
			WHERE v.Usuario_id_usuario = {$id_usuario} AND DATE(v.fecha) = '{$currentDate}'");

		}else{
			$query = $this->pdo->query("SELECT
			COALESCE(SUM(CASE WHEN dv.metodo_pago = 2 THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS nequi,
			COALESCE(SUM(CASE WHEN dv.metodo_pago = 1 THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS efectivo,	
			COALESCE(SUM(CASE WHEN dv.metodo_pago = 3 THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS daviplata,	
			COALESCE(SUM(CASE WHEN dv.metodo_pago = 4 THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS otros,	
			
			mp.nombre
			FROM venta v INNER JOIN detalle_venta dv ON v.id_venta = dv.Venta_id_venta 
			INNER JOIN metodos_pago mp ON mp.id_metodo_pago = dv.metodo_pago
			WHERE DATE(v.fecha) = '{$currentDate}'");

		}
		
		return $query->fetch();
	}

	public function ventasPorRango($rango)
	{
		$id_usuario =  $_SESSION['id_usuario'];
		$rol = $_SESSION['rol'];
		$rango =  json_decode($rango);

		if ($rol === 2) {
			/* unicamente toma el del cajero */
			$query = $this->pdo->query("SELECT  COALESCE(SUM(venta.total),0)as total_venta, usuario.nombres, usuario.apellidos, usuario.id_usuario FROM venta JOIN usuario on venta.Usuario_id_usuario = usuario.id_usuario  WHERE venta.Usuario_id_usuario = {$id_usuario} AND DATE(venta.fecha) BETWEEN '{$rango->start}' AND '{$rango->end}'");
		} else {
			/* Solo administrador */
			$query = $this->pdo->query("SELECT COALESCE(SUM(venta.total),0) as total_venta, usuario.nombres, usuario.apellidos, usuario.id_usuario FROM venta
				JOIN usuario on venta.Usuario_id_usuario = usuario.id_usuario WHERE  DATE(venta.fecha) BETWEEN '{$rango->start}' AND '{$rango->end}' GROUP BY usuario.id_usuario");
		}
		return $query->fetchAll();
	}
	public function ventasPorRangoDetalle($id_usuario, $fecha_inicio, $fecha_final)
	{
		session_start();
		$rol = $_SESSION['rol'];

		if($rol === 2){
			$query = $this->pdo->query("SELECT  usuario.nombres, venta.total as sum_t, usuario.apellidos,sum(detalle_venta.cantidad) as cantidad_total,sum(detalle_venta.cantidad * detalle_venta.precio) as subtotal, articulo.nombre FROM venta 
			INNER JOIN usuario ON venta.Usuario_id_usuario = usuario.id_usuario
			INNER JOIN detalle_venta ON venta.id_venta = detalle_venta.Venta_id_venta
			INNER JOIN articulo ON articulo.id_articulo = detalle_venta.Articulo_id_articulo
			WHERE venta.Usuario_id_usuario = {$id_usuario} AND DATE(venta.fecha) BETWEEN '{$fecha_inicio}' AND '{$fecha_final}' GROUP BY articulo.nombre");
			
			return $query->fetchAll();
			
		}else{
			$query = $this->pdo->query("SELECT  usuario.nombres, usuario.apellidos, usuario.id_usuario, sum(detalle_venta.cantidad) as cantidad_total,sum(detalle_venta.cantidad * detalle_venta.precio) as subtotal, articulo.nombre FROM venta 
			INNER JOIN usuario ON venta.Usuario_id_usuario = usuario.id_usuario
			INNER JOIN detalle_venta ON venta.id_venta = detalle_venta.Venta_id_venta
			INNER JOIN articulo ON articulo.id_articulo = detalle_venta.Articulo_id_articulo
			WHERE venta.Usuario_id_usuario = {$id_usuario} AND DATE(venta.fecha) BETWEEN '{$fecha_inicio}' AND '{$fecha_final}' GROUP BY articulo.nombre");
			
			return $query->fetchAll();

		}
	
	}

	public function cierreCaja($id_usuario){
		$currentDate = date('Y-m-d');

	/* 	$query = $this->pdo->query("SELECT (SELECT COALESCE(sum(total), 0) from venta where DATE(fecha) = '{$currentDate}' AND Usuario_id_usuario = '{$id_usuario}') as ventas,
		(SELECT COALESCE(sum(total), 0) from ingreso where DATE(fecha) = '{$currentDate}' AND Usuario_id_usuario = '{$id_usuario}' ) as ingresos,
		(SELECT COALESCE(sum(total), 0) from gastos where DATE(fecha) = '{$currentDate}' AND id_usuario = '{$id_usuario}' ) as gastos");
 	*/

		 /* ingresos */
        $query = $this->pdo->query("SELECT COALESCE(sum(total), 0) from ingreso where DATE(fecha) = '{$currentDate}' AND Usuario_id_usuario = '{$id_usuario}'");
        $total_ingresos = $query->fetchColumn();

		/* gastos */
		$query = $this->pdo->query("SELECT COALESCE(SUM(total), 0) FROM gastos WHERE DATE(fecha) = '{$currentDate}' AND id_usuario = '{$id_usuario}'");
        $total_gastos = $query->fetchColumn();

		/* ventas */
		$query = $this->pdo->query("SELECT
			COALESCE(SUM(CASE WHEN dv.metodo_pago = 2 THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS nequi,
			COALESCE(SUM(CASE WHEN dv.metodo_pago = 1 THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS efectivo,	
			COALESCE(SUM(CASE WHEN dv.metodo_pago = 3 THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS daviplata,	
			COALESCE(SUM(CASE WHEN dv.metodo_pago = 4 THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS otros,	
			
			mp.nombre
			FROM venta v INNER JOIN detalle_venta dv ON v.id_venta = dv.Venta_id_venta 
			INNER JOIN metodos_pago mp ON mp.id_metodo_pago = dv.metodo_pago
			WHERE v.Usuario_id_usuario = {$id_usuario} AND DATE(v.fecha) = '{$currentDate}'");

		$total_ventas = $query->fetch();

		$resultados = array(
			'ingresos' => $total_ingresos,
			'gastos' => $total_gastos,
			'ventas' => $total_ventas
			
		);

        return $resultados;
 

	}
}
