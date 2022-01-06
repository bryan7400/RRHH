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
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	


//:::::::::::::::::::::::::::::::: TITULO   :::::::::::::::::::::::::::::
	$aula_paralelo = $db->query("SELECT * FROM ins_aula_paralelo  ap,ins_aula au,ins_paralelo pa ,ins_turno tu, ins_nivel_academico ni
WHERE ap.aula_id=au.id_aula AND pa.id_paralelo=ap.paralelo_id
AND ap.turno_id=tu.id_turno AND au.nivel_academico_id=ni.id_nivel_academico 
AND ap.id_aula_paralelo=".$id_aula_p)->fetch();//->first()
foreach ($aula_paralelo as $nro => $aula_paralelo) {
 
    $turno=escape($aula_paralelo['nombre_turno']) ;
    $nivel=escape($aula_paralelo['nombre_nivel']);
    $aula=escape($aula_paralelo['nombre_aula']);
    $paralelo=escape($aula_paralelo['estado_paralelo']);
	}
 $num=1;
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'HORARIO '.$nombre_gestion, 0, true, 'C', false, '', 0, false, 'T', 'M');
 
//:::::::::::::::::::::::::::::::: contenido 1  :::::::::::::::::::::::::::::
$textitle = '<h1 style="text-align:center;">'.$nivel.'</h1>'; 
$textitle .= '<h4 style="text-align:center;">'.$aula.' '.$paralelo.'</h4>'; 
 
// output the HTML content
 $pdf->writeHTML($textitle, true, false, true, false, '');

//:::::::::::::::::::::::::::::::: tabla  :::::::::::::::::::::::::::::

// add a page
// $pdf->AddPage();



$consulta="SELECT * FROM ins_horario_dia where estado='A' and turno_id='$id_turno' ORDER BY hora_ini ASC";    // ORDER BY p.primer_apellido ASC
    $inscritos = $db->query($consulta)->fetch();
