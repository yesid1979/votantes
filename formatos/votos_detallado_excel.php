<?php
// Reporte de Detalles de Votos - Safe Version for PHP 5.3.8
date_default_timezone_set('America/Bogota');
ini_set('memory_limit', '256M');

session_start();
if (!isset($_SESSION['id_usuario'])) {
    header("Location: ../index.php");
    exit;
}

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
    die('Parametros incompletos.');
}

$conexionObj = new Conexion();
$db = $conexionObj->get_conexion();

$nomCandidato = $id_candidato;
if ($id_candidato == 'EN_BLANCO') { 
    $nomCandidato = 'Votos en Blanco'; 
} elseif ($id_candidato == 'NULOS') { 
    $nomCandidato = 'Votos Nulos'; 
} elseif ($id_candidato == 'NO_MARCADOS') { 
    $nomCandidato = 'Tarjetones No Marcados'; 
} else {
    $sc = $db->prepare("SELECT nom_candidato, nom_partido FROM candidatos WHERE id_candidato = :id LIMIT 1");
    $sc->bindValue(':id', $id_candidato);
    $sc->execute();
    $cRow = $sc->fetch(PDO::FETCH_ASSOC);
    if ($cRow) {
        $nomCandidato = $cRow['nom_candidato'] . ' (' . $cRow['nom_partido'] . ')';
    }
}

$sql = "SELECT r.zona, r.puesto, r.mesa, r.votos, r.dpto, r.muni, z.nom_puesto
        FROM registro_votos r
        LEFT JOIN zonas z ON z.num_zona  = r.zona
                         AND z.pues_zona = r.puesto
                         AND z.dpto_zona = r.dpto
                         AND z.mun_zona  = r.muni
        WHERE r.id_candidato = :id_candidato AND r.aspirante = :aspirante";

$params = array(':id_candidato' => $id_candidato, ':aspirante' => $aspirante);
if (!empty($dpto)) { 
    $sql .= " AND r.dpto = :dpto"; 
    $params[':dpto'] = $dpto; 
}
if (!empty($muni)) { 
    $sql .= " AND r.muni = :muni"; 
    $params[':muni'] = $muni; 
}
$sql .= " ORDER BY r.dpto ASC, r.muni ASC, r.zona ASC, r.puesto ASC, r.mesa ASC";

$stmt = $db->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$titulo = "Detalle de Votos - " . $nomCandidato;

$excel = new PHPExcel();
$excel->getProperties()->setCreator("Sistema Votantes")->setTitle($titulo);
$sheet = $excel->setActiveSheetIndex(0);
$sheet->setTitle("Detalle por Mesa");

$sheet->setCellValue('A1', $titulo);
$sheet->mergeCells('A1:G1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

$sheet->setCellValue('A2', "Aspirante: $aspirante | Generado: " . date('d/m/Y H:i'));
$sheet->mergeCells('A2:G2');

$headers = array('Departamento', 'Municipio', 'Zona', 'Puesto', 'Nombre Puesto', 'Mesa', 'Votos');
$col = 'A';
foreach ($headers as $h) {
    $sheet->setCellValue($col . '3', $h);
    $sheet->getStyle($col . '3')->getFont()->setBold(true);
    $col++;
}

$row = 4;
$totalGeneral = 0;
foreach ($datos as $d) {
    $votos = intval($d['votos']);
    $totalGeneral += $votos;

    $sheet->setCellValue("A" . $row, $d['dpto']);
    $sheet->setCellValue("B" . $row, $d['muni']);
    $sheet->setCellValue("C" . $row, $d['zona']);
    $sheet->setCellValue("D" . $row, $d['puesto']);
    $sheet->setCellValue("E" . $row, $d['nom_puesto']);
    $sheet->setCellValue("F" . $row, $d['mesa']);
    $sheet->setCellValue("G" . $row, $votos);
    $row++;
}

$sheet->setCellValue("A" . $row, "TOTAL GENERAL");
$sheet->mergeCells("A$row:F$row");
$sheet->setCellValue("G" . $row, $totalGeneral);
$sheet->getStyle("A$row:G$row")->getFont()->setBold(true);

$safe_candidato = preg_replace('/[^A-Za-z0-9]/', '_', $id_candidato);
$filename = 'Detalle_' . $safe_candidato . '_' . date('Ymd_Hi') . '.xls';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
$writer->save('php://output');
exit;
