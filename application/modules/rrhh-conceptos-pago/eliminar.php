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
	//if (isset($_POST[get_csrf()])) {
		// Obtiene los parametros
		//$id_concepto_pago = (isset($_params[0])) ? $_params[0] : 0;
		$id_concepto_pago = $_POST['id_concepto'];
		// Obtiene el concepto_pago
		$concepto_pago = $db->from('rhh_concepto_pago')->where('id_concepto_pago', $id_concepto_pago)->fetch_first();
		
		// Verifica si existe el concepto_pago
		if ($concepto_pago) {
			// Elimina el concepto_pago
			$db->delete()->from('rhh_concepto_pago')->where('id_concepto_pago', $id_concepto_pago)->limit(1)->execute();
			
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el concepto pago con identificador número ' . $id_concepto_pago . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Eliminación exitosa!', 'El registro se eliminó satisfactoriamente.');
				echo 1;
			} else {
				// Crea la notificacion
				//set_notification('danger', 'Eliminación fallida!', 'El registro no pudo ser eliminado.');
				echo 2;
			}
			
			// Redirecciona la pagina
			//redirect('?/rrhh-conceptos-pago/listar');
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	/*} else {
		// Redirecciona la pagina
		redirect('?/concepto_pago/listar');
	}*/
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>