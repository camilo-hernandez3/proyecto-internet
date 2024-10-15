<?php
require_once('db.php');
date_default_timezone_set('America/Bogota');

class Permisos extends Database
{


    public function index()
    {
        $query = $this->pdo->query('SELECT up.*, r.nombre FROM user_permission AS up JOIN rol AS r ON up.id_rol = r.id_rol');


        return $query->fetchAll();
    }


    public function show($id_equipo)
    {
        $query = $this->pdo->query('SELECT up.*, r.nombre FROM user_permission AS up JOIN rol AS r ON up.id_rol = r.id_rol WHERE id_user_permission_id =' . $id_equipo);
        return $query->fetch();
    }



    public function byRol($usuario)
    {
        session_start();

        $rol = $_SESSION['rol'];

        $query = $this->pdo->query('SELECT * FROM user_permission WHERE id_rol = ' . $rol);
        return $query->fetch();

    }

    public function destroy($id)
    {
        $query = $this->pdo->prepare('DELETE FROM user_permission  where id_user_permission_id =' . $id);
        $query->execute();
    }


    public function store($usuario)
    {

        $usuario = json_decode($usuario);
        $query = $this->pdo->prepare('INSERT INTO user_permission (
            id_rol, 
            could_view_users,
            could_edit_users,
            could_export_users,
            could_view_pc,
            could_export_pc,
            could_create_pc,
            could_edit_pc,
            could_view_users_pc,
            could_view_history_users_pc) VALUES ( 
            :rol_selected,
            :could_view_users,
            :could_edit_users,
            :could_export_users,
            :could_view_pc,
            :could_export_pc,
            :could_create_pc,
            :could_edit_pc,
            :could_view_users_pc,
            :could_view_history_users_pc)');
        
        $query->bindParam(':rol_selected', $usuario->rol_selected);
        $query->bindParam(':could_view_users', $usuario->could_view_users);
        $query->bindParam(':could_edit_users', $usuario->could_edit_users);
        $query->bindParam(':could_export_users', $usuario->could_export_users);
        $query->bindParam(':could_view_pc', $usuario->could_view_pc);
        $query->bindParam(':could_export_pc', $usuario->could_export_pc);
        $query->bindParam(':could_create_pc', $usuario->could_create_pc);
        $query->bindParam(':could_edit_pc', $usuario->could_edit_pc);
        $query->bindParam(':could_view_users_pc', $usuario->could_view_users_pc);
        $query->bindParam(':could_view_history_users_pc', $usuario->could_view_history_users_pc);
        
        $query->execute();

        $lastInsertId = $this->pdo->lastInsertId();
        return $this->show($lastInsertId);

    }

    public function EditEquipo($equipo)
    {
        $editProduct = json_decode($equipo);
        $updateQuery = $this->pdo->prepare('UPDATE user_permission SET 
            id_rol = :rol_selected, 
            could_view_users = :could_view_users,
            could_edit_users = :could_edit_users,
            could_export_users = :could_export_users,
            could_view_pc = :could_view_pc,
            could_export_pc = :could_export_pc,
            could_create_pc = :could_create_pc,
            could_edit_pc = :could_edit_pc,
            could_view_users_pc = :could_view_users_pc,
            could_view_history_users_pc = :could_view_history_users_pc
          WHERE id_user_permission_id = '.$editProduct->id_equipo);


        $updateQuery->bindParam(':rol_selected', $editProduct->rol_selected);
        $updateQuery->bindParam(':could_view_users', $editProduct->could_view_users);
        $updateQuery->bindParam(':could_edit_users', $editProduct->could_edit_users);
        $updateQuery->bindParam(':could_export_users', $editProduct->could_export_users);
        $updateQuery->bindParam(':could_view_pc', $editProduct->could_view_pc);
        $updateQuery->bindParam(':could_export_pc', $editProduct->could_export_pc);
        $updateQuery->bindParam(':could_create_pc', $editProduct->could_create_pc);
        $updateQuery->bindParam(':could_edit_pc', $editProduct->could_edit_pc);
        $updateQuery->bindParam(':could_view_users_pc', $editProduct->could_view_users_pc);
        $updateQuery->bindParam(':could_view_history_users_pc', $editProduct->could_view_history_users_pc);
        
        $updateQuery->execute();
    }

}
