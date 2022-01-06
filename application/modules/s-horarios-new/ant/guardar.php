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
	if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['id_profesor']) && isset($_POST['codigo_profesor']) && isset($_POST['nombre_profesor']) && isset($_POST['materia_id']) && isset($_POST['nombre_materia'])) {
			// Obtiene los datos
			$id_profesor_materia = (isset($_POST['id_profesor_materia'])) ? clear($_POST['id_profesor_materia']) : 0;
			$id_profesor = clear($_POST['id_profesor']);
			$codigo_profesor = clear($_POST['codigo_profesor']);
			$nombre_profesor = clear($_POST['nombre_profesor']);
			$materia_id = clear($_POST['materia_id']);
			$nombre_materia = clear($_POST['nombre_materia']);
			
			// Instancia el profesor_materia
			$profesor_materia = array(
				'id_profesor' => $id_profesor,
				'codigo_profesor' => $codigo_profesor,
				'nombre_profesor' => $nombre_profesor,
				'materia_id' => $materia_id,
				'nombre_materia' => $nombre_materia
			);
			
			// Verifica si es creacion o modificacion
			if ($id_profesor_materia > 0) {
				// Modifica el profesor_materia
				$db->where('id_profesor_materia', $id_profesor_materia)->update('vista_profesor_materia', $profesor_materia);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el a profesor materia con identificador número ' . $id_profesor_materia . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/profesor_materia/ver/' . $id_profesor_materia);
			} else {
				// Crea el profesor_materia
				$id_profesor_materia = $db->insert('vista_profesor_materia', $profesor_materia);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el a profesor materia con identificador número ' . $id_profesor_materia . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/profesor_materia/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/profesor_materia/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>