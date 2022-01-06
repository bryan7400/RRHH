<?php
// Verifica la peticion post
if (is_post()) {
	// Verifica la existencia de datos
	if (isset($_POST['nro_tramite']) && isset($_POST['nro_autorizacion']) && isset($_POST['llave_dosificacion']) && isset($_POST['fecha_limite']) && isset($_POST['leyenda_factura']) && isset($_POST['observacion'])) {
		// Obtiene los datos
		$id_dosificacion    = (isset($_POST['id_dosificacion'])) ? clear($_POST['id_dosificacion']) : 0;
		$nro_tramite        = clear($_POST['nro_tramite']);
		$nro_autorizacion   = clear($_POST['nro_autorizacion']);
		$llave_dosificacion = trim($_POST['llave_dosificacion']);
		$fecha_limite       = clear($_POST['fecha_limite']);
		$leyenda    		= clear($_POST['leyenda_factura']);
		$observacion        = clear($_POST['observacion']);
		
		// Instancia la dosificacion
		$dosificacion = array(
			'nro_tramite'        => $nro_tramite,
			'nro_autorizacion'   => $nro_autorizacion,
			'llave_dosificacion' => base64_encode($llave_dosificacion),
			'fecha_limite'       => date_encode($fecha_limite),
			'leyenda'    		 => $leyenda,
			'observacion'        => $observacion
		);
		
		// Verifica si es creacion o modificacion
		if ($id_dosificacion > 0) {
			// Modifica la dosificacion
			$db->where('id_dosificacion', $id_dosificacion)->update('inv_dosificaciones', $dosificacion);
			
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
			redirect('?/dosificaciones/ver/' . $id_dosificacion);
		} else {
			// Adiciona informacion extra
			$dosificacion['fecha_registro'] = date('Y-m-d');
			$dosificacion['hora_registro'] = date('H:i:s');
			$dosificacion['activo'] = 'n';
			$dosificacion['nro_facturas'] = 0;

			// Crea la dosificacion
			$id_dosificacion = $db->insert('inv_dosificaciones', $dosificacion);
			
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso'  => date('H:i:s'),
				'proceso'       => 'c',
				'nivel'         => 'l',
				'detalle'       => 'Se creó la dosificación con identificador número ' . $id_dosificacion . '.',
				'direccion'     => $_location,
				'usuario_id'    => $_user['id_user']
			));
			
			$_SESSION[temporary] = array(
				'alert'   => 'success',
				'title'   => 'Creación exitosa!',
				'message' => 'El registro se creó satisfactoriamente.'
			);
			
			// Redirecciona la pagina
			redirect('?/dosificaciones/listar');
		}
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