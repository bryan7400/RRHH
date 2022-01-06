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
		$id_contrato = (isset($_params[0])) ? $_params[0] : 0;
		
		// Obtiene el contratos
		$contratos = $db->from('rhh_contratos')->where('id_contrato', $id_contrato)->fetch_first();
		
		// Verifica si existe el contratos
		if ($contratos) {
			// Elimina el contratos
			$db->delete()->from('rhh_contratos')->where('id_contrato', $id_contrato)->limit(1)->execute();
			
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el contratos con identificador número ' . $id_contrato . '.',
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
			redirect('?/contratos/listar');
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/contratos/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>