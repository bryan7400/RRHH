<?php

// Verifica la peticion post
//if (is_post()) {
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Obtiene los parametros
		$id_comunicado = (isset($_params[0])) ? $_params[0] : 0;
		if($id_comunicado>0){
            
        
		// Obtiene el comunidados
		$comunicados = $db->from('ins_comunicados')->where('id_comunicado', $id_comunicado)->fetch_first();
		// Verifica si existe el comunidados
		if ($comunicados) {
			// Elimina el comunidados
            $fecha_actual = date('Y-m-d H:i:s');//obtiene la fecha actual
            
			//$db->delete()->from('ins_comunicados')->where('id_comunicado', $id_comunicado)->limit(1)->execute();
            $est=$db->query("UPDATE ins_comunicados SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".$fecha_actual."' WHERE id_comunicado = '".$id_comunicado."'")->execute();
			
			// Verifica la eliminacion
			if ($est->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el comunidados con identificador número ' . $id_comunicado . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Eliminación exitosa!', 'El registro se eliminó satisfactoriamente.');
                
			} else {
				// Crea la notificacion
				set_notification('danger', 'Eliminación fallida!', 'El registro no pudo ser eliminado.');
			}
			
			// Redirecciona la pagina
			redirect('?/s-comunicados/listar');
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
            
            
       }//si recibe parametro
        else {
		// Redirecciona la pagina
		redirect('?/s-comunicados/listar');
	   }


	//} else {
		// Redirecciona la pagina
		//redirect('?/comunidados/listar');
	//}
//} else {
	// Error 404
//	require_once not_found();
//	exit;
//}

?>