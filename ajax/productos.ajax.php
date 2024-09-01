<?php
require_once "../models/articulo.php";


if(isset($_POST["all"])){

	$producto = new Articulo();
	echo json_encode($producto -> index());

}



if(isset($_POST["id_categoria"])){

	$producto = new Articulo();
	$category= intval($_POST["id_categoria"]);
    
	echo json_encode($producto -> articuloByCategory($category));

}
if(isset($_POST["term"])){

	$producto = new Articulo();
	$term= $_POST["term"];
    
	echo json_encode($producto -> articuloByTerm($term));

}



if(isset($_POST["new_product"])){

	$producto = new Articulo();
	$newProduct= $_POST["new_product"];
    
	echo json_encode($producto -> store($newProduct));

}

if(isset($_POST["product_edit"])){

	$producto = new Articulo();
	$product_edit= $_POST["product_edit"];
    
	echo json_encode($producto -> editProduct($product_edit));

}

if(isset($_POST["id_eliminar"])){

	$producto = new Articulo();
	$id_eliminar= $_POST["id_eliminar"];
    
	echo json_encode($producto -> destroy($id_eliminar));

}

if(isset($_POST["id_articulo"])){

	$producto = new Articulo();
	$id_articulo = intval($_POST["id_articulo"]);
    
	echo json_encode($producto -> show($id_articulo));

}



