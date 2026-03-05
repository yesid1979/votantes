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

$id_candidato = isset($_GET['id_candidato']) ? $_GET['id_candidato'] : '';
$aspirante    = isset($_GET['aspirante'])    ? $_GET['aspirante']    : 'ALCALDIA';
$dpto         = isset($_GET['dpto'])         ? $_GET['dpto']         : '';
$muni         = isset($_GET['muni'])         ? $_GET['muni']         : '';

if (empty($id_candidato)) {
    die('Parámetros incompletos.');
}

// ── Nombre del candidato ──────────────────────────────────────────────────────
$conexionObj = new Conexion();
$db = $conexionObj->get_conexion();

$nomCandidato = $id_candidato;
if ($id_candidato === 'EN_BLANCO')   { $nomCandidato = 'Votos en Blanco'; }
elseif ($id_candidato === 'NULOS')   { $nomCandidato = 'Votos Nulos'; }
elseif ($id_candidato === 'NO_MARCADOS') { $nomCandidato = 'Tarjetones No Marcados'; }
else {
    $sc = $db->prepare("SELECT nom_candidato, nom_partido FROM candidatos WHERE id_candidato = :id LIMIT 1");
    $sc->bindValue(':id', $id_candidato);
    $sc->execute();
    $cRow = $sc->fetch(PDO::FETCH_ASSOC);
    if ($cRow) $nomCandidato = $cRow['nom_candidato'] . ' (' . $cRow['nom_partido'] . ')';
}

// ── Consulta detallada ────────────────────────────────────────────────────────
$sql = "SELECT r.zona, r.puesto, r.mesa, r.votos, r.dpto, r.muni,
               z.nom_puesto
        FROM registro_votos r
        LEFT JOIN zonas z ON z.num_zona  = r.zona
                         AND z.pues_zona = r.puesto
                         AND z.dpto_zona = r.dpto
                         AND z.mun_zona  = r.muni
        WHERE r.id_candidato = :id_candidato AND r.aspirante = :aspirante";

$params = array(':id_candidato' => $id_candidato, ':aspirante' => $aspirante);
if (!empty($dpto)) { $sql .= " AND r.dpto = :dpto"; $params[':dpto'] = $dpto; }
if (!empty($muni)) { $sql .= " AND r.muni = :muni"; $params[':muni'] = $muni; }
$sql .= " ORDER BY r.dpto ASC, r.muni ASC, r.zona ASC, r.puesto ASC, r.mesa ASC";

$stmt = $db->prepare($sql);
foreach ($params as $k => $v) $stmt->bindValue($k, $v);
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ── PHPExcel ──────────────────────────────────────────────────────────────────
$titulo = "Detalle de Votos - " . $nomCandidato;

$excel = new PHPExcel();
$excel->getProperties()
      ->setCreator("Sistema de Votantes")
      ->setTitle($titulo);

$sheet = $excel->getActiveSheet();
$sheet->setTitle("Detalle por Mesa");

$colorHeader   = '1a3c5e';
$colorSubtitle = '2e6da4';
$colorImpar    = 'EDF2F7';
$colorPar      = 'FFFFFF';
$colorGroup    = 'D6EAF8';

// ── Fila 1: Título ────────────────────────────────────────────────────────────
$sheet->mergeCells('A1:G1');
$sheet->setCellValue('A1', $titulo);
$sheet->getStyle('A1')->applyFromArray([
    'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
    'fill'      => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $colorHeader]],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER],
]);
$sheet->getRowDimension(1)->setRowHeight(30);

// ── Fila 2: Info filtros ──────────────────────────────────────────────────────
$infoFiltros = "Aspirante: $aspirante";
if ($dpto) $infoFiltros .= " | Departamento: $dpto";
if ($muni) $infoFiltros .= " | Municipio: $muni";
$infoFiltros .= "  |  Generado: " . date('d/m/Y H:i:s');

$sheet->mergeCells('A2:G2');
$sheet->setCellValue('A2', $infoFiltros);
$sheet->getStyle('A2')->applyFromArray([
    'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => 'FFFFFF']],
    'fill'      => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $colorSubtitle]],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
]);

