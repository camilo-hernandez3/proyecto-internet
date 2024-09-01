<?php
require_once('db.php');
date_default_timezone_set('America/Bogota');
class Compras extends Database
{


    public function compras($id)
    {
        $query = $this->pdo->query('SELECT sum(total) as total_diario FROM ingreso WHERE Usuario_id_usuario =' . $id);
        return $query->fetch();
    }

    public function getCompra(int $id)
	{
		$query = $this->pdo->query('SELECT id_ingreso, fecha, total, CONCAT(usuario.nombres, " ", usuario.apellidos) AS nombre_completo, usuario.nombres, usuario.apellidos
		 FROM ingreso
		 JOIN usuario ON usuario.id_usuario = Usuario_id_usuario
		 WHERE id_ingreso = '.$id);
	
		return $query->fetch();
	}

    public function getCompraDetails(int $id)
	{
		$query = $this->pdo->query('SELECT Articulo_id_articulo, cantidad, precio, articulo.nombre, articulo.descripcion
				FROM detalle_ingreso
				JOIN articulo ON articulo.id_articulo = Articulo_id_articulo
				WHERE detalle_ingreso.Ingreso_id_ingreso = '.$id);
		return $query->fetchAll();
	}



    public function store($idUsuario, $total, $productos)
    {

        $query = $this->pdo->prepare('INSERT INTO ingreso (Usuario_id_usuario, total, fecha) VALUES (:usuario_id, :total, :fecha)');


        $query->bindParam(':usuario_id', $idUsuario);
        $query->bindParam(':total', $total);
        $query->bindParam(':fecha', date('Y-m-d H:i:s'));
        $query->execute();


        if ($query->rowCount() > 0) {

            $id_venta = $this->pdo->lastInsertId();
            $productos = json_decode($productos);
            foreach ($productos as $articulo) {
                $this->insertDetalleIngreso($id_venta, $articulo->id_articulo, $articulo->cantidad, $articulo->precio_venta);
                $this->addProduct($articulo->id_articulo, $articulo->cantidad);
            }
        } else {

            echo "Error en la inserción.";
        }
    }

    public function addProduct($id_articulo, $cantidad)
    {

        $query = $this->pdo->prepare("UPDATE articulo SET stock = stock + $cantidad WHERE id_articulo = $id_articulo");
        $query->execute();
    }



    public function insertDetalleIngreso($id_venta, $id_articulo, $cantidad, $precio)
    {

        $query = $this->pdo->prepare('INSERT INTO detalle_ingreso (Ingreso_id_ingreso, Articulo_id_articulo, cantidad, precio) VALUES (:id_ingreso, :id_articulo, :cantidad, :precio)');


        $query->bindParam(':id_ingreso', $id_venta);
        $query->bindParam(':id_articulo', $id_articulo);
        $query->bindParam(':cantidad', $cantidad);
        $query->bindParam(':precio', $precio);
        $query->execute();


        if ($query->rowCount() > 0) {
            echo "Se guardó correctamente";
        } else {

            echo "Error en la inserción.";
        }
    }



    public function ultimoIngreso($id)
    {
        $currentDate = date('Y-m-d');
        /* $query = $this->pdo->query('SELECT * FROM venta WHERE Usuario_id_usuario ='.$id.' order by fecha desc limit 1'); */
        /* $query = $this->pdo->query("SELECT SUM(total) as total_diario FROM venta WHERE Usuario_id_usuario = {$id} AND DATE(fecha) = '{$currentDate}'"); */
        $query = $this->pdo->query("SELECT SUM(total) as total_diario, 
    	(SELECT total FROM ingreso WHERE Usuario_id_usuario = {$id} AND DATE(fecha) = '{$currentDate}' ORDER BY fecha DESC LIMIT 1) as ultimo_ingreso,
    	(SELECT SUM(total) FROM ingreso  WHERE Usuario_id_usuario = {$id}  AND DATE(fecha) BETWEEN DATE_SUB('{$currentDate}', INTERVAL 30 DAY) AND '{$currentDate}') as total_ultimo_mes FROM ingreso WHERE Usuario_id_usuario = {$id} AND DATE(fecha) = '{$currentDate}'");
        return $query->fetch();
    }

    public function facturas($id_usuario)
    {
        $currentDate = date('Y-m-d');

        $query = $this->pdo->query("SELECT ingreso.id_ingreso, ingreso.estado,  DATE_FORMAT(ingreso.fecha, '%d/%m/%Y %H:%i') AS fecha_ingreso, GROUP_CONCAT(articulo.nombre SEPARATOR ', ') AS nombres_productos, ROUND(ingreso.total) AS total 
        FROM ingreso 
        INNER JOIN detalle_ingreso ON ingreso.id_ingreso = detalle_ingreso.Ingreso_id_ingreso 
        INNER JOIN articulo ON detalle_ingreso.Articulo_id_articulo  = articulo.id_articulo
        WHERE ingreso.Usuario_id_usuario = {$id_usuario} AND DATE(ingreso.fecha) = '{$currentDate}'
        GROUP BY ingreso.id_ingreso");
        return $query->fetchAll();
    }


    public function changeStatusFactura($id_factura){

        $query = $this->pdo->prepare("UPDATE ingreso SET estado = 0 WHERE id_ingreso = $id_factura");
        $query->execute();

    }

    //! Todo --- pending
    public function transacciones($id_usuario)
    {

        $currentDate = date('Y-m-d');
        /* $query = $this->pdo->query("SELECT  COALESCE(SUM(total), 0) as total_diario FROM venta WHERE Usuario_id_usuario = {$id_usuario} AND DATE(fecha) = '{$currentDate}' GROUP BY tipo_pago"); */
        $query = $this->pdo->query("SELECT
		COALESCE(SUM(CASE WHEN dv.tipo_pago = 'Nequi' THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS nequi,
		COALESCE(SUM(CASE WHEN dv.tipo_pago = 'Efectivo' THEN dv.cantidad * dv.precio ELSE 0 END), 0) AS efectivo
	FROM
		detalle_venta dv
	JOIN
		venta v ON dv.Venta_id_venta = v.id_venta
	WHERE
		v.Usuario_id_usuario = {$id_usuario}
		AND DATE(v.fecha) = '{$currentDate}'");

        return $query->fetch();
    }

    //! Todo --- pending
    public function ventasPorRango($rango)
    {
        $id_usuario =  $_SESSION['id_usuario'];
        $rol = $_SESSION['rol'];
        $rango =  json_decode($rango);

        if ($rol === 2) {
            /* unicamente toma el del cajero */
            $query = $this->pdo->query("SELECT  SUM(venta.total) as total_venta, usuario.nombres, usuario.apellidos FROM venta JOIN usuario on venta.Usuario_id_usuario = usuario.id_usuario  WHERE venta.Usuario_id_usuario = {$id_usuario} AND DATE(venta.fecha) BETWEEN '{$rango->start}' AND '{$rango->end}'");
        } else {
            /* Solo administrador */
            $query = $this->pdo->query("SELECT  SUM(venta.total) as total_venta, usuario.nombres, usuario.apellidos FROM venta
				JOIN usuario on venta.Usuario_id_usuario = usuario.id_usuario WHERE  DATE(venta.fecha) BETWEEN '{$rango->start}' AND '{$rango->end}'");
        }
        return $query->fetchAll();
    }
}
