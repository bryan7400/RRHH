 
<?php
 

//require_once libraries . '/phpexcel-2.1/controlador.php';
//require_once libraries . '/phpexcel-2.1/PHPExcel.php';
 //include_once 'phpexcel-2.1/PHPExcel.php';//lib de checkcode
/*
include_once libraries . '/phpexcel-2.1/PHPExcel.php';
$excel=new PHPExcel();
*/



//leer platilla
/*//$objPHPExcel = excel_iniciar("plantilla_rude.xlsx");

//$objReader = new PHPExcel_Reader_Excel2007();
$objReader = PHPExcel_IOFactory::createReader('Excel2007');	//Excel5, Excel2007

 $excel = $objReader->load('planilla_test.xlsx');
        //$objReader->setIncludeCharts(TRUE);
//$objPHPExcel = $objReader->load($ruta_archivo);

//seleccionar una hoja
//$excel->setActiveSheetIndex(0);
//return $objPHPExcel*/

//----------leer 2------------
//$excel=PHPExcel_IOFactory::load('phpexcel-2.1/plantillas/plantilla_test.xlsx');	
//SELECCIONA hoja de excel
/*$excel->setActiveSheetIndex(0);



//insertar el excel
$excel->setActiveSheetIndex(0)
        ->setCellValue('B5','excel 2007')
    ->setCellValue('C6','gege');*/



//selecciona uan celdasu valor
/*$excel->getActiveSheet()->setCellValue( 'b3', 'auto acomodado varaible anteriorr varaible anteriorrvaraible anteriorrvaraible anteriorrvaraible anteriorrvaraible anteriorrvaraible anteriorrvaraible anteriorrvaraible anteriorr');
$excel->getActiveSheet()->setCellValue( 'c2', 'mi tamaño y colo');
//ancho de columnas
$excel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
//ancho de fila 
$excel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
//automatcico
$excel->getActiveSheet()->getRowDimension('3')->setRowHeight(-1); 
//une celdas
$excel->getActiveSheet()->mergeCells('c2:k2');
//funte ramaño
$excel->getActiveSheet()->getStyle('c2')->applyFromArray(
    array(
     'font' => array(
      'size' => 24, 
      'color' => array('rgb' => 'ffffff') 
     ) 
    ) 
);
//funte tipo negrita
$excel->getActiveSheet()->getStyle('c2')->applyFromArray(
    array(
     'font' => array(
      'bold' => true
     ),
     'borders'=>array(
         'allborders'=>array(
            'style'=>PHPExcel_Style_Border::BORDER_THIN
         )
        
      )
    ) 
);
//
 $excel->getActiveSheet()->getStyle("A1")->getFont()->setBold(true) ->setName('Verdana') ->setSize(10) ->getColor()->setRGB('6F6F6F');



//bordes
$excel->getActiveSheet()->getStyle('a2:k3')->applyFromArray(
    array(
       'borders'=>array(
         'allborders'=>array(
            'style'=>PHPExcel_Style_Border::BORDER_THIN
         )
        
      )
    ) 
);


//color de celda
$excel->getActiveSheet()->getStyle('c2')->applyFromArray(
    array(
     'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID, 
      'color' => array('rgb' => '9954ff') 
     ) 
    ) 
); //oooooo*/
/*$objPHPExcel 
    ->getActiveSheet() 
    ->getStyle('A1') 
    ->getFill() 
    ->getStartColor() 
    ->setRGB('FF0000'); */

///margenes de celdas
//stylos margenes



//$writer=PHPExcel_IOFactory::createWriter($objPHPExcel,'Excel2007');

//exclee nuevo
//excel_finalizar2($objPHPExcel, "curso_paralelo.xls");

//$file=PHPExcel_IOFactory::createWriter($excel,'Excel2007');

//descarga en el navegador
/*header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
 header('Content-Disposition: attachment;filename=test.xlsx');
header('Cache-Control: max-age=0');

$file->save('php://output'); */

header('Content-Type: application/vnd.ms-excel; charset-UTF-8');
header('Content-Disposition: attachment;filename=jeje.xlsx');
header('Cache-Control: max-age=0');

//descargas en el servidor
//$file->save('test.xlsx');
?>