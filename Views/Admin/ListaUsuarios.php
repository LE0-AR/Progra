<?php
// 1. INCLUIR LA CONEXIÓN
require_once "../../Controller/Config/Conexion.php";

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <link rel="stylesheet" href="../../Model/css/ListaUsuario.css">
    <?php
    require_once "../../Controller/Server/app.php";
    include "../../Controller/Server/head.php";
    ?>
</head>
<style>

</style>

<body>

    <?php include "../../Model/Admin/Header.php"; ?>

    <div id="content" class="container mt-4">
        <div class="content-header">
            <h2>Listado de Usuarios</h2>
            <button class="btn btn-success" id="btnAgregar">Agregar Usuario</button>
        </div>
        <div class="table-responsive">
            <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="buscarUsuario" placeholder="Buscar usuario...">
            </div>

            <table class="table table-striped table-hover align-middle" id="tablaUsuarios">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Usuario</th>
                        <th>Rol</th>
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
                            $contador = 1; // ← Contador manual para enumerar los registros

                            // 4. RECORRER LOS RESULTADOS CON UN BUCLE while
                            while ($fila = $resultado->fetch_assoc()) {
                                // Determinar el rol en texto
                                $rolTexto = ($fila['IdRol'] == 1) ? "Administrador" : "Usuario";

                                echo "<tr>";
                                echo "<td>" . $contador . "</td>";  // ← Imprime el número de fila
                                echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['telefono']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['correo']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['usuario']) . "</td>";
                                echo "<td>" . htmlspecialchars($rolTexto) . "</td>";
                                echo "<td>";
                                // Botones de acción con data-id para identificar el usuario
                                echo "<button 
                                        class='btn btn-primary btn-sm btnEditar' 
                                        data-id='" . $fila['IdUsuario'] . "' 
                                        data-nombre='" . htmlspecialchars($fila['nombre'], ENT_QUOTES) . "' 
                                        data-telefono='" . htmlspecialchars($fila['telefono'], ENT_QUOTES) . "' 
                                        data-correo='" . htmlspecialchars($fila['correo'], ENT_QUOTES) . "' 
                                        data-usuario='" . htmlspecialchars($fila['usuario'], ENT_QUOTES) . "' 
                                        data-rol='" . $fila['IdRol'] . "'>
                                        Editar
                                    </button> ";
                                echo "<button onclick='confirmarEliminacion(" . $fila['IdUsuario'] . ")' class='btn btn-danger btn-sm'>Eliminar</button>";

                                echo "</td>";
                                echo "</tr>";

                                $contador++;
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No se encontraron usuarios registrados.</td></tr>";
                        }
                        $stmt->close();
                    } else {

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