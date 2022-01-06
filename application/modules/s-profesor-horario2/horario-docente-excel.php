<?php

//:::::::::::::::::::::::::::::::: ARRANQUE Y PARAMETROS  :::::::::::::::::::::::::::::
require_once libraries . '/phpexcel-2.1/controlador.php';
    $id_gestion = $_gestion['id_gestion'];
    $nombre_gestion = $_gestion['gestion'];
    $id_docente=isset($_params[0])?$_params[0]:0; //id_asigancion

//:::::::::::::::::::::::::::::::: inicio excel  :::::::::::::::::::::::::::::
  $objPHPExcel = excel_iniciar("plantilla_horario.xls");

$objPHPExcel->getActiveSheet()->setCellValue( 'H4',date('Y-m-d').' '.date('H:i:s'));


   //_________nombre de docente________
    $con="SELECT * FROM `per_asignaciones` asi,sys_persona per 
WHERE asi.persona_id=per.id_persona 
AND asi.`id_asignacion`=".$id_docente;  

    $inscritoaa = $db->query($con)->fetch();




foreach($inscritoaa as $elemento){
    $objPHPExcel->getActiveSheet()->setCellValue( 'b4', "HORARIO -  ".$elemento['nombres'].' '.$elemento['primer_apellido'].' '.$elemento['segundo_apellido'].' - '.$elemento['codigo_profesor']);
    
}
    

$fil = 6;$num=1;
//________________________________________________________________________
//________________________________________________________________________

//                  -AQUI TOY-
//________________________________________________________________________
//________________________________________________________________________

//:::::::::::::::::::::::::::::::: CABECERA EXCEL   :::::::::::::::::::::::::::::
//fonde ce deldas
$objPHPExcel->getActiveSheet()->getStyle('A'. $fil.':H'. $fil)->applyFromArray(
        array(
         'fill' => array(
          'type' => PHPExcel_Style_Fill::FILL_SOLID, 
          'color' => array('rgb' => '1E16B3')//9954ff') 
         ) 
        ) 
    );
//colore de fuente y tipo
$objPHPExcel->getActiveSheet()->getStyle('A'. $fil.':H'. $fil)->getFont()->setBold(true)->setSize(10) ->getColor()->setRGB('ffffff');
$objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(15);
//_________datos de cabecera________
$objPHPExcel->getActiveSheet()->setCellValue( 'b' . $fil, "HORARIO ");
$objPHPExcel->getActiveSheet()->setCellValue( 'C' . $fil, "LUNES  ");
$objPHPExcel->getActiveSheet()->setCellValue( 'D' . $fil, "MARTES  ");
$objPHPExcel->getActiveSheet()->setCellValue( 'E' . $fil, "MIERCOLES  ");
$objPHPExcel->getActiveSheet()->setCellValue( 'F' . $fil, "JUEVES  ");
$objPHPExcel->getActiveSheet()->setCellValue( 'G' . $fil, "VIERNES  ");
$objPHPExcel->getActiveSheet()->setCellValue( 'h' . $fil, "SABADO  ");

$fil++;


 
//:::::::::::::::::::::::::::::::: CONSULTA SQL  :::::::::::::::::::::::::::::
//echo 'inicio parametro:'.$id_docente.'<br>';
 
$consulta="SELECT  * FROM ins_horario_dia where estado='A'  ORDER BY hora_ini ASC";    // ORDER BY p.primer_apellido ASC
    $inscritos = $db->query($consulta)->fetch();

