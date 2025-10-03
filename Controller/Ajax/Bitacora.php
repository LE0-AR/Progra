<?php
session_start();
require_once "../Config/Conexion.php";

$redirect = "../../Views/Admin/Bitacora.php"; // AJUSTA a ../../View/... si tu carpeta se llama View

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';

    if ($accion === 'agregar') {
        $idCol = intval($_POST['colaborador'] ?? 0);
        $odt = trim($_POST['odt'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        $horas = floatval($_POST['horas'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');

        $sql = "INSERT INTO bitacora (IdColaborador, ODT, Fecha, Horas, Descripcion) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("issds", $idCol, $odt, $fecha, $horas, $descripcion); // i s s d s

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Bitácora agregada correctamente.";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al guardar: " . $conexion->error;
            $_SESSION['tipo_mensaje'] = "error";
        }
        $stmt->close();
        header("Location: " . $redirect);
        exit();
    }

    if ($accion === 'editar') {
        $id = intval($_POST['id'] ?? 0);
        $idCol = intval($_POST['colaborador'] ?? 0);
        $odt = trim($_POST['odt'] ?? '');
        $fecha = trim($_POST['fecha'] ?? '');
        $horas = floatval($_POST['horas'] ?? 0);
        $descripcion = trim($_POST['descripcion'] ?? '');

        $sql = "UPDATE bitacora SET IdColaborador = ?, ODT = ?, Fecha = ?, Horas = ?, Descripcion = ? WHERE IdBitacora = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("issdsi", $idCol, $odt, $fecha, $horas, $descripcion, $id); // i s s d s i

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Bitácora actualizada correctamente.";
            $_SESSION['tipo_mensaje'] = "success";
        } else {
            $_SESSION['mensaje'] = "Error al actualizar: " . $conexion->error;
            $_SESSION['tipo_mensaje'] = "error";
        }
        $stmt->close();
        header("Location: " . $redirect);
        exit();
    }
}

// ELIMINAR por GET (se redirige desde JS)
if (isset($_GET['accion']) && $_GET['accion'] === 'eliminar' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conexion->prepare("DELETE FROM bitacora WHERE IdBitacora = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['mensaje'] = "Bitácora eliminada correctamente.";
        $_SESSION['tipo_mensaje'] = "success";
    } else {
        $_SESSION['mensaje'] = "Error al eliminar: " . $conexion->error;
        $_SESSION['tipo_mensaje'] = "error";
    }
    $stmt->close();
    header("Location: " . $redirect);
    exit();
}

$conexion->close();
