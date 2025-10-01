<?php
require_once "../Config/Conexion.php";

// Forzar que la respuesta sea JSON
header('Content-Type: application/json');

// Función para responder JSON y salir
function respond($arr){
    echo json_encode($arr);
    exit;
}

// Listar usuarios
if(isset($_GET['action']) && $_GET['action'] == "listar"){
    $sql = "SELECT IdUsuario, Nombre, Telefono, Correo, usuario, IdRol FROM usuarios";
    $result = $conexion->query($sql);
    $data = [];
    while($row = $result->fetch_assoc()){
        $data[] = $row;
    }
    respond($data);
}

// Agregar usuario
if(isset($_POST['action']) && $_POST['action'] == "agregar"){
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $idRol = $_POST['idRol'];

    $sql = "INSERT INTO usuarios (Nombre, Telefono, Correo, usuario, password, IdRol) 
            VALUES ('$nombre', '$telefono', '$correo', '$usuario', '$password', '$idRol')";
    respond(['status' => $conexion->query($sql) ? 'ok' : 'error']);
}

// Editar usuario
if(isset($_POST['action']) && $_POST['action'] == "editar"){
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $idRol = $_POST['idRol'];

    $sql = "UPDATE usuarios 
            SET Nombre='$nombre', Telefono='$telefono', Correo='$correo', usuario='$usuario', IdRol='$idRol'
            WHERE IdUsuario=$id";
    respond(['status' => $conexion->query($sql) ? 'ok' : 'error']);
}

// Eliminar usuario
if(isset($_POST['action']) && $_POST['action'] == "eliminar"){
    $id = $_POST['id'];
    $sql = "DELETE FROM usuarios WHERE IdUsuario=$id";
    respond(['status' => $conexion->query($sql) ? 'ok' : 'error']);
}
?>
