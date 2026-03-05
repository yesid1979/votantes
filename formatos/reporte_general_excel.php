<?php
/** Exportador Genérico para Votantes y Simpatizantes **/
require_once("../funcs/class.conexion.php");
require_once('../libs/PHPExcel/PHPExcel.php');
require_once('../libs/PHPExcel/PHPExcel/IOFactory.php');

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'votantes';

$modelo = new Conexion();
$db = $modelo->get_conexion();

if($tipo == 'votantes') {
    $sql = "SELECT a.id_votante as id, a.ced_votante as ced, a.nom_votante as nom, a.tel_votante as tel, a.cel_votante as cel, a.email_votante as email, a.dir_votante as dir, a.barrio_votante as barrio, a.comuna_votante as comuna, b.nom_lider as lider_nom 
            FROM votantes a LEFT JOIN lideres b ON a.ced_lider = b.ced_lider";
    $titulo = "Reporte de Votantes";
} else {
    $sql = "SELECT id_simpatizante as id, ced_simpatizante as ced, nom_simpatizante as nom, tel_simpatizante as tel, cel_simpatizante as cel, email_simpatizante as email, dir_simpatizante as dir, barrio_simpatizante as barrio, comuna_simpatizante as comuna, '' as lider_nom 
            FROM simpatizantes";
    $titulo = "Reporte de Simpatizantes";
}

$stmt = $db->prepare($sql);
$stmt->execute();

$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Sistema Votantes")
                             ->setTitle($titulo);

$objPHPExcel->setActiveSheetIndex(0);
$sheet = $objPHPExcel->getActiveSheet();

// Encabezados
$sheet->SetCellValue('A1', $titulo);
$sheet->mergeCells('A1:I1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

$headers = array('ID', 'CÉDULA', 'NOMBRE', 'TELÉFONO', 'CELULAR', 'EMAIL', 'DIRECCIÓN', 'BARRIO / COMUNA', 'LÍDER');
$col = 'A';
foreach($headers as $h) {
    $sheet->SetCellValue($col.'3', $h);
    $sheet->getStyle($col.'3')->getFont()->setBold(true);
    $sheet->getColumnDimension($col)->setAutoSize(true);
    $col++;
}

$i = 4;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $sheet->SetCellValue('A'.$i, $row['id']);
    $sheet->SetCellValue('B'.$i, $row['ced']);
    $sheet->SetCellValue('C'.$i, $row['nom']);
    $sheet->SetCellValue('D'.$i, $row['tel']);
    $sheet->SetCellValue('E'.$i, $row['cel']);
    $sheet->SetCellValue('F'.$i, $row['email']);
    $sheet->SetCellValue('G'.$i, $row['dir']);
    $sheet->SetCellValue('H'.$i, $row['barrio'] . " - " . $row['comuna']);
    $sheet->SetCellValue('I'.$i, $row['lider_nom']);
    $i++;
}

// Borde a la tabla
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$sheet->getStyle('A3:I'.($i-1))->applyFromArray($styleArray);

header('Content-type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="reporte_'.$tipo.'.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit();
?>
