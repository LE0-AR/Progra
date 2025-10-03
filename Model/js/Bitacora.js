document.addEventListener('DOMContentLoaded', function () {
    const tpl = document.getElementById('tpl-bitacora-form');
    const btnAgregar = document.getElementById('btnAgregar');

    // ABRIR MODAL PARA AGREGAR
    if (btnAgregar) {
        btnAgregar.addEventListener('click', function () {
            openBitacoraModal('agregar');
        });
    }

    // ABRIR MODAL PARA EDITAR (delegación de eventos)
    document.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('btnEditar')) {
            const btn = e.target;
            const data = {
                id: btn.getAttribute('data-id'),
                colaborador: btn.getAttribute('data-colaborador'),
                odt: btn.getAttribute('data-odt'),
                fecha: btn.getAttribute('data-fecha'),
                horas: btn.getAttribute('data-horas'),
                descripcion: btn.getAttribute('data-descripcion')
            };
            openBitacoraModal('editar', data);
        }
    });

    function openBitacoraModal(mode = 'agregar', data = {}) {
        const html = tpl.innerHTML; // contiene el form con las <option> ya generadas por PHP

        Swal.fire({
            title: mode === 'agregar' ? 'Agregar Bitácora' : 'Editar Bitácora',
            html: html,
            width: '700px',
            showCancelButton: true,
            confirmButtonText: mode === 'agregar' ? 'Guardar' : 'Actualizar',
            didOpen: () => {
                // Cuando el modal está listo, rellenamos los valores (si es editar)
                const form = Swal.getPopup().querySelector('#form-bitacora');
                form.querySelector('[name=accion]').value = mode;
                form.querySelector('[name=id]').value = data.id || '';

                if (mode === 'editar') {
                    form.querySelector('[name=colaborador]').value = data.colaborador || '';
                    form.querySelector('[name=odt]').value = data.odt || '';
                    form.querySelector('[name=fecha]').value = data.fecha || '';
                    form.querySelector('[name=horas]').value = data.horas || '';
                    form.querySelector('[name=descripcion]').value = data.descripcion || '';
                }
            },
            preConfirm: () => {
                const form = Swal.getPopup().querySelector('#form-bitacora');
                if (!form.checkValidity()) {
                    Swal.showValidationMessage('Por favor completa todos los campos requeridos');
                    return false;
                }
                return true;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar el formulario (método POST, sin AJAX)
                const form = Swal.getPopup().querySelector('#form-bitacora');
                form.submit();
            }
        });
    }

    // FUNCION PARA ELIMINAR (redirige al controlador con GET)
    window.confirmarEliminacion = function (id) {
        Swal.fire({
            title: '¿Eliminar registro?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirige al controlador para eliminar (puedes cambiar a POST si prefieres)
                window.location.href = "../../Controller/Ajax/Bitacora.php?accion=eliminar&id=" + id;
            }
        });
    };

    // FILTRAR TABLA (buscador)
    const buscar = document.getElementById('buscarBitacora');
    if (buscar) {
        buscar.addEventListener('keyup', function () {
            const filtro = this.value.toLowerCase();
            const filas = document.querySelectorAll('#tablaBitacora tbody tr');
            filas.forEach(row => {
                const texto = row.innerText.toLowerCase();
                row.style.display = texto.indexOf(filtro) !== -1 ? '' : 'none';
            });
        });
    }
});
