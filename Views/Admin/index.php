<?php
require_once "../../Controller/Server/app.php";
require_once "../../Controller/Config/Sesion.php";
require_once "../../Controller/Config/Conexion.php";
// Consultas para contar registros
$sqlUsuarios = "SELECT COUNT(*) as total FROM usuario";
$sqlColaboradores = "SELECT COUNT(*) as total FROM colaborador";
$sqlBitacoras = "SELECT COUNT(*) as total FROM bitacora";

$totalUsuarios = $conexion->query($sqlUsuarios)->fetch_assoc()['total'];
$totalColaboradores = $conexion->query($sqlColaboradores)->fetch_assoc()['total'];
$totalBitacoras = $conexion->query($sqlBitacoras)->fetch_assoc()['total'];

?>
<!DOCTYPE html>
<html lang="es">
<?php
include "../../Controller/Server/head.php";
?>

</head>

<body>
    <!-- Navbar -->
    <?php include "../../Model/Admin/Header.php"; ?>
    <?php include "../../Model/Admin/Home.php"; ?>
    
</body>

</html>