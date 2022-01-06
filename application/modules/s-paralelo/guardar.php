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
		if (isset($_POST['nombre_paralelo']) && isset($_POST['descripcion'])) {
			// Obtiene los datos
			$id_paralelo = (isset($_POST['id_paralelo'])) ? clear($_POST['id_paralelo']) : 0;
			$nombre_paralelo = clear($_POST['nombre_paralelo']);
			$color_paralelo = clear($_POST['color_paralelo']);
			$descripcion = clear($_POST['descripcion']);
			
			// Instancia el paralelo
			$paralelo = array(
				'nombre_paralelo' => $nombre_paralelo,
				'color_paralelo' => $color_paralelo,
				'descripcion' => $descripcion
			);
			
			// Verifica si es creacion o modificacion
			if ($id_paralelo > 0) {
				// Modifica el paralelo
				$db->where('id_paralelo', $id_paralelo)->update('ins_paralelo', $paralelo);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el paralelo con identificador número ' . $id_paralelo . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/paralelo/ver/' . $id_paralelo);
			    echo 1;
			} else {
				// Crea el paralelo
				$id_paralelo = $db->insert('ins_paralelo', $paralelo);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el paralelo con identificador número ' . $id_paralelo . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/paralelo/listar');
				echo 2;
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	// } else {
	// 	// Redirecciona la pagina
	// 	redirect('?/paralelo/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>