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
		$id_modulo = (isset($_params[0])) ? $_params[0] : 0;
		
		// Obtiene el modulo
		$modulo = $db->from('con_modulos')->where('id_modulo', $id_modulo)->fetch_first();
		
		// Verifica si existe el modulo
		if ($modulo) {
			// Elimina el modulo
			$db->delete()->from('con_modulos')->where('id_modulo', $id_modulo)->limit(1)->execute();
			
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el módulo con identificador número ' . $id_modulo . '.',
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
			redirect('?/modulos/listar');
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/modulos/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>