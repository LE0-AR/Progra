<?php
// 1. INCLUIR LA CONEXIÓN
require_once "../../Controller/Config/Conexion.php";
require_once "../../Controller/Config/Sesion.php";

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    require_once "../../Controller/Server/app.php";
    include "../../Controller/Server/head.php";
    ?>
</head>
<style>

</style>

<body>

    <?php include "../../Model/Admin/Header.php";
    include "../../Model/Admin/Usuario.php";
    ?>
    

</body>

</html>