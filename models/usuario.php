<?php
require_once('db.php');
date_default_timezone_set('America/Bogota');

class Usuario extends Database
{


	public function index()
	{
		

		$query = $this->pdo->query('SELECT * FROM usuarios WHERE status_ = 1');
		return $query->fetchAll();
	}

	public function destroy($id)
	{
		$query = $this->pdo->prepare('UPDATE usuarios set status_ = 0, email = CONCAT(email, "_BANNED") where id_usuario =' . $id);
		$query->execute();

		$query2 = $this->pdo->prepare('DELETE from usuarios_has_piso WHERE usuarios_id_usuario =' . $id);
		$query2->execute();
	}

	public function allroles()
	{
		$query = $this->pdo->query('SELECT * FROM rol where status_ = 1');
		return $query->fetchAll();
	}

	public function allPisos()
	{
		$query = $this->pdo->query('SELECT * FROM piso');
		return $query->fetchAll();
	}

	public function show($id_usuario)
	{
		$query = $this->pdo->query('SELECT * FROM usuarios where id_usuario =' . $id_usuario);
		return $query->fetch();
	}

	public function userById($id_usuario)
	{
		$query = $this->pdo->query('SELECT * FROM usuarios AS u LEFT JOIN usuarios_has_piso AS up ON u.id_usuario = up.usuarios_id_usuario WHERE u.id_usuario =' . $id_usuario);
		return $query->fetchAll();
	}

	public function store($usuario)
	{

		$usuario = json_decode($usuario);


		$rolId = null;

		if(isset($usuario->new_rol) && !empty($usuario->new_rol)){

			$q = $this->pdo->prepare('INSERT INTO rol (nombre) VALUES (:nombre)');
			$q->bindParam(':nombre', $usuario->new_rol);
			$q->execute();
			$lastInsertId = $this->pdo->lastInsertId();
			$rolId = $lastInsertId;
		

		}else{
			$rolId = $usuario->selected_rol;
		}	

		

		
		$query = $this->pdo->prepare('INSERT INTO usuarios (nombre, email,user_password,rol_id_rol) VALUES (:nombre, :email, :user_password, :rol_id_rol)');

		$query->bindParam(':rol_id_rol', $rolId);

		$query->bindParam(':nombre', $usuario->nombres);

		$query->bindParam(':email', $usuario->email);
		$query->bindParam(':user_password', $usuario->password);
		

		$query->execute();
		$lastInsertId = $this->pdo->lastInsertId();

		if (isset($usuario->piso_selected) && $usuario->piso_selected && count($usuario->piso_selected) > 0) {
			

			foreach ($usuario->piso_selected as $piso_id) {

				$quer2 = $this->pdo->prepare('INSERT INTO usuarios_has_piso (usuarios_id_usuario,piso_id_piso) VALUES (:usuarios_id_usuario, :piso_id_piso)');

				$quer2->bindParam(':usuarios_id_usuario', $lastInsertId);

				$quer2->bindParam(':piso_id_piso', $piso_id);

				$quer2->execute();
			}


		}


		return $this->show($lastInsertId);
	}


	public function editUser($userEdit)
	{
		$editProduct = json_decode($userEdit);
		$updateQuery = $this->pdo->prepare('UPDATE usuarios SET nombre = :nombre, email = :email, rol_id_rol = :rol_id_rol, user_password = :user_password WHERE id_usuario = :id');

	
		$updateQuery->bindParam(':nombre', $editProduct->nombres);
		$updateQuery->bindParam(':email', $editProduct->email);
		$updateQuery->bindParam(':rol_id_rol', $editProduct->selected_rol);
		$updateQuery->bindParam(':user_password', $editProduct->password);
		

		$updateQuery->bindParam(':id', $editProduct->id_usuario);

		
		$updateQuery->execute();


		if (isset($editProduct->piso_selected) && $editProduct->piso_selected && count($editProduct->piso_selected) > 0) {


			$query2 = $this->pdo->prepare('DELETE from usuarios_has_piso WHERE usuarios_id_usuario =' . $editProduct->id_usuario);
			$query2->execute();

			foreach ($editProduct->piso_selected as $piso_id) {

				$quer2 = $this->pdo->prepare('INSERT INTO usuarios_has_piso (usuarios_id_usuario,piso_id_piso) VALUES (:usuarios_id_usuario, :piso_id_piso)');

				$quer2->bindParam(':usuarios_id_usuario', $editProduct->id_usuario);

				$quer2->bindParam(':piso_id_piso', $piso_id);

				$quer2->execute();
			}


		}


	}

	public function permissions(){
		$rol = $_SESSION['rol'];

		$query = $this->pdo->query('SELECT * FROM user_permission WHERE id_rol = '. $rol);
		return $query->fetch();
	}

	public function logout()
	{
		session_start();
		session_destroy();
	}

	public function authenticated($credentials)
	{
		$credentials = json_decode($credentials);

		if ($credentials->user === null || $credentials->user === '' || $credentials->password === null || $credentials->password === '') {
			return 0;
		}

		try {

			$query = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email AND user_password = :pass");

			$query->bindParam(':email', $credentials->user);
			$query->bindParam(':pass', $credentials->password);

			$query->execute();
			if ($query->rowCount() > 0) {

				$user = $query->fetch(PDO::FETCH_ASSOC);

				// Start or resume the session
				session_start();

				// Store user information in the session
				$_SESSION['nombredelusuario'] = $credentials->user;
				$_SESSION['id_usuario'] = $user['id_usuario'];
				$_SESSION['rol'] = $user['rol_id_rol'];
				$_SESSION['nombre'] = $user['nombre'];
				if (intval($user['rol_id_rol']) == 2) {
					
					return 2;
				} else {
					
					return 1;
				}
			} else {
				return 0;
			}
		} catch (PDOException $e) {
			
			return 0;
			echo 'Error: ' . $e->getMessage();
		}

	}
}
