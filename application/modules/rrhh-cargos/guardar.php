<?php
// Verifica la peticion post
if (is_post()) {
	// Verifica la existencia de datos
	if (isset($_POST['cargo']) && isset($_POST['obligacion']) && isset($_POST['descripcion'])) {
		// Obtiene los datos
		$id_cargo = (isset($_POST['id_cargo'])) ? clear($_POST['id_cargo']) : 0;
		$cargo = clear($_POST['cargo']);
		$obligacion = clear($_POST['obligacion']);
		$descripcion = clear($_POST['descripcion']);
		
		// Instancia el cargo
		$cargo = array(
			'cargo' => $cargo,
			'obligacion' => $obligacion,
			'descripcion' => $descripcion,
			'estado' => 'A',	
			'usuario_modificacion' => $_user['id_user'],	
			'fecha_modificacion' => date('Y-m-d')
		);
		
		// Verifica si es creacion o modificacion
		if ($id_cargo > 0) {
			// Modifica el cargo
			$db->where('id_cargo', $id_cargo)->update('per_cargos', $cargo);
			
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'l',
				'detalle' => 'Se modificó el cargo con identificador número ' . $id_cargo . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
			
			// Crea la notificacion
			set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
			
			// Redirecciona la pagina
			//redirect('?/rrhh-cargos/ver/' . $id_cargo);
		} else {		
			$cargo['usuario_registro'] = $_user['id_user'];	 	
			$cargo['fecha_registro'] = date('Y-m-d');
			
			// Crea el cargo
			$id_cargo = $db->insert('per_cargos', $cargo);
			
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'c',
				'nivel' => 'l',
				'detalle' => 'Se creó el cargo con identificador número ' . $id_cargo . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
			
			// Crea la notificacion
			set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
			
			// Redirecciona la pagina
			//redirect('?/rrhh-cargos/listar');
		}
	    echo "1";        
	} else {
		// Error 400
		require_once bad_request();
		exit;
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>