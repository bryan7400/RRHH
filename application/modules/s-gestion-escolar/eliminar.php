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
		$id_gestion = (isset($_POST['id_gestion'])) ? $_POST['id_gestion'] : 0;
		$gestion = date('Y');
		// Obtiene el gestion
		$consulta="SELECT IFNULL(COUNT(*),0) contador FROM ins_gestion WHERE id_gestion = $id_gestion AND gestion != $gestion";
		$sql = $db->query($consulta)->fetch_first();
		$inscripcion="SELECT IFNULL(COUNT(*),0) contador FROM ins_inscripcion WHERE gestion_id = $id_gestion";
		$sql_ins = $db->query($inscripcion)->fetch_first();
		//$gestion = $db->from('ins_gestion')->where('id_gestion', $id_gestion)->where('gestion','!=', $gestion)->fetch_first();
		
		//var_dump($sql);exit();
		
		// Verifica si existe el gestion
		if ($sql['contador'] > 0 || $sql_ins['contador'] == 0) {
		    
			$fecha_actual = date('Y-m-d H:i:s');//obtiene la fecha actual

			//ejecuta la eliminacion logica de la gestion escolar
			$db->query("UPDATE ins_gestion SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".$fecha_actual."' WHERE id_gestion = '".$id_gestion."'")->execute();
			//$sareacalificacion = $db->select('z.*')->from('estado z')->where('z.estado', 'A')->order_by('z.id_area_calificacion', 'asc')->fetch();
			
			//$db->delete()->from('ins_gestion')->where('id_gestion', $id_gestion)->limit(1)->execute();
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el gestion con identificador número ' . $id_gestion . '.',
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