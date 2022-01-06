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
		if (isset($_POST['descripcion_area']) && isset($_POST['ponderado_area'])) {
			// Obtiene los datos
			$id_area_calificacion = (isset($_POST['id_area'])) ? clear($_POST['id_area']) : 0;
			$descripcion = clear($_POST['descripcion_area']);
			$obtencion_nota = clear($_POST['obtencion_nota']);
			$ponderado = clear($_POST['ponderado_area']);
			
            // obtiene la gestion
            $gestion_id = $_gestion['id_gestion'];
			
			// Instancia el area
			$area = array(
				'descripcion' => $descripcion,
				'obtencion_nota' => $obtencion_nota,
				'ponderado' => $ponderado,
				'gestion_id' => $gestion_id
			);
			
			// Verifica si es creacion o modificacion
			if ($id_area_calificacion > 0) {
				// Modifica el area
				$db->where('id_area_calificacion', $id_area_calificacion)->update('cal_area_calificacion', $area);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el area calificacion con identificador número ' . $id_area_calificacion . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				echo 1;
				// Redirecciona la pagina
				// redirect('?/area/ver/' . $id_area_calificacion);
			} else {
				// Crea el area
				$id_area_calificacion = $db->insert('cal_area_calificacion', $area);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el area calificacion con identificador número ' . $id_area_calificacion . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				echo 2;
				// Redirecciona la pagina
				// redirect('?/area/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	//	echo 3;
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