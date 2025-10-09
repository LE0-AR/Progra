<?php


// Lógica para regresar al formulario de código (debe ir antes de cualquier salida)
if (isset($_POST['regresar'])) {
	// Limpia solo las variables de acceso.
	unset($_SESSION['acceso_codigo']);
	unset($_SESSION['departamento_id']);

	// Redirige al mismo archivo actual (usando $_SERVER['PHP_SELF'])
	header("Location: " . $_SERVER['PHP_SELF']);
	exit;
}

// Arrglo de codigos que equivale a una por su departamento.
// Definir los códigos de acceso válidos y sus correspondientes IDs de departamento.
// Ejemplo: 'CODIGO' => id_departamento *NO MOVER EL 28*
$codigos_validos = [

	'IFG016' => 16,
	'ADMCON' => 28
];

$acceso_autorizado = false;
$mensaje_error = '';

// Validación del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_acceso'])) {
	$codigo_ingresado = trim($_POST['codigo_acceso']);
	if (array_key_exists($codigo_ingresado, $codigos_validos)) {
		$acceso_autorizado = true;
		$_SESSION['acceso_codigo'] = true;
		$_SESSION['departamento_id'] = $codigos_validos[$codigo_ingresado];
	} else {
		$mensaje_error = 'Código incorrecto';
		unset($_SESSION['acceso_codigo']);
		unset($_SESSION['departamento_id']);
	}
} elseif (isset($_SESSION['acceso_codigo']) && $_SESSION['acceso_codigo'] === true) {
	$acceso_autorizado = true;
}

