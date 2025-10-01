$(document).ready(function () {

  function cargarUsuarios() {
    $.get("../../Controller/Ajax/Usuario.php?action=listar", function (data) {
      let usuarios = JSON.parse(data);
      let html = '';
      usuarios.forEach(u => {
        html += `
                <tr>
                    <td>${u.IdUsuario}</td>
                    <td>${u.Nombre}</td>
                    <td>${u.Telefono}</td>
                    <td>${u.Correo}</td>
                    <td>${u.usuario}</td>
                    <td>${u.IdRol}</td>
                    <td>
                        <button class="btn btn-info btn-sm view-btn" data-id="${u.IdUsuario}">Ver</button>
                        <button class="btn btn-warning btn-sm edit-btn" data-id="${u.IdUsuario}">Editar</button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${u.IdUsuario}">Eliminar</button>
                    </td>
                </tr>`;
      });
      $("#tablaUsuarios tbody").html(html);
    });
  }

  cargarUsuarios();

  // Agregar usuario
  $("#btnAgregar").click(function () {
    Swal.fire({
      title: 'Agregar Usuario',
      html:
        '<input id="nombre" class="swal2-input" placeholder="Nombre">' +
        '<input id="telefono" class="swal2-input" placeholder="Teléfono">' +
        '<input id="correo" class="swal2-input" placeholder="Correo">' +
        '<input id="usuario" class="swal2-input" placeholder="Usuario">' +
        '<input id="password" type="password" class="swal2-input" placeholder="Password">' +
        '<input id="idRol" class="swal2-input" placeholder="IdRol">',
      confirmButtonText: 'Agregar',
      showCancelButton: true
    }).then((result) => {
      if (result.isConfirmed) {
        let nombre = $("#nombre").val();
        let telefono = $("#telefono").val();
        let correo = $("#correo").val();
        let usuario = $("#usuario").val();
        let password = $("#password").val();
        let idRol = $("#idRol").val();
        $.post("../../Controller/Ajax/Usuario.php",
          { action: 'agregar', nombre, telefono, correo, usuario, password, idRol },
          function (r) {
            Swal.fire(r.status == 'ok' ? 'Agregado' : 'Error', r.status == 'ok' ? 'Usuario agregado' : 'No se pudo agregar', 'success');
            if (r.status == 'ok') cargarUsuarios();
          }, 'json'); // <-- fuerza a jQuery a interpretar JSON automáticamente

      }
    });
  });

  // Editar usuario
  $(document).on('click', '.edit-btn', function () {
    let id = $(this).data('id');
    $.get("../../Controller/Ajax/Usuario.php?action=listar", function (data) {
      let usuarios = JSON.parse(data);
      let u = usuarios.find(x => x.IdUsuario == id);
      Swal.fire({
        title: 'Editar Usuario',
        html:
          `<input id="nombre" class="swal2-input" value="${u.Nombre}" placeholder="Nombre">` +
          `<input id="telefono" class="swal2-input" value="${u.Telefono}" placeholder="Teléfono">` +
          `<input id="correo" class="swal2-input" value="${u.Correo}" placeholder="Correo">` +
          `<input id="usuario" class="swal2-input" value="${u.usuario}" placeholder="Usuario">` +
          `<input id="idRol" class="swal2-input" value="${u.IdRol}" placeholder="IdRol">`,
        confirmButtonText: 'Guardar',
        showCancelButton: true
      }).then((result) => {
        if (result.isConfirmed) {
          let nombre = $("#nombre").val();
          let telefono = $("#telefono").val();
          let correo = $("#correo").val();
          let usuario = $("#usuario").val();
          let idRol = $("#idRol").val();
          $.post("../../Controller/Server/usuariosController.php",
            { action: 'editar', id, nombre, telefono, correo, usuario, idRol },
            function (resp) {
              let r = JSON.parse(resp);
              Swal.fire(r.status == 'ok' ? 'Guardado' : 'Error', r.status == 'ok' ? 'Usuario actualizado' : 'No se pudo actualizar', r.status == 'ok' ? 'success' : 'error');
              if (r.status == 'ok') cargarUsuarios();
            });
        }
      });
    });
  });

  // Eliminar usuario
  $(document).on('click', '.delete-btn', function () {
    let id = $(this).data('id');
    Swal.fire({
      title: '¿Eliminar usuario?',
      text: '¡No podrás revertir esto!',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Sí, eliminar',
      cancelButtonText: 'Cancelar'
    }).then((result) => {
      if (result.isConfirmed) {
        $.post("../../Controller/Ajax/Usuario.php",
          { action: 'agregar', nombre, telefono, correo, usuario, password, idRol },
          function (r) {
            Swal.fire(r.status == 'ok' ? 'Agregado' : 'Error', r.status == 'ok' ? 'Usuario agregado' : 'No se pudo agregar', 'success');
            if (r.status == 'ok') cargarUsuarios();
          }, 'json'); // <-- fuerza a jQuery a interpretar JSON automáticamente

      }
    });
  });

});
