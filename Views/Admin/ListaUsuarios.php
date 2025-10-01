<?php
// 1. INCLUIR LA CONEXIÓN
require_once "../../Controller/Config/Conexion.php";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <link rel="stylesheet" href="../../Model/css/PanelAdmin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>

    <?php include "../../Model/Admin/Header.php"; ?>

    <div id="content" class="container mt-4">
        <h2 class="mb-3">Listado de Usuarios</h2>
        <button class="btn btn-success mb-3" id="btnAgregar">Agregar Usuario</button>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle" id="tablaUsuarios">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Usuario</th>
                        <th>IdRol</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // 2. PREPARAR LA CONSULTA SQL
                    $sql = "SELECT IdUsuario, nombre, telefono, correo, usuario, IdRol  FROM usuario";

                    // Asumiendo que tu archivo de conexión crea un objeto mysqli llamado $conexion
                    $stmt = $conexion->prepare($sql);

                    if ($stmt) { // Verificamos si la preparación fue exitosa
                        $stmt->execute();
                        $resultado = $stmt->get_result(); // ESTA ES LA LÍNEA CLAVE PARA MYSQLI

                        // 3. VERIFICAR SI HAY REGISTROS
                        if ($resultado->num_rows > 0) {
                            // 4. RECORRER LOS RESULTADOS CON UN BUCLE while
                            while ($fila = $resultado->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($fila['IdUsuario']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['telefono']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['correo']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['usuario']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['IdRol']) . "</td>";
                                echo "<td>";
                                echo "<a href='editar_usuario.php?IdUsuario=" . htmlspecialchars($fila['IdUsuario']) . "' class='btn btn-primary btn-sm'>Editar</a> ";
                                echo "<a href='eliminar_usuario.php?IdUsuario=" . htmlspecialchars($fila['IdUsuario']) . "' class='btn btn-danger btn-sm'>Eliminar</a>";
                                echo "</td>";
                                echo "</tr>";
                            }
                        } else {
                            // Mensaje si no hay usuarios
                            echo "<tr><td colspan='7' class='text-center'>No se encontraron usuarios registrados.</td></tr>";
                        }
                        $stmt->close(); // Buena práctica cerrar el statement
                    } else {
                        // Manejo de error si la consulta no se pudo preparar
                        echo "<tr><td colspan='7' class='text-center'>Error al preparar la consulta: " . htmlspecialchars($conexion->error) . "</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../../Model/js/Usuario.js"></script>
</body>

</html>