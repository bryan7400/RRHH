 
<?php

//var_dump($_POST);die;
require_once libraries . '/phpexcel-2.1/controlador.php';
 


//
//
$inst = $db->query("SELECT * FROM SYS_INSTITUCIONES WHERE id_institucion=1")->fetch_first();

$nombre=$inst['nombre'];
$lema=$inst['lema'];
$id_gestion = $_gestion['id_gestion'];
//if ($boton == "reporte_rude") {

    //$turno       =0; //$_REQUEST['id_estudiante'];
    //$nivel =0;// $_REQUEST['id_inscripcion_rude'];
    //$aula    = 0;//$_REQUEST['id_inscripcion'];
   // $paralelo    = 0;//$_REQUEST['id_inscripcion'];
/*

    $turno=isset($_params[0])?$_params[0]:0;
    $nivel=isset($_params[1])?$_params[1]:0;
    $aula=isset($_params[2])?$_params[2]:0; 
   $paralelo=isset($_params[3])?$_params[3]:0; 
*/

    //var_dump($_REQUEST);exit();

    //$id_inscripcion_rude = $_REQUEST['id_inscripcion_rude'];

/*    $columna = array(
        '1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E', '6' => 'F', '7' => 'G', '8' => 'H', '9' => 'I', '10' => 'J', '11' => 'K', '12' => 'L', '13' => 'M', '14' => 'N', '15' => 'O', '16' => 'P', '17' => 'Q', '18' => 'R', '19' => 'S', '20' => 'T', '21' => 'U', '22' => 'V', '23' => 'W', '24' => 'X', '25' => 'Y', '26' => 'Z', '27' => 'AA', '27' => 'AA', '28' => 'AB', '29' => 'AC', '30' => 'AD', '31' => 'AE', '32' => 'AF', '33' => 'AG', '34' => 'AH', '35' => 'AI', '36' => 'AJ', '37' => 'AK', '38' => 'AL', '39' => 'AM', '40' => 'AN', '41' => 'AO', '42' => 'AP', '43' => 'AQ', '44' => 'AR', '45' => 'AS', '46' => 'AT', '47' => 'AU', '48' => 'AV', '49' => 'AW', '50' => 'AX'
    );*/

    //Colores RGB
/*    $aColores = array('1' => 'ECEA5C', '2' => '8AE245', '3' => 'F577F5', '4' => '537AF5', '5' => 'F35F7F', '6' => 'F752F5', '7' => 'AAFF00');*/

/* $sql="";
    if($turno){
        $sql.=" and ap.turno_id=$turno";
    }

    if($aula){
        $sql.=" and  ap.aula_id=$aula";
    } if($paralelo){
        $sql.=" and  pa.id_paralelo=$paralelo";
    }*/
  // $excel = new PHPExcel();
   $objPHPExcel = excel_iniciar("plantilla_horarios_academico.xls");
//plantilla_inscritosant.xls");//plantilla_horarios_academicos.xlsx");
 
//$objPHPExcel = $excel->getActiveSheet(); 
//$objPHPExcel = new PHPExcel();//crear nuevo excel
 $fil=1;
 //foreach($inst as $rowins )
   //     {
//var_dump($nombre);exit();
    // $objPHPExcel->getActiveSheet()->setCellValue( 'a'.$fil, $inst['nombre']);
   // $objPHPExcel->getActiveSheet()->setCellValue( 'A1', 'nombre');
 $objPHPExcel->getActiveSheet()->setCellValue( 'B' . $fil, $nombre);
     //$objPHPExcel->getActiveSheet()->setRowHigth(1,10);
     //$objPHPExcel->getActiveSheet()->setCellValue( 'B2', $lema);
     $objPHPExcel->getActiveSheet()->setCellValue( 'B2',date('Y-m-d').' '.date('H:i:s'));
