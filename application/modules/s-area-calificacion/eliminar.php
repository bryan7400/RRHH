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
		//$id_area_calificacion = (isset($_params[0])) ? $_params[0] : 0;
		$id_area_calificacion = (isset($_POST['id_area_calificacion'])) ? $_POST['id_area_calificacion'] : 0;
		// Obtiene el area
		$area = $db->from('cal_area_calificacion')->where('id_area_calificacion', $id_area_calificacion)->fetch_first();
		
		$id_area_calificacion="SELECT IFNULL(COUNT(*),0) contador FROM cal_modo_calificacion_area_calificaion WHERE area_calificacion_id = $id_area_calificacion";
		$sql_ins = $db->query($id_area_calificacion)->fetch_first();
		
		// Verifica si existe el area
		if ($area && $sql_ins['contador'] == 0) {
			// Elimina el area
			//$db->delete()->from('cal_area_calificacion')->where('id_area_calificacion', $id_area_calificacion)->limit(1)->execute();
			//obtiene la fecha actual
			$fecha_actual = date('Y-m-d H:i:s');

			//ejecuta la eliminacion logica de la gestion escolar
			$db->query("UPDATE cal_area_calificacion SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".$fecha_actual."' WHERE id_area_calificacion = '".$id_area_calificacion."'")->execute();
			
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el area calificacion con identificador número ' . $id_area_calificacion . '.',
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