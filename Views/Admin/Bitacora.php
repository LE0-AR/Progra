<?php
// 1. INCLUIR LA CONEXIÓN y sesión
require_once "../../Controller/Config/Conexion.php";
require_once "../../Controller/Config/Sesion.php";

// --- Obtener opciones de colaboradores para el select (se ejecuta en servidor)
$opcionesCol = "<option value=''>Seleccione...</option>";
$sqlCol = "SELECT IdColaborador, nombre FROM colaborador ORDER BY nombre ASC";
if ($resCol = $conexion->query($sqlCol)) {
    while ($r = $resCol->fetch_assoc()) {
        $opcionesCol .= "<option value='" . intval($r['IdColaborador']) . "'>" . htmlspecialchars($r['nombre']) . "</option>";
    }
    $resCol->free();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="../../Model/css/ListaUsuario.css">
    <?php
    require_once "../../Controller/Server/app.php";
    include "../../Controller/Server/head.php";
    ?>
    <style>
        .acciones-btns {
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>

    <?php include "../../Model/Admin/Header.php"; ?>

    <div id="content" class="container mt-4">
        <div class="content-header">
            <h2>Registro de Bitacoras</h2>
            <button class="btn btn-success" id="btnAgregar">Agregar Bitacora</button>
        </div>

        <div class="table-responsive">
            <div class="mb-3 mt-3">
                <input type="text" class="form-control" id="buscarBitacora" placeholder="Buscar bitácora...">
            </div>

            <table class="table table-striped table-hover align-middle" id="tablaBitacora">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Colaborador</th>
                        <th>ODT</th>
                        <th>Fecha</th>
                        <th>Horas</th>
                        <th>Descripcion</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $sql = "SELECT b.IdBitacora, b.IdColaborador, c.nombre AS nombre_colaborador, b.ODT, b.Fecha, b.Horas, b.Descripcion
                            FROM bitacora b
                            INNER JOIN colaborador c ON b.IdColaborador = c.IdColaborador
                            ORDER BY b.Fecha DESC";
                    $stmt = $conexion->prepare($sql);
                    if ($stmt) {
                        $stmt->execute();
                        $resultado = $stmt->get_result();
                        if ($resultado->num_rows > 0) {
                            $contador = 1;
                            while ($fila = $resultado->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $contador . "</td>";
                                echo "<td>" . htmlspecialchars($fila['nombre_colaborador']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['ODT']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['Fecha']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['Horas']) . "</td>";
                                echo "<td>" . htmlspecialchars($fila['Descripcion']) . "</td>";
                                echo "<td>";
                                echo "<div class='acciones-btns'>";
                                // Botón editar con data-atributos (incluye IdColaborador para seleccionar en el select)
                                echo "<button 
                                        class='btn btn-primary btn-sm btnEditar' 
                                        data-id='" . intval($fila['IdBitacora']) . "' 
                                        data-colaborador='" . intval($fila['IdColaborador']) . "'
                                        data-odt='" . htmlspecialchars($fila['ODT'], ENT_QUOTES) . "'
                                        data-fecha='" . htmlspecialchars($fila['Fecha'], ENT_QUOTES) . "'
                                        data-horas='" . htmlspecialchars($fila['Horas'], ENT_QUOTES) . "'
                                        data-descripcion='" . htmlspecialchars($fila['Descripcion'], ENT_QUOTES) . "'>
                                        Editar
                                      </button>";
                                echo "<button onclick='confirmarEliminacion(" . intval($fila['IdBitacora']) . ")' class='btn btn-danger btn-sm'>Eliminar</button>";
                                echo "</div>";
                                echo "</td>";
                                echo "</tr>";
                                $contador++;
                            }
                        } else {
                            echo "<tr><td colspan='7' class='text-center'>No se encontraron bitácoras registradas.</td></tr>";
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

    <!-- TEMPLATE con el formulario (se genera en servidor con $opcionesCol) -->
    <template id="tpl-bitacora-form">
        <form id="form-bitacora" method="POST" action="../../Controller/Ajax/Bitacora.php" class="swal-form-grid">
            <input type="hidden" name="accion" value="">
            <input type="hidden" name="id" value="">

            <label>Colaborador:</label>
            <select name="colaborador" class="swal2-select" required>
                <?php echo $opcionesCol; ?>
            </select>

            <label>ODT:</label>
            <input type="text" name="odt" class="swal2-input" required>

            <label>Fecha:</label>
            <input type="date" name="fecha" class="swal2-input" required>

            <label>Horas:</label>
            <input type="number" step="0.25" name="horas" class="swal2-input" required>

            <label>Descripción:</label>
            <textarea name="descripcion" class="swal2-textarea" required rows="4" style="resize:vertical;min-height:80px;"></textarea>
        </form>
    </template>

    <script src="../../Model/js/Bitacora.js"></script>
</body>
</html>
