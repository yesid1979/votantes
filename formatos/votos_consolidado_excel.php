<?php
date_default_timezone_set('America/Bogota');
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Restricción de acceso dinámica
require_once('../funcs/permisos_helper.php');
if (!tienePermiso($_SESSION['tipo_usuario'], 'Resultados', 'ver')) {
    header("Location: ../index.php?url=dashboard/index");
    exit;
}

require_once('../funcs/class.conexion.php');
require_once('phpexcel/Classes/PHPExcel.php');
require_once('phpexcel/Classes/PHPExcel/IOFactory.php');

$aspirante = isset($_GET['aspirante']) ? $_GET['aspirante'] : 'ALCALDIA';
$dpto      = isset($_GET['dpto'])      ? $_GET['dpto']      : '';
$muni      = isset($_GET['muni'])      ? $_GET['muni']      : '';

// ── Consulta ──────────────────────────────────────────────────────────────────
$conexionObj = new Conexion();
$db = $conexionObj->get_conexion();

$sql = "SELECT r.id_candidato, SUM(r.votos) AS total_votos,
               c.nom_candidato, c.nom_partido
        FROM registro_votos r
        LEFT JOIN candidatos c ON c.id_candidato = r.id_candidato
        WHERE r.aspirante = :aspirante";
$params = [':aspirante' => $aspirante];

if (!empty($dpto)) { $sql .= " AND r.dpto = :dpto"; $params[':dpto'] = $dpto; }
if (!empty($muni)) { $sql .= " AND r.muni = :muni"; $params[':muni'] = $muni; }

$sql .= " GROUP BY r.id_candidato ORDER BY total_votos DESC";

$stmt = $db->prepare($sql);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── Títulos ───────────────────────────────────────────────────────────────────
$titulo = "Resultados Consolidados - " . strtoupper($aspirante);
if ($dpto) $titulo .= " | Dpto: $dpto";
if ($muni) $titulo .= " | Muni: $muni";

$totalGeneral = 0;
$totalValidos = 0;
foreach ($datos as $d) {
    $v = intval($d['total_votos']);
    $totalGeneral += $v;
    if (!in_array($d['id_candidato'], ['NULOS', 'NO_MARCADOS'])) $totalValidos += $v;
}

// ── PHPExcel ──────────────────────────────────────────────────────────────────
$excel = new PHPExcel();
$excel->getProperties()
      ->setCreator("Sistema de Votantes")
      ->setTitle($titulo);

$sheet = $excel->getActiveSheet();
$sheet->setTitle("Consolidado");

// Colores
$colorHeader   = '1a3c5e';
$colorSubtitle = '2e6da4';
$colorImpar    = 'EDF2F7';
$colorPar      = 'FFFFFF';
$colorTotal    = 'D4EDDA';
$colorWinner   = 'FFF3CD';

// ── Fila 1: Título principal ──────────────────────────────────────────────────
$sheet->mergeCells('A1:F1');
$sheet->setCellValue('A1', $titulo);
$sheet->getStyle('A1')->applyFromArray([
    'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
    'fill'      => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $colorHeader]],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER],
]);
$sheet->getRowDimension(1)->setRowHeight(30);

// ── Fila 2: Fecha de generación ───────────────────────────────────────────────
$sheet->mergeCells('A2:F2');
$sheet->setCellValue('A2', 'Generado el: ' . date('d/m/Y H:i:s'));
$sheet->getStyle('A2')->applyFromArray([
    'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
    'fill'      => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $colorSubtitle]],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
]);

// ── Fila 3: Totales ───────────────────────────────────────────────────────────
$sheet->mergeCells('A3:C3');
$sheet->setCellValue('A3', 'Total General: ' . number_format($totalGeneral));
$sheet->mergeCells('D3:F3');
$sheet->setCellValue('D3', 'Total Válidos: ' . number_format($totalValidos));
$sheet->getStyle('A3:F3')->applyFromArray([
    'font'      => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '155724']],
    'fill'      => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $colorTotal]],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
]);

// ── Fila 4: Encabezados ───────────────────────────────────────────────────────
$headers = ['#', 'Candidato', 'Partido', 'Votos', '% del Total', '% Válidos'];
$cols    = ['A', 'B', 'C', 'D', 'E', 'F'];
foreach ($headers as $i => $h) {
    $sheet->setCellValue($cols[$i] . '4', $h);
}
$sheet->getStyle('A4:F4')->applyFromArray([
    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill'      => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $colorHeader]],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER],
    'borders'   => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN,
                                     'color' => ['rgb' => 'FFFFFF']]],
]);
$sheet->getRowDimension(4)->setRowHeight(22);

// ── Filas de datos ────────────────────────────────────────────────────────────
$row = 5;
foreach ($datos as $idx => $d) {
    $nom = $d['nom_candidato'];
    if ($d['id_candidato'] === 'EN_BLANCO')   $nom = 'Votos en Blanco';
    if ($d['id_candidato'] === 'NULOS')        $nom = 'Votos Nulos';
    if ($d['id_candidato'] === 'NO_MARCADOS')  $nom = 'Tarjetones No Marcados';

    $votos   = intval($d['total_votos']);
    $pctTot  = $totalGeneral > 0 ? round($votos / $totalGeneral * 100, 2) : 0;
    $pctVal  = $totalValidos > 0 ? round($votos / $totalValidos * 100, 2) : 0;

    $sheet->setCellValue("A$row", $idx + 1);
    $sheet->setCellValue("B$row", $nom);
    $sheet->setCellValue("C$row", $d['nom_partido'] ?: '-');
    $sheet->setCellValue("D$row", $votos);
    $sheet->setCellValue("E$row", $pctTot . '%');
    $sheet->setCellValue("F$row", $pctVal . '%');

    $fillColor = ($idx === 0) ? $colorWinner : (($idx % 2 === 0) ? $colorImpar : $colorPar);
    $sheet->getStyle("A$row:F$row")->applyFromArray([
        'fill'    => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $fillColor]],
        'borders' => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN,
                                       'color' => ['rgb' => 'CCCCCC']]],
        'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
    ]);
    // Candidato y partido alineados a la izquierda
    $sheet->getStyle("B$row:C$row")->getAlignment()
          ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);

    $row++;
}

// ── Ancho de columnas ─────────────────────────────────────────────────────────
$sheet->getColumnDimension('A')->setWidth(6);
$sheet->getColumnDimension('B')->setWidth(35);
$sheet->getColumnDimension('C')->setWidth(30);
$sheet->getColumnDimension('D')->setWidth(14);
$sheet->getColumnDimension('E')->setWidth(14);
$sheet->getColumnDimension('F')->setWidth(14);

// ── Enviar al navegador ───────────────────────────────────────────────────────
$filename = 'Resultados_Consolidados_' . $aspirante . '_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
exit;
