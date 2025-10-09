<?php
require_once "../../Controller/Config/Sesion.php";
require_once "../../Controller/Config/Conexion.php";
// Consultas para contar registros

?>
<!DOCTYPE html>
<html lang="es">
    <link rel="stylesheet" href="../../Model/css/PanelAdmin.css">
<?php
require_once "../../Controller/Server/app.php";
include "../../Controller/Server/head.php";
?>

</head>

<body>
    <!-- Navbar y Reporte -->
    <?php
        include "../../Model/Admin/Header.php"; 
        include "../../Model/public/Reporte_PDF.php";
    ?>
    
</body>

</html>