
<style>
     /* --- Tu CSS Original --- */
     *{
            box-sizing: border-box;
            margin: 0;
            padding: 0;
     }
        #content {
            /* NOTA: Este margen funciona para escritorio, pero lo cambiaremos para móvil */
            margin-top: 100px;
            margin-left: 200px; 
            padding: 30px;
            background-color: #f7f7f7;
            z-index: -1;
        }
        .album-section{
            z-index: -1;
        }
        body {
            background-color: #e9ecef;
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
        .hero-section {
            padding: 48px 0;
            text-align: center;
        }
        .hero-container {
            max-width: 900px;
            padding: 0 15px;
            margin: 0 auto;
        }
        .hero-title {
            font-size: 40px;
            font-weight: 300;
            margin-bottom: 8px;
        }
        .hero-description {
            font-size: 20px;
            font-weight: 300;
            color: #6c757d;
            max-width: 700px;
            margin: 0 auto 16px auto;
        }
        .btn {
            display: inline-block;
            font-weight: 400;
            text-align: center;
            vertical-align: middle;
            cursor: pointer;
            border: 1px solid transparent;
            padding: 6px 12px;
            font-size: 16px;
            border-radius: 6px;
            text-decoration: none;
            margin: 0 4px;
        }
        /* -- He añadido tu clase de botón faltante para el ejemplo -- */
        .btn-outline-success {
            color: #198754;
            border-color: #198754;
        }
        .btn-outline-success:hover {
            color: #fff;
            background-color: #198754;
            border-color: #198754;
        }
        .album-section {
            padding: 48px 0;
            background-color: #f8f9fa;
        }
        .album-container {
            max-width: 960px;
            margin: 0 auto;
            padding: 0 15px;
        }
        .card-grid {
            display: flex;
            justify-content: space-between;
            /* NOTA: Esto causa que las tarjetas se salgan en pantallas pequeñas */
        }
        .card {
            width: 304px; /* NOTA: Este ancho fijo es un problema en móvil */
            background-color: #fff;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.075);
            margin-bottom: 20px; /* Añadido para espaciado vertical */
        }
        .card-thumbnail {
            height: 225px;
            background-color: #55595c;
            color: #eceeef;
            font-size: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card-body {
            padding: 16px;
        }
        .card-text {
            font-size: 16px;
            color: #212529;
            margin-bottom: 16px;
        }
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .time-text {
            font-size: 14px;
            color: #6c757d;
        }
        .time-text p {
            display: inline;
            margin: 0;
            font-weight: bold;
        }

        /* ================================================= */
        /* --- PASO 2: LOS ESTILOS RESPONSIVOS (LA MAGIA) -- */
        /* ================================================= */

        /* Para pantallas de 768px o menos (tablets y móviles) */
        @media (max-width: 768px) {
            
            /* Quitamos el margen izquierdo para que el contenido ocupe todo el ancho */
            #content {
                margin-left: 0;
                margin-top: 80px; /* Reducimos un poco el margen superior */
                padding: 30px;    /* Reducimos el padding para dar más espacio */
                z-index: 0;
            }

            /* Hacemos que la rejilla de tarjetas sea vertical y centrada */
            .card-grid {
                flex-direction: column; /* Apila las tarjetas una sobre otra */
                align-items: center;    /* Centra las tarjetas apiladas */
            }

            /* Hacemos que cada tarjeta sea flexible */
            .card {
                width: 90%; /* La tarjeta ocupa el 90% del ancho disponible */
            }

            /* Ajustamos el tamaño de los títulos para que no sean tan grandes */
            .hero-title {
                font-size: 32px;
            }

            .hero-description {
                font-size: 18px;
            }
        }
</style>
<div id="content" class="flex-grow-1">
        <main>
            <section class="hero-section">
                <div class="hero-container">
                    <h1 class="hero-title">Bienvenidos Administrador</h1>
                    <p class="hero-description">
                        Panel de administarcion de usuarios, colaboradores, bitacoras y reportes.
                    </p>
                    <div>
                        <a href="Colaboradores.php" class="btn btn-outline-success">Ver colaboradores</a>
                        <a href="Bitacora.php" class="btn btn-outline-success">Ver Bitacoras</a>
                    </div>
                </div>
            </section>

            <div class="album-section">
                <div class="album-container">
                    <div class="card-grid">

                        <div class="card">
                            <div class="card-thumbnail">Usuarios</div>
                            <div class="card-body">
                                <p class="card-text">Acceder a los regritros de usuarios.</p>
                                <div class="card-footer">
                                    <div class="btn-group">
                                        <a type="button" class="btn btn-outline-success" href="ListaUsuarios.php"> Ver </a>
                                    </div>
                                    <span class="time-text">Registros <p><?php echo $totalUsuarios; ?></p></span>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-thumbnail">Colaborador</div>
                            <div class="card-body">
                                <p class="card-text">Acceder al listado de los colaborador.</p>
                                <div class="card-footer">
                                    <div class="btn-group">
                                        <a type="button" class="btn btn-outline-success" href="Colaboradores.php">Ver </a>
                                    </div>
                                    <span class="time-text">Registros <p><?php echo $totalColaboradores; ?></p></span>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-thumbnail">Bitacoras</div>
                            <div class="card-body">
                                <p class="card-text">Acceder al listado de las bitacoras.</p>
                                <div class="card-footer">
                                    <div class="btn-group">
                                       <a type="button" class="btn btn-outline-success" href="Bitacora.php">Ver </a>
                                    </div>
                                    <span class="time-text">Registros <p><?php echo $totalBitacoras; ?></p></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </main>
    </div>

