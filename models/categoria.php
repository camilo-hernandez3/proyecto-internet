<?php 
require_once('db.php'); 
date_default_timezone_set('America/Bogota');

class Categoria extends Database
{
	
	public function index()
	{
		$query = $this->pdo->query('SELECT * FROM categoria WHERE estado = 1');
		return $query->fetchAll();
	}

	public function show($id){
		$query = $this->pdo->query('SELECT * FROM categoria WHERE id_categoria = '.$id);
		return $query->fetch();
	}

	function store($categoria){
		$query = $this->pdo->prepare('INSERT INTO categoria (nombre) VALUES (:nombre)');
	
		$query->bindParam(':nombre', $categoria);
		$query->execute();

		$lastInsertId = $this->pdo->lastInsertId();
		return $this->show($lastInsertId);
	}

	public function editCategory($category)
	{
		$category = json_decode($category);

		$updateQuery = $this->pdo->prepare('UPDATE categoria SET nombre = :nombre  WHERE id_categoria = :id');

		// Reemplaza "columna1", "columna2", etc. con los nombres reales de tus columnas y :valor1, :valor2 con los valores actualizados.
		$updateQuery->bindParam(':nombre', $category->nombre);
		$updateQuery->bindParam(':id', $category->id_categoria);
		

		// Ejecuta la consulta UPDATE
		$updateQuery->execute();
	}
	
	function destroy($id_categoria){

		$query = $this->pdo->prepare('UPDATE categoria set estado = 0 where id_categoria ='.$id_categoria);
		$query->execute();

	}
}


