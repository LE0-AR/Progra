<?php
require_once "Controller/Server/app.php";
include_once "Controller/config/conexion.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="Model/css/login.css">
    <script src=" https://unpkg.com/sweetalert/dist/sweetalert.min.js "> </script>
    <?php include_once "Controller/Server/head.php"; ?>
</head>

<body>
    <?php include_once "Views/login.php"; ?>
    <?php require_once "Controller/Server/Validar.php"; ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>