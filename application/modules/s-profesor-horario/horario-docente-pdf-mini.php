<?php

// Obtiene los parametros
//$id_aula_paralelo = (isset($_params[0])) ? $_params[0] : 0;
$id_gestion = $_gestion['id_gestion'];
$nombre_gestion = $_gestion['gestion'];
$id_docente=isset($_params[0])?$_params[0]:0; 
//$id_aula_p=$id_aula_paralelo;
$id_turno=isset($_params[1])?$_params[1]:0; 
// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
//if (!$id_aula_paralelo == 0) {
	// Obtiene los aula_paralelo

/*ORDER BY 
       ins_turno.id_turno asc,
       ins_nivel_academico.id_nivel_academico asc,
       nombre_aula asc,
       nombre_paralelo asc*/
	// Ejecuta un error 404 si no existe los aula_paralelo
	if (!$permiso_listar) { require_once not_found(); exit; }
 /*}else {
	// Obtiene el aula_paralelo
	$aula_paralelo = $db->select('z.*, a.nombre_paralelo as paralelo')->from('ins_aula_paralelo z')->join('ins_paralelo a', 'z.paralelo_id = a.id_paralelo', 'left')->where('z.id_aula_paralelo', $id_aula_paralelo)->fetch_first();
	
	// Ejecuta un error 404 si no existe el aula_paralelo
	if (!$aula_paralelo || !$permiso_ver) { require_once not_found(); exit; }
}*/


//________________________________________________________________________
//________________________________________________________________________

//                  -CONFIGURACION GENERAL manera 2 pdf-
//________________________________________________________________________
//________________________________________________________________________


// Importa la libreria para generar el reporte
 
 
require_once libraries . '/tcpdf-class/tcpdf.php';
//require_once libraries . '/tcpdf/tcpdf.php';//ya declarado en tcpdf-class 
//$pdf=new TCPDF('p','mm','a4');

// Verifica si existen los parametros
//if ($id_aula_paralelo == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');//h=vertical L=horizontal

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	


//:::::::::::::::::::::::::::::::: TITULO   :::::::::::::::::::::::::::::
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'HORARIO '.$nombre_gestion, 0, true, 'C', false, '', 0, false, 'T', 'M');


  //_________nombre de docente________
$con="SELECT * FROM `per_asignaciones` pro,sys_persona per ,ins_horario_profesor_materia hpm, 
`int_aula_paralelo_asignacion_materia` apam,
`ins_aula_paralelo` ap 
WHERE pro.persona_id=per.id_persona
AND apam.`asignacion_id`=pro.`id_asignacion` 
AND ap.`id_aula_paralelo`=apam.`aula_paralelo_id` 
AND hpm.`aula_paralelo_asignacion_materia_id`=apam.`id_aula_paralelo_asignacion_materia` 
AND ap.`estado`='A' 
GROUP BY  apam.asignacion_id ASC";
/*//$con="SELECT * FROM pro_profesor pro,sys_persona per ,ins_horario_profesor_materia hpm, `ins_aula_paralelo` ap WHERE pro.persona_id=per.id_persona
AND hpm.profesor_id=pro.id_profesor AND ap.`id_aula_paralelo`=hpm.`curso_paralelo_id` AND ap.`estado`='A' GROUP BY  hpm.profesor_id ASC";*/



$inscritoaa = $db->query($con)->fetch();

$nombres='';

$cc=0; $cct=0; $tabla1=''; $tabla2=''; $tabla3=''; $title1='';$title2='';$title3='';$tabla4=''; $tabla5=''; $tabla6=''; $title4='';$title5='';$title6='';$tododatos='';$tablaGeneral ='';

