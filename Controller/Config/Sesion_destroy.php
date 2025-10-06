<?php
// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpiar todas las variables de sesión
$_SESSION = [];

// Eliminar la cookie de sesión en el cliente (si existe)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destruir la sesión en el servidor
session_destroy();

// Opcional: destruir cualquier cookie adicional que uses (ejemplo: remember_me)
// setcookie('remember_me', '', time() - 42000, '/');

// Redirigir al login (ajusta la ruta según tu proyecto)
header("Location: ../../");
exit();
?>
