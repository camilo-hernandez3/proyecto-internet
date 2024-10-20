<?php
// Conexión a la base de datos
$mysqli = new mysqli("cafeinternet.store", "u715371815_root", "Luna-2080", "u715371815_proyecto");

// Verificar la conexión
if ($mysqli->connect_error) {
    die("Conexión fallida: " . $mysqli->connect_error);
}

// Consulta para actualizar fecha_final
$query = "UPDATE usuario_equipo
          SET fecha_final = NOW()
          WHERE TIMESTAMPDIFF(SECOND, last_update, NOW()) > 20
          AND (fecha_final IS NULL OR fecha_final = '')";

if ($mysqli->query($query) === TRUE) {
    echo "Registro(s) actualizado(s).";
} else {
    echo "Error: " . $mysqli->error;
}

// Cerrar la conexión
$mysqli->close();

?>