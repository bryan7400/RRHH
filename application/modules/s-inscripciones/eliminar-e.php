<?php


if (is_post()) {
	
		$id_estudiante = (isset($_POST['id_estudiante'])) ? $_POST['id_estudiante'] : 0;
		// Obtiene el id de la gestion actual
		$id_gestion   = $_gestion['id_gestion'];
		$fecha_actual = date('Y-m-d H:i:s');//obtiene la fecha actual
       
		// Verifica si existe el modo
		if ($id_estudiante > 0) {		
			
			$sql_baja_inscripcion = "UPDATE ins_inscripcion SET estado='I', estado_inscripcion='BAJA' WHERE  estudiante_id = $id_estudiante AND gestion_id = $id_gestion";
			$db->query($sql_baja_inscripcion)->execute();	
			
			$sql_baja_inscripcion_historial = "UPDATE ins_inscripcion_historial SET estado='I', estado_inscripcion='BAJA' WHERE  estudiante_id = $id_estudiante AND gestion_id = $id_gestion";			
			$db->query($sql_baja_inscripcion_historial)->execute();

			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se dio de baja al estudiante identificador número ' . $id_estudiante . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));				
				echo 1;
			} else {
				echo 2;
			}
		} else {
			echo 2; 
		}
	
} else {	
	require_once not_found();
	exit;
}

?>