<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	if (isset($_POST[get_csrf()])) {
		// Obtiene los parametros
		$id_tipo_descuento = (isset($_params[0])) ? $_params[0] : 0;
		
		// Obtiene el tipo_descuento
		$tipo_descuento = $db->from('pen_tipo_descuento')->where('id_tipo_descuento', $id_tipo_descuento)->fetch_first();
		
		// Verifica si existe el tipo_descuento
		if ($tipo_descuento) {
			// Elimina el tipo_descuento
			$db->delete()->from('pen_tipo_descuento')->where('id_tipo_descuento', $id_tipo_descuento)->limit(1)->execute();
			
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el tipo descuento con identificador número ' . $id_tipo_descuento . '.',
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
			redirect('?/tipo_descuento/listar');
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/tipo_descuento/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>