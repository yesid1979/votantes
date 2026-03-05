<?php
$conexion = new mysqli('localhost','root','root','asesorias',3306);
if (mysqli_connect_errno()) 
{
    	printf("La conexión con el servidor de base de datos falló: %s\n", mysqli_connect_error());
    	exit();
}
$consulta = "SELECT reparto.id_reparto,reparto.radicado,reparto.fecha_entrada_dep,reparto.fecha_entrada_grup,reparto.asunto,equipos.nom_equipo,usuarios.nom_usuario,reparto.fecha_sal_esp_dep,reparto.fecha_sal_esp_eq from reparto 
INNER JOIN usuarios ON reparto.cod_responsable = usuarios.id_usuario
INNER JOIN equipos ON reparto.cod_equipo = equipos.id_equipo 
WHERE (reparto.estado ='Pendiente' OR reparto.estado ='Pendiente') AND (reparto.subestado ='Nuevo' OR reparto.subestado ='Viejo')
ORDER BY equipos.nom_equipo,usuarios.nom_usuario";
$resultado = $conexion->query($consulta);
if($resultado->num_rows > 0 )
{
 date_default_timezone_set('America/Mexico_City');
   if (PHP_SAPI == 'cli')
			die('Este archivo solo se puede ver desde un navegador web');

		/** Se agrega la libreria PHPExcel */
		require_once 'lib/PHPExcel/PHPExcel.php';

		// Se crea el objeto PHPExcel
		$objPHPExcel = new PHPExcel();

		// Se asignan las propiedades del libro
		$objPHPExcel->getProperties()->setCreator("Codedrinks") //Autor
							 ->setLastModifiedBy("Codedrinks") //Ultimo usuario que lo modificó
							 ->setTitle("Reporte Excel con PHP y MySQL")
							 ->setSubject("Reporte Excel con PHP y MySQL")
							 ->setDescription("Reporte de soporte y asesoria")
							 ->setKeywords("reporte de actividades pendientes")
							 ->setCategory("Reporte excel");

		$tituloReporte = "Relación de actividades pendientes";
		$titulosColumnas = array('No.', 'Radicado Orfeo Padre', 'Fecha de entrada a la dependencia', 'Fecha entrega al equipo de trabajo','Asunto','Equipo de trabajo','Responsable','Fecha salida esperada dependencia','Fecha salida esperada equipo');
		
		$objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_LEGAL);
		
		$objPHPExcel->setActiveSheetIndex(0)
        		    ->mergeCells('A1:I1');
						
		// Se agregan los titulos del reporte
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1',$tituloReporte)
        		    ->setCellValue('A3',  $titulosColumnas[0])
		            ->setCellValue('B3',  $titulosColumnas[1])
        		    ->setCellValue('C3',  $titulosColumnas[2])
            		->setCellValue('D3',  $titulosColumnas[3])
					->setCellValue('E3',  $titulosColumnas[4])
					->setCellValue('F3',  $titulosColumnas[5])
					->setCellValue('G3',  $titulosColumnas[6])
					->setCellValue('H3',  $titulosColumnas[7])
					->setCellValue('I3',  $titulosColumnas[8]);
		
		//Se agregan los datos de los alumnos
		//$objActSheet->setCellValueExplicit('E','8757584',PHPExcel_Cell_DataType::TYPE_STRING);
		$i = 4;
		while ($fila = $resultado->fetch_array())
		{
  			$objPHPExcel->setActiveSheetIndex(0)
        		    ->setCellValue('A'.$i,  $fila['id_reparto'])
		            ->setCellValueExplicit('B'.$i, $fila['radicado'],PHPExcel_Cell_DataType::TYPE_STRING)
					//->setCellValue('B'.$i, '@'.$fila['radicado'])
        		    ->setCellValue('C'.$i,  $fila['fecha_entrada_dep'])
            		->setCellValue('D'.$i, utf8_encode($fila['fecha_entrada_grup']))
					->setCellValue('E'.$i, utf8_encode($fila['asunto']))
				    //->setCellValueExplicit('E'.$i, $fila['asunto'],PHPExcel_Cell_DataType::TYPE_STRING)
					->setCellValue('F'.$i, utf8_encode($fila['nom_equipo']))
					->setCellValue('G'.$i, utf8_encode($fila['nom_usuario']))
					->setCellValue('H'.$i, utf8_encode($fila['fecha_sal_esp_dep']))
					->setCellValue('I'.$i, utf8_encode($fila['fecha_sal_esp_eq']));
					$i++;
		}
		
		$estiloTituloReporte = array(
        	'font' => array(
	        	'name'      => 'Verdana',
    	        'bold'      => true,
        	    'italic'    => false,
                'strike'    => false,
               	'size' =>10,
	            	'color'     => array(
    	            	'rgb' => 'FFFFFF'
        	       	)
            ),
	        'fill' => array(
				'type'	=> PHPExcel_Style_Fill::FILL_SOLID, 
				'color'	=> array('argb' => 'FF220835')
			),
            'borders' => array(
               	'allborders' => array(
                	'style' => PHPExcel_Style_Border::BORDER_NONE                    
               	)
            ), 
            'alignment' =>  array(
        			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'rotation'   => 0,
        			'wrap'          => TRUE
    		)
        );

		$estiloTituloColumnas = array(
            'font' => array(
                'name'      => 'Arial',
                'bold'      => true,                          
                'color'     => array(
                    'rgb' => 'FFFFFF'
                )
            ),
            'fill' 	=> array(
				'type'		=> PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
				'rotation'   => 90,
        		'startcolor' => array(
            		'rgb' => 'c47cf2'
        		),
        		'endcolor'   => array(
            		'argb' => 'FF431a5d'
        		)
			),
            'borders' => array(
            	'top'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                    'color' => array(
                        'rgb' => '143860'
                    )
                ),
                'bottom'     => array(
                    'style' => PHPExcel_Style_Border::BORDER_MEDIUM ,
                    'color' => array(
                        'rgb' => '143860'
                    )
                )
            ),
			'alignment' =>  array(
        			'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        			'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
        			'wrap'          => TRUE
    		));
			
		$estiloInformacion = new PHPExcel_Style();
		$estiloInformacion->applyFromArray(
			array(
           		'font' => array(
               	'name'      => 'Arial',               
               	'color'     => array(
                   	'rgb' => '000000'
               	)
           	),
           	'fill' 	=> array(
				'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
				'color'		=> array('argb' => 'FFFFFFF')
			),
           	'borders' => array(
               	'left'     => array(
                   	'style' => PHPExcel_Style_Border::BORDER_THIN ,
	                'color' => array(
    	            	'rgb' => '3a2a47'
                   	)
               	)             
           	)
        ));
		 
		$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($estiloTituloReporte);
		$objPHPExcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray($estiloTituloColumnas);		
		$objPHPExcel->getActiveSheet()->setSharedStyle($estiloInformacion, "A4:I".($i-1));
						
		for($i = 'A'; $i <= 'I
		'; $i++){
			$objPHPExcel->setActiveSheetIndex(0)			
				->getColumnDimension($i)->setAutoSize(TRUE);
		}
		
		// Se asigna el nombre a la hoja
		$objPHPExcel->getActiveSheet()->setTitle('Pendientes');

		// Se activa la hoja para que sea la que se muestre cuando el archivo se abre
		$objPHPExcel->setActiveSheetIndex(0);
		// Inmovilizar paneles 
		//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
		$objPHPExcel->getActiveSheet(0)->freezePaneByColumnAndRow(0,4);

		// Se manda el archivo al navegador web, con el nombre que se indica (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Reportedependientes.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		exit;
		
	}
	else{
		print_r('No hay resultados para mostrar');
	}
?>