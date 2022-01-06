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
		$id_externo = (isset($_params[0])) ? $_params[0] : 0;
		
		// Obtiene el gondolas
		$gondolas = $db->from('per_postulacion')->where('id_postulacion', $id_externo)->fetch_first();
		
		// Verifica si existe el gondolas
		if ($gondolas) {
			// Elimina el gondolas
			$gondolas = array(
                'estado' => 'I'
            );
            $db->where('id_postulacion', $id_externo)->update('per_postulacion', $gondolas);
		

			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó al postulante con identificador número ' . $id_externo . '.',
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
			redirect('?/rrhh-postulantes/listar');
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