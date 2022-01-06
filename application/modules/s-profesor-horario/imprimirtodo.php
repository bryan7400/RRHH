<?php

// Obtiene los parametros
//$id_aula_paralelo = (isset($_params[0])) ? $_params[0] : 0;
$id_gestion = $_gestion['id_gestion'];
$nombre_gestion = $_gestion['gestion'];
$id_aula_paralelo=isset($_params[0])?$_params[0]:0; 
$id_aula_p=$id_aula_paralelo;
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
	$pdf->Ln(0);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
		// Define el titulo del documento
	//$pdf->Cell(0, -10, 'HORARIO '.$nombre_gestion, 0, true, 'C', false, '', 0, false, 'T', 'M');
$titulo = '<div align="center"><b><font size="13" >HORARIO '.$nombre_gestion.'</font></b> </div> '; 
 $pdf->writeHTML($titulo, true, false, false, false, ''); 



//:::::::::::::::::::::::::::::::: recorrer CADA CURSO ::::::::::::::::::::::::::::: b.estado='A' and  
  
	$aula_paralelos = $db->query("SELECT * FROM ins_aula_paralelo  ap,ins_aula au,ins_paralelo pa ,ins_turno tu, ins_nivel_academico ni
WHERE ap.aula_id=au.id_aula AND pa.id_paralelo=ap.paralelo_id
AND ap.turno_id=tu.id_turno AND au.nivel_academico_id=ni.id_nivel_academico and ap.estado='A'   "
)->fetch();//->first() //AND ap.id_aula_paralelo=".$id_aula_p

