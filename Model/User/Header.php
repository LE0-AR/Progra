  <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
    <div class="container-fluid">
      <button class="btn btn-primary" id="menu-toggle">☰</button>
      <a class="navbar-brand ms-3" href="#">Sistema de bitacoras</a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav ms-auto">
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
        <a href="index.php" class="list-group-item list-group-item-action">Bitacoras</a>
        <a href="#" class="list-group-item list-group-item-action">Reporte</a>
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
      #wrapper {
            display: flex;
        }

        #sidebar {
            /* El menú estará oculto fuera de la pantalla por defecto */
            margin-left: -250px; 
            min-width: 250px;
            max-width: 250px;
            height: 100vh; /* Ocupa toda la altura */
            position: fixed; /* Fijo para que se superponga */
            top: 0;
            left: 0;
            background: #f8f9fa;
            z-index: 1031; /* Un z-index alto para que esté por encima de todo */
            transition: margin-left 0.3s ease; /* Transición suave */
            padding-top: 56px; /* Espacio para que no choque con el navbar */
        }

        /* La clase 'active' que añadimos con JS para MOSTRAR el menú */
        #sidebar.active {
            margin-left: 0;
        }

        #page-content-wrapper {
            width: 100%;
            padding-top: 56px; /* Espacio para el navbar fijo */
        }

        /* Para oscurecer el fondo cuando el menú está abierto en móvil */
        .overlay {
            display: none;
            position: fixed;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1030; /* Justo debajo del sidebar */
        }
        .overlay.active {
            display: block;
        }


        /* --- Estilos para Escritorio (Pantallas más grandes de 768px) --- */
        @media (min-width: 768px) {
            #sidebar {
                /* En escritorio, el menú no está fijo, es parte del flujo */
                position: relative; 
                /* Siempre visible y en su lugar por defecto */
                margin-left: 0; 
                padding-top: 0;
            }

            /* Cuando se colapsa en escritorio, se oculta con margen */
            #sidebar.collapsed {
                margin-left: -250px;
            }
            
            #page-content-wrapper {
                width: 100%;
                padding-top: 56px;
            }
            
            /* Ocultamos el fondo oscuro en escritorio */
            .overlay {
                display: none !important;
            }
        }
  </style>
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const toggleBtn = document.getElementById("sidebar-toggle");
      const sidebar = document.getElementById("sidebar");

      toggleBtn.addEventListener("click", () => {
        // En lugar de 'collapsed', usamos 'active' para mostrar/ocultar
        sidebar.classList.toggle("active");

        // También podrías añadir un overlay para el fondo en móvil
        // (Esto es opcional pero recomendado)
        const overlay = document.querySelector('.overlay');
        if (overlay) {
          overlay.classList.toggle('active');
        }
      });

      // Opcional: Para cerrar el menú al hacer clic en el overlay
      const overlay = document.querySelector('.overlay');
      if (overlay) {
        overlay.addEventListener('click', () => {
          sidebar.classList.remove('active');
          overlay.classList.remove('active');
        });
      }
    });
  </script>
