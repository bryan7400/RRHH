<?php

// var_dump($_POST);exit();
// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Obtiene los parametros
		//$id_gestion = (isset($_params[0])) ? $_params[0] : 0;
		$id_estudiante = (isset($_POST['id_estudiante'])) ? $_POST['id_estudiante'] : 0;
		// Obtiene el modo
        //$modo = $db->from('pro_materia')->where('id_materia', $id_materia)->fetch_first();
        $modo = true;
		
		// Verifica si existe el modo
		if ($modo) {
			$fecha_actual = date('Y-m-d H:i:s');//obtiene la fecha actual

			//ejecuta la eliminacion logica de la modo de calioficación 
			//$db->query("UPDATE ins_inscripcion SET estado = 'I', estado_inscripcion = 'BAJA' , usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".$fecha_actual."' WHERE estudiante_id = '".$id_estudiante."'")->execute();
			$db->query("UPDATE ins_datos_estudiante SET estado = 'A', cuotas_pendiente='' WHERE id_datos_estudiante = '".$id_estudiante."'")->execute();	
			
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se habilito a un estudiante antiguo para la inscripcion con id ' . $id_estudiante . '.',
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
			//redirect('?/gestiones/listar');
			echo $id_estudiante; //se elimino correctamente
		} else {
			// Error 400
			/*require_once bad_request();
			exit;*/
			echo 0; //no se encontro el registro que se quiere eliminar
		}
	//} else {
		// Redirecciona la pagina
		//redirect('?/gestiones/listar');
	//}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>