// Puedes obtener el id del departamento así:
$departamento_id = isset($_SESSION['departamento_id']) ? $_SESSION['departamento_id'] : null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<!-- SweetAlert2 CDN -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<style>
		body {
			background: #f5f6fa;
			font-family: 'Segoe UI', Arial, sans-serif;
		}

		.center-container {
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			min-height: 70vh;
		}

		.access-form {
			background: #fff;
			padding: 32px 40px;
			border-radius: 12px;
			box-shadow: 0 4px 24px rgba(44, 62, 80, 0.10);
			display: flex;
			flex-direction: column;
			align-items: center;
			min-width: 250px;
		}

		.access-form h2 {
			margin-bottom: 24px;
			color: #273c75;
		}

		.access-form input[type="text"] {
			padding: 11px 16px;
			border: 1px solid #dcdde1;
			border-radius: 6px;
			font-size: 18px;
			margin-bottom: 19px;
			width: 100%;
			transition: border-color 0.2s;
		}

		.access-form input[type="text"]:focus {
			border-color: #4078c0;
			outline: none;
		}

		.access-form button {
			background: linear-gradient(90deg, #4078c0 0%, #273c75 100%);
			color: #fff;
			border: none;
			border-radius: 6px;
			padding: 11px 32px;
			font-size: 18px;
			cursor: pointer;
			transition: background 0.2s;
		}

		.access-form button:hover {
			background: linear-gradient(90deg, #273c75 0%, #4078c0 100%);
		}

		.report-title {
			text-align: center;
			color: #273c75;
			margin-top: 48px;
			font-size: 40px;
			letter-spacing: 2px;
			font-weight: 700;
			text-shadow: 0 2px 8px rgba(44, 62, 80, 0.08);
		}

		.reportes-container {
			display: flex;
			flex-wrap: wrap;
			gap: 32px;
			justify-content: center;
			margin-top: 40px;
		}

		.reporte-card {
			background: #fff;
			border-radius: 10px;
			box-shadow: 0 2px 12px rgba(44, 62, 80, 0.10);
			padding: 32px 24px;
			min-width: 260px;
			max-width: 320px;
			display: flex;
			flex-direction: column;
			align-items: center;
			transition: transform 0.15s;
		}

		.reporte-card:hover {
			transform: translateY(-6px) scale(1.03);
			box-shadow: 0 6px 24px rgba(44, 62, 80, 0.13);
		}

		.reporte-icon {
			font-size: 45px;
			color: #4078c0;
			margin-bottom: 16px;
		}

		.reporte-title {
			font-size: 21px;
			font-weight: 600;
			color: #273c75;
			margin-bottom: 11px;
			text-align: center;
		}

		.reporte-desc {
			font-size: 16px;
			color: #636e72;
			margin-bottom: 19px;
			text-align: center;
		}

		.reporte-btn {
			background: #4078c0;
			color: #fff;
			border: none;
			border-radius: 5px;
			padding: 8px 24px;
			font-size: 16px;
			cursor: pointer;
			transition: background 0.2s;
			text-decoration: none;
		}

		.reporte-btn:hover {
			background: #273c75;
		}

		@media (max-width: 700px) {
			.reportes-container {
				flex-direction: column;
				align-items: center;
			}
		}
	</style>
</head>

<body>

	<div class="center-container">
		<?php if (!$acceso_autorizado): ?>
			<!-- Primera pantalla: formulario de acceso -->
			<form method="post" class="access-form">
				<h2>Ingrese el código de acceso</h2>
				<input type="password" name="codigo_acceso" placeholder="Código" required autofocus>
				<br>
				<button type="submit">Acceder</button>
			</form>
			<!-- Primera pantalla: formulario de acceso -->
			<?php if ($mensaje_error): ?>
				<script>
					Swal.fire({
						icon: 'error',
						title: 'Código incorrecto',
						text: '<?php echo $mensaje_error; ?>',
						confirmButtonColor: '#4078c0'
					});
				</script>
			<?php endif; ?>
		<?php elseif ($departamento_id == 28): ?>
			<!-- Tercera pantalla: acceso ADMCON -->
			<?php
			// Consulta para obtener los colaboradores (nombre_producto)
			$colaboradores = [];
			$query = mysqli_query($conexion, "SELECT Nombre FROM colaborador ORDER BY Nombre ASC");
			while ($row = mysqli_fetch_assoc($query)) {
				$colaboradores[] = $row['Nombre'];
			}
			?>
			<!-- Tercera pantalla: acceso autorizado -->
			<div>
				<div class="report-title" style="margin-bottom:0.5rem;">

					REPORTES POR COLABORADOR
				</div>
				<div style="text-align:center; margin-bottom:2rem;">
					<span style="color:#4078c0; font-weight:600; font-size:1.1rem;">
						Administrador
					</span>
					<br>
					<span style="color:#273c75; font-size:1.2rem;">
						Departamento ID: <?php echo htmlspecialchars($departamento_id); ?>
					</span>
				</div>
				<div class="reportes-container" style="justify-content:center;">
					<form class="access-form" id="form-fechas-admcon" action="../PDF/AdminPersonal.php" method="post" target="_blank" style="box-shadow:0 8px 32px rgba(44,62,80,0.13);padding:2.5rem 2.5rem;max-width:400px;">
						<div style="width:100%;text-align:center;margin-bottom:1.5rem;">

							<h2 style="margin:0;font-size:1.5rem;color:#273c75;">Generar Reporte PDF </h2>
							<p style="color:#636e72;font-size:1rem;margin-top:0.5rem;">Seleccione el colaborador y el rango de fechas para generar el reporte.</p>
						</div>
						<input type="hidden" name="departamento_id" value="<?php echo htmlspecialchars($departamento_id); ?>">
						<label for="colaborador" style="align-self:flex-start;margin-bottom:0.3rem;font-weight:500;color:#4078c0;">Seleccione el colaborador</label>
						<select name="colaborador" id="colaborador" required style="margin-bottom:1.2rem; padding:0.7rem 1rem; border-radius:8px; border:1.5px solid #b2bec3; width:100%;font-size:1.08rem;background:#f5f6fa;">
							<option value="" disabled selected>Seleccione...</option>
							<?php foreach ($colaboradores as $colaborador): ?>
								<option value="<?php echo htmlspecialchars($colaborador); ?>"><?php echo htmlspecialchars($colaborador); ?></option>
							<?php endforeach; ?>
						</select>
						<div style="width:100%;display:flex;gap:1rem;flex-wrap:wrap;">
							<div style="flex:1;">
								<label for="fecha_inicio_admcon" style="margin-bottom:0.3rem;font-weight:500;color:#4078c0;">Fecha inicio</label>
								<input type="date" id="fecha_inicio_admcon" name="fecha_inicio" required style="margin-bottom:1rem;width:100%;padding:0.6rem 0.7rem;border-radius:8px;border:1.5px solid #b2bec3;">
							</div>
							<div style="flex:1;">
								<label for="fecha_final_admcon" style="margin-bottom:0.3rem;font-weight:500;color:#4078c0;">Fecha final</label>
								<input type="date" id="fecha_final_admcon" name="fecha_final" required style="margin-bottom:1rem;width:100%;padding:0.6rem 0.7rem;border-radius:8px;border:1.5px solid #b2bec3;">
							</div>
						</div>
						<button type="submit" style="background:linear-gradient(90deg,#4078c0 0%,#273c75 100%);color:#fff;border:none;border-radius:8px;padding:0.8rem 2.2rem;font-size:1.15rem;font-weight:600;cursor:pointer;box-shadow:0 2px 8px rgba(44,62,80,0.10);margin-top:0.5rem;">
							&#128196; Generar PDF
						</button>
					</form>
				</div>
				<!-- Botón Regresar -->
				<div style="text-align:center; margin-top:2.5rem;">
					<form method="post" style="display:inline;">
						<input type="hidden" name="regresar" value="1">
						<button type="submit" style="background:#e74c3c;color:#fff;border:none;padding:0.8rem 2.2rem;border-radius:8px;font-size:1.1rem;cursor:pointer;font-weight:600;box-shadow:0 2px 8px rgba(44,62,80,0.10);">
							&#8592; Regresar
						</button>
					</form>
				</div>
			</div>
			<!-- tercera pantalla: FIN -->
		<?php else: ?>
			<!-- Segunda pantalla: acceso autorizado -->
			<div>
				<div class="report-title">REPORTES</div>
				<?php if ($departamento_id): ?>
					<div style="text-align:center; margin-bottom:1.5rem;">
						<span style="color:#4078c0; font-weight:600;">Departamento ID:</span>
						<span style="color:#273c75;"><?php echo htmlspecialchars($departamento_id); ?></span>
					</div>
				<?php endif; ?>
				<div class="reportes-container">
					<!--menu de control de fechas-->
					<form class="access-form" id="form-fechas" action="../PDF/Bitacora.php" method="post" target="_blank">
						<h2>Generar Reporte PDF</h2>
						<input type="hidden" name="departamento_id" value="<?php echo htmlspecialchars($departamento_id); ?>">
						<label style="align-self:flex-start;margin-bottom:0.3rem;">Fecha inicio</label>
						<input type="date" id="fecha_inicio" name="fecha_inicio" required style="margin-bottom:1rem;">
						<label style="align-self:flex-start;margin-bottom:0.3rem;">Fecha final</label>
						<input type="date" id="fecha_final" name="fecha_final" required style="margin-bottom:1.5rem;">
						<button type="submit">Generar PDF</button>
					</form>
				</div>
				<!-- Botón Regresar -->
				<div style="text-align:center; margin-top:2rem;">
					<form method="post" style="display:inline;">
						<input type="hidden" name="regresar" value="1">
						<button type="submit" style="background:#e74c3c;color:#fff;border:none;padding:0.7rem 2rem;border-radius:6px;font-size:1.1rem;cursor:pointer;">Regresar</button>
					</form>
				</div>
			</div>
		<?php endif; ?>
	</div>


</body>

</html>