$cc=0; $cct=0; $tabla1=''; $tabla2=''; $tabla3=''; $title1='';$title2='';$title3='';$tabla4=''; $tabla5=''; $tabla6=''; $title4='';$title5='';$title6='';
foreach ($aula_paralelos as $aula_paralelo) {
 
    $turno=escape($aula_paralelo['nombre_turno']) ;
    $id_turno=escape($aula_paralelo['id_turno']) ;//---->new
    $nivel=escape($aula_paralelo['nombre_nivel']);
    $aula=escape($aula_paralelo['nombre_aula']);
    $id_aula_paralelo=escape($aula_paralelo['id_aula_paralelo']);//---->new
    $paralelo=escape($aula_paralelo['estado_paralelo']);

    $num=1;
    //:::::::::::::::::::::::::::::::: titulo  :::::::::::::::::::::::::::::

    //$textitle = '<h3 style="text-align:center;">'.$nivel.'</h3>'; 
    //$textitle .= '<h4 style="text-align:center;">'.$aula.' '.$paralelo.'</h4>';  
    // output the HTML content
    // $pdf->writeHTML($textitle, true, false, true, false, '');
 

$consulta="SELECT * FROM ins_horario_dia where estado='A' and turno_id='$id_turno' ORDER BY hora_ini ASC";// $id_turno   // ORDER BY p.primer_apellido ASC
    $inscritos = $db->query($consulta)->fetch();
$body='';

//::::::::::: ::::::::::::  RECORRER POR HORA ::::::: :::::::::::: ::::::::::
foreach($inscritos as $elementoi){
     $hora_id=$elementoi['id_horario_dia']; 
     //echo('hora'.':__________'.$hora_id.'<br>'); 
      //  echo escape($elemento['hora_ini']).'-'.escape($elemento['hora_fin']).'<br>' ;
    //estraer la 
     $sql="";
     /*if($id_docente){
        $sql.=" and  z.profesor_id=$id_docente";
    }*/
    $id_aula_p=$id_aula_paralelo;
    
       if($id_aula_p){
        $sql.=" and  apam.`aula_paralelo_id`=$id_aula_p";
    }
     $consulta="
	SELECT hd.*,z.*,m.*,
        b.*,hd.`complemento`,

(SELECT GROUP_CONCAT(CONCAT(per.nombres ,' ', per.primer_apellido,' ', per.segundo_apellido) 
SEPARATOR ' | ')AS nombres_completo FROM `per_asignaciones` asi,sys_persona per WHERE asi.persona_id=per.id_persona AND apam.asignacion_id=asi.`id_asignacion`)AS nombres_doc,

  (SELECT GROUP_CONCAT(CONCAT(SUBSTRING(per.nombres, 1, 1),' ', SUBSTRING(per.primer_apellido, 1, 1),' ', SUBSTRING(per.segundo_apellido, 1, 1))
	SEPARATOR ' | ')AS nombres_completo   FROM  `per_asignaciones` asi,sys_persona  per
	WHERE asi.persona_id=per.id_persona AND apam.asignacion_id=asi.`id_asignacion`)AS iniciales


FROM ins_horario_profesor_materia z,`int_aula_paralelo_asignacion_materia` apam, ins_aula_paralelo b, ins_aula c, ins_nivel_academico d, ins_paralelo e, ins_turno tu, pro_materia m, ins_horario_dia hd , ins_gestion ge 
WHERE apam.aula_paralelo_id=b.id_aula_paralelo AND tu.id_turno=b.turno_id 
AND b.aula_id=c.id_aula AND c.nivel_academico_id= d.id_nivel_academico AND b.paralelo_id= e.id_paralelo 
AND apam.materia_id=m.id_materia AND z.horario_dia_id=hd.id_horario_dia AND 
z.`aula_paralelo_asignacion_materia_id`=apam.`id_aula_paralelo_asignacion_materia` 
AND
z.estado='A'AND b.estado='A' 
AND ge.estado='A'
AND c.estado='A'AND e.estado_paralelo='A' 
AND tu.estado='A'
AND d.estado='A'AND hd.estado='A'
AND m.estado='A' 
AND z.horario_dia_id='$hora_id' 
AND z.gestion_id=$id_gestion 

 ".$sql." 

ORDER BY z.horario_dia_id ASC"; 
    
    /*if($id_aula_p){
        $sql.=" and  z.curso_paralelo_id=$id_aula_p";
    }
     $consulta="
	SELECT hd.*,z.*,m.*,
        b.*,hd.`complemento`,

    (SELECT GROUP_CONCAT(CONCAT(per.nombres ,' ', per.primer_apellido,' ', per.segundo_apellido)
	SEPARATOR ' | ')AS nombres_completo  
    FROM  pro_profesor pro,sys_persona  per
	WHERE 
	pro.persona_id=per.id_persona AND
	z.profesor_id=pro.id_profesor 
	)AS nombres_doc,
	
    (SELECT GROUP_CONCAT(CONCAT(SUBSTRING(per.nombres, 1, 1),' ', SUBSTRING(per.primer_apellido, 1, 1),' ', SUBSTRING(per.segundo_apellido, 1, 1))
	SEPARATOR ' | ')AS nombres_completo  
    FROM  pro_profesor pro,sys_persona  per
	WHERE 
	pro.persona_id=per.id_persona AND
	z.profesor_id=pro.id_profesor 
	)AS iniciales


  FROM ins_horario_profesor_materia z,
  
	ins_aula_paralelo b,
	ins_aula c,
	ins_nivel_academico d,
	ins_paralelo e,
	ins_turno tu,
	`pro_materia` m,
	`ins_horario_dia` hd ,
	ins_gestion ge
 
WHERE z.curso_paralelo_id=b.id_aula_paralelo 
	AND tu.id_turno=b.turno_id AND b.aula_id=c.id_aula 
	AND c.nivel_academico_id= d.id_nivel_academico 
	AND b.paralelo_id= e.id_paralelo AND z.materia_id=m.id_materia 	AND 
    z.horario_dia_id=hd.id_horario_dia AND   
    z.gestion_id=ge.id_gestion AND 
    
	z.estado='A'AND 
	b.estado='A' AND
	ge.estado='A'AND 
	c.estado='A'AND 
	e.estado_paralelo='A' AND 
	tu.estado='A'AND 
	d.estado='A'AND
	hd.estado='A'AND
	m.estado='A' and 
    z.horario_dia_id='$hora_id'   AND
   z.gestion_id=$id_gestion 
    ".$sql." ORDER BY z.horario_dia_id asc"; 
    */
 
    
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
     
    $horaini= '<small>'.$elementoi["hora_ini"].' - '.$elementoi["hora_fin"] .'</small>';
    //_________obterncio nde datos de un horario ________
    foreach($listatodo as $elemento){
        //codigo de materia
        $cod_materia=substr($elemento['nombre_materia'], 0, 3);   
         if($elemento['cod_materia']!=null){
             $cod_materia=$elemento['cod_materia'];
         } 
        
        if($elemento['nombres_doc']==null){
            $datoscelda='<b ><font size="10" >'.$cod_materia.'</font></b>'.'<br>(<small>No</small>)';
             
        }else{   
            $datoscelda='<b ><font size="10" > '.$cod_materia.'</font></b>'.'<br> <small>('.escape($elemento['iniciales']).')</small>'; 
        }
        
         
 
        
        
        if($elemento["dia_semana_id"]==1){
            $lunes='<td  align="center" bgcolor="'.$elemento["color_materia"].'" >'.$datoscelda.'</td>'; 
    //echo('-  lunes:'.$lunes.'');
       //$lunes= $elemento["nombre_materia"]; echo $lunes;
       //$objPHPExcel->getActiveSheet()->setCellValue( 'c' . $fil, $datoscelda);
        }else{
           // $lunes='<td></td>';
            //$body.='<td></td>'; 
        }
        
        if($elemento["dia_semana_id"]==2){
            $martes='<td  align="center" bgcolor="'.$elemento["color_materia"].'" >'.$datoscelda.'</td>'; 
            // echo($martes.':__________');
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'd' . $fil,  $datoscelda);
        } else{
            //$martes='<td></td>';
            //$body.='<td></td>'; 
        }
        
        if($elemento["dia_semana_id"]==3){$miercoles= '<td  align="center" bgcolor="'.$elemento["color_materia"].'" >'.$datoscelda.'</td>'; 
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'E' . $fil, $datoscelda);
        } else{
           // $miercoles='<td></td>';
           // $body.='<td></td>'; 
        }
        
        if($elemento["dia_semana_id"]==4){$jueves='<td  align="center" bgcolor="'.$elemento["color_materia"].'" >'.$datoscelda.'</td>'; 
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'F' . $fil, $datoscelda);
        } else{
           // $body.='<td></td>'; 
        }if($elemento["dia_semana_id"]==5){$viernes='<td  align="center" bgcolor="'.$elemento["color_materia"].'" >'.$datoscelda.'</td>'; 
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'G' . $fil, $datoscelda);
        } else{
           // $lunes='<td></td>';
           // $body.='<td></td>'; 
        }if($elemento["dia_semana_id"]==6){$sabado='<td  align="center" bgcolor="'.$elemento["color_materia"].'" ><small >'.$datoscelda.'</small></td>'; 
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'H' . $fil, $datoscelda);
        } else{
            //$sabado='<td></td>';
           // $body.='<td></td>'; 
        }
       
        //$body.='</tr>';
        // echo escape($elemento['hora_ini']).'-'.escape($elemento['nombre_materia']) ;
        
    //echo($num.$horaini.$lunes.$martes.$miercoles.$jueves.$viernes.$sabado);exit();
        //$cc=1;
    }

    if($lunes=='')
       $lunes='<td></td>';
    //else
    //   $lunes=$lunes ; 
    
    if($martes=='')
       $martes='<td></td>';
    //else
    //   $martes=$martes;
    
    if($miercoles=='')
       $miercoles='<td></td>';
    //else
    //   $miercoles='<td style="background: #a3ffcc;"><small>'.$miercoles.'</small></td>';
    if($jueves=='')
       $jueves='<td></td>';
    //else
    //   $jueves='<td style="background: #a3ffcc;"><small>'.$jueves.'</small></td>';
        if($viernes=='')
       $viernes='<td></td>';
    //else
    //   $viernes='<td style="background: #a3ffcc;"><small>'.$viernes.'</small></td>';
    if($sabado=='')
       $sabado='<td></td>';
    //else
    //   $sabado='<td style="background: #a3ffcc;"><small>'.$sabado.'</small></td>';
    
 
    //_________imprimir recreo________
    if($elementoi["complemento"]=='descanso'){
        //fonde ce deldas
 
        $body.='<tr><td colspan="8" align="center" cellpadding="4"><small>RECREO</small></td></tr>';//$body.='<tr><td></td><td align="center">'.$horaini.'</td><td colspan="6" align="center"  cellpadding="5">RECREO</td></tr>';
         
    }else{
         $body.='<tr><td align="center">'.$num.'</td><td align="center">'.$horaini.'</td>'.$lunes.$martes.$miercoles.$jueves.$viernes.$sabado.' </tr>';$num++; 
    }  
}
  
