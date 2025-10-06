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
    /* ----- ESTILOS GENERALES Y BOTONES ----- */
    .acciones-btns {
        display: flex;
        gap: 8px;
        justify-content: center;
        align-items: center;
    }
    .pagination-container {
        display: flex;
        gap: 6px;
        justify-content: center;
        align-items: center;
        margin-top: 16px;
    }
    .pagination-container button {
        padding: 4px 10px;
        border: 1px solid #ddd;
        background: #fff;
        cursor: pointer;
        border-radius: 4px;
    }
    .pagination-container button.active {
        background: #007bff;
        color: #fff;
        border-color: #007bff;
    }

    /* ----- ESTILOS DE LA TABLA ----- */
    #tablaBitacora {
        width: 100%;
        table-layout: fixed;
        min-width: 700px;
        border-collapse: collapse;
    }
    #tablaBitacora td,
    #tablaBitacora th {
        padding: 8px;
        vertical-align: top;
    }
    
    #tablaBitacora tbody td {
        height: 70px; /* Alto de las filas */
    }

    /* ----- ANCHOS DE COLUMNAS PERSONALIZADOS ----- */
/* ----- ANCHOS DE COLUMNAS PERSONALIZADOS ----- */
        #tablaBitacora th:nth-child(1) { width: 4%; }   /* Columna No */
        #tablaBitacora th:nth-child(2) { width: 15%; }  /* Columna Colaborador */
        #tablaBitacora th:nth-child(3) { width: 12%; }  /* Columna ODT */
        #tablaBitacora th:nth-child(4) { width: 10%; }  /* Columna Fecha */
        #tablaBitacora th:nth-child(5) { width: 6%; }   /* Columna Horas */
        /* La columna 6 (Descripción) no necesita ancho, tomará el espacio sobrante */
        #tablaBitacora th:nth-child(7) { width: 160px; } /* Columna Acciones con ancho fijo */

    /* ----- ESTILOS PARA LA CELDA DE DESCRIPCIÓN ----- */
    .desc-col {
        word-wrap: break-word;
    }
    .description-content {
        max-height: 120px;
        overflow-y: auto;
        white-space: pre-wrap;
        padding-right: 8px;
    }

    /* ----- ESTILOS RESPONSIVE ----- */
    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }
    @media (max-width: 900px) {
        #tablaBitacora th:nth-child(3), #tablaBitacora td:nth-child(3),
        #tablaBitacora th:nth-child(5), #tablaBitacora td:nth-child(5) {
            display: none;
        }
        #tablaBitacora { min-width: 500px; }
    }
    @media (max-width: 600px) {
        #tablaBitacora th:nth-child(4), #tablaBitacora td:nth-child(4),
        #tablaBitacora th:nth-child(7), #tablaBitacora td:nth-child(7) {
            display: none;
        }
        #tablaBitacora { min-width: 320px; }
        .desc-col {
            font-size: 13px;
            padding: 4px;
        }
    }
</style>
</head>

<body>

    <?php include "../../Model/User/Header.php"; ?>

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
                                echo "<td class='desc-col' ... >";
                                echo "<div class='description-content'>" . htmlspecialchars($fila['Descripcion']) . "</div>";
                                echo "</td>";
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
            <!-- Pie de página para paginación (debe estar aquí) -->
            <div id="paginacion" class="pagination-container"></div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const perPage = 10;
            const tabla = document.getElementById('tablaBitacora');
            const tbody = tabla ? tabla.tBodies[0] : null;
            const paginacion = document.getElementById('paginacion');
            const buscador = document.getElementById('buscarBitacora');

            function getRows() {
                // Solo filas que no son mensajes de "no hay registros"
                return Array.from(tbody.querySelectorAll('tr')).filter(row => {
                    const tds = row.querySelectorAll('td');
                    return !(tds.length === 1 && tds[0].hasAttribute('colspan'));
                });
            }

            function getFilteredRows() {
                const filtro = buscador ? buscador.value.trim().toLowerCase() : '';
                const rows = getRows();
                if (!filtro) return rows;
                return rows.filter(row => {
                    return Array.from(row.querySelectorAll('td')).slice(0, -1).map(td => td.textContent.toLowerCase()).join(' ').includes(filtro);
                });
            }

            function showPage(page) {
                const rows = getFilteredRows();
                const totalPages = Math.ceil(rows.length / perPage) || 1;
                page = Math.max(1, Math.min(page, totalPages));
                // Oculta todos
                getRows().forEach(row => row.style.display = 'none');
                // Muestra solo los filtrados y paginados
                rows.forEach((row, i) => {
                    row.style.display = (i >= (page - 1) * perPage && i < page * perPage) ? '' : 'none';
                });
                renderPagination(page, totalPages, rows.length);
            }

            function renderPagination(current, total, totalRows) {
                paginacion.innerHTML = '';
                // Solo mostrar si hay más de 10 registros
                if (totalRows <= perPage) {
                    paginacion.style.display = 'none';
                    return;
                }
                paginacion.style.display = 'flex';

                // Botón anterior
                const prev = document.createElement('button');
                prev.textContent = 'Anterior';
                prev.disabled = current === 1;
                prev.onclick = () => showPage(current - 1);
                paginacion.appendChild(prev);

                // Botones de página (máximo 10 visibles)
                let maxButtons = 10;
                let start = Math.max(1, current - Math.floor(maxButtons / 2));
                let end = Math.min(total, start + maxButtons - 1);
                if (end - start < maxButtons - 1) start = Math.max(1, end - maxButtons + 1);

                for (let i = start; i <= end; i++) {
                    const btn = document.createElement('button');
                    btn.textContent = i;
                    btn.className = (i === current ? 'active' : '');
                    btn.onclick = () => showPage(i);
                    paginacion.appendChild(btn);
                }

                // Botón siguiente
                const next = document.createElement('button');
                next.textContent = 'Siguiente';
                next.disabled = current === total;
                next.onclick = () => showPage(current + 1);
                paginacion.appendChild(next);
            }

            // Inicializar paginación
            if (tbody) showPage(1);

            // Actualizar paginación al buscar
            if (buscador) {
                buscador.addEventListener('input', function() {
                    showPage(1);
                });
            }
        });
    </script>
</body>

</html>