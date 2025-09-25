<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "dbinfoseg";

$conexion = mysqli_init();
mysqli_options($conexion, MYSQLI_OPT_CONNECT_TIMEOUT, 60); 
// Intentar conectar
if (!mysqli_real_connect($conexion, $host, $user, $password, $db)) {
    die("Error de conexión: " . mysqli_connect_error());
}

echo "<script>console.log('live serve');</script>";

// Verificar si la conexión sigue activa antes de ejecutar consultas
if (!mysqli_ping($conexion)) {
    die("Error: La conexión con MySQL se ha perdido.");
}