foreach($inscritoaa as $elementod) {
 
 //::::: :::::::::: inicio de 1 HORARIO :::::::: ::::::::::: ::::::::::
    //$nombres="HORARIO -  ".$elementod['nombres'].' ' .$elementod['primer_apellido']. ' '.$elementod['segundo_apellido'].' - '.$elementod['codigo_profesor'];
    $nombre=$elementod['nombres'];
    $ape_paterno=$elementod['primer_apellido'];
    $ape_materno=$elementod['segundo_apellido'];
     $cod_profesor='';//$elementod['codigo_profesor'];
    $id_profesor=$elementod['id_asignacion'];
    //$textitle = '<h1 style="text-align:center;">'.$nombres.'</h1>';  
    // output the HTML content
     //$pdf->writeHTML($textitle, true, false, true, false, '');
     $num=1;//numero de hora
    


$consulta="SELECT  * FROM ins_horario_dia where estado='A'  ORDER BY hora_ini ASC";    // ORDER BY p.primer_apellido ASC
    $inscritos = $db->query($consulta)->fetch();
$body='';
foreach($inscritos as $elementop){
     $hora_id=$elementop['id_horario_dia']; 
     //echo('hora'.':__________'.$hora_id.'<br>'); 
      //  echo escape($elemento['hora_ini']).'-'.escape($elemento['hora_fin']).'<br>' ;
    //estraer la 
    $sql="";
    if($id_profesor){
        $sql.=" and  apam.asignacion_id=$id_profesor";
       // $sql.=" and  z.profesor_id=$id_profesor";
    }
 
  /*   $consulta="

SELECT  k.hora_ini,
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
    ".$sql." ORDER BY z.horario_dia_id asc"; */   
    
     $consulta="
SELECT k.hora_ini, k.hora_fin, tu.nombre_turno, 
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
    
/*  COLOR Y ALTURA
$objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(85);
     $objPHPExcel->getActiveSheet()->getStyle('a' . $fil.':h' . $fil)->applyFromArray(
                array(
                   'borders'=>array(
                     'allborders'=>array(
                        'style'=>PHPExcel_Style_Border::BORDER_THIN
                     )

                  )
                ) 
            );*/
$datoscelda='';
$simaterias=0;//control de horas sin datos
    $horaini=  '<small>'.$elementop["hora_ini"].' - '.$elementop["hora_fin"] .'</small>';
    
    //'<small>'.$elementoi["hora_ini"].' - '.$elementoi["hora_fin"] .'</small>';
    
    
 foreach($listatodo as $elemento){
          
//codigo de materia
$cod_materia=substr($elemento['nombre_materia'], 0, 3);   
 if($elemento['cod_materia']!=null){
     $cod_materia=$elemento['cod_materia'];
 } 
        
 
            $datoscelda='-'.$cod_materia.'-'.' <br>'.$elemento['nombre_aula'].' '.$elemento['nombre_paralelo'];//.'
            //$datoscelda='-'.$cod_materia.'-'.' <br>'.$elemento['descripcion_aula_paralelo'].'';//.'  
        
        
        //:::::::::::::::::::::::::::::::: ASIGNACION DE DATOS A SU DIA :::::::::::::::::::::::::::::
        if($elemento["dia_semana_id"]==1){
            //$lunes=$datoscelda;   
             $lunes='<td  align="center" bgcolor="'.$elemento["color_materia"].'" >'.$datoscelda.'</td>';
            $simaterias=1;
  
        }else{
           // $lunes='<td></td>';
            //$body.='<td></td>'; 
        }
        
        if($elemento["dia_semana_id"]==2){
            $martes='<td  align="center" bgcolor="'.$elemento["color_materia"].'" >'.$datoscelda.'</td>';
            $simaterias=1;
            // echo($martes.':__________');
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'd' . $fil,  $datoscelda);
        } else{
            //$martes='<td></td>';
            //$body.='<td></td>'; 
        }
        
        if($elemento["dia_semana_id"]==3){$miercoles='<td  align="center" bgcolor="'.$elemento["color_materia"].'" >'.$datoscelda.'</td>';
            $simaterias=1;
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'E' . $fil, $datoscelda);
        } else{
           // $miercoles='<td></td>';
           // $body.='<td></td>'; 
        }
        
        if($elemento["dia_semana_id"]==4){$jueves='<td  align="center" bgcolor="'.$elemento["color_materia"].'" >'.$datoscelda.'</td>';
            $simaterias=1;
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'F' . $fil, $datoscelda);
        } else{
           // $body.='<td></td>'; 
        }if($elemento["dia_semana_id"]==5){$viernes='<td  align="center" bgcolor="'.$elemento["color_materia"].'" >'.$datoscelda.'</td>';
            $simaterias=1;
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'G' . $fil, $datoscelda);
        } else{
           // $lunes='<td></td>';
           // $body.='<td></td>'; 
        }if($elemento["dia_semana_id"]==6){$sabado='<td  align="center" bgcolor="'.$elemento["color_materia"].'" >'.$datoscelda.'</td>';
            $simaterias=1;
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'H' . $fil, $datoscelda);
        } else{
            //$sabado='<td></td>';
           // $body.='<td></td>'; 
        }
       
        //$body.='</tr>';
        // echo escape($elemento['hora_ini']).'-'.escape($elemento['nombre_materia']) ;
        
    //echo($num.$horaini.$lunes.$martes.$miercoles.$jueves.$viernes.$sabado);exit();

    }