$body='';
foreach($inscritos as $elemento){
     $hora_id=$elemento['id_horario_dia']; 
     //echo('hora'.':__________'.$hora_id.'<br>'); 
      //  echo escape($elemento['hora_ini']).'-'.escape($elemento['hora_fin']).'<br>' ;
    //estraer la 
     $sql="";
     /*if($id_docente){
        $sql.=" and  z.profesor_id=$id_docente";
    }*/
    
        if($id_aula_p){
        $sql.=" and  apam.`aula_paralelo_id`=$id_aula_p";
    }
     $consulta="
	SELECT hd.*,z.*,m.nombre_materia,hd.complemento,

(SELECT GROUP_CONCAT(CONCAT(per.nombres ,' ', per.primer_apellido,' ', per.segundo_apellido) 
SEPARATOR ' | ')AS nombres_completo FROM `per_asignaciones` asi,sys_persona per WHERE asi.persona_id=per.id_persona AND apam.asignacion_id=asi.`id_asignacion`)AS nombres_doc

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
/*    if($id_aula_p){
        $sql.=" and  z.curso_paralelo_id=$id_aula_p";
    }
     $consulta="
	SELECT hd.*,z.*,m.nombre_materia,hd.complemento, 
	(SELECT GROUP_CONCAT(CONCAT(per.nombres ,' ', per.primer_apellido,' ', 
	per.segundo_apellido) SEPARATOR ' | ')AS nombres_completo 
	FROM pro_profesor pro,sys_persona per 
	WHERE pro.persona_id=per.id_persona 
	AND z.profesor_id=pro.id_profesor )AS nombres_doc 
	FROM ins_horario_profesor_materia z, 
	ins_aula_paralelo b, ins_aula c, 
	ins_nivel_academico d, ins_paralelo e, ins_turno tu, 
	pro_materia m, ins_horario_dia hd,
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
     
    $horaini= $elemento["hora_ini"].' - '.$elemento["hora_fin"] ;
    foreach($listatodo as $elemento){
        
        if($elemento['nombres_doc']==null){
            $datoscelda='--'.escape($elemento['nombre_materia']).'--'.' (  Sin docente asignado)';
             
        }else{
            $datoscelda='--'.escape($elemento['nombre_materia']).'--'.' ('.escape($elemento['nombres_doc']).')'; 
        }
        
         

        
       //$body.='<tr>';
        //$body.='<td>'.$num.'</td>';
        //$objPHPExcel->getActiveSheet()->setCellValue( 'a' . $fil, $num);
        //$body.='<td>'.$elemento["hora_ini"].' - '.$elemento["hora_fin"].'</td>';
        //$objPHPExcel->getActiveSheet()->setCellValue( 'b' . $fil, $elemento["hora_ini"].' - '.$elemento["hora_fin"]);
        
        
        if($elemento["dia_semana_id"]==1){
            $lunes=$datoscelda;
    //echo('-  lunes:'.$lunes.'');
       //$lunes= $elemento["nombre_materia"]; echo $lunes;
       //$objPHPExcel->getActiveSheet()->setCellValue( 'c' . $fil, $datoscelda);
        }else{
           // $lunes='<td></td>';
            //$body.='<td></td>'; 
        }
        
        if($elemento["dia_semana_id"]==2){
            $martes=$datoscelda;
            // echo($martes.':__________');
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'd' . $fil,  $datoscelda);
        } else{
            //$martes='<td></td>';
            //$body.='<td></td>'; 
        }
        
        if($elemento["dia_semana_id"]==3){$miercoles= $datoscelda;
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'E' . $fil, $datoscelda);
        } else{
           // $miercoles='<td></td>';
           // $body.='<td></td>'; 
        }
        
        if($elemento["dia_semana_id"]==4){$jueves=$datoscelda;
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'F' . $fil, $datoscelda);
        } else{
           // $body.='<td></td>'; 
        }if($elemento["dia_semana_id"]==5){$viernes=$datoscelda;
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'G' . $fil, $datoscelda);
        } else{
           // $lunes='<td></td>';
           // $body.='<td></td>'; 
        }if($elemento["dia_semana_id"]==6){$sabado=$datoscelda;
       //$martes= $elemento["nombre_materia"]; echo $martes;
        //$objPHPExcel->getActiveSheet()->setCellValue( 'H' . $fil, $datoscelda);
        } else{
            //$sabado='<td></td>';
           // $body.='<td></td>'; 
        }
       
        //$body.='</tr>';
        // echo escape($elemento['hora_ini']).'-'.escape($elemento['nombre_materia']) ;
        
    //echo($num.$horaini.$lunes.$martes.$miercoles.$jueves.$viernes.$sabado);exit();
        $cc=1;
    }

    if($lunes=='')
       $lunes='<td></td>';
    else
       $lunes='<td style="background: #a3ffcc;">'.$lunes.'</td>';   
    if($martes=='')
       $martes='<td></td>';
    else
       $martes='<td style="background: #a3ffcc;">'.$martes.'</td>';
    if($miercoles=='')
       $miercoles='<td></td>';
    else
       $miercoles='<td style="background: #a3ffcc;">'.$miercoles.'</td>';
    if($jueves=='')
       $jueves='<td></td>';
    else
       $jueves='<td style="background: #a3ffcc;">'.$jueves.'</td>';
        if($viernes=='')
       $viernes='<td></td>';
    else
       $viernes='<td style="background: #a3ffcc;">'.$viernes.'</td>';
    if($sabado=='')
       $sabado='<td></td>';
    else
       $sabado='<td style="background: #a3ffcc;">'.$sabado.'</td>';
    
    
/*    echo('-  lunes:'.$lunes.'');
    echo('-  martes:'.$martes.'');
    echo('-  miercoles:'.$miercoles.'');*/
    
    
    
    
    
   // unir a imprimir
    
    //$body.='<tr><td></td><td></td>'.$lunes.'<td></td><td></td><td></td><td></td><td></td> </tr>';

    //_________imprimir recreo________
    if($elemento["complemento"]=='descanso'){
        //fonde ce deldas
 
        $body.='<tr><td></td><td>'.$horaini.'</td><td colspan="6">RECREO</td></tr>';
        
       // $cc=1;
    }else{
         $body.='<tr><td>'.$num.'</td><td>'.$horaini.'</td>'.$lunes.$martes.$miercoles.$jueves.$viernes.$sabado.' </tr>';$num++; 
    }  
}
  

 $tabla = $style;
//$tabla .=$textitle;
	$tabla .= '<table cellpadding="5" border="1" style="text-align:center">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="5%">#</th>';
	$tabla .= '<th width="17%"><h3>HORARIO</h3></th>';
	$tabla .= '<th width="13%"><h3>LUNES</h3></th>';
	$tabla .= '<th width="13%"><h3>MARTES</h3></th>';
	$tabla .= '<th width="13%"><h3>MIERCOLES</h3></th>';
	$tabla .= '<th width="13%"><h3>JUEVES</h3></th> '; 	 
	$tabla .= '<th width="13%"><h3>VIERNES</h3></th> '; 	 
	$tabla .= '<th width="13%"><h3>SABADO</h3></th> '; 	 
	$tabla .= '</tr>';
	 $tabla .= $body;
	$tabla .= '</table>';
   // echo $tabla;

//_________convertir a pdf________
$pdf->writeHTML($tabla, true, false, false, false, ''); 
	// Genera el nombre del archivo
$nombre = 'aula_paralelo_' . date('Y-m-d_H-i-s') . '.pdf';
 
// Cierra y devuelve el fichero pdf
 $pdf->Output($nombre, 'I');

?>