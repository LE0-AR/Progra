<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../Config/db.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action === 'list') {
    $result = $conexion->query("SELECT id, usuario, IdRol FROM usuario");
    $data = [];
    while ($row = $result->fetch_assoc()) $data[] = $row;
    echo json_encode(['status' => 'success', 'data' => $data]);
    exit;
}

if ($action === 'save') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $IdRol = $_POST['IdRol'] ?? 'User';

    if ($usuario === '' || ($id === 0 && $password === '')) {
        echo json_encode(['status' => 'error', 'message' => 'Faltan datos obligatorios']);
        exit;
    }

    if ($id === 0) {
        // insertar
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuario (usuario, password, IdRol) VALUES (?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("sss", $usuario, $hash, $IdRol);
        if ($stmt->execute()) echo json_encode(['status' => 'success', 'message' => 'Usuario creado']);
        else echo json_encode(['status' => 'error', 'message' => $conexion->error]);
        $stmt->close();
    } else {
        // actualizar
        if (!empty($password)) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE usuario SET usuario = ?, password = ?, IdRol = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssi", $usuario, $hash, $IdRol, $id);
        } else {
            $sql = "UPDATE usuario SET usuario = ?, IdRol = ? WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssi", $usuario, $IdRol, $id);
        }
        if ($stmt->execute()) echo json_encode(['status' => 'success', 'message' => 'Usuario actualizado']);
        else echo json_encode(['status' => 'error', 'message' => $conexion->error]);
        $stmt->close();
    }
    exit;
}

if ($action === 'delete') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    if ($id === 0) {
        echo json_encode(['status' => 'error', 'message' => 'ID inválido']);
        exit;
    }
    $sql = "DELETE FROM usuario WHERE id = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) echo json_encode(['status' => 'success', 'message' => 'Usuario eliminado']);
    else echo json_encode(['status' => 'error', 'message' => $conexion->error]);
    $stmt->close();
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Acción no válida']);
$conexion->close();
