<?php
// Archivo: Controller/Ajax/Usuario.php (VERSIÓN FINAL COMPLETA)

session_start();
require_once "../Config/Conexion.php";

header('Content-Type: application/json; charset=utf-8');

if (isset($_GET['accion']) && $_GET['accion'] == "obtener" && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM usuario WHERE IdUsuario = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($fila = $resultado->fetch_assoc()) {
        echo json_encode(["status" => "ok", "data" => $fila]);
    } else {
        echo json_encode(["status" => "error", "msg" => "Usuario no encontrado"]);
    }
}

// ==========================================================
// == 2. AGREGAR UN NUEVO USUARIO ==
// ==========================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'agregar') {
    
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $clave = $_POST['clave'];
    $idRol = $_POST['rol'];

    $clave_hasheada = password_hash($clave, PASSWORD_DEFAULT);
    
    // VERIFICA que los nombres de tus columnas (nombre, telefono, etc.) sean correctos.
    // Asegúrate que la columna de la contraseña se llame 'password' en tu DB.
    $sql = "INSERT INTO usuario (nombre, telefono, correo, usuario, password, IdRol) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("sssssi", $nombre, $telefono, $correo, $usuario, $clave_hasheada, $idRol);
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Usuario agregado correctamente.";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al guardar en la base de datos.";
            $_SESSION['tipo_mensaje'] = "error";
        }
    } else {
        $_SESSION['mensaje'] = "Error al preparar la consulta.";
        $_SESSION['tipo_mensaje'] = "error";
    }
    
    // Revisa que esta ruta a tu lista sea la correcta.
    header('Location: ../../Views/Admin/ListaUsuarios.php');
    exit(); 
}

// ==========================================================
// == 3. EDITAR UN USUARIO EXISTENTE ==
// ==========================================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] == 'editar') {
    
    $idUsuario = $_POST['idUsuario'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $idRol = $_POST['rol'];
    $clave = $_POST['clave'];

    if (!empty($clave)) {
        // Si el usuario escribió una nueva contraseña, la actualizamos.
        $clave_hasheada = password_hash($clave, PASSWORD_DEFAULT);
        $sql = "UPDATE usuario SET nombre=?, telefono=?, correo=?, usuario=?, password=?, IdRol=? WHERE IdUsuario=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sssssii", $nombre, $telefono, $correo, $usuario, $clave_hasheada, $idRol, $idUsuario);
    } else {
        // Si el campo de contraseña está vacío, no la actualizamos.
        $sql = "UPDATE usuario SET nombre=?, telefono=?, correo=?, usuario=?, IdRol=? WHERE IdUsuario=?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("ssssii", $nombre, $telefono, $correo, $usuario, $idRol, $idUsuario);
    }

    if ($stmt && $stmt->execute()) {
        $_SESSION['mensaje'] = "Usuario actualizado correctamente.";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al actualizar el usuario.";
        $_SESSION['tipo_mensaje'] = "error";
    }
    
    header('Location: ../../Views/Admin/ListaUsuarios.php');
    exit();
}

// ==========================================================
// == 4. ELIMINAR UN USUARIO ==
// ==========================================================
if (isset($_GET['accion']) && $_GET['accion'] == 'eliminar' && isset($_GET['id'])) {
    
    $idUsuario = intval($_GET['id']);
    $sql = "DELETE FROM usuario WHERE IdUsuario = ?";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $idUsuario);
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Usuario eliminado correctamente.";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al eliminar el usuario.";
            $_SESSION['tipo_mensaje'] = "error";
        }
    } else {
        $_SESSION['mensaje'] = "Error al preparar la consulta.";
        $_SESSION['tipo_mensaje'] = "error";
    }
    
    header('Location: ../../Views/Admin/ListaUsuarios.php');
    exit();
}

// ==========================================================
// == 5. REDIRECCIÓN FINAL (SI NINGUNA ACCIÓN COINCIDE) ==
// ==========================================================
header('Location: ../../Views/Admin/ListaUsuarios.php');
exit();
?>