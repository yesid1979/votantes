<?php
// Reporte de Resultados Consolidados - Safe Version for PHP 5.3.8
// Desactivar reporte de errores para evitar que mensajes "Deprecated" corrompan el archivo binario
error_reporting(0);
ini_set('display_errors', 0);

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
require_once('../libs/PHPExcel/PHPExcel.php');
require_once('../libs/PHPExcel/PHPExcel/IOFactory.php');

$aspirante = isset($_GET['aspirante']) ? $_GET['aspirante'] : 'ALCALDIA';
$dpto      = isset($_GET['dpto'])      ? $_GET['dpto']      : '';
$muni      = isset($_GET['muni'])      ? $_GET['muni']      : '';

$conexionObj = new Conexion();
$db = $conexionObj->get_conexion();

// Consulta con GROUP BY compatible
$sql = "SELECT r.id_candidato, SUM(r.votos) AS total_votos,
               c.nom_candidato, c.nom_partido
        FROM registro_votos r
        LEFT JOIN candidatos c ON c.id_candidato = r.id_candidato
        WHERE r.aspirante = :aspirante";

$params = array(':aspirante' => $aspirante);
if (!empty($dpto)) { 
    $sql .= " AND r.dpto = :dpto"; 
    $params[':dpto'] = $dpto; 
}
if (!empty($muni)) { 
    $sql .= " AND r.muni = :muni"; 
    $params[':muni'] = $muni; 
}

$sql .= " GROUP BY r.id_candidato, c.nom_candidato, c.nom_partido ORDER BY total_votos DESC";

$stmt = $db->prepare($sql);
foreach ($params as $k => $v) {
    $stmt->bindValue($k, $v);
}
$stmt->execute();
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

$totalGeneral = 0;
$totalValidos = 0;
foreach ($datos as $d) {
    $v = intval($d['total_votos']);
    $totalGeneral += $v;
    if ($d['id_candidato'] != 'NULOS' && $d['id_candidato'] != 'NO_MARCADOS') {
        $totalValidos += $v;
    }
}

$titulo = "Resultados Consolidados - " . $aspirante;

$excel = new PHPExcel();
$excel->getProperties()->setCreator("Sistema Votantes")->setTitle($titulo);
$sheet = $excel->setActiveSheetIndex(0);
$sheet->setTitle("Consolidado");

// Estilos simples para maxima compatibilidad
$sheet->setCellValue('A1', $titulo);
$sheet->mergeCells('A1:F1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

$sheet->setCellValue('A2', 'Generado el: ' . date('d/m/Y H:i:s'));
$sheet->mergeCells('A2:F2');

$sheet->setCellValue('A3', 'Total General: ' . number_format($totalGeneral));
$sheet->setCellValue('D3', 'Total Validos: ' . number_format($totalValidos));

// Encabezados
$headers = array('#', 'Candidato', 'Partido', 'Votos', 'Porcentaje Total', 'Porcentaje Validos');
$col = 'A';
foreach ($headers as $h) {
    $sheet->setCellValue($col . '4', $h);
    $sheet->getStyle($col . '4')->getFont()->setBold(true);
    $col++;
}

$row = 5;
foreach ($datos as $idx => $d) {
    $nom = $d['nom_candidato'];
    if ($d['id_candidato'] == 'EN_BLANCO') $nom = 'Votos en Blanco';
    if ($d['id_candidato'] == 'NULOS') $nom = 'Votos Nulos';
    if ($d['id_candidato'] == 'NO_MARCADOS') $nom = 'Tarjetones No Marcados';

    $votos = intval($d['total_votos']);
    $pctTot = ($totalGeneral > 0) ? round(($votos / $totalGeneral) * 100, 2) : 0;
    $pctVal = ($totalValidos > 0) ? round(($votos / $totalValidos) * 100, 2) : 0;

    $sheet->setCellValue("A" . $row, $idx + 1);
    $sheet->setCellValue("B" . $row, $nom);
    $sheet->setCellValue("C" . $row, (!empty($d['nom_partido']) ? $d['nom_partido'] : '-'));
    $sheet->setCellValue("D" . $row, $votos);
    $sheet->setCellValue("E" . $row, $pctTot . '%');
    $sheet->setCellValue("F" . $row, $pctVal . '%');
    $row++;
}

// Limpiar el nombre del archivo de caracteres raros
$safe_aspirante = preg_replace('/[^A-Za-z0-9]/', '_', $aspirante);
$filename = 'Consolidado_' . $safe_aspirante . '_' . date('Ymd_Hi') . '.xls';

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
$writer->save('php://output');
exit;
