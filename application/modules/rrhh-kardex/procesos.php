<?php
$nombre_dominio = escape($_institution['nombre_dominio']);
// Verifica la peticion post
if (is_post()) {
    $id_gestion = $_gestion['id_gestion'];
    $accion = $_POST['accion']; 
    if($accion == "listar_tabla"){
 
        $feriados = $db->query("SELECT (SELECT COUNT(kp.tipo_kardex) FROM per_kardex_personal kp WHERE kp.tipo_kardex='felicitacion' AND kp.persona_id=e.id_persona AND kp.estado='A')as cantFeli,
        (SELECT COUNT(kp.tipo_kardex) FROM per_kardex_personal kp WHERE kp.tipo_kardex='sancion'  AND kp.persona_id=e.id_persona AND kp.estado='A') as cantSanc,
        asi.*, ca.`cargo`, e.*, p.*  FROM `per_asignaciones` asi 
                                LEFT JOIN sys_persona e  ON asi.`persona_id` = e.`id_persona`                                 
                                LEFT JOIN per_postulacion p  ON p.`id_postulacion` = e.`postulante_id`                                 
                                LEFT JOIN `per_cargos` ca  ON asi.`cargo_id` = ca.`id_cargo`                                
                                LEFT JOIN `ins_gestion` g  ON g.`gestion` = YEAR(asi.fecha_inicio)
                              
                                ")->fetch();
        
 
        
         echo json_encode($feriados); 
    }
   if($accion == "guardar_kardex"){
    //var_dump($_POST);
    //exit;
      if (isset($_POST['fecha_felicitacion'])) {
		// Obtiene los datos
       $tipoFelSanc = (isset($_POST['tipoFelSanc'])) ? clear($_POST['tipoFelSanc']) :'';
       $id_persona = (isset($_POST['id_persona'])) ? clear($_POST['id_persona']) :'';
       $fecha = (isset($_POST['fecha_felicitacion'])) ? clear($_POST['fecha_felicitacion']) :0;
       $concepto = (isset($_POST['concepto'])) ? clear($_POST['concepto']) :0;
       $observacion = (isset($_POST['descripcion'])) ? clear($_POST['descripcion']) :0;
       $tipo = (isset($_POST['tipo_f'])) ? clear($_POST['tipo_f']) :0;
       $file = (isset($_POST['file'])) ? clear($_POST['file']) :0;
		 
          
       $id_kardex = (isset($_POST['id_kardex'])) ? clear($_POST['id_kardex']) :false;
         
       
       $nombre_archivo_documento = isset($_FILES["archivo_documento"]["name"]) ? ($_FILES["archivo_documento"]["name"]) : false;
       $archivo_documento_nombre = clear($_POST['archivo_documento_nombre']);


		$archivo_documentoedit = $db->query("SELECT * FROM rrhh_contrato WHERE archivo_documento='$archivo_documento_nombre'")->fetch_first();


	if (($nombre_archivo_documento != '') || ($archivo_documento_nombre != '')) {

		if ($nombre_archivo_documento != ''){
			$formatos_permitidos =  array('pdf', 'jpg', 'jpeg', 'png', 'docx');
 			$archivo_documento = $_FILES['archivo_documento']["name"];
 			$extension = pathinfo($nombre_archivo_documento, PATHINFO_EXTENSION);
 			$extension = strtolower($extension);

 			if (!in_array($extension, $formatos_permitidos)) {
 				$archivo_documentos_permitidos = 1;
 			} else {
 				$output_dir = 'files/' . $nombre_dominio . '/rrhh/kardex/evaluaciones_personal/';
 				$imagen =  date('dmY_His') . '_' . '.' . pathinfo($nombre_archivo_documento, PATHINFO_EXTENSION);;
 				if (!move_uploaded_file($_FILES['archivo_documento']["tmp_name"], $output_dir . $imagen)) {
 					$msg = 'No pudo subir el archivo_documento';
 					var_dump($msg);
 				} else {
 					
 				}
 			}

 			if ($archivo_documento_nombre != ''){
 				unlink('files/' . $nombre_dominio . '/rrhh/kardex/evaluaciones_personal/'.$archivo_documento_nombre);
 			} else {
 				
 			}

 			$archivo_documento = clear($imagen);
 			



		} else {


				$archivo_documento = clear($_POST['archivo_documento_nombre']);
			}

}


		// Convierte el array en texto
		//$dias = implode(',', $dias);
 
		//id_kardex fecha_kardex concepto_kardex observacion_kardex tipo_kardex adjunto_kardex gestion estado
		// Verifica si es creacion o modificacion
		if ($id_kardex) {
            $kardex = array(
			'fecha_kardex' => $fecha,
			'concepto_kardex' => $concepto,
			'observacion_kardex' => $observacion,
			//'tipo_kardex' => $tipoFelSanc,//el tipo Felic o Sanc no es editable
			'tipo_ev_kardex' => $tipo, 
			'adjunto_kardex' =>$archivo_documento,  
			//'estado' => 'A',// solo al eliminar
            'usuario_modificacion'=> $_user['id_user'],
            'fecha_modificacion'=> date('Y-m-d')
		);
			// Modifica el horario
			$db->where('id_kardex', $id_kardex)->update('per_kardex_personal', $kardex);
			
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'l',
				'detalle' => 'Se modificó el kardex de eehh con identificador número ' . $id_kardex . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
			
			 
			// Redirecciona la pagina
			//redirect('?/rh-horarios/ver/' . $id_horario);
            echo 2;
		} else {
			// Crea el horario
            $kardex = array(
			'fecha_kardex' => $fecha,
			'concepto_kardex' => $concepto,
			'observacion_kardex' => $observacion,
			'tipo_kardex' => $tipoFelSanc,
			'tipo_ev_kardex' => $tipo,
			'persona_id' => $id_persona,
			'adjunto_kardex' =>$archivo_documento, 
			'gestion' => 1,//cambiar
			'estado' => 'A',
            'usuario_modificacion'=> $_user['id_user'],
            'fecha_modificacion'=> date('Y-m-d'),
            'usuario_registro'=> $_user['id_user'],
            'fecha_registro'=> date('Y-m-d')
		);
			$id_horario = $db->insert('per_kardex_personal', $kardex);
             
            /*if(!isset($horarios_ids['horario_id'])|| $horarios_ids['horario_id']!=''){
            $horario_id = $horarios_ids['horario_id'].','.$id_horario; 
            }else{ 
			$horario_id = $id_horario;
            }*/
            
            /*$horario = array(
			'horario_id' => $horario_id, 
            'usuario_modificacion'=> $_user['id_user'],
            'fecha_modificacion'=> date('Y-m-d')
		  );*/
            
           /* $db->where('id_asignacion', $id_asignacion)->update('per_asignaciones', $horario);*/
       
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso'  => date('H:i:s'),
				'proceso'       => 'c',
				'nivel'         => 'l',
				'detalle'       => 'Se creó el kardex con identificador número ' . $id_horario . '.',
				'direccion'     => $_location,
				'usuario_id'    => $_user['id_user']
			));
			
			 
            echo 1;
		}
	} else {
		 echo 3; 
	}
   }
   if($accion == "listar_reg_kardex"){  
     $persona=isset($_POST['idpersona'])?$_POST['idpersona']:0;
       
        $feriados = $db->query("SELECT * FROM per_kardex_personal kp WHERE  kp.estado='A' AND persona_id=$persona")->fetch();// kp.tipo_kardex='felicitacion' AND
     
 
        
         echo json_encode($feriados); 
   }
    
    if($accion == "eliminar_kardex"){
        
          $id_componente = $_POST['id_componente']; 
        // verificamos su exite el id en tabla
        $horario = $db->from('per_kardex_personal')->where('id_kardex',$id_componente)->fetch_first();  
        //en caso de si eliminarar en caso de no dara mensaje de error
        if($horario){
            //NOELIMINAR $db->delete()->from('per_horarios')->where('id_horario', $id_horario)->limit(1)->execute(); 
          $esta=$db->query("UPDATE per_kardex_personal SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".date('Y-m-d')."' WHERE id_kardex = '".$id_componente."'")->execute();
            
            if ($esta){//$db->affected_rows) {
                    //registrarProceso('Se eliminó el horario con identificador número ' . $id_horario . '.',$db);
                    registrarProceso('Se eliminó kardex personal con identificador número ' ,$id_componente ,$db,$_location,$_user['id_user']);
                    echo 1;//'Eliminado Correctamente.';
                }else{
                    echo 3;//'No se pudo eliminar';
                }
        }
    }
    
    
    //borrar lo demas
  /* if($accion == "eliminar_horarios"){
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
    
    */
        
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


