<?php 
require_once('db.php'); 
date_default_timezone_set('America/Bogota');


class Northwind extends Database
{
	/* public function getCustomer(int $id)
	{
		$query = $this->pdo->query('SELECT * FROM customers WHERE id = '.$id);
		return $query->fetch();
	} */

	public function getOrder(int $id)
	{
		$query = $this->pdo->query('SELECT id_venta, fecha, total, CONCAT(usuario.nombres, " ", usuario.apellidos) AS nombre_completo, usuario.nombres, usuario.apellidos
		 FROM venta
		 JOIN usuario ON usuario.id_usuario = Usuario_id_usuario
		 WHERE id_venta = '.$id);
	
		return $query->fetch();
	}

	public function getOrderDetails(int $id)
	{
		$query = $this->pdo->query('SELECT Articulo_id_articulo, cantidad, precio, articulo.nombre, articulo.descripcion
				FROM detalle_venta
				JOIN articulo ON articulo.id_articulo = Articulo_id_articulo
				WHERE detalle_venta.Venta_id_venta = '.$id);
		return $query->fetchAll();
	}
}