foreach($inscritos as $elemento){
      $hora_id=$elemento['id_horario_dia']; 
     
      //  echo escape($elemento['hora_ini']).'-'.escape($elemento['hora_fin']).'<br>' ;
    //estraer la 
     $sql="";
    
    
/*     if($id_docente){
        $sql.=" and  z.profesor_id=$id_docente";
    }
     $consulta="SELECT  k.hora_ini,
        k.hora_fin,
        tu.nombre_turno,
        c.nombre_aula,
        e.nombre_paralelo,
        d.nombre_nivel,
        h.nombres,
        h.primer_apellido,
        i.*,
        b.*,
        z.id_horario_profesor_materia,
        z.curso_paralelo_id,
        z.dia_semana_id AS dia_semana_id,
        g.codigo_profesor,
        z.curso_paralelo_id,
        z.horario_dia_id,
        z.profesor_id,
        z.materia_id
  FROM ins_horario_profesor_materia z,
  
	ins_aula_paralelo b,
	ins_aula c,
	ins_nivel_academico d,
	ins_paralelo e,
	pro_profesor g,
	sys_persona h,
	pro_materia i,
	ins_turno tu,
	ins_horario_dia k,
	ins_gestion ge
WHERE z.curso_paralelo_id=b.id_aula_paralelo AND 
	tu.id_turno=b.turno_id AND
	b.aula_id=c.id_aula AND 
	c.nivel_academico_id= d.id_nivel_academico AND
    b.paralelo_id= e.id_paralelo AND 
        
    
    z.profesor_id= g.id_profesor AND
    g.persona_id=h.id_persona AND
    z.horario_dia_id=k.id_horario_dia AND
    z.materia_id=i.id_materia   AND   
     z.gestion_id=ge.id_gestion   AND  
        z.estado='A' AND 
    b.estado='A' AND
    c.estado='A' AND
    e.estado_paralelo='A' AND
    d.estado='A' AND
    tu.estado='A' AND
    ge.estado='A' AND
    k.estado='A' AND
    i.estado='A' and
    z.horario_dia_id='$hora_id'   AND
   z.gestion_id=$id_gestion 
    ".$sql." ORDER BY k.hora_ini asc";*/
     if($id_docente){
        $sql.=" and  apam.asignacion_id=$id_docente";
    }
     $consulta="SELECT k.hora_ini, k.hora_fin, tu.nombre_turno, 
c.nombre_aula, e.nombre_paralelo, d.nombre_nivel, h.nombres, h.primer_apellido, i.*, b.*, 
 
  z.*,apam.*

FROM ins_horario_profesor_materia z, `int_aula_paralelo_asignacion_materia` apam, `per_asignaciones` asi, ins_aula_paralelo b, ins_aula c, ins_nivel_academico d, ins_paralelo e, 
  sys_persona h, pro_materia i, ins_turno tu, ins_horario_dia k, ins_gestion ge 
WHERE z.`aula_paralelo_asignacion_materia_id`=apam.`id_aula_paralelo_asignacion_materia` 
AND apam.aula_paralelo_id=b.id_aula_paralelo  AND apam.`asignacion_id`=asi.`id_asignacion`
 AND tu.id_turno=b.turno_id 
 AND b.aula_id=c.id_aula AND c.nivel_academico_id= d.id_nivel_academico AND b.paralelo_id= e.id_paralelo
  AND apam.`asignacion_id`= asi.`id_asignacion` AND asi.persona_id=h.id_persona AND z.horario_dia_id=k.id_horario_dia 
  AND apam.materia_id=i.id_materia AND z.gestion_id=ge.id_gestion AND z.estado='A' AND b.estado='A' 
  AND c.estado='A' AND e.estado_paralelo='A' AND d.estado='A' AND tu.estado='A' AND ge.estado='A' 
  AND k.estado='A' AND i.estado='A' AND z.horario_dia_id='$hora_id' 
AND z.gestion_id=$id_gestion  ".$sql." ORDER BY k.hora_ini ASC";
    
    $listatodo = $db->query($consulta)->fetch();
    
    //iniciamos datos vacios
    $lunes=''; $martes=''; $miercoles=''; $jueves='';$viernes='';$sabado='';$horaini='';
    
    $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(85);
     $objPHPExcel->getActiveSheet()->getStyle('a' . $fil.':h' . $fil)->applyFromArray(
                array(
                   'borders'=>array(
                     'allborders'=>array(
                        'style'=>PHPExcel_Style_Border::BORDER_THIN
                     )

                  )
                ) 
            );

    foreach($listatodo as $elemento){

        
        $horaini= $elemento["hora_ini"].' - '.$elemento["hora_fin"] ;
        //datos celda
        $datoscelda='--'.escape($elemento['nombre_materia']).'--'.escape($elemento['nombre_aula']).' '.escape($elemento['nombre_paralelo']).' ('.escape($elemento['nombre_nivel']).')';
     //signar materia(s) 
        if($elemento["dia_semana_id"]==1){
       //$lunes= $elemento["nombre_materia"]; echo $lunes;
       $objPHPExcel->getActiveSheet()->setCellValue( 'c' . $fil, $datoscelda);
        } if($elemento["dia_semana_id"]==2){
       //$martes= $elemento["nombre_materia"]; echo $martes;
        $objPHPExcel->getActiveSheet()->setCellValue( 'd' . $fil,  $datoscelda);
        } if($elemento["dia_semana_id"]==3){
       //$martes= $elemento["nombre_materia"]; echo $martes;
        $objPHPExcel->getActiveSheet()->setCellValue( 'E' . $fil, $datoscelda);
        } if($elemento["dia_semana_id"]==4){
       //$martes= $elemento["nombre_materia"]; echo $martes;
        $objPHPExcel->getActiveSheet()->setCellValue( 'F' . $fil, $datoscelda);
        } if($elemento["dia_semana_id"]==5){
       //$martes= $elemento["nombre_materia"]; echo $martes;
        $objPHPExcel->getActiveSheet()->setCellValue( 'G' . $fil, $datoscelda);
        } if($elemento["dia_semana_id"]==6){
       //$martes= $elemento["nombre_materia"]; echo $martes;
        $objPHPExcel->getActiveSheet()->setCellValue( 'H' . $fil, $datoscelda);
        } 
        
        // echo escape($elemento['hora_ini']).'-'.escape($elemento['nombre_materia']) ;
        
        $cc=1;
    }
    
    //echo $horaini.$lunes.$martes.$miercoles.$jueves.$viernes.$sabado.'<br>';
    echo $horaini.'<br>';
    $objPHPExcel->getActiveSheet()->setCellValue( 'a' . $fil, $num);
    $objPHPExcel->getActiveSheet()->setCellValue( 'b' . $fil, $elemento["hora_ini"].' - '.$elemento["hora_fin"]);
    if($cc){
    $fil++;
    $num++;
        
    }
    $cc=0;
}


echo 'datos';

//:::::::::::::::::::::::::::::::: LLENADO DE PARAMETRO EXCEL  :::::::::::::::::::::::::::::


















//:::::::::::::::::::::::::::::::: impresion excel  :::::::::::::::::::::::::::::
   $objPHPExcel->setActiveSheetIndex(0);   //la primera Hoja de excel se numera como 0
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '');
    excel_finalizar($objPHPExcel, "horario.xls");

?>