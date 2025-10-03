document.addEventListener('DOMContentLoaded', function () {

    // ====== AGREGAR COLABORADOR ======
    const btnAgregar = document.getElementById('btnAgregar');

    if (btnAgregar) {
        btnAgregar.addEventListener('click', function () {
            Swal.fire({
                title: 'Agregar Nuevo Colaborador',
                width: '650px',
                html: `
                    <form id="formulario-agregar" method="POST" action="../../Controller/Ajax/Colaborador.php" class="swal-form-grid">
                        <input type="hidden" name="accion" value="agregar">

                        <label for="nombre" class="swal-form-label">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="swal2-input" required>

                        <label for="telefono" class="swal-form-label">Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" class="swal2-input">

                        <label for="correo" class="swal-form-label">Correo:</label>
                        <input type="email" id="correo" name="correo" class="swal2-input" required>

                        <label for="area" class="swal-form-label">Área:</label>
                        <select id="area" name="area" class="swal2-input" required>
                            <option value="">Seleccione un área</option>
                            <option value="16">Infoseg</option>
                        </select>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#dc3545',

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

    // ====== EDITAR COLABORADOR ======
    document.querySelectorAll('.btnEditar').forEach(boton => {
        boton.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-nombre');
            const telefono = this.getAttribute('data-telefono');
            const correo = this.getAttribute('data-correo');
            const area = this.getAttribute('data-area');

            Swal.fire({
                title: 'Editar Colaborador',
                width: '650px',
                html: `
                    <form id="formulario-editar" method="POST" action="../../Controller/Ajax/Colaborador.php" class="swal-form-grid">
                        <input type="hidden" name="accion" value="editar">
                        <input type="hidden" name="id" value="${id}">

                        <label for="nombre" class="swal-form-label">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" class="swal2-input" value="${nombre}" required>

                        <label for="telefono" class="swal-form-label">Teléfono:</label>
                        <input type="text" id="telefono" name="telefono" class="swal2-input" value="${telefono}">

                        <label for="correo" class="swal-form-label">Correo:</label>
                        <input type="email" id="correo" name="correo" class="swal2-input" value="${correo}" required>

                        <label for="area" class="swal-form-label">Área:</label>
                        <select id="area" name="area" class="swal2-input" required>
                            <option value="">Seleccione un área</option>
                            <option value="16" ${area === '16' ? 'selected' : ''}>Infoseg</option>
                        </select>
                    </form>
                `,
                showCancelButton: true,
                confirmButtonText: 'Actualizar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#007bff',
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
        });
    });

});

// ====== ELIMINAR COLABORADOR ======
function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Eliminar colaborador?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d'
    }).then((result) => {
        if (result.isConfirmed) {
            // Redirigir al controlador con el ID
            window.location.href = "../../Controller/Ajax/Colaborador.php?accion=eliminar&id=" + id;
        }
    });
}
// ====== Buscador de COLABORADOR ======

const buscarInput = document.getElementById('buscarColaborador');

if (buscarInput) {
    buscarInput.addEventListener('keyup', function () {
        const filtro = this.value.toLowerCase();
        const tabla = document.getElementById('tablaColaboradores');
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
