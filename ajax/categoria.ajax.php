<?php
require_once "../models/categoria.php";




if(isset($_POST["categoria"])){

	$cat = new Categoria();

	$nombre= $_POST["categoria"];
    
    echo json_encode( $cat->store($nombre));

}

if(isset($_POST["all"])){
	$cat = new Categoria();
    echo json_encode($cat->index());

}

if(isset($_POST["id_eliminar"])){

	$categoria = new Categoria();
	$id_eliminar= $_POST["id_eliminar"];
    
	echo json_encode($categoria -> destroy($id_eliminar));
}

if(isset($_POST["edit_category"])){
	$cat = new Categoria();
    $categoria = $_POST["edit_category"];
    echo json_encode($cat->editCategory($categoria));

}

if(isset($_POST["id_categoria"])){
	$categoria = new Categoria();
	$id_categoria = $_POST["id_categoria"];
    echo json_encode($categoria->show($id_categoria));

}