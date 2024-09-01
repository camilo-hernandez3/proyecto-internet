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

	public function destroy($id){
		$query = $this->pdo->prepare('UPDATE usuarios set status_ = 0 where id_usuario =' . $id);
		$query->execute();
	}

	public function allroles(){
		$query = $this->pdo->query('SELECT * FROM rol');
		return $query->fetchAll();
	}

	public function show($id_usuario)
	{
		$query = $this->pdo->query('SELECT * FROM usuarios where id_usuario =' . $id_usuario);
		return $query->fetch();
	}

	public function store($usuario)
	{

		$usuario = json_decode($usuario);

		$query = $this->pdo->prepare('INSERT INTO usuarios (nombre, email,user_password,rol_id_rol) VALUES (:nombre, :email, :user_password, :rol_id_rol)');
		

		$query->bindParam(':nombre', $usuario->nombres);
		
		$query->bindParam(':email', $usuario->email);
		$query->bindParam(':user_password', $usuario->password);
		$query->bindParam(':rol_id_rol', $usuario->selected_rol);
		
		$query->execute();
		$lastInsertId = $this->pdo->lastInsertId();
		return $this->show($lastInsertId);
	}

	public function logout(){
		session_start();
		session_destroy();
	}

	public function authenticated($credentials)
	{
		$credentials = json_decode($credentials);

		if ($credentials->user === null || $credentials->user === '' || $credentials->password === null ||  $credentials->password === '') {
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
				if (intval($user['rol_id_rol']) == 1) {
					/* header("location: ../stats.php"); */
					return 1;
				} else {
					/* header("location: ../sales.php"); */
					return 2;
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
