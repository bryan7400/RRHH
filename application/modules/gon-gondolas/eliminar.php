<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (true) {
	// Verifica la cadena csrf
//var_dump($_params[0]);exit();
		// Obtiene los parametros
		$id_gondola = (isset($_params[0])) ? $_params[0] : 0;
		
		// Obtiene el gondolas
		$gondolas = $db->from('gon_gondolas')->where('id_gondola', $id_gondola)->fetch_first();
		
		// Verifica si existe el gondolas
		if ($gondolas) {
			// Elimina el gondolas
			//$db->delete()->from('gon_gondolas')->where('id_gondola', $id_gondola)->limit(1)->execute();
			
			 $db->where('id_gondola', $id_gondola)->update('gon_gondolas', array('estado' => 'I'));
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el gondolas con identificador número ' . $id_gondola . '.',
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
			redirect('?/gon-gondolas/listar');
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