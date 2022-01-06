<?php
require_once libraries . '/phpexcel-2.1/controlador.php';
require_once libraries . '/numbertoletter-class/NumberToLetterConverter.php';
// Obtiene los datos del monto total
$nombre_gestion = $_gestion['gestion'];

//se requiere tres datos id_aula_paralelo, asignacion_docente_id, materia_id, id_gestion
$id_gestion = $_gestion['id_gestion'];
$id_persona = $_user['persona_id'];

$abecedario = array("A", "B", "C", "D", "E", "F", "G", "H", "i", "J", "K", "L", "M", "N", "O", "p", "Q", "R");


  $objPHPExcel = excel_iniciar("plantilla_inscritos.xls");



$col = 1;
$fil = 1;

$_sql = "SELECT p.primer_apellido,p.segundo_apellido,p.nombres, u.active, CONCAT(au.nombre_aula,' ',pa.nombre_paralelo,' ',ni.nombre_nivel) AS curso , te.nombre_tipo_estudiante 
            FROM ins_inscripcion AS i 
            INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
            INNER JOIN sys_persona p ON e.persona_id=p.id_persona
            INNER JOIN sys_users u ON u.persona_id = p.id_persona
            INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=i.aula_paralelo_id  
            INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
            INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
            INNER JOIN ins_turno tu ON tu.id_turno=ap.turno_id
            INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
            INNER JOIN ins_tipo_estudiante te ON te.id_tipo_estudiante=i.tipo_estudiante_id
            WHERE i.gestion_id= $id_gestion
            AND i.estado='A'
            ORDER BY   p.primer_apellido ASC,  p.segundo_apellido ASC , u.active DESC";
// echo $_sql;
// exit();
$estudiantesql = $db->query($_sql)->fetch();


$columna = array(
  '1' => 'A',
  '2' => 'B',
  '3' => 'C',
  '4' => 'D',
  '5' => 'E',
  '6' => 'F',
  '7' => 'G',
  '8' => 'H',
  '9' => 'I',
  '10' => 'J',
  '11' => 'K',
  '12' => 'L',
  '13' => 'M',
  '14' => 'N',
  '15' => 'O',
  '16' => 'P',
  '17' => 'Q',
  '18' => 'R',
  '19' => 'S',
  '20' => 'T',
  '21' => 'U',
  '22' => 'V',
  '23' => 'W',
  '24' => 'X',
  '25' => 'Y',
  '26' => 'Z',
  '27' => 'AA',
  '28' => 'AB',
  '29' => 'AC',
  '30' => 'AD',
  '31' => 'AE',
  '32' => 'AF',
  '33' => 'AG',
  '34' => 'AH',
  '35' => 'AI',
  '36' => 'AJ',
  '37' => 'AK',
  '38' => 'AL',
  '39' => 'AM',
  '40' => 'AN',
  '41' => 'AO',
  '42' => 'AP',
  '43' => 'AQ',
  '44' => 'AR',
  '45' => 'AS',
  '46' => 'AT',
  '47' => 'AU',
  '48' => 'AV',
  '49' => 'AW',
  '50' => 'AX',
  '51' => 'AY',
  '52' => 'Az' 

);

/******************************************* */
$fil = 6;

$styleArray = array('font' => array('bold' => false, 'size' => 8), 'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT,));
$objPHPExcel->getActiveSheet()->getStyle('A' . ($fil) . ':' . 'G' . ($fil))->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$objPHPExcel->getActiveSheet()->setCellValue('A' . $fil, "Nro");
//PRIMER APELLIDO
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet()->setCellValue('B' . $fil, "Primer apellido");
//SEGUNDO APELLIDO
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet()->setCellValue('C' . $fil, "Segundo apellido");
//NOMBRES
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$objPHPExcel->getActiveSheet()->setCellValue('D' . $fil, "Nombres");  

//NOMBRES
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
$objPHPExcel->getActiveSheet()->setCellValue('E' . $fil, "Estado");  

//NOMBRES
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
$objPHPExcel->getActiveSheet()->setCellValue('F' . $fil, "Curso");  
    
//NOMBRES
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
$objPHPExcel->getActiveSheet()->setCellValue('G' . $fil, "Tipo estudiante"); 

$fil = 7;
$nro = 0;
foreach ($estudiantesql as $key => $estudiante) {
   
  
    //NRO
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $fil, $nro);
    //PRIMER APELLIDO
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $fil, $estudiante['primer_apellido']);
    //SEGUNDO APELLIDO
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $fil, $estudiante['segundo_apellido']);
    //NOMBRES
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $fil, $estudiante['nombres']);  
    
    //NOMBRES
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
    if($estudiante['active'] == 's'){
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $fil, "ACTIVO");  
    }else{
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $fil, "INACTIVO");  
    }
    
    //NOMBRES
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(40);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $fil, $estudiante['curso']);  
     
    //NOMBRES
     $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(40);
     $objPHPExcel->getActiveSheet()->setCellValue('G' . $fil, $estudiante['nombre_tipo_estudiante']);  
  
    $fil++;
    $nro++;    
}

$fil++;

// $fil = ($fil + 2);
// $objPHPExcel->getActiveSheet()->setBreak('A' . ($fil), PHPExcel_Worksheet::BREAK_ROW);
// $fil++;

//exit;
//-------------------------------------------------- finalizar
//mostrar la primera hoja de excel
//seleccionar una hoja
$objPHPExcel->getActiveSheet()->setCellValue('A1', '');
$objPHPExcel->setActiveSheetIndex(0);   //la primera Hoja de excel se numera como 0
excel_finalizar($objPHPExcel, "Reporte Usuarios/estudiante.xls");
//}
