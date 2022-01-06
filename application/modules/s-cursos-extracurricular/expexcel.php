 
<?php

//var_dump($_institution);die;
require_once libraries . '/phpexcel-2.1/controlador.php';
//$id_gestion = $_gestion['id_gestion'];
//$nombre_gestion = $_gestion['gestion'];
  
    $is_asig=isset($_params[0])?$_params[0]:0;
    $id_curso=isset($_params[1])?$_params[1]:0;
    // var_dump($id_curso);exit();
$fil = 11;//N fila de las celdas
    //var_dump($_REQUEST);exit(); //$sql="";  //$sqlpar="";
       //var_dump($rows1);exit();
    $objPHPExcel = excel_iniciar("plantilla_inscritos_extracurricular.xls");
   // $objPHPExcel = excel_iniciar("plantilla_inscritos_extracurricular.xls");
        
        
    //DATOS DE INSTITUCION
    $unidad=$_institution['nombre']; 
    //$turno=$aulasql['nombre_turno'];
    $lema=$_institution['lema'];
    $direccion=$_institution['direccion'];
    $objPHPExcel->getActiveSheet()->setCellValue( 'C1', $unidad);
    $objPHPExcel->getActiveSheet()->setCellValue( 'c2', $lema);
    $objPHPExcel->getActiveSheet()->setCellValue( 'c3', $direccion);
     $objPHPExcel->getActiveSheet()->setCellValue( 'e3',date('Y-m-d').' '.date('H:i:s'));
    
   if($id_curso!=0){
              $listasignaciones = $db->query("SELECT  per.nombres,per.primer_apellido,per.segundo_apellido,per.foto,cur.nombre_curso,
  asi.*   FROM ext_curso cur
INNER JOIN  ext_curso_asignacion asi ON asi.curso_id=cur.id_curso
INNER JOIN per_asignaciones asid ON asid.id_asignacion=asi.asignacion_id
INNER JOIN sys_persona per ON per.id_persona=asid.persona_id
 WHERE asi.estado='A' AND asi.curso_id=$id_curso ORDER BY asi.habilitado asc")->fetch(); // AND asi.gestion=1 



        foreach($listasignaciones as $cursos )
        {
            //agragamo fecha y titulo del reporte 
            //$fil2=($fil+2);
                $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(30);//ALTURA DE CELDA
            $objPHPExcel->getActiveSheet()->setCellValue( 'c'.$fil, "CURSO: ".$cursos['nombre_curso']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'e'.$fil, "EXPOSITOR: ".$cursos['nombres'].' '.$cursos['primer_apellido'].' '.$cursos['segundo_apellido']);
                $fil=$fil+1; 
                $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(15);//ALTURA DE CELDA
            $objPHPExcel->getActiveSheet()->setCellValue( 'c'.$fil, "FECHA INI: ".$cursos['fecha_inicio']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'E'.$fil, "MODULO: ".$cursos['modulo']);
                $fil=$fil+1; 
                $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(15);//ALTURA DE CELDA
            $objPHPExcel->getActiveSheet()->setCellValue( 'c'.$fil, "HORA: ".$cursos['horario_dia']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'E'.$fil, "CARGA HORARIA: ".$cursos['carga_horaria']);
            
                $fil=$fil+1;  
                $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(5);//ALTURA DE CELDA
                $fil=$fil+1;  
                $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(5);//ALTURA DE CELDA
//agregar titulo
                $fil=$fil+1;  
            $objPHPExcel->getActiveSheet()->setCellValue( 'b'.$fil, "N");
            $objPHPExcel->getActiveSheet()->setCellValue( 'c'.$fil, "NOMBRES");
            $objPHPExcel->getActiveSheet()->setCellValue( 'D'.$fil, "CURSO");
            $objPHPExcel->getActiveSheet()->setCellValue( 'E'.$fil, "FECHA Y HORA INSCRIPCION");
            $objPHPExcel->getActiveSheet()->setCellValue( 'F'.$fil, "TIPO/OBS");
            $objPHPExcel->getActiveSheet()->getStyle('b' . $fil.':f' . $fil)->applyFromArray(
                array(
                   'borders'=>array(
                     'allborders'=>array(
                        'style'=>PHPExcel_Style_Border::BORDER_THIN
                     )

                  )
                ) 
            );
            //fonde ce deldas
    $objPHPExcel->getActiveSheet()->getStyle('B'. $fil.':F'. $fil)->applyFromArray(
        array(
         'fill' => array(
          'type' => PHPExcel_Style_Fill::FILL_SOLID, 
          'color' => array('rgb' => '1E16B3')//9954ff') 
         ) 
        ) 
    );
        //colore de fuente y tipo
    $objPHPExcel->getActiveSheet()->getStyle('B'. $fil.':F'. $fil)->getFont()->setBold(true)->setSize(10) ->getColor()->setRGB('ffffff');
    $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(20);//ALTURA DE CELDA
            $fil=$fil+1; 
    
            
            
            
             
            
            $is_asig=$cursos['id_curso_asignacion'];
            $resinscritos = $db->query("SELECT per.nombres,per.primer_apellido,per.segundo_apellido,per.genero,
        au.nombre_aula,pa.nombre_paralelo,ni.nombre_nivel,
        ins.* FROM ext_curso_inscripcion ins 
        INNER JOIN ins_estudiante est ON est.id_estudiante=ins.estudiante_id
        INNER JOIN sys_persona per ON per.id_persona=est.persona_id

        INNER JOIN ins_inscripcion insc ON insc.estudiante_id=est.id_estudiante
        INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=insc.aula_paralelo_id
        INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
        INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
        INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id


        WHERE ins.curso_asignacion_id=$is_asig and ins.estado='A' ")->fetch();

        $cc=1;
         foreach($resinscritos as $rowins )
        {
            $objPHPExcel->getActiveSheet()->setCellValue('b'.$fil,$cc);$cc=$cc+1;
            $objPHPExcel->getActiveSheet()->setCellValue( 'c'.$fil, $rowins['nombres'].' '.$rowins['primer_apellido'].' '.$rowins['segundo_apellido']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'd'.$fil, $rowins['nombre_aula'].' '.$rowins['nombre_paralelo'].' '.$rowins['nombre_nivel']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'E'.$fil, $rowins['fecha_registro']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'F'.$fil, $rowins['observacion']); 
             //bordes de celda
            $objPHPExcel->getActiveSheet()->getStyle('b' . $fil.':f' . $fil)->applyFromArray(
                array(
                   'borders'=>array(
                     'allborders'=>array(
                        'style'=>PHPExcel_Style_Border::BORDER_THIN
                     )

                  )
                ) 
            );

            $fil=$fil+1; 
        }
            $fil=$fil+1; 

        } 
       
   } else{
         //listdo de estudiantes 
         
         
         
       //DATOS DEL CURSO
       $cursos = $db->query("SELECT  per.nombres,per.primer_apellido,per.segundo_apellido,per.foto,cur.nombre_curso,
          asi.*   FROM ext_curso cur
        INNER JOIN  ext_curso_asignacion asi ON asi.curso_id=cur.id_curso
        INNER JOIN per_asignaciones asid ON asid.id_asignacion=asi.asignacion_id
        INNER JOIN sys_persona per ON per.id_persona=asid.persona_id
         WHERE asi.estado='A' AND asi.id_curso_asignacion=$is_asig ORDER BY asi.habilitado asc ")->fetch_first();// AND asi.gestion=1 

        
             // $id_curso=$_POST['id_curso'];

            //agragamo fecha y titulo del reporte 
            $objPHPExcel->getActiveSheet()->setCellValue( 'F3',date('Y-m-d').' '.date('H:i:s'));
            $objPHPExcel->getActiveSheet()->setCellValue( 'c5', "CURSO: ".$cursos['nombre_curso']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'c6', "FECHA INI: ".$cursos['fecha_inicio']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'c7', "HORA: ".$cursos['horario_dia']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'e5', "EXPOSITOR: ".$cursos['nombres'].' '.$cursos['primer_apellido'].' '.$cursos['segundo_apellido']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'E6', "MODULO: ".$cursos['modulo']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'E7', "CARGA HORARIA: ".$cursos['carga_horaria']);


        //::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
        //RECORRER inscritos
        $resinscritos = $db->query("SELECT ci.id_curso_inscripcion,ci.curso_asignacion_id,e.id_estudiante,p.*,  au.nombre_aula,pa.nombre_paralelo,ni.nombre_nivel,ci.fecha_registro,ci.observacion
                                        FROM ext_curso_asignacion AS ca
                                        INNER JOIN ext_curso_inscripcion AS ci ON ci.curso_asignacion_id = ca.id_curso_asignacion   
                                        INNER JOIN ins_estudiante AS e ON e.id_estudiante = ci.estudiante_id
                                        INNER JOIN sys_persona AS p ON p.id_persona = e.persona_id 
                           
                                                     
                                        INNER JOIN ins_inscripcion insc ON insc.estudiante_id=e.id_estudiante
													INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=insc.aula_paralelo_id
													INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
													INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
													INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
													
                                        WHERE ca.id_curso_asignacion = $is_asig AND ci.estado = 'A'  
                                        GROUP BY ci.id_curso_inscripcion
                                        ORDER BY p.nombres ASC ")->fetch();
      /*  $resinscritos = $db->query("SELECT per.nombres,per.primer_apellido,per.segundo_apellido,per.genero,
        au.nombre_aula,pa.nombre_paralelo,ni.nombre_nivel,
        ins.* FROM ext_curso_inscripcion ins 
        INNER JOIN ins_estudiante est ON est.id_estudiante=ins.estudiante_id
        INNER JOIN sys_persona per ON per.id_persona=est.persona_id

        INNER JOIN ins_inscripcion insc ON insc.estudiante_id=est.id_estudiante
        INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=insc.aula_paralelo_id
        INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
        INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
        INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id


        WHERE ins.curso_asignacion_id=$is_asig and ins.estado='A' ")->fetch();*/

     //var_dump($resinscritos);exit();
        $cc=1;//numeracion de vista 
         foreach($resinscritos as $rowins )
        {

            $objPHPExcel->getActiveSheet()->setCellValue('b'.$fil,$cc);$cc=$cc+1;
            $objPHPExcel->getActiveSheet()->setCellValue( 'c'.$fil, $rowins['nombres'].' '.$rowins['primer_apellido'].' '.$rowins['segundo_apellido']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'd'.$fil, $rowins['nombre_aula'].' '.$rowins['nombre_paralelo'].' '.$rowins['nombre_nivel']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'E'.$fil, $rowins['fecha_registro']);
            $objPHPExcel->getActiveSheet()->setCellValue( 'F'.$fil, $rowins['observacion']); 
             //bordes de celda
            $objPHPExcel->getActiveSheet()->getStyle('b' . $fil.':f' . $fil)->applyFromArray(
                array(
                   'borders'=>array(
                     'allborders'=>array(
                        'style'=>PHPExcel_Style_Border::BORDER_THIN
                     )

                  )
                ) 
            );

            $fil=$fil+1; 
         }
   }
      
  
     //pruebaresp[i]['primer_apellido']+' '+resp[i]['segundo_apellido']+'</td>  <td>3° A sec</td>  <td>'+resp[i]['fecha_registro']+'</td>  <td>'+resp[i]['tipo_inscripcion']+'/'+resp[i]['observacion']
    
    //cabecera turno:::::::::::::::::::::::::::
    //$objPHPExcel->getActiveSheet()->mergeCells('A'. $fil.':I'. $fil);//UNE CELDAS
    //$objPHPExcel->getActiveSheet()->setCellValue( 'A' . $fil, " TURNO  ".$rowtur['nombre_turno']);//ASIGAN VALORES
    //$objPHPExcel->getActiveSheet()->getStyle('A'. $fil.':I'. $fil)->getFont()->setBold(true)->setSize(16) ->getColor()->setRGB('000000');//COLOR Y ESTILO
    //$objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(20);//ALTURA DE CELDA
    //$fil++;//recorremos una fila
   //var_dump('nombre:'.$rowins['nombres'].' '.$rowins['nombres']);
 
////:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//obtenemos el id turno actual
    
 /* $sqlT="    AND tu.id_turno=". $rowtur['id_turno'];
     
   //$objPHPExcel->getActiveSheet()->setCellValue( 'b4',"turn".$turno."gest ".$id_gestion."niv:".$nivel);
     //en cado de envio de variable nivel lo filtraremos la consulta
   $sqlN="";    
if($nivel){
    $sqlN.=" and  ni.id_nivel_academico=$nivel";
  } 
     
$nivelsql = $db->query("SELECT ni.id_nivel_academico,ni.nombre_nivel
   FROM ins_aula_paralelo ap
   INNER JOIN ins_turno tu ON tu.id_turno=ap.turno_id
   INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
   INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
   WHERE ap.estado='A' and au.estado='A' and ni.estado='A' and tu.gestion_id=$id_gestion ".$sqlT." ".$sqlN." GROUP BY ni.id_nivel_academico")->fetch();
    
     

$cc2=1;
 foreach($nivelsql as $rowniv )
{
   //   $objPHPExcel->getActiveSheet()->setCellValue( 'c'.$cc2, $rowniv['nombre_nivel']);$cc2=$cc2+1; 
 //$total = sizeof($nivelsql);
 
   // if ($total > 0) {
    //::::::::cabecera nivel:::::::::::::::::::::::::::
     $objPHPExcel->getActiveSheet()->mergeCells('A'. $fil.':I'. $fil);//UNE CELDAS
       $objPHPExcel->getActiveSheet()->setCellValue( 'A' . $fil, "  NIVEL  ".$rowniv['nombre_nivel']);//ASIGAN VALORES
        $objPHPExcel->getActiveSheet()->getStyle('A'. $fil.':I'. $fil)->getFont()->setBold(true)->setSize(14) ->getColor()->setRGB('7C75FF');//COLOR Y ESTILO
        $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(18);//ALTURA DE CELDA
        $fil++;
   // }
     
     
     
     
     
////:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//generamos la consulta del nivel academico actual
 $sqlN="    AND ni.id_nivel_academico=". $rowniv['id_nivel_academico'];
 //En caso de envio de variable aula lo filtraremos la consulta    
 $sqlcur="";  
if($aula){
     
    $sqlcur.=" and  au.id_aula=$aula"; 
 } 
//RECORRER CURSO DE UN MISMO NIVEL
     
$sqlReporte = "SELECT au.id_aula,au.nombre_aula,ni.id_nivel_academico,ni.nombre_nivel
       FROM ins_aula_paralelo ap
       INNER JOIN ins_turno tu ON tu.id_turno=ap.turno_id
       INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
       INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id

       WHERE ap.estado='A' and au.estado='A' and ni.estado='A' and tu.gestion_id=$id_gestion ".$sqlN." ".$sqlT." ".$sqlcur."
          GROUP BY au.id_aula
       ";
//echo ($sqlReporte);exit();     
$aulasqlr = $db->query($sqlReporte)->fetch();//
 
//$objPHPExcel->getActiveSheet()->setCellValue( 'b4',"gest ".$id_gestion."niv:".$nivel);
$cc1=1;

//recorrer las aulas resultantes
 foreach($aulasqlr as $rowaula )
{
    //$objPHPExcel->getActiveSheet()->setCellValue( 'c'.$cc1, $rowins['id_aula']);$cc1=$cc1+1; 
      

  //$totalf = sizeof($aulasqlr);
 
    //if ($aulasqlr) {
     
$id_aula=$rowaula['id_aula']; 
$sqlcur="  and  au.id_aula=".$id_aula;//.2; 
     
 //cabecera nivel:::::::::::::::::::::::::::
$objPHPExcel->getActiveSheet()->mergeCells('A'. $fil.':I'. $fil);//UNE CELDAS
       $objPHPExcel->getActiveSheet()->setCellValue( 'A' . $fil, "  AULA  ".$rowaula['nombre_aula']);//ASIGAN VALORES
        $objPHPExcel->getActiveSheet()->getStyle('A'. $fil.':I'. $fil)->getFont()->setBold(true)->setSize(12) ->getColor()->setRGB('1E16B3');//COLOR Y ESTILO
        $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(15);//ALTURA DE CELDA
        $fil++;
  //  }
//en caso de recibir la variable paralelo
   $sqlpar="";     
if($paralelo){
     $sqlpar.=" and pa.id_paralelo=$paralelo"; 
 } 


//recorrer los paralelos toods o (de un aula)
$hola="SELECT pa.id_paralelo,pa.nombre_paralelo,au.nombre_aula FROM ins_inscripcion i
 INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=i.aula_paralelo_id
  INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id 
  INNER JOIN ins_turno tu ON tu.id_turno=ap.turno_id
  INNER JOIN ins_aula au ON au.id_aula=ap.aula_id  
  INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
  
  where tu.gestion_id=$id_gestion ".$sqlcur." ".$sqlN." ".$sqlT." ".$sqlpar."
   GROUP BY pa.nombre_paralelo";
$paralelosql = $db->query($hola)->fetch();// ".$sql0." 
     
    //$objPHPExcel->getActiveSheet()->setCellValue( 'c'.$fil, $rowpar['id_paralelo'].$hola);$fil++; 
     
     
 $cc=1;    

     //recorrer los paralelos resultantes
 foreach($paralelosql as $rowpar )
{

    $sqlpar="";
    //$objPHPExcel->getActiveSheet()->setCellValue( 'c'.$fil, $rowpar['id_paralelo'].$hola); $cc=$cc+1;
     
     $id_paralelo=$rowpar['id_paralelo'];
    
    $sqlpar=" and  pa.id_paralelo=".$id_paralelo;//.2; 




//tablas inner
$select_iner=" FROM ins_inscripcion z
     INNER JOIN ins_gestion g ON z.gestion_id=g.id_gestion  
    INNER JOIN ins_estudiante e ON z.estudiante_id=e.id_estudiante
    INNER JOIN sys_persona p ON e.persona_id=p.id_persona  
    INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=z.aula_paralelo_id  
    INNER JOIN ins_aula au ON au.id_aula=ap.aula_id  
    INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
    INNER JOIN ins_turno tu ON tu.id_turno=ap.turno_id
    INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
    INNER JOIN ins_tipo_estudiante te ON te.id_tipo_estudiante=z.tipo_estudiante_id
    LEFT JOIN 
    (SELECT GROUP_CONCAT( CONCAT(pp.nombres,' ', pp.primer_apellido,' ', pp.segundo_apellido) SEPARATOR ' | ') AS nombres_familiar, 
    GROUP_CONCAT(f.telefono_oficina SEPARATOR ' | ') AS contacto, ef.estudiante_id
    FROM ins_familiar f 
    INNER JOIN sys_persona pp ON f.persona_id=pp.id_persona
    INNER JOIN ins_estudiante_familiar ef ON ef.familiar_id=f.id_familiar
    GROUP BY ef.estudiante_id
    ) f ON e.id_estudiante=f.estudiante_id";
	// Obtiene los aula_paralelo
	$aula_paralelo = $db->query("SELECT e.*,z.*,p.numero_documento,ap.*,au.*,pa.*,ni.*,te.*,tu.*, CONCAT(p.primer_apellido,' ',p.segundo_apellido,' ',p.nombres) nombre_completo, f.* ".$select_iner." WHERE z.gestion_id=".$id_gestion." ".$sqlcur." ".$sqlN." ".$sqlT." ".$sqlpar." 
    AND z.estado='A' 
        AND g.estado='A'
        AND ap.estado='A'
        AND pa.estado_paralelo='A'
        AND au.estado='A'
        AND ni.estado='A'
    
    ORDER BY tu.id_turno ASC,ni.id_nivel_academico ASC,ni.id_nivel_academico ASC,au.id_aula ASC,pa.id_paralelo ASC,
    p.primer_apellido ASC")->fetch();//" ".$sql.



$total_instritos = $db->query("SELECT COUNT(z.id_inscripcion)as total
   ".$select_iner."
    WHERE z.gestion_id=".$id_gestion." ".$sqlcur." ".$sqlN." ".$sqlT." ".$sqlpar."
    AND z.estado='A' 
        AND g.estado='A'
        AND ap.estado='A'
        AND pa.estado_paralelo='A'
        AND au.estado='A'
        AND ni.estado='A'
    ORDER BY 
    p.primer_apellido ASC")->fetch();

$total_m = $db->query("SELECT COUNT(z.id_inscripcion)as total
   ".$select_iner."
    WHERE z.gestion_id=".$id_gestion." ".$sqlcur." ".$sqlN." ".$sqlT." ".$sqlpar."
    AND p.genero='m'
        AND z.estado='A' 
        AND g.estado='A'
        AND ap.estado='A'
        AND pa.estado_paralelo='A'
        AND au.estado='A'
        AND ni.estado='A'
    ORDER BY 
    p.primer_apellido ASC")->fetch();

$total_v = $db->query("SELECT COUNT(z.id_inscripcion)as total
        ".$select_iner."
    WHERE z.gestion_id=".$id_gestion." ".$sqlcur." ".$sqlN." ".$sqlT." ".$sqlpar."
    AND p.genero='v'
        AND z.estado='A' 
        AND g.estado='A'
        AND ap.estado='A'
        AND pa.estado_paralelo='A'
        AND au.estado='A'
        AND ni.estado='A'
    ORDER BY 
    p.primer_apellido ASC")->fetch();


    $total = sizeof($aula_paralelo);


    //si hay registros, colocar datos en las celdas de la hoja actual

    if ($total > 0) {
 
        
$num=1;
    
    //::::::::::::::::::::::TITULO:::::::::::::::::
        $objPHPExcel->getActiveSheet()->mergeCells('A'. $fil.':J'. $fil);//UNE CELDAS
       $objPHPExcel->getActiveSheet()->setCellValue( 'A' . $fil, "      - PARALELO ".$rowpar['nombre_paralelo']);//ASIGAN VALORES
        $objPHPExcel->getActiveSheet()->getStyle('A'. $fil.':J'. $fil)->getFont()->setBold(true)->setSize(12) ->getColor()->setRGB('1E16B3');//COLOR Y ESTILO
        $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(24);//ALTURA DE CELDA
        $fil++;
        
    //::::::::::::::::::::::TOTALES:::::::::::::::::
        foreach ($total_instritos as $nro => $total_instritos) {
            $objPHPExcel->getActiveSheet()->setCellValue( 'c' . $fil, "Total Inscritos");//('A10', "X"); 
            $objPHPExcel->getActiveSheet()->setCellValue( 'd' . $fil, escape($total_instritos['total']));//('A10', "X"); 
        }
         $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(14);
        $fil++;
        foreach ($total_m as $nro => $total_m) {

            $objPHPExcel->getActiveSheet()->setCellValue( 'c' . $fil, "Total Mujeres");
            $objPHPExcel->getActiveSheet()->setCellValue( 'd' . $fil, escape($total_m['total']));//('A10', "X"); 
        }
        $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(14);
        $fil++;
        foreach ($total_v as $nro => $total_v) {
            $objPHPExcel->getActiveSheet()->setCellValue( 'c' . $fil, "Total Varones");
              $objPHPExcel->getActiveSheet()->setCellValue( 'd' . $fil, escape($total_v['total']));//('A10', "X"); 
        } 
        $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(14);
        $fil++;
        $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(8);
        $fil++;
         
        
//::::::::::::: PARTE CABECERA:::::::::::::::::::
    //fonde ce deldas
    $objPHPExcel->getActiveSheet()->getStyle('A'. $fil.':J'. $fil)->applyFromArray(
        array(
         'fill' => array(
          'type' => PHPExcel_Style_Fill::FILL_SOLID, 
          'color' => array('rgb' => '1E16B3')//9954ff') 
         ) 
        ) 
    );
        //colore de fuente y tipo
    $objPHPExcel->getActiveSheet()->getStyle('A'. $fil.':J'. $fil)->getFont()->setBold(true)->setSize(10) ->getColor()->setRGB('ffffff');
        
        //cabecera de tabla
    $objPHPExcel->getActiveSheet()->setCellValue( 'b' . $fil, "COD COMPLETO");
    $objPHPExcel->getActiveSheet()->setCellValue( 'C' . $fil, "NOMBRE");
    $objPHPExcel->getActiveSheet()->setCellValue( 'D' . $fil, "DOCUMENTO");
    $objPHPExcel->getActiveSheet()->setCellValue( 'E' . $fil, "NIVEL");
    $objPHPExcel->getActiveSheet()->setCellValue( 'F' . $fil, "CURSO");
    $objPHPExcel->getActiveSheet()->setCellValue( 'G' . $fil, "PARALELO");
    $objPHPExcel->getActiveSheet()->setCellValue( 'H' . $fil, "TIPO");
    $objPHPExcel->getActiveSheet()->setCellValue( 'I' . $fil, "TUTOR");
    $objPHPExcel->getActiveSheet()->setCellValue( 'J' . $fil, "TEL");
    $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(15);
        $fil++;
        
//::::::::::::::::CUERPO:::::::::::::::::::
        
        foreach($aula_paralelo as $row )
        {
             $objPHPExcel->getActiveSheet()->setCellValue( 'A' . $fil, $num);
             $objPHPExcel->getActiveSheet()->setCellValue( 'B' . $fil, escape($row['codigo_estudiante']));
             $objPHPExcel->getActiveSheet()->setCellValue( 'C' . $fil, escape($row['nombre_completo']));//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'D' . $fil, $row['numero_documento']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'E' . $fil, $row['nombre_nivel']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'F' . $fil, $row['nombre_aula'].' '.$row['nombre_paralelo']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'G' . $fil, $row['nombre_paralelo']);//$rowins['id_paralelo']);//$row['nombre_paralelo']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'H' . $fil, $row['nombre_tipo_estudiante']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'I' . $fil, $row['nombres_familiar']);//('A10', "X");
             $objPHPExcel->getActiveSheet()->setCellValue( 'J' . $fil, $row['contacto']);//('A10', "X");
            $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(30);//alto de celda
            //bordes de celda
            $objPHPExcel->getActiveSheet()->getStyle('a' . $fil.':j' . $fil)->applyFromArray(
                array(
                   'borders'=>array(
                     'allborders'=>array(
                        'style'=>PHPExcel_Style_Border::BORDER_THIN
                     )

                  )
                ) 
            );
            $fil++;
            $num++;
        }
        $objPHPExcel->getActiveSheet()->getRowDimension($fil)->setRowHeight(20); $fil++;
       
         

    }
     
 }//fon for paralelo
        
     
 }//fin for aulassql

 }//for final de nivelsql
     */
     
 //}//fin each turnosql     
//exit(); 
    //exit;     
    //-------------------------------------------------- finalizar
    //mostrar la primera hoja de excel
    //seleccionar una hoja
    $objPHPExcel->setActiveSheetIndex(0);   //la primera Hoja de excel se numera como 0
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '');
    excel_finalizar($objPHPExcel, "inscritoscurso.xls");
//}
 

?>