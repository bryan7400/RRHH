<?php 
// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	// if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		//var_dump($_POST);

		if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_final']) && isset($_POST['descripcion_feriado'])) {
			// Obtiene los datos
			$id_feriado = (isset($_POST['id_feriado'])) ? clear($_POST['id_feriado']) : 0;
			$fecha_inicio = clear($_POST['fecha_inicio']);
			$fecha_final = clear($_POST['fecha_final']);
			$descripcion = clear($_POST['descripcion_feriado']);
			
			// Instancia el sferiados
			$sferiados = array(
				'fecha_inicio' => date_encode($fecha_inicio),
				'fecha_final' => date_encode($fecha_final), 	
				'descripcion' => $descripcion, 	
				'gestion_id' => $_gestion['id_gestion'],

				'estado' => 'A',	
				'usuario_registro' => $_user['id_user'],	
				'fecha_registro' => date('Y-m-d'), 	
				'usuario_modificacion' => $_user['id_user'],	
				'fecha_modificacion' => date('Y-m-d')
			);
			
			// Verifica si es creacion o modificacion
			if ($id_feriado > 0) {
				// Modifica el sferiados
				$db->where('id_dias_feriados', $id_feriado)->update('asi_dias_feriados', $sferiados);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el sferiados con identificador número ' . $id_feriado . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/sferiados/ver/' . $id_feriado);
				echo 1;
			} else {
				// Crea el sferiados
				$id_feriado = $db->insert('asi_dias_feriados', $sferiados);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el sferiados con identificador número ' . $id_feriado . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/sferiados/listar');
				echo 2;
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	// } else {
	// 	// Redirecciona la pagina
	// 	redirect('?/sferiados/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>