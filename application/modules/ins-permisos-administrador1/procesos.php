<?php

$id_gestion=$_gestion['id_gestion'];
// Verifica la peticion post
if (is_post()) {
    
    $accion = $_POST['accion']; 
     

    //obtiene la fecha actual del sistema


    if ($accion == "listar_familiares") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    
        $id_estudiante = $_POST['estudiante_id'];
    
     

          $informacion_estudiante = $db->query("SELECT * FROM  ins_estudiante_familiar ief
INNER JOIN ins_familiar ie ON ie.id_familiar=ief.familiar_id
INNER JOIN sys_persona  per ON per.id_persona=ie.persona_id
INNER JOIN sys_users su ON su.persona_id = per.id_persona

WHERE ief.estudiante_id =  $id_estudiante  ")->fetch(); 
  


echo json_encode($informacion_estudiante); 
 }


    




    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 

        
        if ($accion == "listar_materias") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;


$id_gestion=$_gestion['id_gestion'];

$id_estudiante = $_POST['estudiante_id'];

$hijos = $db->query("SELECT e.id_estudiante,ins.id_inscripcion,ins.aula_paralelo_id,au.nombre_aula,pa.nombre_paralelo,ni.nombre_nivel,tu.nombre_turno, per.id_persona, per.nombres, per.primer_apellido, per.segundo_apellido, per.tipo_documento, per.numero_documento, per.complemento, per.expedido, per.genero, per.fecha_nacimiento, per.direccion, IF(per.foto != 'NULL', IF(per.foto !='',per.foto,''),'')AS foto,f.id_familiar, su.id_user, su.rol_id
        from ins_estudiante_familiar ef INNER JOIN ins_familiar  f ON ef.familiar_id=f.id_familiar
        INNER JOIN ins_estudiante  e ON e.id_estudiante=ef.estudiante_id
        INNER JOIN sys_persona  per ON per.id_persona=e.persona_id
        INNER JOIN sys_users su ON su.persona_id = per.id_persona
        LEFT  JOIN ins_inscripcion ins ON ins.estudiante_id  = e.id_estudiante
        LEFT  JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo  = ins.aula_paralelo_id
        INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
        INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
        INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
        INNER JOIN ins_turno tu ON tu.id_turno=ap.turno_id
        WHERE id_estudiante=$id_estudiante AND ins.estado = 'A' AND ins.gestion_id = $id_gestion AND su.estado = 'A' AND su.visible = 's'")->fetch_first();  
    
      
    
    $aula = $hijos["aula_paralelo_id"];





         $sql="";
    
    if($aula){
        $sql.=" and  apam.aula_paralelo_id=$aula";
    }
 


$consulta="
SELECT d.*, hd.*, z.*, m.*,hd.`complemento`,apam.*,

    (SELECT GROUP_CONCAT(CONCAT(per.nombres ,' ', per.primer_apellido,' ', per.segundo_apellido)
    SEPARATOR ' | ')AS nombres_completo  
    FROM  per_asignaciones asig,sys_persona  per
    WHERE 
    asig.persona_id=per.id_persona AND
    apam.asignacion_id=asig.id_asignacion 
    )AS nombres_doc,
    
    (SELECT GROUP_CONCAT(CONCAT(SUBSTRING(per.nombres, 1, 1),' ', SUBSTRING(per.primer_apellido, 1, 1),' ', SUBSTRING(per.segundo_apellido, 1, 1))
    SEPARATOR ' | ')AS nombres_completo  
    FROM  per_asignaciones asig,sys_persona  per
    WHERE 
    asig.persona_id=per.id_persona AND
    apam.asignacion_id=asig.id_asignacion  
    )AS iniciales


  FROM ins_horario_profesor_materia z,
  
    ins_aula_paralelo b,
    ins_aula c,
    ins_nivel_academico d,
    ins_paralelo e,
    ins_turno tu,
    pro_materia m,
    ins_horario_dia hd,
    ins_gestion ge,
    int_aula_paralelo_asignacion_materia apam
WHERE apam.aula_paralelo_id=b.id_aula_paralelo AND 
    tu.id_turno=b.turno_id AND
    b.aula_id=c.id_aula AND
    c.nivel_academico_id= d.id_nivel_academico AND
    b.paralelo_id= e.id_paralelo   AND
    apam.`materia_id`=m.`id_materia` AND
    z.`horario_dia_id`=hd.`id_horario_dia` AND 
    apam.`id_aula_paralelo_asignacion_materia`=z.`aula_paralelo_asignacion_materia_id` AND 
     
    z.estado='A'AND 
    b.estado='A' AND
    ge.estado='A'AND 
    c.estado='A'AND 
    e.estado_paralelo='A' AND 
    tu.estado='A'AND 
    d.estado='A'AND
    hd.estado='A'AND
    m.estado='A' AND
    apam.estado='A'
     
     ".$sql." AND
   z.gestion_id=$id_gestion 
   GROUP BY `id_aula_paralelo_asignacion_materia`
    ORDER BY z.`horario_dia_id`  
 ";   

 $inscritos = $db->query($consulta)->fetch();



$informacion_estudiante = $db->query("SELECT *,CONCAT(id_curso,'e') as id_materia, nombre_curso AS nombre_materia FROM ext_curso_inscripcion eci
INNER JOIN ext_curso_asignacion eca ON eca.id_curso_asignacion = eci.curso_asignacion_id
INNER JOIN ext_curso ec ON ec.id_curso = eca.curso_id
WHERE eca.estado = 'A' 
AND estudiante_id = $id_estudiante")->fetch();


$array_final = array_merge($inscritos, $informacion_estudiante);

echo json_encode($array_final);



    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 
}



        if ($accion == "listar_horarios") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;


$id_gestion=$_gestion['id_gestion'];

$id_estudiante = $_POST['estudiante_id'];



$turno_estudiante = $db->query("SELECT * FROM ins_inscripcion 

WHERE estudiante_id = $id_estudiante ")->fetch_first();
    
    $turno = $turno_estudiante["turno_id"];





 

$informacion_estudiante = $db->query("SELECT * FROM ins_horario_dia 

WHERE turno_id = '$turno' ")->fetch();



echo json_encode($informacion_estudiante);


}



      if ($accion == "listar_todas_materias") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;


$id_gestion=$_gestion['id_gestion'];

$id_estudiante = $_POST['estudiante_id'];

$hijos = $db->query("SELECT e.id_estudiante,ins.id_inscripcion,ins.aula_paralelo_id,au.nombre_aula,pa.nombre_paralelo,ni.nombre_nivel,tu.nombre_turno, per.id_persona, per.nombres, per.primer_apellido, per.segundo_apellido, per.tipo_documento, per.numero_documento, per.complemento, per.expedido, per.genero, per.fecha_nacimiento, per.direccion, IF(per.foto != 'NULL', IF(per.foto !='',per.foto,''),'')AS foto,f.id_familiar, su.id_user, su.rol_id
        from ins_estudiante_familiar ef INNER JOIN ins_familiar  f ON ef.familiar_id=f.id_familiar
        INNER JOIN ins_estudiante  e ON e.id_estudiante=ef.estudiante_id
        INNER JOIN sys_persona  per ON per.id_persona=e.persona_id
        INNER JOIN sys_users su ON su.persona_id = per.id_persona
        LEFT  JOIN ins_inscripcion ins ON ins.estudiante_id  = e.id_estudiante
        LEFT  JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo  = ins.aula_paralelo_id
        INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
        INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
        INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
        INNER JOIN ins_turno tu ON tu.id_turno=ap.turno_id
        WHERE id_estudiante=$id_estudiante AND ins.estado = 'A' AND ins.gestion_id = $id_gestion AND su.estado = 'A' AND su.visible = 's'")->fetch_first();  
    
      
    
    $aula = $hijos["aula_paralelo_id"];





         $sql="";
    
    if($aula){
        $sql.=" and  apam.aula_paralelo_id=$aula";
    }
 


$consulta="
SELECT d.*, hd.*, z.*, m.*,hd.`complemento`,apam.*,

    (SELECT GROUP_CONCAT(CONCAT(per.nombres ,' ', per.primer_apellido,' ', per.segundo_apellido)
    SEPARATOR ' | ')AS nombres_completo  
    FROM  per_asignaciones asig,sys_persona  per
    WHERE 
    asig.persona_id=per.id_persona AND
    apam.asignacion_id=asig.id_asignacion 
    )AS nombres_doc,
    
    (SELECT GROUP_CONCAT(CONCAT(SUBSTRING(per.nombres, 1, 1),' ', SUBSTRING(per.primer_apellido, 1, 1),' ', SUBSTRING(per.segundo_apellido, 1, 1))
    SEPARATOR ' | ')AS nombres_completo  
    FROM  per_asignaciones asig,sys_persona  per
    WHERE 
    asig.persona_id=per.id_persona AND
    apam.asignacion_id=asig.id_asignacion  
    )AS iniciales


  FROM ins_horario_profesor_materia z,
  
    ins_aula_paralelo b,
    ins_aula c,
    ins_nivel_academico d,
    ins_paralelo e,
    ins_turno tu,
    pro_materia m,
    ins_horario_dia hd,
    ins_gestion ge,
    int_aula_paralelo_asignacion_materia apam
WHERE apam.aula_paralelo_id=b.id_aula_paralelo AND 
    tu.id_turno=b.turno_id AND
    b.aula_id=c.id_aula AND
    c.nivel_academico_id= d.id_nivel_academico AND
    b.paralelo_id= e.id_paralelo   AND
    apam.`materia_id`=m.`id_materia` AND
    z.`horario_dia_id`=hd.`id_horario_dia` AND 
    apam.`id_aula_paralelo_asignacion_materia`=z.`aula_paralelo_asignacion_materia_id` AND 
     
    z.estado='A'AND 
    b.estado='A' AND
    ge.estado='A'AND 
    c.estado='A'AND 
    e.estado_paralelo='A' AND 
    tu.estado='A'AND 
    d.estado='A'AND
    hd.estado='A'AND
    m.estado='A' AND
    apam.estado='A'
     
     ".$sql." AND
   z.gestion_id=$id_gestion 
   GROUP BY `id_aula_paralelo_asignacion_materia`
    ORDER BY z.`horario_dia_id`  
 ";   

 $inscritos = $db->query($consulta)->fetch();



$informacion_estudiante = $db->query("SELECT *, nombre_curso AS nombre_materia, CONCAT(id_curso,'e') as id_materia FROM ext_curso_inscripcion eci
INNER JOIN ext_curso_asignacion eca ON eca.id_curso_asignacion = eci.curso_asignacion_id
INNER JOIN ext_curso ec ON ec.id_curso = eca.curso_id
WHERE eca.estado = 'A' 
AND estudiante_id = $id_estudiante")->fetch();



$materias_ = '';
foreach($inscritos as $key => $array_fin){ 

        $materias_ .= $array_fin['id_materia'].','; 
        }



$materias_extra = '';
foreach($informacion_estudiante as $key => $array_fin){ 

        $materias_extra .= $array_fin['id_materia'].','; 
        }



$materias_juntas=$materias_.$materias_extra;
$materias = array(
                
                'materias' => $materias_juntas
            );

 
}



   if ($accion == "listar_todos_horarios") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;


$id_gestion=$_gestion['id_gestion'];

$id_estudiante = $_POST['estudiante_id'];



$turno_estudiante = $db->query("SELECT * FROM ins_inscripcion 

WHERE estudiante_id = $id_estudiante ")->fetch_first();
    
    $turno = $turno_estudiante["turno_id"];





 

$informacion_estudiante = $db->query("SELECT * FROM ins_horario_dia 

WHERE turno_id = '$turno' ")->fetch();


$vacunas = '';
foreach($informacion_estudiante as $key => $informacion_estudiant){ 

        $vacunas .= $informacion_estudiant['id_horario_dia'].','; 
        }


$horarios = array(
                
                'horarios' => $vacunas
            );



echo json_encode($horarios);


}





   if($accion == "recuperar_datos"){
        $id_externo = $_POST['id_permiso'];
        $cliente = $db->query("SELECT * FROM ins_permisos WHERE id_permiso='$id_externo'")->fetch_first(); 
       

        echo json_encode($cliente); 
    }

 if($accion == "aprobar_permiso"){
        
      
        $id_permiso = (isset($_POST['id_permiso'])) ? $_POST['id_permiso'] : 0;
        // Obtiene el area
        $cliente = $db->from('ins_permisos')->where('id_permiso', $id_permiso)->fetch_first();
        
        
        
        
        // Verifica si existe el area
        if ($cliente> 0) {
            // Elimina el area
            //$db->delete()->from('ins_permisos')->where('id_permiso', $id_permiso)->limit(1)->execute();
            //obtiene la fecha actual
            $fecha_actual = date('Y-m-d H:i:s');

            //ejecuta la eliminacion logica de la gestion escolar
            $db->query("UPDATE ins_permisos SET seguimiento_permiso = 'APROBADO', 
                usuario_modificacion = '".$_user['id_user']."', 
                fecha_modificacion = '".$fecha_actual."' 
                WHERE id_permiso = '".$id_permiso."'")->execute();
            
            // Verifica la eliminacion
            if ($db->affected_rows) {
                // Guarda el proceso
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'd',
                    'nivel' => 'm',
                    'detalle' => 'Se eliminó el Cliente con identificador número ' . $id_permiso . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
                ));
                
                // Crea la notificacion
                //set_notification('success', 'Eliminación exitosa!', 'El registro se eliminó satisfactoriamente.');
            } else {
                // Crea la notificacion
                //set_notification('danger', 'Eliminación fallida!', 'El registro no pudo ser eliminado.');
            }
            
            // Redirecciona la pagina
            //redirect('?/area/listar');
            echo 1; //se elimino correctamente
        } else {
            // Error 400
            // require_once bad_request();
            // exit;
            echo 2; //no se encontro el registro que se quiere eliminar
        }

    }



if($accion == "rechazar_permiso"){
        
      
        $id_permiso = (isset($_POST['id_permiso'])) ? $_POST['id_permiso'] : 0;
        // Obtiene el area
        $cliente = $db->from('ins_permisos')->where('id_permiso', $id_permiso)->fetch_first();
        
        
        
        
        // Verifica si existe el area
        if ($cliente> 0) {
            // Elimina el area
            //$db->delete()->from('ins_permisos')->where('id_permiso', $id_permiso)->limit(1)->execute();
            //obtiene la fecha actual
            $fecha_actual = date('Y-m-d H:i:s');

            //ejecuta la eliminacion logica de la gestion escolar
            $db->query("UPDATE ins_permisos SET seguimiento_permiso = 'RECHAZADO', 
                usuario_modificacion = '".$_user['id_user']."', 
                fecha_modificacion = '".$fecha_actual."' 
                WHERE id_permiso = '".$id_permiso."'")->execute();
            
            // Verifica la eliminacion
            if ($db->affected_rows) {
                // Guarda el proceso
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'd',
                    'nivel' => 'm',
                    'detalle' => 'Se eliminó el Cliente con identificador número ' . $id_permiso . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
                ));
                
                // Crea la notificacion
                //set_notification('success', 'Eliminación exitosa!', 'El registro se eliminó satisfactoriamente.');
            } else {
                // Crea la notificacion
                //set_notification('danger', 'Eliminación fallida!', 'El registro no pudo ser eliminado.');
            }
            
            // Redirecciona la pagina
            //redirect('?/area/listar');
            echo 1; //se elimino correctamente
        } else {
            // Error 400
            // require_once bad_request();
            // exit;
            echo 2; //no se encontro el registro que se quiere eliminar
        }

    }





  if ($accion == "listar_todas_materias") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;


$id_gestion=$_gestion['id_gestion'];





$informacion_estudiante = $db->query("SELECT *, nombre_curso AS nombre_materia, CONCAT(id_curso,'e') as id_materia FROM ext_curso_inscripcion eci
INNER JOIN ext_curso_asignacion eca ON eca.id_curso_asignacion = eci.curso_asignacion_id
INNER JOIN ext_curso ec ON ec.id_curso = eca.curso_id
WHERE eca.estado = 'A' 
AND estudiante_id = $id_estudiante")->fetch();


$array_final = array_merge($inscritos, $informacion_estudiante);

$vacunas = '';
foreach($array_final as $key => $array_fin){ 

        $vacunas .= $array_fin['id_materia'].','; 
        }


$materias = array(
                
                'materias' => $vacunas
            );

echo json_encode($materias);



    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 
}





    
    if($accion == "eliminar_personal"){
        $id_asignacion = $_POST['id_componente']; 
        
        $empleado = $db->query("SELECT asi.id_asignacion, e.id_persona 
                                FROM sys_persona e 
                                INNER JOIN per_asignaciones asi ON asi.persona_id = e.id_persona                                 
                                WHERE id_asignacion='$id_asignacion'                                
                                ")->fetch_first();

        $esta=$db->query("  UPDATE per_asignaciones 
                            SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".date('Y-m-d')."' 
                            WHERE id_asignacion = '".$id_asignacion."'
                        ")->execute();
        
        if ($esta){
            registrarProceso('Se eliminó el horario con identificador número ' ,$id_asignacion,$db,$_location,$_user['id_user']);
            echo 1;//'Eliminado Correctamente.';
        }else{
            echo 2;//'No se pudo eliminar';
        }
    }
    
    if($accion == "eliminar_horarios"){
        $id_horario = $_POST['id_componente']; 
        // verificamos su exite el id en tabla
        $horario = $db->from('per_horarios')
                      ->where('id_horario',$id_horario)
                      ->fetch_first();  

        //en caso de si eliminarar en caso de no dara mensaje de error
        if(validar($horario)){
            //NOELIMINAR $db->delete()->from('per_horarios')->where('id_horario', $id_horario)->limit(1)->execute(); 
            $esta=$db->query("  UPDATE per_horarios 
                                SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".date('Y-m-d')."' 
                                WHERE id_horario = '".$id_horario."'
                            ")->execute();
            
            if ($esta){
                //$db->affected_rows) {
                //registrarProceso('Se eliminó el horario con identificador número ' . $id_horario . '.',$db);
                registrarProceso('Se eliminó el horario con identificador número ' ,$id_horario ,$db,$_location,$_user['id_user']);
                echo 1;//'Eliminado Correctamente.';
            }else{
                echo 2;//'No se pudo eliminar';
            }
        }
    }
    
    if($accion == "actividad_horarios"){ 
        $id_componente = $_POST['id_componente']; 
        $actividad= $_POST['actividad'];
        
        if($actividad=='s'){
            $actividad='n';
        }else{
            $actividad='s'; 
        }
        
        //var_dump($actividad);exit();
 
        $horario = $db->from('per_horarios')->where('id_horario',$id_componente)->fetch_first(); 
        //en caso de si eliminarar en caso de no dara mensaje de error
        if(validar($horario)){
           $esta=$db->query("UPDATE per_horarios SET active = '$actividad', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".date('Y-m-d')."' WHERE id_horario = '".$id_componente."'")->execute();
            
          //$esta=$db->query("UPDATE asi_dias_feriados SET active = $actividad, usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".date('Y-m-d')."' WHERE id_dias_feriados = '".$id_componente."'")->execute();
            
            if ($esta){//$db->affected_rows) {
                    //registrarProceso('Se eliminó el horario con identificador número ' . $id_horario . '.',$db);
                    registrarProceso('Se cambio actividad el cargo con identificador número ' ,$id_componente ,$db,$_location,$_user['id_user']);
                    echo 1;//'Eliminado Correctamente.';
                }else{
                    echo 2;//'No se pudo eliminar';
                }
        }
           
    }
    
    if($accion == "guardar_datos"){
       /* if (!isset($_POST['dias'])){
            echo 11;exit();
        }*/
        if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_final'])&& isset($_POST['descripcion'])) {//poner mas
            // Obtiene los datos
            $id_componente = (isset($_POST['id_componente'])) ? clear($_POST['id_componente']) : 0; 
            
            $fecha_inicio = clear($_POST['fecha_inicio']);
            $fecha_final = clear($_POST['fecha_final']);
            $descripcion = clear($_POST['descripcion']); 
            
           

            // Verifica si es creacion o modificacion
            if ($id_componente > 0) {
                 // Instancia el horario
            $datos = array(
                'fecha_inicio' => $fecha_inicio,
                'fecha_final' => $fecha_final, 
                'descripcion' => $descripcion,
                'gestion_id' => $id_gestion,
                'estado' => 'A', 
                'usuario_modificacion'=> $_user['id_user'],
                'fecha_modificacion'=> date('Y-m-d')
            );
                // Modifica el horario 
                $db->where('id_dias_feriados', $id_componente)->update('asi_dias_feriados', $datos);

                // Guarda el proceso
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'u',
                    'nivel' => 'l',
                    'detalle' => 'Se modificó el feriado con identificador número ' . $id_componente . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
                ));

                /*$_SESSION[temporary] = array(
                    'alert'   => 'success',
                    'title'   => 'Actualización satisfactoria!',
                    'message' => 'El registro se actualizó correctamente.'
                );*/

                // Redirecciona la pagina
                //redirect('?/rh-horarios/ver/' . $id_horario);
                echo 2;
            } else {
                 // Instancia el horario
                $datos = array(
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_final' => $fecha_final, 
                    'descripcion' => $descripcion,
                    'gestion_id' => $id_gestion,
                    'estado' => 'A',
                    'usuario_registro'=> $_user['id_user'],
                    'fecha_registro'=> date('Y-m-d'),
                    'usuario_modificacion'=> $_user['id_user'],
                    'fecha_modificacion'=> date('Y-m-d')
                );
                // Crea el horario
                $id_rett = $db->insert('asi_dias_feriados', $datos);

                // Guarda el proceso
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso'  => date('H:i:s'),
                    'proceso'       => 'c',
                    'nivel'         => 'l',
                    'detalle'       => 'Se creó el feriado con identificador número ' . $id_rett . '.',
                    'direccion'     => $_location,
                    'usuario_id'    => $_user['id_user']
                ));

                // Crea la notificacion
                /*$_SESSION[temporary] = array(
                    'alert'   => 'success',
                    'title'   => 'Adición satisfactoria!',
                    'message' => 'El registro se guardó correctamente.'
                );*/

                // Redirecciona la pagina
                //redirect('?/rh-horarios/listar');
                echo 1;
            }
        } else {
             echo 10; 
        }
    }
    if($accion == "listarhorarios"){
        $componentes_id = isset($_POST['componentes_id'])? $_POST['componentes_id']: '0';//string
      
        $horario =   $db->from('per_asignaciones')
                        ->where('id_asignacion',$componentes_id)
                        ->fetch_first();
          
        $sql='';
        if($horario){
            $horarios_id=$horario['horario_id'];
            
            //$horarios_id = isset($horarios_id)?$_POST['horarios_id']:'';//string
            $valores=explode(',',$horarios_id);
            
            //var_dump($valores);exit(); 
            
            if($valores){
                $sql.=' and (id_horario='.$valores[0];
            
            for($i=1; $i<count($valores); $i++)
            {
                //echo $valores[$i];
                $sql.=' OR id_horario='.$valores[$i];
            }
           
                $sql.=')';
            }
            //var_dump($sql);exit();
            
            //echo '  sql:'.$sql;
            //$personas = $db->query("SELECT pe.* FROM sys_persona pe,sys_users us WHERE pe.id_persona=us.persona_id ".$sql)->fetch();

            $horarios_SQL= "SELECT * 
                            FROM per_horarios 
                            WHERE estado='A' ".$sql." 
                            ORDER BY active asc";

            $horarios = $db->query($horarios_SQL)->fetch();
        }
          /*$arrayHorarios=explode(',',$idshorarios);
            $count=sizeof($arrayHorarios);
           $sql='';
            if(isset($idshorarios)){
                if($count>0){
                    $sql.="AND (";
                    $cc=1;
                    foreach($arrayHorarios as $row){
                       if($cc){
                            $sql.=" id_horario=".$row;    
                            $cc=0; 
                       } else{
                            $sql.=" or id_horario=".$row;    
                       }
                    }
                    $sql.=")";
                    
                }else{
                    foreach($arrayHorarios as $row){
                      $sql.=" and id_horario=".$row;    
                    }
                }
            var_dump($sql);exit();
            $horarios = $db->query("SELECT * FROM per_horarios WHERE estado='A' ".$sql)->fetch(); 
            }*/
     
          // var_dump($feriados);exit();
        echo json_encode($horarios); 
    }
    
    if($accion == "guardar_horarios"){
     if (!isset($_POST['dias'])){
        echo 11;exit();
    }
    if (isset($_POST['dias']) && isset($_POST['entrada']) && isset($_POST['salida']) && isset($_POST['descripcion'])) {
		// Obtiene los datos
		$id_horario = (isset($_POST['id_componente'])) ? clear($_POST['id_componente']) : 0;
		$dias = $_POST['dias'];
		$entrada = clear($_POST['entrada']);
		$salida = clear($_POST['salida']);
		$descripcion = clear($_POST['descripcion']); 
		$active = clear($_POST['active']); 
		$id_asignacion = clear($_POST['id_asignacion']);
        
		//$id_persona = clear($_POST['id_persona']);
      
		// Convierte el array en texto
		$dias = implode(',', $dias);
		
		// Instancia el horario
		/*$horario = array(
			'dias' => $dias,
			'entrada' => $entrada,
			'salida' => $salida,
			'descripcion' => $descripcion,
			'estado' => 'A',
			'active' =>$active,
            'usuario_modificacion'=> $_user['id_user'],
            'fecha_modificacion'=> date('Y-m-d'),
            'usuario_modificacion'=> $_user['id_user'],
            'fecha_modificacion'=> date('Y-m-d')
		);*/
		
		// Verifica si es creacion o modificacion
		if ($id_horario > 0) {
            $horario = array(
			'dias' => $dias,
			'entrada' => $entrada,
			'salida' => $salida,
			'descripcion' => $descripcion,
			'estado' => 'A',
			'active' =>$active, 
            'usuario_modificacion'=> $_user['id_user'],
            'fecha_modificacion'=> date('Y-m-d')
		);
			// Modifica el horario
			$db->where('id_horario', $id_horario)->update('per_horarios', $horario);
			
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'l',
				'detalle' => 'Se modificó el horario con identificador número ' . $id_horario . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
			
			$_SESSION[temporary] = array(
				'alert'   => 'success',
				'title'   => 'Actualización satisfactoria!',
				'message' => 'El registro se actualizó correctamente.'
			);
			
			// Redirecciona la pagina
			//redirect('?/rh-horarios/ver/' . $id_horario);
            echo 2;
		} else {
			// Crea el horario
            $horario = array(
			'dias' => $dias,
			'entrada' => $entrada,
			'salida' => $salida,
			'descripcion' => $descripcion,
			'estado' => 'A',
			'active' =>$active,
            'usuario_modificacion'=> $_user['id_user'],
            'fecha_modificacion'=> date('Y-m-d'),
            'usuario_registro'=> $_user['id_user'],
            'fecha_registro'=> date('Y-m-d')
		);
			$id_horario = $db->insert('per_horarios', $horario);
            
			//agregar a usuario seleccionado
            $horarios_ids = $db->query("SELECT * FROM per_asignaciones where id_asignacion=$id_asignacion")->fetch_first();
            if(!isset($horarios_ids['horario_id'])|| $horarios_ids['horario_id']!=''){
            $horario_id = $horarios_ids['horario_id'].','.$id_horario;
                //|| $horarios_ids['horario_id']==''|| $horarios_ids['horario_id']==0
            }else{ 
			$horario_id = $id_horario;
            }
            
            $horario = array(
			'horario_id' => $horario_id, 
            'usuario_modificacion'=> $_user['id_user'],
            'fecha_modificacion'=> date('Y-m-d')
		  );
            
            $db->where('id_asignacion', $id_asignacion)->update('per_asignaciones', $horario);
            
            //var_dump($db);exit();
            
            
             //$codigo_mayor = $db->query("UPDATE per_asignaciones SET horario_id = horario_id+$id_horario WHERE id_asignacion = id_asignacion;")->fetch();
            
            //$id_anterior = $codigo_mayor['id_comunicado'];
            
            
            
            
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso'  => date('H:i:s'),
				'proceso'       => 'c',
				'nivel'         => 'l',
				'detalle'       => 'Se creó el horario con identificador número ' . $id_horario . '.',
				'direccion'     => $_location,
				'usuario_id'    => $_user['id_user']
			));
			
			// Crea la notificacion
			$_SESSION[temporary] = array(
				'alert'   => 'success',
				'title'   => 'Adición satisfactoria!',
				'message' => 'El registro se guardó correctamente.'
			);

			// Redirecciona la pagina
			//redirect('?/rh-horarios/listar');
            echo 1;
		}
	} else {
		 echo 10; 
	}
    }
    
//echo 'holaa comoosasdas'; exit();
//resume: para verificar si un id existe en una determinada tabla, si no existe detiene el proceso y muestra errr si existe, devuelve true

    
    
}else {
	// Error 404
	require_once not_found();
	exit;
}


function validar($horarios){ 
        if (!$horarios)  {
            // Error 400 
            require_once bad_request();
            exit; 
         }
            return true; 
} 
function registrarProceso($detalle,$id_horario,$db,$_location,$user)
 {//,$pros,$niv){
        $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso'  => date('H:i:s'),
                    'proceso'       => 'u',//$pros
                    'nivel'         => 'l',//$niv
                    'detalle'       => $detalle . $id_horario . '.',
                    'direccion'     => $_location,
                    'usuario_id'    => $user
                )); 
  }
    // registrarProceso('Se eliminó el horario con identificador número ' . $id_horario . '.');//,'u','l');//u y l proceso y nivel con uso?


