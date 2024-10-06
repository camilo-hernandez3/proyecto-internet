<?php
session_start();
if (isset($_POST['btningresar'])) {
	$dbhost = "localhost";
	$dbuser = "root";
	$dbpass = "root";
	$dbname = "u715371815_root";
	$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	$Usuario = $_POST['txtusuario'];
	$Contraseña = $_POST['txtpassword'];
	$query = mysqli_query($conn, "Select * from usuario where email = '$Usuario' and password = '$Contraseña'");
	$nr = mysqli_num_rows($query);
	if ($nr != 0) {
		while ($row = mysqli_fetch_array($query)) {
			if ($Usuario == $row['email'] && $Contraseña == $row['password']) {
				$_SESSION['nombredelusuario'] = $Usuario;
				$_SESSION['id_usuario'] = $row['id_usuario'];
				$_SESSION['rol'] = $row['rol_id_rol'];
				if (intval($row['rol_id_rol']) == 1) {
					header("location:users.php");
				} else {
					header("location:users.php");
				}
			}
		}
	} else {
		echo "<script>alert('correo y/o contraseña no coinciden');window.location= 'index.php' </script>";
	}
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Tienda del soldado GSECO</title>
	<link rel="stylesheet" href="./css/estilo.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
	<div class="container">
		<div class="login-content">
			<form method="POST">
				<div class="title-container">
					<!-- <img src="./img/logo.png" alt="Icono" class="icon"> -->
					<h2 class="title">BIENVENIDO</h2>
				</div>
				<div class="input-div one">
					<div class="i">
						<i class="fas fa-user"></i>
					</div>
					<div class="div">
						<h5>Correo electrónico</h5>
						<input type="email" id="user"  name="txtusuario" class="input">
					</div>
				</div>
				<div class="input-div pass">
					<div class="i">
						<i class="fas fa-lock"></i>
					</div>
					<div class="div">
						<h5>Contraseña</h5>
						<input type="password"  id="user_password" name="txtpassword" class="input">
					</div>
				</div>
				<input type="button" name="btningresar" class="btn" onclick="autenthicated()" value="Iniciar Sesión">
			</form>
		</div>
	</div>
	<script type="text/javascript" src="js/main.js"></script>
	<script src="./js/login.js"></script>
</body>

</html>