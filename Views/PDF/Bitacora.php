<?php
require_once('../../Model/fpdf/fpdf.php');

require_once('../Lagout/Sesion.php');
// --- 1. CONEXIÓN SEGURA Y DATOS INICIALES ---

// Inicializar conexión al principio
// Nota: Se usó 'transformetal' en el cuerpo, asumo esa es la BD correcta.
$conexion = mysqli_connect("localhost", "root", "", "dbinfoseg");
if (!$conexion) {
    die('Error de conexión a la base de datos: ' . mysqli_connect_error());
}


$departamento_id = isset($_POST['departamento_id']) ? intval($_POST['departamento_id']) : null;
$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : null;
$fecha_final = isset($_POST['fecha_final']) ? $_POST['fecha_final'] : null;

if (!$departamento_id || !$fecha_inicio || !$fecha_final) {
    die('Faltan parámetros necesarios (ID de departamento, fecha de inicio o fecha de fin).');
}

$departamentos = [
    16 => ['nombre' => 'Infoseg', 'area' => '16'],
    // Puedes agregar más departamentos aquí
];

// --- 2. CLASE PDF MEJORADA ---

class PDF extends FPDF
{
    private $isFirstPage = true;
    public $isResumenPage = false;
    public $dept_info = ['nombre' => 'No Definido', 'area' => 'N/A'];
    public $fecha_inicio = '';
    public $fecha_final = '';

    function Header()
    {
        // El Header del detalle solo se ejecuta si NO es la página de resumen
        if ($this->isResumenPage) {
            return; // Omitir el encabezado de tabla si es la página de resumen
        }
        
        // Configuración de encabezado principal (detalles de la bitácora)
        if ($this->isFirstPage || $this->PageNo() > 1) {
            // Re-imprimir logo y título en cada página
            $this->SetY(10); 
            // Asegúrate de que la ruta de la imagen sea correcta
            // $this->Image('images/selecomnet.png', 10, 8, 65); 
            
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 5, utf8_decode('BITÁCORA DEPARTAMENTO DE ' .
                mb_strtoupper($this->dept_info['nombre']) . ' ÁREA ' . $this->dept_info['area']), 0, 1, 'C');
            $this->SetFont('Arial', '', 10);
            $this->Cell(0, 5, utf8_decode('Del ' . date('d/m/Y', strtotime($this->fecha_inicio)) .
                ' al ' . date('d/m/Y', strtotime($this->fecha_final))), 0, 1, 'C');
            $this->Ln(5);
            $this->isFirstPage = false;
        }

        // Encabezados de la tabla de detalle
        $this->SetFont('Arial', 'B', 11);
        $this->Cell(45, 8, 'ODT', 1, 0, 'C');
        $this->Cell(40, 8, 'Colaborador', 1, 0, 'C');
        $this->Cell(180, 8, utf8_decode('Descripción - Actividades'), 1, 0, 'C');
        $this->Cell(30, 8, 'Fecha', 1, 0, 'C');
        $this->Cell(20, 8, 'Horas', 1, 1, 'C');
    }

    function Footer()
    {
        // El pie de página permanece vacío para no interferir con PrintResumenFinal
    }

    // Función para imprimir el resumen al final con firmas dinámicas
    function PrintResumenFinal()
    {
        $firmas_config = [
            16 => ['elabora' => 'Persona 16', 'autoriza' => 'Persona 16'],
            'default' => ['elabora' => 'Elaborado por', 'autoriza' => 'Autorizado por']
        ];

        global $departamento_id;
        $firma = isset($firmas_config[$departamento_id]) ? $firmas_config[$departamento_id] : $firmas_config['default'];

        // Asegurarse de tener suficiente espacio y establecer Y al final de la página
        if ($this->GetY() + 35 > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
        $this->SetY($this->GetPageHeight() - 35);

        $this->SetFont('Arial', '', 9);
        $cellWidth = 80;
        $spacing = 40;
        $totalWidth = $cellWidth * 2 + $spacing;
        $startX = ($this->GetPageWidth() - $totalWidth) / 2;

        $this->SetX($startX);
        $this->Cell($cellWidth, 6, '___________________________________', 0, 0, 'C');
        $this->Cell($spacing, 6, '', 0, 0);
        $this->Cell($cellWidth, 6, '___________________________________', 0, 1, 'C');

        $this->SetX($startX);
        $this->Cell($cellWidth, 6, 'ELABORADO POR: ' . $firma['elabora'], 0, 0, 'C');
        $this->Cell($spacing, 6, '', 0, 0);
        $this->Cell($cellWidth, 6, 'AUTORIZADO POR : ' . $firma['autoriza'], 0, 1, 'C');
    }
    
    // Función para imprimir filas con celdas multilínea (mejorada)
    function PrintRow($data)
    {
        // Definir anchos de columna
         $w = [45, 40, 180, 30, 20];
        $lineHeight = 4; // Altura de línea para texto pequeño

        // Calcular el número de líneas necesarias para la descripción
        $nb = $this->NbLines($w[2], utf8_decode($data['actividades']));
        $h = max($lineHeight * $nb, 6); // Altura mínima de 6mm

        // Salto de página si es necesario
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }

        $x = $this->GetX();
        $y = $this->GetY();

        $this->SetFont('Arial', '', 8);

        // ODT y Colaborador (celdas de borde fijo)
        $this->Cell($w[0], $h, utf8_decode($data['odt']), 1, 0, 'C');
        $this->Cell($w[1], $h, utf8_decode($data['colaborador']), 1, 0, 'L');

        // Dibujar borde de descripción y escribir texto con MultiCell
        $this->Rect($x + $w[0] + $w[1], $y, $w[2], $h); // Dibujar el rectángulo grande
        $this->SetXY($x + $w[0] + $w[1], $y);
        $this->MultiCell($w[2], $lineHeight, utf8_decode($data['actividades']), 0, 'L');
        
        // Volver a la posición para las últimas columnas
        $this->SetXY($x + $w[0] + $w[1] + $w[2], $y);

        // Fecha y Horas (celdas de borde fijo)
        $this->Cell($w[3], $h, date('d/m/Y', strtotime($data['fecha'])), 1, 0, 'C');
        $this->Cell($w[4], $h, $data['horas'], 1, 1, 'C'); // Salto de línea

        // Colocar el cursor al inicio de la siguiente fila
        $this->SetY($y + $h);
    }
    
    // Calcula el número de líneas que ocupará un texto en una celda de ancho $w (se mantiene tu lógica)
    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 and $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
    }
}
// --- 3. CONSULTAS Y GENERACIÓN DEL DETALLE ---
// Configurar el PDF
$pdf = new PDF('L', 'mm', array(356, 216));