//_________ harmar toda la tabla ________
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

    
 
 //:::::::::::::::::::::::::::::::: acomodar TABLAS :::::::::::::::::::::::::::::
   // echo $cc;exit();
   $cc++; //.$aula.$paralelo.'
    if($cc==1){
   $tabla1 =$tabla; 
    $title1 ='<p style="text-align:center;"><b>'.$aula.' '.$paralelo.'</b> <font size="7" >'.$turno.' '.$nivel.' </font></p>';  
   /*$title1 ='<h5>'.$aula.' '.$paralelo.'</h5><p>'.$turno.' '.$nivel.'</p>';*/
    }else if($cc==2){
   $tabla2 =$tabla;
   $title2 ='<p style="text-align:center;"><b>'.$aula.' '.$paralelo.'</b> <font size="7" >'.$turno.' '.$nivel.' </font></p>';  
    }else if($cc==3){
   $tabla3 =$tabla;  
   $title3 ='<p style="text-align:center;"><b>'.$aula.' '.$paralelo.'</b> <font size="7" >'.$turno.' '.$nivel.'</font></p>';   
    }
    else   if($cc==4){
   $tabla4 =$tabla; 
   $title4 ='<p style="text-align:center;"><b>'.$aula.' '.$paralelo.'</b> <font size="7" >'.$turno.' '.$nivel.'</font></p>';   
    } else if($cc==5){
   $tabla5 =$tabla;  
   $title5 ='<p style="text-align:center;"><b>'.$aula.' '.$paralelo.'</b> <font size="7" >'.$turno.' '.$nivel.'</font></p>';   
    } else if($cc==6){
   $tabla6 =$tabla;  
   $title6 ='<p style="text-align:center;"><b>'.$aula.' '.$paralelo.'</b> <font size="7" >'.$turno.' '.$nivel.'</font></p>'; 
    } 
    $tablaGeneral='<table ><tr><th  width="32%">'.$title1.'</th><th  width="1%"></th><th width="32%">'.$title2.'</th><th  width="1%"></th><th width="32%">'.$title3.'</th> </tr> <tr><td style="font-size:4pt;">'.$tabla1.'</td><td></td> <td>'.$tabla2.'</td> <td></td> <td>'.$tabla3.'</td></tr>  <tr><td  width="32%">|</td><td  width="1%"></td><td width="32%"></td><td  width="1%"></td></tr> <tr><th  width="32%">'.$title4.'</th><th  width="1%"></th><th width="32%">'.$title5.'</th><th  width="1%"></th><th width="32%">'.$title6.'</th> </tr> <tr><td style="font-size:4pt;">'.$tabla4.'</td><td></td> <td>'.$tabla5.'</td> <td></td> <td>'.$tabla6.'</td></tr></table>';
    