//agregar color y ....
    if($lunes=='')
       $lunes='<td></td>';
    //else
    //   $lunes='<td style="background: #a3ffcc;">'.$lunes.'</td>';   
    if($martes=='')
       $martes='<td></td>';
    //else
    //   $martes='<td style="background: #a3ffcc;">'.$martes.'</td>';
    if($miercoles=='')
       $miercoles='<td></td>';
    //else
    //   $miercoles='<td style="background: #a3ffcc;">'.$miercoles.'</td>';
    if($jueves=='')
       $jueves='<td></td>';
    //else
    //   $jueves='<td style="background: #a3ffcc;">'.$jueves.'</td>';
        if($viernes=='')
       $viernes='<td></td>';
    //else
    //   $viernes='<td style="background: #a3ffcc;">'.$viernes.'</td>';
    if($sabado=='')
       $sabado='<td></td>';
    //else
    //   $sabado='<td style="background: #a3ffcc;">'.$sabado.'</td>';
    
  
    //_________imprimir recreo________
    //if($elemento["complemento"]=='descanso'){
        //fonde ce deldas
 
       // $body.='<tr><td></td><td>'.$horaini.'</td><td colspan="6">RECREO</td></tr>';
        
       // $cc=1;
    //}else{
    if($simaterias){//control de horas sin datos
     
        $body.='<tr><td>'.$num.'</td><td>'.$horaini.'</td>'.$lunes.$martes.$miercoles.$jueves.$viernes.$sabado.' </tr>';$num++; 
        $simaterias=0;
    }
    //}  
}
  
 //$tabla = $style; 
	$tabla = '<table border="1" cellpadding="1">';//  border="1" cellpadding="2"  style="text-align:center"  cellspacing="3"
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="5%" align="center">#</th>';
	$tabla .= '<th width="17%" align="center"><small >HORARIO</small></th>';
	$tabla .= '<th width="13%"  align="center"><small>LUNES</small></th>';
	$tabla .= '<th width="13%" align="center"><small>MARTES</small></th>';
	$tabla .= '<th width="13%" align="center"><small>MIERC</small></th>';
	$tabla .= '<th width="13%" align="center"><small>JUEVES</small></th> '; 	 
	$tabla .= '<th width="13%" align="center"><small>VIERNES</small></th> '; 	 
	$tabla .= '<th width="13%" align="center"><small>SABADO</small></th> '; 	 
	$tabla .= '</tr>';
	 $tabla .= $body;
	$tabla .= '</table>';