// Configurar datos para el encabezado
$pdf->dept_info = isset($departamentos[$departamento_id]) ? $departamentos[$departamento_id] : ['nombre' => 'No Definido', 'area' => 'N/A'];
$pdf->fecha_inicio = $fecha_inicio;
$pdf->fecha_final = $fecha_final;

$pdf->AliasNbPages();
$pdf->AddPage();
// Imprimir el logo en la primera página
// $pdf->Image('images/selecomnet.png', 10, 8, 65);
$pdf->SetFont('Arial', '', 8);

// Consulta para el detalle (usando Prepared Statement)
$consulta_detalle = "SELECT
    c.Nombre AS colaborador,
    DATE(p.fecha) AS fecha,
    SUM(p.Horas) AS horas,
    GROUP_CONCAT(p.ODT SEPARATOR ', ') AS odt,
    GROUP_CONCAT(p.Descripcion SEPARATOR '; ') AS actividades
FROM
    bitacora p
INNER JOIN
    colaborador c ON p.IdColaborador = c.IdColaborador
WHERE
    p.fecha BETWEEN ? AND ?
    AND c.Area = ?
GROUP BY
    c.IdColaborador, c.Nombre, DATE(p.fecha)
ORDER BY
    c.Nombre ASC, DATE(p.fecha) ASC
";

// 1. Preparar la consulta
$stmt_detalle = mysqli_prepare($conexion, $consulta_detalle);

if (!$stmt_detalle) {
    die('Error al preparar la consulta de detalle: ' . mysqli_error($conexion));
}

// 2. Vincular los parámetros ('ssi' = string, string, integer)
if (!mysqli_stmt_bind_param($stmt_detalle, 'ssi', $fecha_inicio, $fecha_final, $departamento_id)) {
    die('Error al vincular parámetros: ' . mysqli_stmt_error($stmt_detalle));
}

// 3. Ejecutar la consulta
if (!mysqli_stmt_execute($stmt_detalle)) {
    die('Error al ejecutar la consulta: ' . mysqli_stmt_error($stmt_detalle));
}

// 4. Obtener el resultado
$resultado_detalle = mysqli_stmt_get_result($stmt_detalle);

// 's' para string, 'i' para integer
mysqli_stmt_bind_param($stmt_detalle, 'ssi', $fecha_inicio, $fecha_final, $departamento_id);
mysqli_stmt_execute($stmt_detalle);
$resultado_detalle = mysqli_stmt_get_result($stmt_detalle);

// Contenido de la tabla de detalle
while ($row = mysqli_fetch_assoc($resultado_detalle)) {
    $pdf->PrintRow([
        'odt' => $row['odt'],
        'colaborador' => $row['colaborador'],
        'actividades' => $row['actividades'],
        'fecha' => $row['fecha'],
        'horas' => $row['horas']
    ]);
}
mysqli_stmt_close($stmt_detalle); // Cerrar statement de detalle


// --- 4. CONSULTAS Y GENERACIÓN DEL RESUMEN ---

$pdf->isResumenPage = true;
$pdf->AddPage();

