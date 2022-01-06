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
		//$id_gestion = (isset($_params[0])) ? $_params[0] : 0;
		$id_nivel_academico = (isset($_POST['id_nivel'])) ? $_POST['id_nivel'] : 0;
		// Obtiene el modo
		$modo = $db->from('ins_nivel_academico')->where('id_nivel_academico', $id_nivel_academico)->fetch_first();
		
		$aula_paralelo="SELECT IFNULL(COUNT(*),0) contador FROM ins_aula WHERE nivel_academico_id = $id_nivel_academico";
		$sql_ins = $db->query($aula_paralelo)->fetch_first();
		
		// Verifica si existe el modo
		if ($modo && $sql_ins['contador'] == 0) {
			$fecha_actual = date('Y-m-d H:i:s');//obtiene la fecha actual

			//ejecuta la eliminacion logica de la modo de calioficación 
			$db->query("UPDATE ins_nivel_academico SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".$fecha_actual."' WHERE id_nivel_academico = '".$id_nivel_academico."'")->execute();
			
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el nivel académico con identificador número ' . $id_nivel_academico . '.',
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
			//redirect('?/gestiones/listar');
			echo 1; //se elimino correctamente
		} else {
			// Error 400
			/*require_once bad_request();
			exit;*/
			echo 2; //no se encontro el registro que se quiere eliminar
		}
	//} else {
		// Redirecciona la pagina
		//redirect('?/gestiones/listar');
	//}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>