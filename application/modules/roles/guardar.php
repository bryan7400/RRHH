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
		if (isset($_POST['rol']) && isset($_POST['descripcion'])) {
			// Obtiene los datos
			$id_rol = (isset($_POST['id_rol'])) ? clear($_POST['id_rol']) : 0;
			$rol = clear($_POST['rol']);
			$descripcion = clear($_POST['descripcion']);
			
			// Instancia el rol
			$rol = array(
				'rol' => $rol,
				'descripcion' => $descripcion
			);
			
			// Verifica si es creacion o modificacion
			if ($id_rol > 0) {				
				// Modifica el rol
				$db->where('id_rol', $id_rol)->update('sys_roles', $rol);

				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'h',
					'detalle' => 'Se modificó el rol con identificador número ' . $id_rol . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');

				// Redirecciona la pagina
				redirect('?/roles/listar/' . $id_rol);
			} else {
				// Crea el rol
				$id_rol = $db->insert('sys_roles', $rol);

				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'h',
					'detalle' => 'Se creó el rol con identificador número ' . $id_rol . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

				// Redirecciona la pagina
				redirect('?/roles/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/roles/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>