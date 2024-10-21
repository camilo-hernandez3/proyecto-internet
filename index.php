<?php
session_start();
if (isset($_POST['btningresar'])) {
	$dbhost = "monitoreokonekti.online";
	$dbuser = "u311227962_root";
	$dbpass = "Luna-2080";
	$dbname = "u311227962_proyecto";
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
				header("location:welcome.php");
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
	<title>monitoreo konekti</title>
	<link rel="apple-touch-icon" sizes="76x76" href="https://www.konekti.com.co/wp-content/uploads/2019/05/cropped-Logo_Web-1-192x192.png">
    <link rel="icon" type="image/png" href="https://www.konekti.com.co/wp-content/uploads/2019/05/cropped-Logo_Web-1-192x192.png">
	<link rel="stylesheet" href="./css/estilo.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

	<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>x
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		
	</style>
</head>

<body>
	<div class="container">
		<div class="login-content">
			<form method="POST">
				<div class="text-center" style="margin-top:15px">
					<img src="./img/logo.png" alt="Icono" class="icon" style="width: 200px;">
					<!-- <img src="https://www.konekti.com.co/wp-content/uploads/2019/05/cropped-Logo_Web-1-150x150.png" alt=""> -->
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