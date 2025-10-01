<!DOCTYPE html>
<html lang="es">
    <link rel="stylesheet" href="../../Model/css/PanelAdmin.css">
<?php
require_once "../../Config/config.php";
include "../../Controller/Server/head.php";
?>

</head>

<body>
    <!-- Navbar -->
    <?php include "../../Model/Admin/Header.php"; ?>
    <?php include "../../Model/Admin/Home.php"; ?>
    <script>
        const toggleBtn = document.getElementById("menu-toggle");
        const sidebar = document.getElementById("sidebar");

        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("collapsed");
        });
    </script>
</body>

</html>