$objPHPExcel->getActiveSheet()->setCellValue( 'B3','HORARIOS ACADEMICOS');
   //  }


     /*$objPHPExcel->getActiveSheet()->mergeCells('a'. ($fil).':i'. ($fil));
     $fil++;
     $objPHPExcel->getActiveSheet()->mergeCells('a'. ($fil).':i'. ($fil));
     $fil++;
     $objPHPExcel->getActiveSheet()->mergeCells('a'. ($fil).':i'. ($fil));
     $fil++;*/
 $horarios = $db->query("SELECT * 
FROM ins_horario_dia ho,ins_turno WHERE ho.estado='A'
AND ho.turno_id=ins_turno.id_turno AND ins_turno.`gestion_id`=$id_gestion AND ins_turno.`estado`='A'  AND ho.`estado`='A' 
ORDER BY ho.turno_id ASC,ho.hora_ini ASC")->fetch();
 

    $total = sizeof($horarios);


/*    $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(15);//ALTURA DE CELDA-----------
   //bordes de celda
            $objPHPExcel->getActiveSheet()->getStyle('B' . $fil.':'.$colmax . ($fil+2))->applyFromArray(
                array(
                   'borders'=>array(  'allborders'=>array(  'style'=>PHPExcel_Style_Border::BORDER_THIN ) )
                ) 
            );//borde celadas..---------------
      $objPHPExcel->getActiveSheet()->mergeCells('B'. ($fil).':'.$colmax. ($fil))->getStyle(('B'. ($fil).':k'. ($fil)))->applyFromArray(
                array(
                'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,               )
        )//RIZONTAL_LEFT  HORIZONTAL_RIGHT HORIZONTAL_RIGHT
        );//CENTRA CELDAS-----------
 
     $objPHPExcel->getActiveSheet()->getStyle('B' . ($fil).':'.$colmax. ($fil))->applyFromArray(
                array(
                   'borders'=>array(  'top'=>array(  'style'=>PHPExcel_Style_Border::BORDER_THIN ),'left'=>array(  'style'=>PHPExcel_Style_Border::BORDER_THIN ),'right'=>array(  'style'=>PHPExcel_Style_Border::BORDER_THIN ) )
                ) 
            );
     $objPHPExcel->getActiveSheet()->getStyle('B'. ($fil-3).':F'. $fil)->getFont()->setBold(false)->setSize(9) ->getColor()->setRGB('0000000');//MAÑANO DE LETRA---------
     
          $objPHPExcel->getActiveSheet()->mergeCells('B'. ($fil).':'.$colmax. ($fil));//UNE CELDAS------------
        */
//ancho de fila 
//$excel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
//automatcico
//$objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(-1); 
        
 
    if ($total > 0) {

        //$dep = $aula_paralelo[0]['primer_apellido'];
        $num=1; 
        $fil = 4;
        
        //TITULOS::::
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10); //ancho de columnas 
        $objPHPExcel->getActiveSheet()->setCellValue( 'B' . $fil, '#');
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15); //ancho de columnas
        $objPHPExcel->getActiveSheet()->setCellValue( 'C' . $fil, 'HORA INICIO');
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15); //ancho de columnas
        $objPHPExcel->getActiveSheet()->setCellValue( 'D' . $fil, 'HORA FIN');
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25); //ancho de columnas 
        $objPHPExcel->getActiveSheet()->setCellValue( 'E' . $fil, 'DESCRIPCION TIPO');
        
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15); //ancho de columnas
        $objPHPExcel->getActiveSheet()->setCellValue( 'F' . $fil, 'TURNO');
        
        
        $objPHPExcel->getActiveSheet()->getStyle('B'.$fil.':F'.$fil)->applyFromArray(
    array(
     'fill' => array(
      'type' => PHPExcel_Style_Fill::FILL_SOLID, 
      'color' => array('rgb' => 'c0e2ff') 
     ) 
    ) 
);
        
      //  var_dump($horarios);exit();
        
        $fil++;
        foreach($horarios as $row )
        {
             $objPHPExcel->getActiveSheet()->setCellValue( 'B' . $fil, $num);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'C' . $fil, escape($row['hora_ini']));//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'D' . $fil, $row['hora_fin']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'E' . $fil, $row['complemento']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'F' . $fil, $row['nombre_turno'].' '.$row['nombre_paralelo']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->getStyle('B' . $fil.':F' . ($fil+2))->applyFromArray(
                array(
                   'borders'=>array(  'allborders'=>array(  'style'=>PHPExcel_Style_Border::BORDER_THIN ) )
                ) 
            );//borde celadas..---------------
            $fil++;//segundo_apellido
            $num++;//nombre_materia
        }
/*nombre_completo
numero_documento
nombre_turno
nombre_nivel
nombre_aula
nombre_paralelo
nombre_tipo_estudiante
nombres_familiar*/
      
    }

    //exit;     
    //-------------------------------------------------- finalizar
    //mostrar la primera hoja de excel
    //seleccionar una hoja
    $objPHPExcel->setActiveSheetIndex(0);   //la primera Hoja de excel se numera como 0
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '');
    excel_finalizar($objPHPExcel, "profesor_horario.xls");


?>