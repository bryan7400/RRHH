<?php

// Verifica la peticion post
// if (is_post()) {
	// Obtiene los parametros
	$id_dosificacion = (isset($_params[0])) ? $_params[0] : 0;
	
	// Obtiene la dosificacion
	$dosificacion = $db->from('inv_dosificaciones')->where('id_dosificacion', $id_dosificacion)->fetch_first();
	
	// Verifica si existe la dosificacion
	if ($dosificacion) {
		$estado = ($dosificacion['activo'] == 'N') ? 'S' : 'N';

		// Instancia el dosificacion
		$data = array(
			'activo' => $estado
		);
		// Modifica la dosificacion activo = s
		$db->where('id_dosificacion', $id_dosificacion)->update('inv_dosificaciones',$data);
		
		// Guarda el proceso
		$db->insert('sys_procesos', array(
			'fecha_proceso' => date('Y-m-d'),
			'hora_proceso'  => date('H:i:s'),
			'proceso'       => 'u',
			'nivel'         => 'l',
			'detalle'       => 'Se modificó la dosificación con identificador número ' . $id_dosificacion . '.',
			'direccion'     => $_location,
			'usuario_id'    => $_user['id_user']
		));
		
		$_SESSION[temporary] = array(
			'alert'   => 'success',
			'title'   => 'Modificación exitosa!',
			'message' => 'El registro se modificó satisfactoriamente.'
		);
		
		// Redirecciona la pagina
		redirect('?/dosificaciones/listar');
	} else {
		// Error 400
		require_once bad_request();
		exit;
	}
// } else {
// 	// Error 404
// 	require_once not_found();
// 	exit;
// }

?>