<?php
require_once "Controller/Server/app.php";
include_once "Controller/config/conexion.php";

$mensaje = '';
$redireccion = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

    $sql = "SELECT * FROM usuario WHERE usuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['IdRol'] = $user['IdRol'];

            if ($_SESSION['IdRol'] == 1) {
                $mensaje = "Bienvenido Administrador";
                $redireccion = "Views/Admin/";
            } else if ($_SESSION['IdRol'] == 2) {
                $mensaje = "Bienvenido Usuario";
                $redireccion = "Views/User/";
            }
        } else {
            $mensaje = "Contraseña incorrecta";
        }
    } else {
        $mensaje = "Usuario no encontrado";
    }

    $stmt->close();
    $conexion->close();
}
?>