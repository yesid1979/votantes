<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Depuracion Excel - Votantes</h2>";

// 1. Probar conexion
echo "Cargando conexion...<br>";
require_once('../funcs/class.conexion.php');
try {
    $c = new Conexion();
    $db = $c->get_conexion();
    echo "DB Conectada.<br>";
} catch (Exception $e) {
    die("ERROR DB: " . $e->getMessage());
}

// 2. Probar Consulta
echo "Probando consulta SQL...<br>";
$aspirante = 'SENADO';
$sql = "SELECT r.id_candidato, SUM(r.votos) AS total_votos
        FROM registro_votos r
        WHERE r.aspirante = :aspirante
        GROUP BY r.id_candidato";
$stmt = $db->prepare($sql);
$stmt->bindValue(':aspirante', $aspirante);
$stmt->execute();
$count = count($stmt->fetchAll());
echo "Consulta ejecutada. Filas: $count<br>";

// 3. Probar PHPExcel
echo "Cargando PHPExcel...<br>";
require_once('../libs/PHPExcel/PHPExcel.php');
echo "PHPExcel cargado.<br>";

try {
    $excel = new PHPExcel();
    $excel->setActiveSheetIndex(0);
    $sheet = $excel->getActiveSheet();
    $sheet->setCellValue('A1', 'Prueba Exitosa');
    
    echo "Creando Writer...<br>";
    $writer = PHPExcel_IOFactory::createWriter($excel, 'Excel5');
    
    // En lugar de enviar al navegador, intentamos salvar a un archivo temporal
    $tempFile = 'test_excel.xls';
    $writer->save($tempFile);
    echo "Archivo temporal '$tempFile' creado correctamente.<br>";
    
    if (file_exists($tempFile)) {
        echo "<b>EXITO:</b> La libreria funciona perfectamente en este servidor.<br>";
        unlink($tempFile);
    }
} catch (Exception $e) {
    echo "ERROR LIBRERIA: " . $e->getMessage() . "<br>";
}

echo "<br>Si todo lo anterior dice 'Exito', el problema era un caracter especial o un error de sesion/permisos.<br>";
echo "Prueba finalizada.";
