<?php
    session_start();
    include_once("conexion.php"); 
    if(!isset($_SESSION['id_usuario'])){
        header('location: index.php');

    }else{
        $var_session = $_SESSION['id_usuario'];
        $rol = $_SESSION['rol']; 
        //Consulta para mostrar la informacion del usuario//
        $queryUsuarios = mysqli_query($conn, "SELECT * FROM usuarios where id_usuario = $var_session");
        while($mostrar = mysqli_fetch_array($queryUsuarios))
        {
            $idUsuario = $mostrar['id_usuario'];
            $nombreUsuario = $mostrar['nombre'];
        } 

    }
   
 
