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
		// Verifica la existencia de datos
		if (isset($_POST['nombre_turno'])) {
			// Obtiene los datos
			$id_turno      = (isset($_POST['id_turno']))?$_POST['id_turno'] : 0;
			$nombre_turno  = clear($_POST['nombre_turno']);
			$descripcion   = clear($_POST['descripcion']);
			$hora_inicio   = clear($_POST['hora_inicio']);
			$hora_fin      = clear($_POST['hora_final']);
			$id_gestion    = $_gestion['id_gestion'];
			$orden         = clear($_POST['orden']);
			
			// Verifica si es creacion o modificacion
			if ($id_turno > 0) {
				// Modifica el materia
			    	// Instancia el materia
    			$turno = array(
    				'nombre_turno' => $nombre_turno,
    				'descripcion' => $descripcion,
    				'fecha_modificacion' => date('Y-m-d'),				
    				'usuario_modificacion' => $_user['id_user'],
    				'estado'=>'A',
    				'hora_inicio' => $hora_inicio,
    				'hora_final' => $hora_fin,
    				'gestion_id' => $id_gestion,
    				'orden' => $orden
    			); 
				$db->where('id_turno', $id_turno)->update('ins_turno', $turno);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el turno con identificador número ' . $id_turno . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				// set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				// redirect('?/materia/ver/' . $id_materia);
				echo 1;
			} else {
				// Crea el materia
					// Instancia el materia
    			$turno = array(
    				'nombre_turno' => $nombre_turno,
    				'descripcion' => $descripcion,
    				'fecha_registro' => date('Y-m-d'),
    				'usuario_registro' => $_user['id_user'],
    				'fecha_modificacion' => "0000-00-00",				
    				'usuario_modificacion' => '0',
    				'estado'=>'A',
    				'hora_inicio' => $hora_inicio,
    				'hora_final' => $hora_fin,
    				'gestion_id' => $id_gestion,
    				'orden' => $orden
    			);
				$id_turno = $db->insert('ins_turno', $turno);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el turno con identificador número ' . $id_turno . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				// set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				// redirect('?/materia/listar');
				echo 2;
			}
		}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>