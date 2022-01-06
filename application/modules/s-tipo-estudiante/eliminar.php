<?php
 
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
//var_dump('expression');exit();
// Verifica la peticion post 
if (is_post()) {

	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Obtiene los parametros
		//$id_tipo_estudiante = (isset($_params[0])) ? $_params[0] : 0;
	    $id_tipo_estudiante = (isset($_POST['id_tipo_estudiante'])) ? $_POST['id_tipo_estudiante'] : 0;
	    $gestion = date('Y');
		
		$consulta="SELECT IFNULL(COUNT(*),0) contador FROM ins_gestion g inner join ins_tipo_estudiante te on g.id_gestion=te.gestion_id  WHERE g.gestion != $gestion";
		$sql = $db->query($consulta)->fetch_first();
		
		// Obtiene el tipo
		$tipo = $db->from('ins_tipo_estudiante')->where('id_tipo_estudiante', $id_tipo_estudiante)->fetch_first();
		
		$inscripcion="SELECT IFNULL(COUNT(*),0) contador FROM ins_inscripcion WHERE tipo_estudiante_id = $id_tipo_estudiante";
		$sql_ins = $db->query($inscripcion)->fetch_first();
		
		// Verifica si existe el tipo
		if ($tipo && $sql_ins['contador'] == 0) {
			// Elimina el tipo
			//$db->delete()->from('ins_tipo_estudiante')->where('id_tipo_estudiante', $id_tipo_estudiante)->limit(1)->execute();
			//obtiene la fecha actual
			$fecha_actual = date('Y-m-d H:i:s');
			//ejecuta la eliminacion logica de la gestion escolar
			$db->query("UPDATE ins_tipo_estudiante SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".$fecha_actual."' WHERE id_tipo_estudiante = '".$id_tipo_estudiante."'")->execute();
			
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el tipo estudiante con identificador número ' . $id_tipo_estudiante . '.',
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
			//redirect('?/tipo/listar');
			echo 1; //se elimino correctamente
		} else {
			// Error 400
			// require_once bad_request();
			// exit;
			echo 2; //no se encontro el registro que se quiere eliminar
		}
	// } else {
	// 	// Redirecciona la pagina
	// 	redirect('?/tipo/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>