// Impresión de encabezado específico para el resumen
// $pdf->Image('images/selecomnet.png', 10, 8, 65);
$pdf->SetY(10);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, utf8_decode('BITÁCORA DEPARTAMENTO DE ' . mb_strtoupper($pdf->dept_info['nombre']) . ' ÁREA ' . $pdf->dept_info['area']), 0, 1, 'C');
$pdf->Ln(5);
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(0, 5, utf8_decode('RESUMEN DE HORAS DEL ' . date('d/m/Y', strtotime($fecha_inicio)) . ' al ' . date('d/m/Y', strtotime($fecha_final))), 0, 1, 'C');
$pdf->Ln(10);

// Consulta para obtener el resumen 
$consulta_resumen = "SELECT 
                        c.Nombre AS nombre_colaborador,
                        p.IdColaborador, 
                        DATE(p.fecha) AS fecha,
                        SUM(p.Horas) AS horas
                    FROM 
                        bitacora p
                    INNER JOIN 
                        colaborador c ON p.IdColaborador = c.IdColaborador
                    WHERE 
                        p.fecha BETWEEN ? AND ?
                        AND c.Area = ?
                    GROUP BY 
                        p.IdColaborador, c.Nombre, DATE(p.fecha) -- 2. Y agregado aquí
                    ORDER BY 
                        c.Nombre ASC, fecha ASC"; 

$stmt_resumen = mysqli_prepare($conexion, $consulta_resumen);
if (!$stmt_resumen) {
    // Cerrar conexión antes de morir
    mysqli_close($conexion);
    die('Error al preparar la consulta de resumen: ' . mysqli_error($conexion));
}
mysqli_stmt_bind_param($stmt_resumen, 'ssi', $fecha_inicio, $fecha_final, $departamento_id);
mysqli_stmt_execute($stmt_resumen);
$resultado_resumen = mysqli_stmt_get_result($stmt_resumen);

// Arrays para almacenar datos
$datos_resumen = [];
$fechas_unicas = [];
$usuarios_unicos = [];
$totales_por_fecha = [];
$totales_por_usuario = [];
$gran_total = 0;

// Procesar resultados
while ($row = mysqli_fetch_assoc($resultado_resumen)) {
    $fecha = date('d/m/Y', strtotime($row['fecha']));
    if (!in_array($fecha, $fechas_unicas)) {
        $fechas_unicas[] = $fecha;
        $totales_por_fecha[$fecha] = 0;
    }
    if (!in_array($row['nombre_colaborador'], $usuarios_unicos)) {
        $usuarios_unicos[] = $row['nombre_colaborador'];
        $totales_por_usuario[$row['nombre_colaborador']] = 0;
    }
    $datos_resumen[$row['nombre_colaborador']][$fecha] = $row['horas'];
    $totales_por_fecha[$fecha] += $row['horas'];
    $totales_por_usuario[$row['nombre_colaborador']] += $row['horas'];
    $gran_total += $row['horas'];
}
mysqli_stmt_close($stmt_resumen); // Cerrar statement de resumen

// Configurar anchos de columnas del resumen
$w = array(50);
foreach ($fechas_unicas as $f) $w[] = 15;
$w[] = 20;

// Encabezados del resumen
$pdf->SetFont('Arial', 'B', 7.5);
$pdf->Cell($w[0], 8, 'COLABORADOR', 1, 0, 'C');
foreach ($fechas_unicas as $i => $fecha) {
    $pdf->Cell($w[$i + 1], 8, $fecha, 1, 0, 'C');
}
$pdf->Cell($w[count($w) - 1], 8, 'TOTAL', 1, 1, 'C');

// Datos del resumen
$pdf->SetFont('Arial', '', 8);
foreach ($usuarios_unicos as $usuario) {
    $pdf->Cell($w[0], 6, utf8_decode($usuario), 1, 0, 'L');
    foreach ($fechas_unicas as $i => $fecha) {
        $valor = isset($datos_resumen[$usuario][$fecha]) ?
            number_format($datos_resumen[$usuario][$fecha], 2) : ' ';
        $pdf->Cell($w[$i + 1], 6, $valor, 1, 0, 'C');
    }
    $pdf->Cell($w[count($w) - 1], 6, number_format($totales_por_usuario[$usuario], 2), 1, 1, 'C');
}

// Fila de totales
$pdf->SetFont('Arial', 'B', 9);
$pdf->Cell($w[0], 7, 'TOTAL', 1, 0, 'R');
foreach ($fechas_unicas as $i => $fecha) {
    $pdf->Cell($w[$i + 1], 7, number_format($totales_por_fecha[$fecha], 2), 1, 0, 'C');
}
$pdf->Cell($w[count($w) - 1], 7, number_format($gran_total, 2), 1, 1, 'C');

// Imprimir firmas al final
$pdf->PrintResumenFinal();

// --- 5. CIERRE DE CONEXIÓN Y SALIDA DEL PDF ---
mysqli_close($conexion);
$pdf->Output();