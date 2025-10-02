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

document.addEventListener('DOMContentLoaded', function () {

    const btnAgregar = document.getElementById('btnAgregar');

    if (btnAgregar) {
        btnAgregar.addEventListener('click', function () {

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
document.addEventListener('DOMContentLoaded', function () {

    const tablaUsuarios = document.getElementById('tablaUsuarios');

    if (tablaUsuarios) {
        tablaUsuarios.addEventListener('click', function (event) {
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
    buscarInput.addEventListener('keyup', function () {
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