//var_dump($tabla);  exit();
//$tabla='<table border="1" cellpadding="2" ><tr><td  width="5%">n</td><td><small>HORAS</small></td><td  align="center"><small>LUNES</small></td><td ><small>MARTES</small></td><td><small>MIERCOLES</small></td><td><small>JUEVES</small></td><td><small>VERNES</small></td><td><small>SABADO</small></td></tr><tr><td>1</td><td>08:00 09:00</td><td><small>MAT <br>ECD</small></td><td>qui</td></tr></table>';
   // echo $tabla;

    $cc++; //.$aula.$paralelo.'
    if($cc==1){ 
   $tabla1 =$tabla; 
    $title1 ='<p style="text-align:center;"><b>'.$cod_profesor.'</b> <font size="7" >'.$nombre.' '.$ape_paterno.' '.$ape_materno.' </font></p>'; 
 
    }else if($cc==2){
   $tabla2 =$tabla;
   $title2 ='<p style="text-align:center;"><b>'.$cod_profesor.'</b> <font size="7" >'.$nombre.' '.$ape_paterno.' '.$ape_materno.' </font></p>'; 
    }else if($cc==3){
   $tabla3 =$tabla;  
   $title3 ='<p style="text-align:center;"><b>'.$cod_profesor.'</b> <font size="7" >'.$nombre.' '.$ape_paterno.' '.$ape_materno.' </font></p>'; 
    }    if($cc==4){ 
   $tabla4 =$tabla; 
    $title4 ='<p style="text-align:center;"><b>'.$cod_profesor.'</b> <font size="7" >'.$nombre.' '.$ape_paterno.' '.$ape_materno.' </font></p>'; 
 
    }else if($cc==5){
   $tabla5 =$tabla;
   $title5 ='<p style="text-align:center;"><b>'.$cod_profesor.'</b> <font size="7" >'.$nombre.' '.$ape_paterno.' '.$ape_materno.' </font></p>';
    }else if($cc==6){
   $tabla6 =$tabla;  
   $title6 ='<p style="text-align:center;"><b>'.$cod_profesor.'</b> <font size="7" >'.$nombre.' '.$ape_paterno.' '.$ape_materno.' </font></p>';
    }
    $tablaGeneral='<table ><tr><th  width="32%">'.$title1.'</th><th  width="1%"></th><th width="32%">'.$title2.'</th><th  width="1%"></th><th width="32%">'.$title3.'</th> </tr> <tr><td style="font-size:4pt;">'.$tabla1.'</td><td></td> <td>'.$tabla2.'</td> <td></td> <td>'.$tabla3.'</td></tr>  <tr><td  width="32%">|</td><td  width="1%"></td><td width="32%"></td><td  width="1%"></td></tr> <tr><th  width="32%">'.$title4.'</th><th  width="1%"></th><th width="32%">'.$title5.'</th><th  width="1%"></th><th width="32%">'.$title6.'</th> </tr> <tr><td style="font-size:4pt;">'.$tabla4.'</td><td></td> <td>'.$tabla5.'</td> <td></td> <td>'.$tabla6.'</td></tr></table>'; 
    
    $cct++;
   $tam=count($inscritoaa);
       
if($cct==$tam || $cc==6){
     //var_dump($tam.' '.$cc.' '.$cct);  exit();
    $pdf->writeHTML($tablaGeneral, true, false, false, false, '');
    $cc=0;$tabla1=''; $tabla2=''; $tabla3=''; $title1='';$title2='';$title3='';$tabla4=''; $tabla5=''; $tabla6=''; $title4='';$title5='';$title6='';$tododatos='';$tablaGeneral ='';
    $pdf->AddPage();
    if($cct!=$tam){ 
            $pdf->AddPage(); 
    }
}
    
//if($cc>3){
//    $tablaGeneral.='<table ><tr><th  width="32%">'.$title1.'</th><th  width="1%"></th><th width="32%">'.$title2.'</th><th  width="1%"></th><th width="32%">'.$title3.'</th> </tr> <tr><td style="font-size:4pt;">'.$tabla1.'</td><td></td> <td>'.$tabla2.'</td> <td></td> <td>'.$tabla3.'</td></tr> </table>'; 
    
