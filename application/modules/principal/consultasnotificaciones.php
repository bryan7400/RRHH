<?php   
 
    //Nombre de dominio 
    $nombre_dominio =$_institution['nombre_dominio'];
    
   // var_dump($_user['id_user']);exit();
    $id_usuario      = $_user['id_user'];
    $ruta_principal  = '../sistema/';
    $gestion         = $_gestion['gestion'];
    $id_gestion      = $_gestion['id_gestion'];
    
    //Identificar la ruta de archivos
    $url_estudiante  = $_institution["url_estudiante"].$nombre_dominio.'/';
    $pv_fecha_limite = $_institution["pv_fecha_limite"];
    $pw_color_prim = $_institution["pw_color_prim"];

    $modo = $db->query("SELECT * FROM cal_modo_calificacion cal WHERE  cal.estado ='A'")->fetch();

    //$fecha_actual = date('Y-m-d');
    $fecha = date('m-d');
    $fecha_actual = $gestion.'-'.$fecha;
    //var_dump($fecha_actual);exit();
    $periodo = $db->query("SELECT * FROM cal_modo_calificacion cal WHERE cal.gestion_id = $id_gestion AND  cal.fecha_final >= '$fecha_actual' AND cal.fecha_inicio <= '$fecha_actual'")->fetch_first();
    $id_modo = $periodo['id_modo_calificacion'];
    //var_dump($id_modo);exit();

    $estudiante=$db->query("SELECT * FROM sys_users ux 
    INNER JOIN sys_persona per ON ux.persona_id=per.id_persona
    INNER JOIN ins_estudiante ie ON ie.persona_id = per.id_persona    
    INNER JOIN ins_inscripcion ins ON ins.estudiante_id=ie.id_estudiante
    INNER JOIN ins_nivel_academico na ON ins.nivel_academico_id = na.id_nivel_academico
    WHERE ux.id_user = $id_usuario AND ins.gestion_id = $id_gestion ")->fetch_first();

    $id_estudiante    = $estudiante['id_estudiante'];
    $id_aula_paralelo = $estudiante['aula_paralelo_id'];
    $id_nivel_academico = $estudiante['nivel_academico_id'];
    $acronimo			= $estudiante['acronimo_nivel'];
    $area             = $estudiante['area'];
    $genero           = $estudiante['genero']; 

    //var_dump($id_aula_paralelo);exit();
    /*$asesor = $db->query("SELECT * FROM ins_aula_paralelo ap
    INNER JOIN ins_aula aula ON ap.aula_id = aula.id_aula
    INNER JOIN ins_paralelo p ON p.id_paralelo=ap.paralelo_id
    INNER JOIN ins_aula a ON a.id_aula=ap.aula_id
    INNER JOIN ins_turno t ON ap.turno_id = t.id_turno
    INNER JOIN ins_nivel_academico na ON a.nivel_academico_id=na.id_nivel_academico
    WHERE ap.id_aula_paralelo = $id_aula_paralelo ")->fetch_first();
    
    $asesor_curso = $db->query("SELECT sp.nombres nombre_asesor, sp.primer_apellido primer_apellido_asesor, sp.segundo_apellido segundo_apellido_asesor FROM pro_asignacion_asesor paa
    INNER JOIN per_asignaciones pa ON paa.asignacion_id = pa.id_asignacion
    INNER JOIN sys_persona sp ON pa.persona_id = sp.id_persona
    WHERE paa.aula_paralelo_id = $id_aula_paralelo")->fetch_first();
 
    $familiar = $db->query("SELECT *
    FROM ins_estudiante_familiar ef
    INNER JOIN ins_familiar f ON ef.familiar_id = f.id_familiar
    INNER JOIN sys_persona p ON f.persona_id=p.id_persona
    WHERE f.estado = 'A' 
    AND ef.estudiante_id = $id_estudiante")->fetch_first();
   

    $rude = $db->query("SELECT e.id_estudiante, e.codigo_estudiante, e.persona_id, p.*,  i.*, ir.*
    FROM ins_estudiante AS e
    LEFT JOIN sys_persona p ON p.id_persona = e.persona_id
    LEFT JOIN ins_inscripcion AS i ON i.estudiante_id = e.id_estudiante
    LEFT JOIN ins_inscripcion_rude AS ir ON ir.ins_estudiante_id = i.estudiante_id
    LEFT JOIN ins_documentos AS d ON d.estudiante_id = e.id_estudiante
    WHERE e.id_estudiante = $id_estudiante AND i.gestion_id = $id_gestion ")->fetch_first();
    
    
     
    $asigancion_docentes = $db->query("SELECT *
    FROM pro_asignacion_docente where aula_paralelo_id=$id_aula_paralelo")->fetch();
     */
?>
    
<?php
$id_user=$_user['id_user'];
$persona_id=$_user['persona_id'];
$persona_actual=$_user['persona_id'];
$rol_id=$_user['rol_id'];
//var_dump($_user);exit();
//$asigancion_docente_id=$asigancion_docente['id_asignacion_docente'];
 
//if (is_post()) {
//    $id_gestion = $_gestion['id_gestion'];
   $accion = $_POST['accion'];      
    
    
    if($accion == "listar_notificaciones_nueva"){
       
        //$id_asignacion_docente = $_POST['id_asignacion_docente']; 
        $id_comunicado= $_POST['id_comunicado']; 
        //$tipo= $_POST['tipo'];//n e   
        
        //var_dump($id_asignacion_docente);exit();
          //ver si ya esta registrado la persona en visto el mensaje
        
        $comunicados = $db->query(" SELECT com.*
        FROM ins_comunicados com WHERE com.id_comunicado= $id_comunicado")->fetch_first();
       
        $tipo=$comunicados['estado_curso'];
        $id_asignacion_docente = $comunicados['asignacion_docente_id']; 
        // var_dump($id_asignacion_docente);exit(); 
        if($tipo=='N'){
            $res = $db->query("SELECT m.* FROM 
        pro_asignacion_docente ad
         INNER JOIN pro_materia m ON m.id_materia = ad.materia_id
          WHERE id_asignacion_docente=$id_asignacion_docente")->fetch_first();
            
        }else if($tipo=='E'){
            $res = $db->query("SELECT c.*
            FROM ext_curso_asignacion ca 
            INNER JOIN ext_curso c ON ca.curso_id = c.id_curso
            WHERE ca.id_curso_asignacion=$id_asignacion_docente")->fetch_first();
            
        }
        
        
      $respt=array(
          'mat'=>$res,
          'com'=>$comunicados,
          );
        //var_dump($respt);exit(); 
         $vista_personas_id=$comunicados['vista_personas_id'];
        $arr_v=explode(',',$vista_personas_id);
         if(!in_array($id_user,$arr_v)){
          //revisar
            $sqlp="UPDATE ins_comunicados
            SET vista_personas_id = CONCAT(vista_personas_id,'$id_user,')
            where id_comunicado= $id_comunicado;";

            $leido=$db->query($sqlp)->execute();  
            
        }
         
         echo json_encode($respt); 
        
    }
    
    if($accion == "listar_notificaciones"){
        $com = array(); 
     
       /*  $sqlAsignacionesdocente='(com.`asignacion_docente_id`=0 or ';
        $estadoarmad=true;
         foreach ($asigancion_docentes as $key => $rows) {
             if($estadoarmad){
                  $sqlAsignacionesdocente.=' com.`asignacion_docente_id`='.$rows['id_asignacion_docente'];
                  $estadoarmad=false;
             }else{
                  $sqlAsignacionesdocente.=' OR com.`asignacion_docente_id`='.$rows['id_asignacion_docente'];
             }
              
         }
        $sqlAsignacionesdocente.=')'; */
        
       //var_dump($id_user);exit();
  
        
         $res = $db->query("SELECT com.*,su.username 
        FROM ins_comunicados com 
         
         LEFT JOIN sys_users su ON su.id_user=com.usuario_registro 
 
            WHERE (com.persona_id LIKE '%,$id_user,%' OR
             com.usuarios LIKE '%,$rol_id,%')AND 
             com.vista_personas_id not LIKE '%,$id_user,%' AND 
           
             com.estado='A' 
             ORDER BY com.fecha_inicio desc
         
             ")->fetch();// estudaintes OR com.grupo='t' OR com.grupo='$genero' docentes and $sqlAsignacionesdocente dhoy y dos defhas atras   com.fecha_final>=date_sub(CURDATE(), INTERVAL 2 DAY) and
        
        
         foreach($res as $val){
             $grupo=$val['grupo'];//tipo todos,si
             $vista_personas_id=$val['vista_personas_id'];
             $usuarios=$val['usuarios'];
             $persona_id=$val['persona_id'];
             
             if($grupo=='t'  || $grupo=='m' || $grupo=='v'){
                 array_push($com,$val);
             }else{
                 
                 $arr_p=explode(',',$persona_id); 
                 $arr_u=explode(',',$usuarios);
                 $arr_v=explode(',',$vista_personas_id);
           
                 if((in_array($id_user,$arr_p) || in_array($rol_id,$arr_u) ) && !in_array($id_user,$arr_v)){
                    array_push($com,$val);
                 }
                
             }
             
         }
         
       //var_dump($com);exit();
        
        echo json_encode($com); 
        
    }
//}
