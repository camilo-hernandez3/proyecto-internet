<?php
require_once('db.php');
date_default_timezone_set('America/Bogota');

class permisos extends Database
{


    public function index()
    {
        $query = $this->pdo->query('SELECT up.*, r.nombre FROM user_permission AS up JOIN rol AS r ON up.id_rol = r.id_rol');


        return $query->fetchAll();
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

        $query = $this->pdo->prepare('INSERT INTO equipos (descripcion, ip_address,mac_adress,piso_id_piso, ram, procesador, almacenamiento) VALUES (:descripcion, :ip_address, :mac_address, :piso_id_piso, :ram, :procesador, :almacenamiento)');


       /*  $query->bindParam(':descripcion', $usuario->description);

        $query->bindParam(':ip_address', $usuario->ip_address);
        $query->bindParam(':mac_address', $usuario->mac_address);
        $query->bindParam(':piso_id_piso', $usuario->piso);
        $query->bindParam(':ram', $usuario->ram);
        $query->bindParam(':procesador', $usuario->procesador);
        $query->bindParam(':almacenamiento', $usuario->almacenamiento); */

        $query->execute();
        $lastInsertId = $this->pdo->lastInsertId();

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
