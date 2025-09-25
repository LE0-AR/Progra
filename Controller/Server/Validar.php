<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizamos las entradas
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    // Consulta solo el usuario para obtener el hash de la contraseña
    $sql = "SELECT * FROM usuario WHERE usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hash_guardado = $user['password']; // Recuperamos el hash de la base de datos

        // Comparamos la contraseña ingresada con el hash almacenado
        if (password_verify($password, $hash_guardado)) {
            session_start();
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['IdRol'] = $user['IdRol'];

            // Validación de rol
            if ($_SESSION['IdRol'] == 'Admin') {
                echo "<script> 
                    swal({
                        title: 'Bienvenido Administrador',
                        text: 'Acceso correcto',
                        icon: 'success',
                        timer: 1000,
                        buttons: false
                    }).then(function() {
                        window.location.href = 'Views/Admin/';
                    });
                </script>";
            } else if ($_SESSION['IdRol'] == 'User') {
                echo "<script> 
                    swal({
                        title: 'Bienvenido Usuario',
                        text: 'Acceso correcto',
                        icon: 'success',
                        timer: 1000,
                        buttons: false
                    }).then(function() {
                        window.location.href = 'Views/User/';
                    });
                </script>";
            }
            exit(); 
        } else {
            echo "<script> 
                swal({
                    title: 'Error',
                    text: 'Credenciales incorrectas',
                    icon: 'error'
                });
            </script>";
        }
    } else {
        echo "<script> 
            swal({
                title: 'Error',
                text: 'Credenciales incorrectas',
                icon: 'error'
            });
        </script>";
    }
// Cerrar la conexión y liberar resultados
    $stmt->close();
    $conexion->close();
}