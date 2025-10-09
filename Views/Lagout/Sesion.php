<?php 
// Iniciar la sesión
session_start();
//capturar los datos obtenidos y si la sesión está activa que si se pueda navegar de lo contrario redireccionar un archivo
if(!isset($_SESSION['usuario'])) {
    header("Location: ../../");
    exit(); 
}
// Validación adicional por rol
if(isset($_SESSION['IdRol'])) {
    $ruta_actual = $_SERVER['PHP_SELF'];
    
    // Si es usuario normal intentando acceder a área de admin
    if($_SESSION['IdRol'] == 2 && strpos($ruta_actual, '/Admin/') !== false) {
        header("Location: ../user/");
        exit();
    }
}
?>