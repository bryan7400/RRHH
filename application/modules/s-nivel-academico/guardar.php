<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
$id_gestion = $_gestion['id_gestion'];
// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['nombre_nivel']) && isset($_POST['descripcion_nivel'])) {
			// Obtiene los datos
			$id_nivel = (isset($_POST['id_nivel'])) ? clear($_POST['id_nivel']) : 0;
			$nombre_nivel = clear($_POST['nombre_nivel']);
			$descripcion_nivel = clear($_POST['descripcion_nivel']);
			$acronimo_nivel = clear($_POST['acronimo_nivel']);
			$tipo_calificacion = clear($_POST['tipo_calificacion']);
			$gestion_id = $_gestion['id_gestion'];
			$color = (isset($_POST['color'])) ? clear($_POST['color']) : '#000000';
			$orden_nivel = (isset($_POST['orden_nivel'])) ? clear($_POST['orden_nivel']) : 0;
			
			// Instancia el stipoestudiante
			$nivel = array(
				'nombre_nivel' => $nombre_nivel,
				'descripcion' => $descripcion_nivel,
				'fecha_registro' => date('Y-m-d H:i:s'),
				'gestion_id' => $gestion_id,
				'color_nivel' => $color,
				'acronimo_nivel' => $acronimo_nivel,
				'tipo_calificacion' => $tipo_calificacion,
				'orden_nivel' => $orden_nivel
			);
			
			// Verifica si es creacion o modificacion
			if ($id_nivel > 0) {
				
				// Modifica el stipoestudiante
				$db->where('id_nivel_academico', $id_nivel)->update('ins_nivel_academico', $nivel);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el tipo estudiante con identificador número ' . $id_nivel . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				echo 2;
				// Redirecciona la pagina
				//redirect('?/s-nivel-academico/ver/' . $id_nivel);
			} else {
				// Crea el s-nivel-academico
				$id_nivel = $db->insert('ins_nivel_academico', $nivel);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el nivel académico con identificador número ' . $id_nivel . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				echo 1;
				// Redirecciona la pagina
				//redirect('?/s-nivel-academico/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	// } else {
	// 	// Redirecciona la pagina
	// 	//redirect('?/s-nivel-academico/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>