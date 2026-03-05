<?php
/** Se agrega la libreria PHPExcel **/
require_once("../funcs/class.conexion.php");
require_once('phpexcel/Classes/PHPExcel.php');
require_once('phpexcel/Classes/PHPExcel/IOFactory.php');
//conexion a la base
$modelo = new Conexion();
$conexion=$modelo->get_conexion();
$sql = "SELECT id_lider, ced_lider, nom_lider, dir_lider, barrio_lider, comuna_lider, email_lider, cel_lider, nom_puesto, mesa_lider";
$sql.=" FROM lideres";
$consultas=$conexion->prepare($sql);
$consultas->execute();
$totalData = $consultas->rowCount();
if($totalData > 0 )
{
   date_default_timezone_set('America/Bogota');
   if (PHP_SAPI == 'cli')
			die('Este archivo solo se puede ver desde un navegador web');
   /*while ($fila=$consultas->fetch(PDO::FETCH_ASSOC))
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
   }*/
   
$fecha_actual=fechaCastellano(date('d-m-Y H:i:s')); 
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
$objReader = PHPExcel_IOFactory::createReader('Excel2007');
$objPHPExcel = $objReader->load("lideres.xlsx");
// Indicamos que se pare en la hoja uno del libro
$objPHPExcel->setActiveSheetIndex(0);
//Escribimos en la hoja en la celda B1
$objPHPExcel->getActiveSheet()->SetCellValue('C1', $fecha_actual);
$objPHPExcel->getActiveSheet()->SetCellValue('C2', "hola");
//$objPHPExcel->getActiveSheet()->SetCellValue('C11', $unidad_adm);
//$objPHPExcel->getActiveSheet()->SetCellValue('C12', $oficina_prod);
//$objPHPExcel->getActiveSheet()->SetCellValue('C13', $objeto);
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
//$consulta1 = "SELECT * from detalle_fuid where id_fuid='$id' and estado_fuid='Activo'"; 
//$resultado1 = $conexion->query($consulta1);	
$i = 9;
$k=1;
while ($rows = $consultas->fetch(PDO::FETCH_ASSOC))
{
  $objPHPExcel->getActiveSheet()->getStyle('A'.$i.':I'.$i.'')->applyFromArray(
		array(
			
			'borders' => array(
				'allborders'     => array(
 					'style' => PHPExcel_Style_Border::BORDER_THIN
 				) 
			),
			'font'=> array('size'=>11,'name'=>'arial')
		)
);	
  $objPHPExcel->setActiveSheetIndex(0)//->insertNewRowBefore($i+1,1)
			  ->SetCellValue('A'.$i, $rows['id_lider'])
			  ->SetCellValue('B'.$i, $rows['ced_lider'])
			  ->SetCellValue('C'.$i, $rows['nom_lider'])
			  ->SetCellValue('D'.$i, $rows['cel_lider'])
			  ->SetCellValue('E'.$i, $rows['email_lider'])
			  ->SetCellValue('F'.$i, $rows['dir_lider']." ".$rows['barrio_lider'])
			  ->SetCellValue('G'.$i, $rows['comuna_lider'])
			  ->SetCellValue('H'.$i, $rows['nom_puesto'])
			  ->SetCellValue('I'.$i, $rows['mesa_lider']); 
			 // $objPHPExcel->getActiveSheet()->getStyle('M'.($i))->getAlignment()->setWrapText(true);

			  
  /*if ($k>15)
  {
	$i=$i+1;  
	$objPHPExcel->setActiveSheetIndex(0);
	
	//$objPHPExcel->setActiveSheetIndex(0)->getStyle("A".$i)->getFont()->setBold(true);
    $objPHPExcel->setActiveSheetIndex(0)->SetCellValue('A'.$i,'Elaborado por ________________________________________________');	
    $k=0;
  }
  $k=$k+1;*/
  if ($k % 50 == 0) 
  {
	    // Add a page break
	   $objPHPExcel->getActiveSheet()->setBreak('A'.$i, PHPExcel_Worksheet::BREAK_ROW );
	   $k=0; 	   
  } 
  $i++; 
  $k=$k+1;  
}	
$styleArray = array
(
  'borders' => array(
  'allborders' => array(
  'style' => PHPExcel_Style_Border::BORDER_THIN)),
  'font'=> array('size'=>11,'name'=>'arial')
);
$objPHPExcel->getActiveSheet()->getStyle('A9:I'.($i-1))->applyFromArray($styleArray);
unset($styleArray);
// Se asigna el nombre a la hoja
$objPHPExcel->getActiveSheet()->setTitle('listado lideres');
$objPHPExcel->getActiveSheet()->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 8);
//Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
/*$gdImage = imagecreatefromjpeg('Imagen1.jpg');
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
$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());*/


// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
$objPHPExcel->setActiveSheetIndex(0);
// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
header('Content-type: application/vnd.ms-excel');
//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="formato_lideres.xlsx"');
header('Cache-Control: max-age=0');
//Guardamos el archivo en formato Excel 2007
//Si queremos trabajar con Excel 2003, basta cambiar el 'Excel2007' por 'Excel5' y el nombre del archivo de salida cambiar su formato por '.xls'
$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
//$objWriter->save("php://output/Archivo_salida.xlsx");
exit();
}
else
{
  print_r('No hay resultados para mostrar');
}
function fechaCastellano ($fecha) 
{
  $fecha = substr($fecha, 0, 10);
  $numeroDia = date('d', strtotime($fecha));
  $dia = date('l', strtotime($fecha));
  $mes = date('F', strtotime($fecha));
  $anio = date('Y', strtotime($fecha));
  $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
  $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
  $nombredia = str_replace($dias_EN, $dias_ES, $dia);
  $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
  $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
  return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
}
?>
