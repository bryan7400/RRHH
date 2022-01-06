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
		if (isset($_POST['modulo']) && isset($_POST['visible']) && isset($_POST['descripcion'])) {
			// Obtiene los datos
			$id_modulo = (isset($_POST['id_modulo'])) ? clear($_POST['id_modulo']) : 0;
			$modulo = clear($_POST['modulo']);
			$visible = clear($_POST['visible']);
			$descripcion = clear($_POST['descripcion']);
			
			// Instancia el modulo
			$modulo = array(
				'modulo' => $modulo,
				'visible' => $visible,
				'descripcion' => $descripcion
			);
			
			// Verifica si es creacion o modificacion
			if ($id_modulo > 0) {
				// Modifica el modulo
				$db->where('id_modulo', $id_modulo)->update('con_modulos', $modulo);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el módulo con identificador número ' . $id_modulo . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/modulos/ver/' . $id_modulo);
			} else {
				// Crea el modulo
				$id_modulo = $db->insert('con_modulos', $modulo);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el módulo con identificador número ' . $id_modulo . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/modulos/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/modulos/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>