//arma datos de tabla
// datos 1
//$tablaGeneral='<table ><tr><th  width="32%">'.$title1.'</th><th  width="1%"></th><th width="32%"></th><th  width="1%"></th><th width="32%"></th> </tr> <tr><td style="font-size:4pt;">'.$tabla1.'</td><td></td> <td></td> <td></td> <td></td></tr> </table>';
//datos 2 
//$tablaGeneral='<table ><tr><th  width="32%">'.$title1.'</th><th  width="1%"></th><th width="32%">'.$title2.'</th><th  width="1%"></th><th width="32%"></th> </tr> <tr><td style="font-size:4pt;">'.$tabla1.'</td><td>'.$tabla2.'</td> <td></td> <td></td> <td></td></tr> </table>';
    //datos 2 
//$tablaGeneral='<table ><tr><th  width="32%">'.$title1.'</th><th  width="1%"></th><th width="32%">'.$title2.'</th><th  width="1%"></th><th width="32%">'.$title3.'</th> </tr> <tr><td style="font-size:4pt;">'.$tabla1.'</td><td></td> <td>'.$tabla2.'</td> <td></td> <td>'.$tabla3.'</td></tr> </table>'; 
  
  //if($cc>3){
      // arma nueva tabla
   //   $tododatos.=$tablaGeneral;
       
 //}
    
    
 //$pdf->writeHTML($tablaGeneral, true, false, false, false, ''); 
//var_dump($tablaGeneral);  exit();
    //$cct++;
    //$cc=0;
 
  //}

    //if($cct>1){
       // Adiciona la pagina
/*	$pdf->AddPage(); 
    $tablaGeneral='<table ><tr><th  width="32%">'.$title1.'</th><th  width="1%"></th><th width="32%">'.$title2.'</th><th  width="1%"></th><th width="32%">'.$title3.'</th> </tr> <tr><td style="font-size:4pt;">'.$tabla1.'</td><td></td> <td>'.$tabla2.'</td> <td></td> <td>'.$tabla3.'</td></tr><tr><td> </td></tr> </table>';
    $pdf->writeHTML($tablaGeneral, true, false, false, false, ''); */
     // Adiciona la pagina
     //$pdf->writeHTML($tablaGeneral, true, false, false, false, '');    
	//$pdf->AddPage(); 
    //$cct=0;
 //}


} 
//$tablaGeneral='<table ><tr><th  width="32%">'.$title1.'</th><th  width="1%"></th><th width="32%">'.$title2.'</th><th  width="1%"></th><th width="32%">'.$title3.'</th> </tr> <tr><td style="font-size:4pt;">'.$tabla1.'</td><td></td> <td>'.$tabla2.'</td> <td></td> <td>'.$tabla3.'</td></tr>  <tr><td  width="32%">|</td><td  width="1%"></td><td width="32%"></td><td  width="1%"></td></tr> <tr><th  width="32%">'.$title1.'</th><th  width="1%"></th><th width="32%">'.$title2.'</th><th  width="1%"></th><th width="32%">'.$title3.'</th> </tr> <tr><td style="font-size:4pt;">'.$tabla1.'</td><td></td> <td>'.$tabla2.'</td> <td></td> <td>'.$tabla3.'</td></tr></table>'; 
//var_dump($tablaGeneral);  exit();
 //if(!$cc>3){
//$pdf->writeHTML($tablaGeneral, true, false, false, false, '');
 //}





//_________convertir a pdf________
//$pdf->writeHTML($tabla, true, false, false, false, ''); 
	// Genera el nombre del archivo
$nombre = 'aula_paralelo_' . date('Y-m-d_H-i-s') . '.pdf';
 
// Cierra y devuelve el fichero pdf
 $pdf->Output($nombre, 'I');

?>