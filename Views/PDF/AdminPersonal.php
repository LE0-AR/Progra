<?php
// Muestra solo errores fatales, no warnings.
error_reporting(E_ERROR | E_PARSE);

// --- INCLUDES ---
require_once('../../Model/fpdf/fpdf.php');

require_once('../../Controller/Config/Sesion.php');

// --- CLASE PDF PERSONALIZADA (Extiende FPDF) ---
class PDF extends FPDF
{
    // ... (El código de la clase PDF no cambia, se queda igual) ...
    // Función para una celda con salto de línea automático
    function Row($data, $widths, $aligns)
    {
        $nb = 0;
        for ($i = 0; $i < count($data); $i++) {
            $nb = max($nb, $this->NbLines($widths[$i], $data[$i]));
        }
        $h = 5 * $nb;
        $this->CheckPageBreak($h);

        for ($i = 0; $i < count($data); $i++) {
            $w = $widths[$i];
            $a = isset($aligns[$i]) ? $aligns[$i] : 'L';
            $x = $this->GetX();
            $y = $this->GetY();
            $this->Rect($x, $y, $w, $h);
            $this->MultiCell($w, 5, $data[$i], 0, $a);
            $this->SetXY($x + $w, $y);
        }
        $this->Ln($h);
    }

    function CheckPageBreak($h)
    {
        if ($this->GetY() + $h > $this->PageBreakTrigger) {
            $this->AddPage($this->CurOrientation);
        }
    }

    function NbLines($w, $txt)
    {
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
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
                } else {
                    $i = $sep + 1;
                }
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else {
                $i++;
            }
        }
        return $nl;
    }

    function Header()
    {
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, utf8_decode("REPORTE DE ACTIVIDADES POR COLABORADOR"), 0, 1, 'C');
        $this->Ln(5);
    }
}

// --- CONEXIÓN A LA BASE DE DATOS ---
$conexion = mysqli_connect("localhost", "root", "", "dbinfoseg");

// --- OBTENCIÓN DE DATOS DEL FORMULARIO ---
$fecha_inicio = $_POST['fecha_inicio'] ?? null;
$fecha_final = $_POST['fecha_final'] ?? null;
$colaborador = $_POST['colaborador'] ?? null;

// --- VALIDACIÓN DE DATOS ---
if (!$colaborador || !$fecha_inicio || !$fecha_final) {
    die("Faltan datos para generar el reporte (fechas o colaborador).");
}

// --- CONSULTA SQL SIMPLIFICADA ---
$consulta = "SELECT
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
                AND c.Nombre = ?
            GROUP BY
                c.IdColaborador, c.Nombre, DATE(p.fecha)
            ORDER BY
                c.Nombre ASC, DATE(p.fecha) ASC";

// --- EJECUCIÓN SEGURA DE LA CONSULTA ---
$stmt = $conexion->prepare($consulta);
if ($stmt === false) {
    die("Error al preparar la consulta: " . $conexion->error);
}

// Vincula los 3 parámetros. "sss" = string, string, string
$stmt->bind_param("sss", $fecha_inicio, $fecha_final, $colaborador);
$stmt->execute();
$resultado = $stmt->get_result();

// --- VALIDACIÓN DE RESULTADOS ---
if ($resultado->num_rows == 0) {
    die("No se encontraron actividades para '$colaborador' en el rango de fechas seleccionado.");
}

// --- CREACIÓN DEL PDF ---
$pdf = new PDF('L', 'mm', array(356, 216));
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 6, utf8_decode("Reporte de: $colaborador"), 0, 1, 'C');
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(0, 6, utf8_decode('Del ' . date('d/m/Y', strtotime($fecha_inicio)) . ' al ' . date('d/m/Y', strtotime($fecha_final))), 0, 1, 'C');
$pdf->Ln(5);

// --- ENCABEZADOS DE LA TABLA ---
$pdf->SetFont('Arial', 'B', 10);
$widths = [30, 50, 40, 35, 180];
$aligns = ['C', 'L', 'C', 'C', 'L'];
$pdf->Row(['Fecha', 'Colaborador', 'ODT', 'Horas', 'Actividades'], $widths, $aligns);

// --- CUERPO DE LA TABLA (DATOS) ---
$pdf->SetFont('Arial', '', 10);
$total_horas = 0;
while ($row = mysqli_fetch_assoc($resultado)) {
    $pdf->Row(
        [
            date('d/m/Y', strtotime($row['fecha'])),
            utf8_decode($row['colaborador']),
            utf8_decode($row['odt']),
            number_format($row['horas'], 2),
            utf8_decode($row['actividades'])
        ],
        $widths,
        $aligns
    );
    $total_horas += $row['horas'];
}

// --- FILA DE TOTALES ---
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell($widths[0] + $widths[1] + $widths[2], 8, 'Total Horas:', 1, 0, 'R');
$pdf->Cell($widths[3], 8, number_format($total_horas, 2), 1, 0, 'C');
$pdf->Cell($widths[4], 8, '', 1, 1, 'C');

// --- CIERRE DE CONEXIÓN Y SALIDA DEL PDF ---
$stmt->close();
$conexion->close();

$pdf->Output();
?>