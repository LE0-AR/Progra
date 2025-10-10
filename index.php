    <?php
    include_once "Controller/Server/Validar.php";
    ?>

    <head>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
        <link rel="stylesheet" href="Model/css/login.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <link rel="shortcut icon" href="./Model/img/LOGO.png" type="image/x-icon">
        <?php include_once "Controller/Server/head.php"; ?>
    </head>
    <?php if (!empty($mensaje)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                <?php if (!empty($redireccion)): ?>
                    Swal.fire({
                        title: '<?php echo $mensaje; ?>',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '<?php echo $redireccion; ?>';
                    });
                <?php else: ?>
                    Swal.fire({
                        title: 'Error',
                        text: '<?php echo $mensaje; ?>',
                        icon: 'error'
                    });
                <?php endif; ?>
            });
        </script>
    <?php endif; ?>
    <?php include_once "./Controller/Ajax/login.php"; ?>

    </html>