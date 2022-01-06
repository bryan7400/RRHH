<?php

//var_dump($_POST);exit();

if (is_post()) {
	
		$id_estudiante = (isset($_POST['id_estudiante'])) ? $_POST['id_estudiante'] : 0;
		// Obtiene el id de la gestion actual
		$id_gestion   = $_gestion['id_gestion'];
		$fecha_actual = date('Y-m-d H:i:s');//obtiene la fecha actual
		
		
		if ($id_estudiante > 0) {		
			
			//Iniciamos la transaccion
			$db->query("BEGIN")->execute();				
			
            try {               
               
                $a_inscripcion = array(
                    'estado_inscripcion' => 'INSCRITO',                    
                    'fecha_estado_inscripcion' => $fecha_actual,
                	'observacion_estado_inscripcion' => 'Se revirtio el estado de la inscripcion'
                );
                           
                $a_condicion = array(
                    'estudiante_id' => $id_estudiante,
                    'gestion_id' => $id_gestion
                );
            
                $res_i  = $db->where($a_condicion)->update('ins_inscripcion', $a_inscripcion);
                $res_hi = $db->where($a_condicion)->update('ins_inscripcion_historial', $a_inscripcion);
                
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'd',
                    'nivel' => 'm',
                    'detalle' => 'Se cambio el estado de la inscripcion del estudiante con identificador número ' . $id_estudiante . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
                ));
                
                } catch (Exception $exception) {
                    //eliminamos la transaccion
                    $db->query("ROLLBACK")->execute();					
                }

                //Guardamos la transaccion
                $db->query("COMMIT")->execute();		
            echo '1'; // si se puede eliminar
		} else {
			echo '2'; 
		}
	
} else {	
	require_once not_found();
	exit;
}

?>