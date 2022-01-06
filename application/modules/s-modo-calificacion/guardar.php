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
		if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_final']) && isset($_POST['descripcion_modo'])) {
			// Obtiene los datos
			$id_modo_calificacion = (isset($_POST['id_modo'])) ? clear($_POST['id_modo']) : 0;
            
			$fecha_inicio = clear($_POST['fecha_inicio']);
			$fecha_final = clear($_POST['fecha_final']);
			$descripcion = clear($_POST['descripcion_modo']);
			
			// Instancia el modocalificacion
			$modocalificacion = array(
				'fecha_inicio' => date_encode($fecha_inicio),
				'fecha_final' => date_encode($fecha_final),
				'descripcion' => $descripcion,
				//'gestion_id' => $_gestion['id_gestion']
			);
			
			// Verifica si es creacion o modificacion
			if ($id_modo_calificacion > 0) {
				// instancia para crear 
				$editar = array(
					'usuario_modificacion' => $_user['id_user'],
					'fecha_modificacion' => date('Y-m-d H:i:s')
				);
				// Crea la union de instancias
				$instacia_union = array_merge_recursive($modocalificacion, $editar);

				// Modifica el modocalificacion
				$db->where('id_modo_calificacion', $id_modo_calificacion)->update('cal_modo_calificacion', $instacia_union);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el modo calificacion con identificador número ' . $id_modo_calificacion . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				// set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				// redirect('?/modocalificacion/ver/' . $id_modo_calificacion);
				echo 1;
			} else {
				// instancia para crear 
				$crear = array(
					'usuario_registro' => $_user['id_user'],
					'fecha_registro' => date('Y-m-d H:i:s'),
					'gestion_id' => $_gestion['id_gestion']
				);
				// Crea la union de instancias
				$instacia_union = array_merge_recursive($modocalificacion, $crear);
				
				// Crea el modocalificacion
				$id_modo_calificacion = $db->insert('cal_modo_calificacion', $instacia_union);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el modo calificacion con identificador número ' . $id_modo_calificacion . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				// set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				// redirect('?/modocalificacion/listar');
				echo 2;
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	// } else {
	// 	// Redirecciona la pagina
	// 	redirect('?/modocalificacion/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>