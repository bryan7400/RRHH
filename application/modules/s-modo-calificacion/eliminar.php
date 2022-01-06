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
		$id_modo_calificacion = (isset($_POST['id_modo'])) ? $_POST['id_modo'] : 0;
		// Obtiene el modo
		
		// Verifica si existe el modo
		if ($id_modo_calificacion) {
			$fecha_actual = date('Y-m-d H:i:s');//obtiene la fecha actual

			//ejecuta la eliminacion logica de la modo de calioficación 
			$db->query("UPDATE cal_modo_calificacion SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".$fecha_actual."' WHERE id_modo_calificacion = '".$id_modo_calificacion."'")->execute();
			
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el modo de calificación con identificador número ' . $id_modo_calificacion . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
			} 
			echo 1; //se elimino correctamente
		} else {
			// Error 400
			/*require_once bad_request();
			exit;*/
			echo 2; //no se encontro el registro que se quiere eliminar
		}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>