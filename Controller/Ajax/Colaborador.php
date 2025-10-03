<?php
// Incluir conexión
require_once "../Config/Conexion.php";

// Asegurar que se reciba la acción
if (!isset($_REQUEST['accion'])) {
    die("Acción no especificada");
}

$accion = $_REQUEST['accion'];

switch ($accion) {

    // =====================================================
    // AGREGAR COLABORADOR
    // =====================================================
    case 'agregar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $telefono = trim($_POST['telefono']);
            $correo = trim($_POST['correo']);
            $area = trim($_POST['area']);

            $sql = "INSERT INTO colaborador (nombre, telefono, correo, area) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssss", $nombre, $telefono, $correo, $area);

            if ($stmt->execute()) {
                header("Location: ../../Views/Admin/Colaboradores.php?msg=agregado");
            } else {
                header("Location: ../../Views/Admin/Colaboradores.php?msg=error");
            }
            $stmt->close();
        }
        break;

    // =====================================================
    // EDITAR COLABORADOR
    // =====================================================
    case 'editar':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $nombre = trim($_POST['nombre']);
            $telefono = trim($_POST['telefono']);
            $correo = trim($_POST['correo']);
            $area = trim($_POST['area']);

            $sql = "UPDATE colaborador SET nombre = ?, telefono = ?, correo = ?, area = ? WHERE IdColaborador = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssi", $nombre, $telefono, $correo, $area, $id);

            if ($stmt->execute()) {
                header("Location: ../../Views/Admin/Colaboradores.php?msg=editado");
            } else {
                header("Location: ../../Views/Admin/Colaboradores.php?msg=error");
            }
            $stmt->close();
        }
        break;

    // =====================================================
    // ELIMINAR COLABORADOR
    // =====================================================
    case 'eliminar':
        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);

            $sql = "DELETE FROM colaborador WHERE IdColaborador = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                header("Location: ../../Views/Admin/Colaboradores.php?msg=eliminado");
            } else {
                header("Location: ../../Views/Admin/Colaboradores.php?msg=error");
            }
            $stmt->close();
        }
        break;

    default:
        echo "Acción no válida.";
        break;
}

$conexion->close();
