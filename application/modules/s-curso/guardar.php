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
		if (isset($_POST['nombre_aula']) && isset($_POST['descripcion']) && isset($_POST['nivel_academico']) ) {
			// Obtiene los datos
			$id_aula = (isset($_POST['id_aula'])) ? clear($_POST['id_aula']) : 0;
			$nombre_aula = clear($_POST['nombre_aula']);
			$descripcion = clear($_POST['descripcion']);
			$nivel_academico_id = clear($_POST['nivel_academico']);
			$orden = (isset($_POST['orden'])) ? clear($_POST['orden']) : 0;
			
			// Instancia el aula
			$aula = array(
				'nombre_aula' => $nombre_aula,
				'descripcion' => $descripcion,
				'nivel_academico_id' => $nivel_academico_id,
				'anio_escolaridad' => $orden,
				'estado' => 'A',
				'gestion_id' => $_gestion['id_gestion'],
				'usuario_registro' => $_user['id_user'],
				'fecha_registro' => Date('Y-m-d H:i:s'),
				'usuario_modificacion' => 0,
				'fecha_modificacion' => '0000-00-00 00:00:00'
			);
			
			// Verifica si es creacion o modificacion
			if ($id_aula > 0) {
				// Modifica el aula
				$db->where('id_aula', $id_aula)->update('ins_aula', $aula);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el aula con identificador número ' . $id_aula . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/aula/ver/' . $id_aula);
				echo 2;
			} else {
				// Crea el aula
				$id_aula = $db->insert('ins_aula', $aula);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el aula con identificador número ' . $id_aula . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/aula/listar');
				echo 1;
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	//} else {
		// Redirecciona la pagina
		//redirect('?/aula/listar');
	//}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>