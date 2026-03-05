<?php
/** Se agrega la libreria PHPExcel **/
require_once('../libs/PHPExcel/PHPExcel.php');
require_once('../libs/PHPExcel/PHPExcel/IOFactory.php');
//Variable que recibe el codigo
$identificador=$_GET['codigo'];
//conexion a la base
$conexion = new mysqli('localhost','root','root','fuids');
if (mysqli_connect_errno()) 
{
   printf("La conexion con el servidor de base de datos fallo: %s\n", mysqli_connect_error());
   exit();
}
$consulta = "SELECT a.id_fuid,a.dia_fuid,a.mes_fuid,a.year_fuid,a.num_tansf,a.entidad_remite,a.oficina_prod,a.objeto,b.nom_entidad,c.nom_dependencia from fuid a 
LEFT JOIN entidades b ON a.entidad_prod = b.id_entidad
LEFT JOIN dependencias c ON a.unidad_adm = c.id_dependencia
where a.id_fuid='$identificador'"; 
$conexion->set_charset('utf8');
$resultado = $conexion->query($consulta);
if($resultado->num_rows > 0 )
{
   date_default_timezone_set('America/Bogota');
   if (PHP_SAPI == 'cli')
			die('Este archivo solo se puede ver desde un navegador web');
   while ($fila = $resultado->fetch_array())
   {
      $id=$fila['id_fuid'];
	  $entidad_remite=$fila['entidad_remite'];
	  $entidad_prod=$fila['nom_entidad'];
	  $unidad_adm=$fila['nom_dependencia'];
	  $oficina_prod=$fila['oficina_prod'];
	  $objeto=$fila['objeto'];
	  $dia_fuid=$fila['dia_fuid'];
	  $mes_fuid=$fila['mes_fuid'];
	  $year_fuid=$fila['year_fuid'];
	  $num_tansf=$fila['num_tansf'];
   }
$objPHPExcel = new PHPExcel();
// Se asignan las propiedades del libro
$objPHPExcel->getProperties()->setCreator("Juridica") //Autor
							 ->setLastModifiedBy("Juridica") //Ultimo usuario que lo modificó
							 ->setTitle("Formato Unico de Inventario Documental Activos")
							 ->setSubject("Formato Unico De Inventario Documental Activos")
							 ->setDescription("Formato Unico De Inventario Documental Activos")
							 ->setKeywords("Formato")
							 ->setCategory("Reporte excel");

// Leemos un archivo Excel 2007
//$objReader = PHPExcel_IOFactory::createReader('Excel5');
//$objPHPExcel = $objReader->load("../formatos/formato.xls");
// Indicamos que se pare en la hoja uno del libro
$objPHPExcel->setActiveSheetIndex(0);
//Escribimos en la hoja en la celda B1
$objPHPExcel->getActiveSheet()->SetCellValue('C9', $entidad_remite);
$objPHPExcel->getActiveSheet()->SetCellValue('C10', $entidad_prod);
$objPHPExcel->getActiveSheet()->SetCellValue('C11', $unidad_adm);
$objPHPExcel->getActiveSheet()->SetCellValue('C12', $oficina_prod);
$objPHPExcel->getActiveSheet()->SetCellValue('C13', $objeto);
/*$objPHPExcel->getActiveSheet()->SetCellValue('C8', $dia_fuid);
$objPHPExcel->getActiveSheet()->SetCellValue('C8', $mes_fuid);
$objPHPExcel->getActiveSheet()->SetCellValue('C8', $year_fuid);
$objPHPExcel->getActiveSheet()->SetCellValue('C8', $num_tansf);*/
/*Auto ajuste de las columnas*/
/*for($i = 'A'; $i <= 'B
		'; $i++){
			$objPHPExcel->setActiveSheetIndex(0)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}*/
//Se agregan los datos de los elementos	
$consulta1 = "SELECT * from detalle_fuid where id_fuid='$id' and estado_fuid='Activo'"; 
$resultado1 = $conexion->query($consulta1);	
$i = 17;
$k=1;
while ($rows = $resultado1->fetch_array())
{
  $objPHPExcel->setActiveSheetIndex(0)
              ->insertNewRowBefore($i+1,1)
			  ->SetCellValue('A'.$i, $rows['num_orden'])
			  ->SetCellValue('B'.$i, $rows['cod_fuid'])
			  ->SetCellValue('C'.$i, $rows['nom_fuid'])
			  ->SetCellValue('D'.$i, $rows['fecha_inicial'])
			  ->SetCellValue('E'.$i, $rows['fecha_final'])
			  ->SetCellValue('F'.$i, $rows['caja_fuid'])
			  ->SetCellValue('G'.$i, $rows['carpeta_fuid'])
			  ->SetCellValue('H'.$i, $rows['tomo_fuid'])
			  ->SetCellValue('I'.$i, $rows['otro_fuid']) 
			  ->SetCellValue('J'.$i, $rows['num_folios']) 
			  ->SetCellValue('K'.$i, $rows['soporte_fuid']) 
			  ->SetCellValue('L'.$i, $rows['frecuencia_fuid']) 
			  ->SetCellValue('M'.$i, $rows['notas_fuid']); 
  if ($k>15)
  {
	$i=$i+1;  
	$objPHPExcel->getActiveSheet()->setBreak('A'.$i, PHPExcel_Worksheet::BREAK_ROW );	
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i,'');
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i,'');
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i,'');
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$i,'Cargo  ______________________________________________________');  		  
    $k=0;
  }
  $k=$k+1; 	
  $i++; 
}	
$num_rows=$i+4;
$contador1=$num_rows+1;
$contador2=$num_rows+2;
$contador3=$num_rows+3;
$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$num_rows)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("D".$num_rows)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->getStyle("K".$num_rows)->getFont()->setBold(true);
$objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$num_rows,'Elaborado por ________________________________________________');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.$contador1,'Cargo  ______________________________________________________');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.$contador2,'Firma _______________________________________________________');
$objPHPExcel->getActiveSheet()->SetCellValue('A'.$contador3,'Lugar _______________________________ Fecha __________________');
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$num_rows,'Revisado por ________________________________________________');
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$contador1,'Cargo  ______________________________________________________');
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$contador2,'Firma _______________________________________________________');
$objPHPExcel->getActiveSheet()->SetCellValue('D'.$contador3,'Lugar _______________________________ Fecha __________________');
$objPHPExcel->getActiveSheet()->SetCellValue('K'.$num_rows,'Recibido por: ________________________________________________');
$objPHPExcel->getActiveSheet()->SetCellValue('K'.$contador1,'Cargo  ______________________________________________________');
$objPHPExcel->getActiveSheet()->SetCellValue('K'.$contador2,'Firma _______________________________________________________');
$objPHPExcel->getActiveSheet()->SetCellValue('K'.$contador3,'Lugar _______________________________ Fecha __________________');
// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('Fuid activos');
$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 16);
$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
$objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LETTER);
$objPHPExcel->getActiveSheet()->getPageSetup()->setScale(46);
//$objPHPExcel->getActiveSheet()->getDefaultColumnDimension('A17:M'.($i-1))->setWidth(20);
/*foreach(range('C','C'.($i-1)) as $columnID)
{
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
}*/
//$objPHPExcel->getActiveSheet()->getStyle('A17:M'.($i-1))->getAlignment()->setWrapText(true); 
$styleArray = array(
  'borders' => array(
    'allborders' => array(
      'style' => PHPExcel_Style_Border::BORDER_THIN
    )
  )
);
$objPHPExcel->getActiveSheet()->getStyle('A17:M'.($i-1))->applyFromArray($styleArray);
unset($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('M'.($i-1))->getAlignment()->setWrapText(true);
//Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
$gdImage = imagecreatefromjpeg('Imagen1.jpg');
$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
$objDrawing->setName('Fuid activos');
$objDrawing->setDescription('Fuid activos');
$objDrawing->setImageResource($gdImage);
$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
$objDrawing->setHeight(132);
$objDrawing->setCoordinates('A1');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

$gdImage = imagecreatefromjpeg('Imagen2.jpg');
$objDrawing = new PHPExcel_Worksheet_MemoryDrawing();
$objDrawing->setName('Fuid activos');
$objDrawing->setDescription('Fuid activos');
$objDrawing->setImageResource($gdImage);
$objDrawing->setRenderingFunction(PHPExcel_Worksheet_MemoryDrawing::RENDERING_JPEG);
$objDrawing->setMimeType(PHPExcel_Worksheet_MemoryDrawing::MIMETYPE_DEFAULT);
$objDrawing->setHeight(120);
$objDrawing->setCoordinates('L9');
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());


// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);
// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
header('Content-type: application/vnd.ms-excel');
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="fuid_activos.xls"');
header('Cache-Control: max-age=0');
//Guardamos el archivo en formato Excel 2007
//Si queremos trabajar con Excel 2003, basta cambiar el 'Excel2007' por 'Excel5' y el nombre del archivo de salida cambiar su formato por '.xls'
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
//$objWriter->save("php://output/Archivo_salida.xlsx");
exit();
}
else
{
  print_r('No hay resultados para mostrar');
}
?>
