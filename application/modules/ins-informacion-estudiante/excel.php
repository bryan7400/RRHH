<?php
require_once libraries . '/phpexcel-2.1/controlador.php';
require_once libraries . '/numbertoletter-class/NumberToLetterConverter.php';

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

$permiso_imprimir = in_array('imprimir', $_views);
// Verifica si existen los parametros

$clientes = $db->query("SELECT * FROM sys_cliente WHERE estado_cliente = 'A'")->fetch();




$abecedario = array("a", "b", "c", "d","e", "f", "G", "H","i", "J", "K","L", "M", "N", "O", "p", "Q", "r","s", "T", "U", "V","W", "X", "Y", "Z","aa", "Ab", "Ac", "Ad","Ae", "Af", "AG", "AH","Ai", "AJ", "AK","AL", "AM", "AN", "AO", "Ap", "AQ", "Ar","as", "aT", "aU", "aV","aW", "aX", "aY", "aZ","ba", "bb", "bc", "bd","be", "bf", "bG", "bH","bi", "bJ", "bK","bL", "bM", "bN", "bO", "bp", "bQ", "br","bs", "bT", "bU", "bV","bW", "bX", "bY", "bZ");
 
 
$objPHPExcel = excel_iniciar("plantilla_kardex_filtro_admin2.xls");
      
$col = 1;
$fil = 1;//N fila de las celdas
 
//titulo:::::::::::::::::::::::::::::::::
//
  $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(25);//ALTURA DE CELDA
  $objPHPExcel->getActiveSheet()->getStyle('A'. ($fil).':'.'h'. $fil)->getFont()->setBold(true)->setSize(20) ->getColor()->setRGB('0000000');
  $objPHPExcel->getActiveSheet()->setCellValue( 'b'.$fil, "Clientes ");      
    $fil=$fil+4;  //++++++++ 






 

$clientes = $db->query("SELECT * FROM sys_cliente WHERE estado_cliente = 'A'")->fetch();
 
//:::::::::::::excel tabla felicitaciones
$objPHPExcel->getActiveSheet()->setCellValue( 'D'.$fil, 'Lista de Clientes');
$objPHPExcel->getActiveSheet()->getStyle('A'. ($fil).':'.'j'. $fil)->getFont()->setBold(true)->setSize(15) ->getColor()->setRGB('0000000');

//tamao del formato
 $objPHPExcel->getActiveSheet()->getStyle('b'.($fil).':'.'j'.($fil+1))->applyFromArray(
    array(
     'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID, 
      'color' => array('rgb' => '00ff3a') ) 
    )
);
        $fil++;  //++++++++++++++++++++++++++++++++++  
 
$objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(15);//ALTURA DE CELDA 

$objPHPExcel->getActiveSheet()->setCellValue( 'b'.$fil, 'Nombres');
$objPHPExcel->getActiveSheet()->setCellValue( 'c'.$fil, 'Documento');
$objPHPExcel->getActiveSheet()->setCellValue( 'd'.$fil, 'N# Documento');
$objPHPExcel->getActiveSheet()->setCellValue( 'e'.$fil, 'Expedido');
$objPHPExcel->getActiveSheet()->setCellValue( 'f'.$fil, 'Genero');
$objPHPExcel->getActiveSheet()->setCellValue( 'g'.$fil, 'Fecha de Nacimiento');
$objPHPExcel->getActiveSheet()->setCellValue( 'h'.$fil, 'Direccion');
$objPHPExcel->getActiveSheet()->setCellValue( 'i'.$fil, 'Celular');
$objPHPExcel->getActiveSheet()->setCellValue( 'j'.$fil, 'Correo');

$fil++;



foreach($clientes as $rows )
 { 
    /*if($tit==1){
       $objPHPExcel->getActiveSheet()->setCellValue( 'b'.($fil-5), 'Aula:');
       $objPHPExcel->getActiveSheet()->setCellValue( 'b'.($fil-4),'Paralelo:');
       $objPHPExcel->getActiveSheet()->setCellValue( 'b'.($fil-3), 'Nivel:');
        
        $objPHPExcel->getActiveSheet()->setCellValue( 'c'.($fil-5), $rows['nombre_aula']);
       $objPHPExcel->getActiveSheet()->setCellValue( 'c'.($fil-4), $rows['nombre_paralelo']);
       $objPHPExcel->getActiveSheet()->setCellValue( 'c'.($fil-3), $rows['nombre_nivel']);
       
        $tit=0;
    }*/
    
    $objPHPExcel->getActiveSheet()->setCellValue( 'b'.$fil, $rows['nombres']);
    $objPHPExcel->getActiveSheet()->setCellValue( 'c'.$fil, $rows['tipo_documento']);
    $objPHPExcel->getActiveSheet()->setCellValue( 'd'.$fil, $rows['numero_documento']);
    $objPHPExcel->getActiveSheet()->setCellValue( 'e'.$fil, $rows['expedido']);
    $objPHPExcel->getActiveSheet()->setCellValue( 'f'.$fil, $rows['genero']);  
    $objPHPExcel->getActiveSheet()->setCellValue( 'g'.$fil, $rows['fecha_nacimiento']);
    $objPHPExcel->getActiveSheet()->setCellValue( 'h'.$fil, $rows['direccion']);
    $objPHPExcel->getActiveSheet()->setCellValue( 'i'.$fil, $rows['celular']); 
    $objPHPExcel->getActiveSheet()->setCellValue( 'j'.$fil, $rows['email']);    
    $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(35);//ALTURA DE CELDA
    
    
        $fil++;  //++++++++++++++++++++++++++++++++++  
}




    

   // echo json_encode($datos);

    $objPHPExcel->getActiveSheet()->setCellValue('A1', '');
    $objPHPExcel->setActiveSheetIndex(0);   //la primera Hoja de excel se numera como 0
    excel_finalizar($objPHPExcel, "clientes.xls");