// ── Fila 3: Encabezados ───────────────────────────────────────────────────────
$headers = ['Departamento', 'Municipio', 'Zona', 'Puesto', 'Nombre Puesto', 'Mesa', 'Votos'];
$cols    = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
foreach ($headers as $i => $h) {
    $sheet->setCellValue($cols[$i] . '3', $h);
}
$sheet->getStyle('A3:G3')->applyFromArray([
    'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
    'fill'      => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $colorHeader]],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                    'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER],
    'borders'   => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN,
                                     'color' => ['rgb' => 'FFFFFF']]],
]);
$sheet->getRowDimension(3)->setRowHeight(22);

// ── Datos + subtotales por grupo ──────────────────────────────────────────────
$row         = 4;
$totalGeneral = 0;
$prevGroup   = null;
$groupVotos  = 0;
$groupStart  = 4;

// Para insertar subtotales necesitamos recorrer por grupos
$grupos = array();
foreach ($datos as $d) {
    $gKey = $d['dpto'] . '|' . $d['muni'] . '|' . $d['zona'] . '|' . $d['puesto'];
    if (!isset($grupos[$gKey])) {
        $grupos[$gKey] = array(
            'dpto'      => $d['dpto'),
            'muni'      => $d['muni'],
            'zona'      => $d['zona'],
            'puesto'    => $d['puesto'],
            'nom_puesto'=> $d['nom_puesto'],
            'mesas'     => [],
            'subtotal'  => 0,
        ];
    }
    $grupos[$gKey]['mesas'][] = array('mesa' => $d['mesa'), 'votos' => intval($d['votos'])];
    $grupos[$gKey]['subtotal'] += intval($d['votos']);
    $totalGeneral += intval($d['votos']);
}

$mesaIdx = 0;
foreach ($grupos as $g) {
    foreach ($g['mesas'] as $m) {
        $fillColor = ($mesaIdx % 2 === 0) ? $colorImpar : $colorPar;
        $sheet->setCellValue("A$row", $g['dpto']);
        $sheet->setCellValue("B$row", $g['muni']);
        $sheet->setCellValue("C$row", $g['zona']);
        $sheet->setCellValue("D$row", $g['puesto']);
        $sheet->setCellValue("E$row", $g['nom_puesto']);
        $sheet->setCellValue("F$row", str_pad($m['mesa'], 2, '0', STR_PAD_LEFT));
        $sheet->setCellValue("G$row", $m['votos']);
        $sheet->getStyle("A$row:G$row")->applyFromArray([
            'fill'      => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $fillColor]],
            'borders'   => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN,
                                             'color' => ['rgb' => 'CCCCCC']]],
            'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
        ]);
        $sheet->getStyle("E$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $row++;
        $mesaIdx++;
    }
    // Subtotal del grupo
    $sheet->mergeCells("A$row:F$row");
    $sheet->setCellValue("A$row", 'Subtotal  Zona ' . $g['zona'] . ' / Puesto ' . $g['puesto'] . ' — ' . $g['nom_puesto']);
    $sheet->setCellValue("G$row", $g['subtotal']);
    $sheet->getStyle("A$row:G$row")->applyFromArray([
        'font'      => ['bold' => true],
        'fill'      => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $colorGroup]],
        'borders'   => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_MEDIUM,
                                         'color' => ['rgb' => '2e6da4']]],
        'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
    ]);
    $sheet->getStyle("A$row")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
    $row++;
}

// ── Fila Total General ────────────────────────────────────────────────────────
$sheet->mergeCells("A$row:F$row");
$sheet->setCellValue("A$row", 'TOTAL GENERAL');
$sheet->setCellValue("G$row", $totalGeneral);
$sheet->getStyle("A$row:G$row")->applyFromArray([
    'font'      => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
    'fill'      => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => $colorHeader]],
    'borders'   => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_MEDIUM]],
    'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
]);

// ── Ancho de columnas ─────────────────────────────────────────────────────────
$sheet->getColumnDimension('A')->setWidth(18);
$sheet->getColumnDimension('B')->setWidth(18);
$sheet->getColumnDimension('C')->setWidth(10);
$sheet->getColumnDimension('D')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(35);
$sheet->getColumnDimension('F')->setWidth(10);
$sheet->getColumnDimension('G')->setWidth(12);

// ── Inmovilizar encabezados ───────────────────────────────────────────────────
$sheet->freezePaneByColumnAndRow(0, 4);

// ── Enviar al navegador ───────────────────────────────────────────────────────
$safeId   = preg_replace('/[^A-Za-z0-9_]/', '_', $id_candidato);
$filename = 'Detalle_Votos_' . $safeId . '_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
exit;
