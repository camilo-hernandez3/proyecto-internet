<?php
$conn = new mysqli("monitoreokonekti.online","u311227962_root","Luna-2080","u311227962_proyecto");
	
	if($conn->connect_errno)
	{
		echo "No hay conexión: (" . $conn->connect_errno . ") " . $conn->connect_error;
	}
?>