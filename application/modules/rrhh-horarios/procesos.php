<?php
// Verifica la peticion post
if (is_post()) {
    $accion = $_POST['accion'];
    
    if($accion == "listar_horarios"){

       $horarios = $db->from('per_horarios')->where('estado','A')->order_by('fecha_modificacion', 'desc')->fetch(); 
         echo json_encode($horarios); 
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
    
    if($accion == "guardar_horarios"){
    if (!isset($_POST['dias'])){
        echo 11;exit();
    }
    if (isset($_POST['dias']) && isset($_POST['entrada']) && isset($_POST['salida'])) {// && isset($_POST['aplicadoa'])
        
		// Obtiene los datos
		$id_horario = isset($_POST['id_componente']) ? clear($_POST['id_componente']) : 0;
		$dias = $_POST['dias'];
		$entrada = clear($_POST['entrada']);
		$salida = clear($_POST['salida']);
		$fecha_inicio = clear($_POST['fecha_inicio']);
		$fecha_fin = clear($_POST['fecha_fin']); 
		$aplicadoa_ini = isset($_POST['aplicadoa'])?$_POST['aplicadoa']:'';
		$concepto_pago = isset($_POST['concepto_pago'])?$_POST['concepto_pago']:0;
		$descripcion = clear($_POST['descripcion']);

		// Convierte el array en texto
		$dias = implode(',', $dias);
        
        if($aplicadoa_ini!=0 || $aplicadoa_ini!=''){
            
		$aplicadoa = implode(',', $aplicadoa_ini);
        }else{
            $aplicadoa='';
        }
            
		//var_dump($aplicadoa);exit();
		// Instancia el horario
		
		// Verifica si es creacion o modificacion
		if ($id_horario > 0) {
            $horario = array(
                'dias' => $dias,
                'entrada' => $entrada,
                'salida' => $salida,
                'descripcion' => $descripcion,
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'aplicadoa' => $aplicadoa, 
                'concepto_pago_id' => $concepto_pago, 
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
            $horario = array(
			'dias' => $dias,
			'entrada' => $entrada,
			'salida' => $salida,
			'descripcion' => $descripcion,
			'fecha_inicio' => $fecha_inicio,
			'fecha_fin' => $fecha_fin,
			'aplicadoa' => $aplicadoa,
             'concepto_pago_id' => $concepto_pago, 
			'active' => 's',
			'estado' => 'A',
            'usuario_modificacion'=> $_user['id_user'],
            'fecha_modificacion'=> date('Y-m-d'),
            'usuario_registro'=> $_user['id_user'],
            'fecha_registro'=> date('Y-m-d')
		);
           
			// Crea el horario
			$id_horario = $db->insert('per_horarios', $horario);
            
			//:::::::::::::::::::::::::::::::::::::::REPARAR: revisar::::::::::::::::::::::::::::::::
            //insertara a cada asignacion de empleado
            $horarios = $db->query('SELECT nivel_academico_id,id_asignacion,horario_id FROM per_asignaciones')->fetch();
            foreach($horarios as $rowhor){ 
                $id_asignacion=$rowhor['id_asignacion'];
                //
                foreach($aplicadoa_ini as $nivel_aca){
                    
                    //horario con horarios ya asignados
                    $niveles_asi=explode(',', $rowhor['nivel_academico_id']);//convierte en array
                    //ver si existe el nivel en la asignacion
                    if(in_array($nivel_aca,$niveles_asi)){
                         
                        if($rowhor['horario_id']!=''){

                                $horario_id_new=$rowhor['horario_id'].','.$id_horario;
                                $horario_datos = array(
                                    'horario_id' => $horario_id_new
                                );
                                // Modifica el horario
                                $db->where('id_asignacion', $id_asignacion)->update('per_asignaciones', $horario_datos);
                        }else{
                          $horario_id_new=$id_horario;
                                $horario_datos = array(
                                    'horario_id' => $horario_id_new
                                );
                                // Modifica el horario
                                $db->where('id_asignacion', $id_asignacion)->update('per_asignaciones', $horario_datos);
                                //var_dump($horario_datos);exit();
                        }
                    
                    }
                   
                    
                }
            }
            
            //recorremos el asigandoa 
            //recorremos las personas con nivel=insertado(asigandoa)
            //compramos si son iguales
            
            //si editamos asigacion agregando el id horario 
            //no
            
            
            
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

 function registrarProceso($detalle,$id_horario,$db,$_location,$user){//,$pros,$niv){
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


