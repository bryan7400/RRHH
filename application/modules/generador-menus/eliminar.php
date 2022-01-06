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
		// Obtiene los parametros
		$id_menu = (isset($_params[0])) ? $_params[0] : 0;

		// Obtiene el menu
		$menu = $db->from('sys_menus')->where('id_menu', $id_menu)->fetch_first();

		// Obtiene los menus
		$menus = $db->get('sys_menus');

		// Obtiene estado
		$estado = verificar_submenu($menus, $menu['id_menu']);

		// Verifica si existe el menu
		if ($menu && !$estado) {
			// Elimina el menu
			$db->delete()->from('sys_menus')->where('id_menu', $id_menu)->limit(1)->execute();

			// Elimina los permisos
			$db->delete()->from('sys_permisos')->where('menu_id', $id_menu)->execute();

			// Verifica si fue el menu eliminado
			if ($db->affected_rows) {
				// Crea la notificacion
				set_notification('success', 'Eliminación exitosa!', 'El registro se eliminó satisfactoriamente.');
			} else {
				// Crea la notificacion
				set_notification('danger', 'Eliminación fallida!', 'El registro no pudo ser eliminado.');
			}

			// Redirecciona la pagina
			redirect('?/generador-menus/principal');
		} else {
			// Error 404
			require_once not_found();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/generador-menus/principal');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>