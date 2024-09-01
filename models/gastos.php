<?php
require_once('db.php');
date_default_timezone_set('America/Bogota');


class Gastos extends Database
{
  
	public function gastosDiarios(){
		$id_usuario =  $_SESSION['id_usuario'];
		$rol = $_SESSION['rol'];

		if($rol === 2){
			$currentDate = date('Y-m-d');
			$query = $this->pdo->query("SELECT COALESCE(sum(total), 0) as total from gastos WHERE id_usuario = '{$id_usuario}' AND DATE(fecha) = '{$currentDate}'");
			return $query->fetch();
			
		}else{
			$currentDate = date('Y-m-d');
			$query = $this->pdo->query("SELECT COALESCE(sum(total), 0) as total from gastos WHERE DATE(fecha) = '{$currentDate}'");
			return $query->fetch();

		}


	}

    public function all($id){
    
        $currentDate = date('Y-m-d');
        	
		$query = $this->pdo->query("SELECT * from gastos WHERE id_usuario = '{$id}' AND DATE(fecha) = '{$currentDate}'");
		return $query->fetchAll();
      
    }

    public function store($descripcion, $total, $id_usuario, $categoria_gasto)
    {

        $query = $this->pdo->prepare('INSERT INTO gastos (descripcion, total, id_usuario ,fecha, categoria_gasto) VALUES (:descripcion, :total, :id_usuario, :fecha, :categoria_gasto)');

        $query->bindParam(':descripcion', $descripcion);
        $query->bindParam(':total', $total);
        $query->bindParam(':id_usuario', $id_usuario);
        $query->bindParam(':fecha', date('Y-m-d H:i:s'));
        $query->bindParam(':categoria_gasto', $categoria_gasto);
        $query->execute();

    }


	public function gastosPorCategoria($categoria, $rango){
		$currentDate = date('Y-m-d');
		$rango = json_decode($rango);

		/* Operacionales */
		if($categoria == 1){
			$query = $this->pdo->query("SELECT * FROM gastos WHERE categoria_gasto <> 11 AND DATE(fecha) BETWEEN '{$rango->start}' AND '{$rango->end}'");
			
		}else{
			/* no operacionales */
			$query = $this->pdo->query("SELECT * FROM gastos WHERE categoria_gasto = 11 AND DATE(fecha) BETWEEN '{$rango->start}' AND '{$rango->end}' ORDER BY fecha");
			
		}
		return $query->fetchAll();
	}

    
	public function gastosPorRango($rango)
	{

		$id_usuario =  $_SESSION['id_usuario'];
		$rol = $_SESSION['rol'];
		$rangoFecha = json_decode($rango);

		$formatted_start_date = date('Y-m-d H:i:s', strtotime($rangoFecha->start . ' 00:00:00'));
		$formatted_end_date = date('Y-m-d H:i:s', strtotime($rangoFecha->end . ' 23:59:59'));
			

		if ($rol === 2) {
			$query = $this->pdo->prepare("SELECT * FROM gastos WHERE id_usuario = :id_usuario AND fecha BETWEEN :start_date AND :end_date");

			// Asignar valores a los parámetros
			$query->bindParam(':id_usuario', $id_usuario);
			$query->bindParam(':start_date', $formatted_start_date);
			$query->bindParam(':end_date', $formatted_end_date);

			// Ejecutar la consulta preparada
			$query->execute();

			// Obtener los resultados, por ejemplo:
			$resultados = $query->fetchAll(PDO::FETCH_ASSOC);
			return $resultados;

		} else {
			/* Solo administrador */
			$query = $this->pdo->query("SELECT * from gastos");
			return $query->fetchAll();
		}
		

	}

		

}