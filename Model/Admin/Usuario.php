    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            padding: 20px;
        }

        #content {
            margin-top: 70px;
            margin-left: 200px;
            padding: 40px;
            background-color: #f7f7f7;
            min-height: calc(100vh - 70px);
        }

        /* Nuevos estilos para ajustar espaciado */
        .content-header {
            margin-bottom: 40px;
            /* Más espacio después del título */
        }

        #btnAgregar {
            margin-bottom: 30px;
            /* Más espacio después del botón */
        }

        .table-responsive {
            margin-top: 20px;
            /* Espacio antes de la tabla */
        }


        /*Alesrtas de agregar*/
        /* --- Estilos para el formulario en SweetAlert2 --- */
        /* --- Estilos para el formulario GRID en SweetAlert2 --- */

        /* Estilo para el título, simulando el encabezado azul del ejemplo */
        .swal2-title {
            background-color: #007bff;
            /* Un azul similar al del ejemplo */
            color: white !important;
            padding: 15px !important;
            margin: -20px -20px 20px -20px;
            /* Ajusta para que ocupe todo el ancho */
            border-radius: 5px 5px 0 0;
            font-size: 1.25em !important;
        }

        /* El contenedor del formulario ahora es una parrilla (grid) */
        .swal-form-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            /* Columna 1: autoancho para etiquetas. Columna 2: el resto del espacio */
            gap: 15px 10px;
            /* 15px de espacio vertical, 10px horizontal */
            align-items: center;
            /* Centra verticalmente las etiquetas con sus campos */
            text-align: left;
        }

        /* Estilo para las etiquetas en la parrilla */
        .swal-form-label {
            justify-self: end;
            /* Alinea las etiquetas a la derecha */
            font-weight: 600;
            color: #444;
        }

        /* Asegura que los inputs usen todo el ancho de su columna */
        .swal-form-grid .swal2-input,
        .swal-form-grid .swal2-select {
            width: 100% !important;
            margin: 0 !important;
        }
    </style>

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

    <script>
        /*Eliniar un registro */
        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, ¡bórralo!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, redirigimos a un script PHP para eliminar
                    window.location.href = '../../Controller/Ajax/Usuario.php?id=' + id;
                }
            })
        }

        /*Guardar un registro*/

        document.addEventListener('DOMContentLoaded', function() {

            const btnAgregar = document.getElementById('btnAgregar');

            if (btnAgregar) {
                btnAgregar.addEventListener('click', function() {

                    Swal.fire({
                        // El título ahora se ve como un encabezado gracias al CSS
                        title: 'Agregar Nuevo Usuario',
                        width: '650px', // Hacemos el modal un poco más ancho para el diseño de 2 columnas

                        // --- HTML con la nueva estructura GRID ---
                        html: `
                    <form id="formulario-agregar" method="POST" action="../../Controller/Ajax/Usuario.php" class="swal-form-grid">

                        <input type="hidden" name="accion" value="agregar">
                        
                        <label for="nombre" class="swal-form-label">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="swal2-input" required>
                        
                        <label for="telefono" class="swal-form-label">Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" class="swal2-input">
                        
                        <label for="correo" class="swal-form-label">Correo:</label>
                        <input type="email" id="correo" name="correo" class="swal2-input" required>
                        
                        <label for="usuario" class="swal-form-label">Usuario:</label>
                        <input type="text" id="usuario" name="usuario" class="swal2-input" required>
                        
                        <label for="clave" class="swal-form-label">Contraseña:</label>
                        <input type="password" id="clave" name="clave" class="swal2-input" required>
                        
                        <label for="rol" class="swal-form-label">Rol:</label>
                        <select id="rol" name="rol" class="swal2-select">
                            <option value="2">Usuario</option>
                            <option value="1">Administrador</option>
                        </select>
                    </form>
                `,
                        showCancelButton: true,
                        confirmButtonText: 'Guardar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#28a745', // Verde para el botón de guardar
                        cancelButtonColor: '#dc3545', // Rojo para el botón de cancelar

                        preConfirm: () => {
                            const form = document.getElementById('formulario-agregar');
                            if (!form.checkValidity()) {
                                Swal.showValidationMessage('Por favor, completa todos los campos requeridos.');
                                return false;
                            }
                            return true;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('formulario-agregar').submit();
                        }
                    });

                });
            }
        });

        /* Editar */
        document.addEventListener('DOMContentLoaded', function() {

            const tablaUsuarios = document.getElementById('tablaUsuarios');

            if (tablaUsuarios) {
                tablaUsuarios.addEventListener('click', function(event) {
                    if (event.target && event.target.classList.contains('btnEditar')) {

                        // Obtenemos los datos del usuario desde los data-atributos
                        const id = event.target.getAttribute('data-id');
                        const nombre = event.target.getAttribute('data-nombre');
                        const telefono = event.target.getAttribute('data-telefono');
                        const correo = event.target.getAttribute('data-correo');
                        const usuario = event.target.getAttribute('data-usuario');
                        const rol = event.target.getAttribute('data-rol');

                        Swal.fire({
                            title: 'Editar Usuario',
                            width: '650px',
                            html: `
                        <form id="formulario-editar" method="POST" action="../../Controller/Ajax/Usuario.php" class="swal-form-grid">

                            <input type="hidden" name="accion" value="editar">
                            <input type="hidden" name="idUsuario" value="${id}">

                            <label for="nombre">Nombre:</label>
                            <input type="text" name="nombre" class="swal2-input" value="${nombre}" required>

                            <label for="telefono">Teléfono:</label>
                            <input type="text" name="telefono" class="swal2-input" value="${telefono}">

                            <label for="correo">Correo:</label>
                            <input type="email" name="correo" class="swal2-input" value="${correo}" required>

                            <label for="usuario">Usuario:</label>
                            <input type="text" name="usuario" class="swal2-input" value="${usuario}" required>

                            <label for="clave">Contraseña (dejar vacío si no cambia):</label>
                            <input type="password" name="clave" class="swal2-input">

                            <label for="rol">Rol:</label>
                            <select name="rol" class="swal2-select">
                                <option value="2" ${rol == 2 ? "selected" : ""}>Usuario</option>
                                <option value="1" ${rol == 1 ? "selected" : ""}>Administrador</option>
                            </select>
                        </form>
                    `,
                            showCancelButton: true,
                            confirmButtonText: 'Guardar Cambios',
                            cancelButtonText: 'Cancelar',
                            confirmButtonColor: '#28a745',
                            cancelButtonColor: '#dc3545',
                            preConfirm: () => {
                                const form = document.getElementById('formulario-editar');
                                if (!form.checkValidity()) {
                                    Swal.showValidationMessage('Por favor, completa todos los campos requeridos.');
                                    return false;
                                }
                                return true;
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('formulario-editar').submit();
                            }
                        });
                    }
                });
            }
        });

        /* Eliminar Un Usuario*/

        function confirmarEliminacion(id) {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "¡No podrás revertir esto!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirige a Usuario.php para eliminar
                    window.location.href = `../../Controller/Ajax/Usuario.php?accion=eliminar&id=${id}`;
                }
            });
        }

        /*barra de busqqueda */
        // Filtrar la tabla por cualquier columna
        const buscarInput = document.getElementById('buscarUsuario');

        if (buscarInput) {
            buscarInput.addEventListener('keyup', function() {
                const filtro = this.value.toLowerCase();
                const tabla = document.getElementById('tablaUsuarios');
                const filas = tabla.getElementsByTagName('tr');

                for (let i = 1; i < filas.length; i++) { // Empezamos desde 1 para saltar el header
                    const celdas = filas[i].getElementsByTagName('td');
                    let textoFila = '';
                    for (let j = 0; j < celdas.length - 1; j++) { // -1 para no contar las acciones
                        textoFila += celdas[j].textContent.toLowerCase() + ' ';
                    }

                    if (textoFila.indexOf(filtro) > -1) {
                        filas[i].style.display = '';
                    } else {
                        filas[i].style.display = 'none';
                    }
                }
            });
        }
    </script>

    