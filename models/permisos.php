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
        $query = $this->pdo->query('SELECT up.*, r.nombre FROM user_permission AS up JOIN rol AS r ON up.id_rol = r.id_rol WHERE id_user_permission =' . $id_equipo);
        return $query->fetch();
    }



    public function byRol($usuario)
    {

        $rol = $_SESSION['rol'];

        $query = $this->pdo->query('SELECT * FROM user_permission WHERE id_rol = ' . $rol);
        return $query->fetchAll();

    }

    public function destroy($id)
    {
        $query = $this->pdo->prepare('DELETE FROM user_permission  where id_user_permission_id =' . $id);
        $query->execute();
    }


    public function store($usuario)
    {

        $usuario = json_decode($usuario);
        $query = $this->pdo->prepare('INSERT INTO user_permissions (
            rol_selected, 
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
        $updateQuery = $this->pdo->prepare('UPDATE equipos SET descripcion = :descripcion, ip_address = :ip_address, mac_adress = :mac_adress, piso_id_piso = :piso_id_piso, ram = :ram, procesador = :procesador, almacenamiento = :almacenamiento  WHERE id_equipo = :id');


        $updateQuery->bindParam(':descripcion', $editProduct->description);
        $updateQuery->bindParam(':ip_address', $editProduct->ip_address);

        $updateQuery->bindParam(':mac_adress', $editProduct->mac_address);
        +
            $updateQuery->bindParam(':piso_id_piso', $editProduct->piso);
        $updateQuery->bindParam(':ram', $editProduct->ram);
        $updateQuery->bindParam(':procesador', $editProduct->procesador);
        $updateQuery->bindParam(':almacenamiento', $editProduct->almacenamiento);


        $updateQuery->bindParam(':id', $editProduct->id_equipo);


        $updateQuery->execute();
    }

}
