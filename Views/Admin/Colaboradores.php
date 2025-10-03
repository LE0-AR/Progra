<?php
// 1. INCLUIR LA CONEXIÓN Y SESIÓN
require_once "../../Controller/Config/Conexion.php";
require_once "../../Controller/Config/Sesion.php";

// Definir el mapeo de áreas
$areas = [
    '16' => 'Infoseg',
];
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

<body>
    <?php include "../../Model/Admin/Header.php"; ?>

    <div id="content" class="container mt-4">
        <div class="content-header">
            <h2>Listado de Colaboradores</h2>
            <button class="btn btn-success" id="btnAgregar">Agregar Colaborador</button>
        </div>

        <div class="table-responsive">
            <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="buscarColaborador" placeholder="Buscar colaborador...">
            </div>

            <table class="table table-striped table-hover align-middle" id="tablaColaboradores">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Area</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // 2. PREPARAR LA CONSULTA SQL
                    $sql = "SELECT IdColaborador, nombre, telefono, correo, area FROM colaborador";

                    $stmt = $conexion->prepare($sql);

                    if ($stmt) {
                        $stmt->execute();
                        $resultado = $stmt->get_result();

                        if ($resultado->num_rows > 0) {
                            $contador = 1;

                            while ($fila = $resultado->fetch_assoc()) {
                                // Convertir el ID de área a texto usando el array
                                $areaTexto = isset($areas[$fila['area']]) ? $areas[$fila['area']] : $fila['area'];

                                echo "<tr>";
                                echo "<td>" . $contador . "</td>";
                                echo "<td>" . htmlspecialchars($fila['nombre']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['telefono']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['correo']) . "</td>";
                                echo "<td>" . htmlspecialchars($areaTexto) . "</td>";
                                echo "<td>";
                                echo "<button 
                                        class='btn btn-primary btn-sm btnEditar' 
                                        data-id='" . $fila['IdColaborador'] . "'
                                        data-nombre='" . htmlspecialchars($fila['nombre'], ENT_QUOTES) . "'
                                        data-telefono='" . htmlspecialchars($fila['telefono'], ENT_QUOTES) . "'
                                        data-correo='" . htmlspecialchars($fila['correo'], ENT_QUOTES) . "'
                                        data-area='" . htmlspecialchars($fila['area'], ENT_QUOTES) . "'>
                                        Editar
                                    </button> ";
                                echo "<button onclick='confirmarEliminacion(" . $fila['IdColaborador'] . ")' class='btn btn-danger btn-sm'>Eliminar</button>";
                                echo "</td>";
                                echo "</tr>";

                                $contador++;
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No se encontraron colaboradores registrados.</td></tr>";
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
    <script src="../../Model/js/Colaborador.js"></script>
</body>

</html>
