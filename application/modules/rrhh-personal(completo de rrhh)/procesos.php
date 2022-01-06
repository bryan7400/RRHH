<?php
// Verifica la peticion post
if (is_post()) {
    $id_gestion = $_gestion['id_gestion'];
    $accion = $_POST['accion']; 
    if($accion == "listar_tabla"){
 
        $feriados = $db->query("SELECT asi.*, ca.cargo, e.*, p.* 
                                FROM per_asignaciones asi 
                                LEFT JOIN sys_persona e  ON asi.persona_id = e.id_persona                                 
                                LEFT JOIN per_postulacion p  ON p.id_postulacion = e.postulante_id                                 
                                LEFT JOIN per_cargos ca  ON asi.cargo_id = ca.id_cargo                                
                                LEFT JOIN ins_gestion g  ON g.gestion = YEAR(asi.fecha_inicio)
                                WHERE g.id_gestion=$id_gestion
                                ")->fetch();
        
        
/*
 SELECT IFNULL(a.horario_id, 0) AS horario_id, asi.*,ca.`cargo`,e.*  FROM `per_asignaciones` asi
LEFT JOIN sys_persona e  ON asi.`persona_id` = e.`id_persona`
LEFT JOIN `per_cargos` ca  ON asi.`cargo_id` = ca.`id_cargo`

LEFT JOIN (SELECT a.* FROM per_asignaciones a 
LEFT JOIN per_asignaciones b ON a.persona_id = b.persona_id AND a.fecha_asignacion < b.fecha_asignacion 
WHERE b.fecha_asignacion IS NULL) a ON e.id_persona = a.persona_id 
*/


       // $feriados = $db->query("SELECT z.*, g.gestion FROM asi_dias_feriados z,ins_gestion g  WHERE z.gestion_id=g.id_gestion  AND z.estado='A' AND z.`gestion_id`=".$id_gestion)->fetch();
        //armar new array con horas y dias ... 
        
         echo json_encode($feriados); 
    }
     
   if($accion == "eliminar_horarios"){
           $id_horario = $_POST['id_componente']; 
        // verificamos su exite el id en tabla
        $horario = $db->from('per_horarios')->where('id_horario',$id_horario)->fetch_first();  
        //en caso de si eliminarar en caso de no dara mensaje de error
        if(validar($horario)){
            //NOELIMINAR $db->delete()->from('per_horarios')->where('id_horario', $id_horario)->limit(1)->execute(); 
          $esta=$db->query("UPDATE per_horarios SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".date('Y-m-d')."' WHERE id_horario = '".$id_horario."'")->execute();
            
            if ($esta){//$db->affected_rows) {
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
      $componentes_id = isset($_POST['componentes_id'])?$_POST['componentes_id']:'0';//string
      
    $horario = $db->from('per_asignaciones')->where('id_asignacion',$componentes_id)->fetch_first();
          
     $horarios_id=$horario['horario_id'];
        
      //$horarios_id = isset($horarios_id)?$_POST['horarios_id']:'';//string
      $valores=explode(',',$horarios_id);
        
      //var_dump($valores);exit(); 
        
        
        
        $sql='';
        if($valores){
            $sql.=' and(id_horario='.$valores[0];
        
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
         
        $horarios = $db->query("SELECT * FROM per_horarios WHERE estado='A' ".$sql." order by active asc")->fetch();
 
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


