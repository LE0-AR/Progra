  <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid">
      <button class="btn btn-primary" id="menu-toggle">☰</button>
      <a class="navbar-brand ms-3" href="#">Sistema de bitacoras</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="../../Views/Admin/">Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Reportes</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Acciones</a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="../../Views//Lagout/Sesion_destroy.php">Cerrar sesion</a></li>
              <li><a class="dropdown-item" href="../../index.php">Cambiar cuenta</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="d-flex">
    <!-- Sidebar -->
    <div id="sidebar" class="border-end">
      <div class="list-group list-group-flush">
        <a href="ListaUsuarios.php" class="list-group-item list-group-item-action">Usuarios</a>
        <a href="Colaboradores.php" class="list-group-item list-group-item-action">Colaboradores</a>
        <a href="Bitacora.php" class="list-group-item list-group-item-action">Bitacoras</a>
        <a href="Reporte.php" class="list-group-item list-group-item-action">Reporte</a>
      </div>
    </div>


  </div>
  <style>
    /* Navbar fijo arriba */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
    }

    /* Sidebar fijo debajo del navbar */
    #sidebar {
      position: fixed;
      top: 56px;
      /* altura del navbar */
      left: 0;
      width: 200px;
      height: calc(100vh - 56px);
      /* ocupa todo el alto restante */
      background: #f8f9fa;
      border-right: 1px solid #ddd;
      padding-top: 20px;
      overflow-y: auto;
      /* scroll si el contenido crece mucho */
    }

    /* Contenido principal desplazado */




    #navbar {
      flex-grow: 1;
    }

    #sidebar {
      min-width: 200px;
      max-width: 200px;
      background: #f8f9fa;
      transition: margin-left 0.3s;
      display: flex;
      align-items: center;
      /* Centra verticalmente el contenido */
      padding: 20px 0;
    }

    .list-group {
      width: 100%;
    }

    .list-group-item {
      padding: 15px 20px;
      /* Más espacio entre elementos */
    }

    #sidebar.collapsed {
      margin-left: -200px;
    }
  </style>
  <script>
    const toggleBtn = document.getElementById("menu-toggle");
    const sidebar = document.getElementById("sidebar");

    toggleBtn.addEventListener("click", () => {
      sidebar.classList.toggle("collapsed");
    });

    // EDITAR con confirmación
  </script>