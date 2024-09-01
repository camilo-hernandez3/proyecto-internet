<?php
require_once('db.php');
date_default_timezone_set('America/Bogota');

class Articulo extends Database
{


	public function index()
	{
		$query = $this->pdo->query('SELECT articulo.*, categoria.nombre as categoria FROM articulo
		join categoria on categoria.id_categoria = 	categoria_id_categoria  WHERE articulo.estado = 1');
		return $query->fetchAll();
	}

	public function articuloByCategory($category)
	{
		$query = $this->pdo->query('SELECT * FROM articulo where categoria_id_categoria =' . $category . ' AND estado = 1');
		return $query->fetchAll();
	}

	public function articuloByTerm($term)
	{

		$termWithWildcards = '%' . $term . '%'; // Agregar los caracteres % alrededor del término

		$query = $this->pdo->prepare("SELECT * FROM articulo WHERE nombre LIKE :term AND estado = 1");
		$query->bindValue(':term', $termWithWildcards, PDO::PARAM_STR);
		$query->execute();

		return $query->fetchAll();
	}

	public function show($id_articulo)
	{
		$query = $this->pdo->query('SELECT * FROM articulo where id_articulo =' . $id_articulo);
		return $query->fetch();
	}
	public function editProduct($product)
	{
		$editProduct = json_decode($product);
		$updateQuery = $this->pdo->prepare('UPDATE articulo SET nombre = :nombre, precio_venta = :precio_venta, stock = :stock, categoria_id_categoria = :categoria_id_categoria, stock_deseado = :stock_deseado  WHERE id_articulo = :id');

		// Reemplaza "columna1", "columna2", etc. con los nombres reales de tus columnas y :valor1, :valor2 con los valores actualizados.
		$updateQuery->bindParam(':nombre', $editProduct->nombre);
		$updateQuery->bindParam(':precio_venta', $editProduct->precio);
		$updateQuery->bindParam(':stock', $editProduct->cantidad);
		/* $updateQuery->bindParam(':descripcion', $editProduct->descripcion); */
		$updateQuery->bindParam(':categoria_id_categoria', $editProduct->selectCategoria);
		$updateQuery->bindParam(':stock_deseado', $editProduct->stockMaximo);

		$updateQuery->bindParam(':id', $editProduct->id_articulo);

		// Ejecuta la consulta UPDATE
		$updateQuery->execute();
	}

	function store($articulo)
	{

		$articulo = json_decode($articulo);

		$query = $this->pdo->prepare('INSERT INTO articulo (nombre,stock,precio_venta,stock_deseado, categoria_id_categoria) VALUES (:nombre, :stock, :precio_venta, :stock_deseado, :categoria_id_categoria)');


		$query->bindParam(':nombre', $articulo->nombre);
		$query->bindParam(':stock', $articulo->cantidad);
		$query->bindParam(':precio_venta', $articulo->precio);
		$query->bindParam(':stock_deseado', $articulo->stockMaximo);
		$query->bindParam(':categoria_id_categoria', $articulo->selectCategoria);
		$query->execute();
		$lastInsertId = $this->pdo->lastInsertId();
		return $this->show($lastInsertId);
	}

	function destroy($id_articulo)
	{

		$query = $this->pdo->prepare('UPDATE articulo set estado = 0 where id_articulo =' . $id_articulo);
		$query->execute();
	}
}