$cct++;//contador de totales 
   $tam=count($aula_paralelos); 
     //var_dump($tam.' '.$cc);  exit();
if($cct==$tam || $cc==6){
   $pdf->writeHTML($tablaGeneral, true, false, false, false, '');
    $cc=0;$tabla1=''; $tabla2=''; $tabla3=''; $title1='';$title2='';$title3='';$tabla4=''; $tabla5=''; $tabla6=''; $title4='';$title5='';$title6='';$tododatos='';$tablaGeneral ='';
    if($cct!=$tam){ 
        $pdf->AddPage(); 
    }
}
    
 












/*if($cc>3){
    $tablaGeneral='<table ><tr><th  width="32%">'.$title1.'</th><th  width="1%"></th><th width="32%">'.$title2.'</th><th  width="1%"></th><th width="32%">'.$title3.'</th> </tr> <tr><td style="font-size:4pt;">'.$tabla1.'</td><td></td> <td>'.$tabla2.'</td> <td></td> <td>'.$tabla3.'</td></tr> </table>';
    $pdf->writeHTML($tablaGeneral, true, false, false, false, ''); 
    $cct++;
    $cc=0;
  }
 
    if($cct>1){ 
	$pdf->AddPage(); 
    $cct=0;
    }  */
    
 } 

 

	// Genera el nombre del archivo
$nombre = 'aula_paralelo_' . date('Y-m-d_H-i-s') . '.pdf';
 
// Cierra y devuelve el fichero pdf
 $pdf->Output($nombre, 'I');

?>