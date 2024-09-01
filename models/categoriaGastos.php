<?php
require_once('db.php');
date_default_timezone_set('America/Bogota');

class CategoriaGastos extends Database
{
  
    public function all(){
      $query = $this->pdo->query("SELECT * from categoria_gastos");
      return $query->fetchAll();
    }

}