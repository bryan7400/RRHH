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
		//$id_medico_estudiante = (isset($_params[0])) ? $_params[0] : 0;
		$id_medico_estudiante = (isset($_POST['id_medico_estudiante'])) ? $_POST['id_medico_estudiante'] : 0;
		// Obtiene el area
		$cliente = $db->from('ins_registro_medico_estudiante')->where('id_medico_estudiante', $id_medico_estudiante)->fetch_first();
		
		
		
		
		// Verifica si existe el area
		if ($cliente> 0) {
			// Elimina el area
			//$db->delete()->from('rrhh_contrato')->where('id_medico_estudiante', $id_medico_estudiante)->limit(1)->execute();
			//obtiene la fecha actual
			$fecha_actual = date('Y-m-d H:i:s');

			//ejecuta la eliminacion logica de la gestion escolar
			$db->query("UPDATE ins_registro_medico_estudiante SET estado = 'I', 
				usuario_modificacion = '".$_user['id_user']."', 
				fecha_modificacion = '".$fecha_actual."' 
				WHERE id_medico_estudiante = '".$id_medico_estudiante."'")->execute();
			
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el Interes con identificador número ' . $id_medico_estudiante . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Eliminación exitosa!', 'El registro se eliminó satisfactoriamente.');
			} else {
				// Crea la notificacion
				//set_notification('danger', 'Eliminación fallida!', 'El registro no pudo ser eliminado.');
			}
			
			// Redirecciona la pagina
			//redirect('?/area/listar');
			echo 1; //se elimino correctamente
		} else {
			// Error 400
			// require_once bad_request();
			// exit;
			echo 2; //no se encontro el registro que se quiere eliminar
		}
	// } else {
	// 	// Redirecciona la pagina
	// 	redirect('?/area/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>