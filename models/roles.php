<?php
require_once('db.php');
date_default_timezone_set('America/Bogota');

class Roles extends Database
{


    public function index()
    {
        $query = $this->pdo->query('SELECT up.*, r.nombre FROM user_permission AS up JOIN rol AS r ON up.id_rol = r.id_rol');


        return $query->fetchAll();
    }


    public function show($id_equipo)
    {
        $query = $this->pdo->query('SELECT * from rol where id_rol =' . $id_equipo);
        return $query->fetch();
    }



    public function roles()
    {
    
        $query = $this->pdo->query('SELECT * FROM rol where status_ = 1');
        return $query->fetchAll();

    }

    public function destroy($id)
    {
        $query = $this->pdo->prepare('UPDATE rol set status_ = 0 where id_rol =' . $id);
        $query->execute();
    }


    public function store($usuario)
    {

        $usuario = json_decode($usuario);
        $query = $this->pdo->prepare('INSERT INTO rol (
            nombre) VALUES ( 
            :rol_selected)');
        
        $query->bindParam(':rol_selected', $usuario->rol_selected);
        
        $query->execute();

        $lastInsertId = $this->pdo->lastInsertId();
        return $this->show($lastInsertId);

    }

    public function EditEquipo($equipo)
    {
        $editProduct = json_decode($equipo);
        $updateQuery = $this->pdo->prepare('UPDATE rol SET 
            nombre = :rol_selected
            
          WHERE id_rol = '.$editProduct->id_equipo);


        $updateQuery->bindParam(':rol_selected', $editProduct->rol_selected);
        
        
        $updateQuery->execute();
    }

}
