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
		if (isset($_POST['menu']) && isset($_POST['icono']) && isset($_POST['ruta']) && isset($_POST['antecesor_id'])) {
			// Obtiene los datos
			$id_menu = (isset($_POST['id_menu'])) ? clear($_POST['id_menu']) : 0;
			$menu = clear($_POST['menu']);
			$icono = clear($_POST['icono']);
			$ruta = clear($_POST['ruta']);
			$antecesor_id = clear($_POST['antecesor_id']);

			// Genera el modulo
			$modulo = '';
			if ($ruta != '') {
				$modulo = explode('/', $ruta);
				$modulo = $modulo[1];
			}

			// Instancia el menu
			$menu = array(
				'menu' => $menu,
				'icono' => $icono,
				'ruta' => $ruta,
				'modulo' => $modulo,
				'orden' => '0',
				'antecesor_id' => $antecesor_id
			);

			// Verifica si es creacion o modificacion
			if ($id_menu > 0) {
				// Modifica el menu
				$db->where('id_menu', $id_menu)->update('sys_menus', $menu);
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/generador-menus/ver/' . $id_menu);
			} else {
				// Crea el menu
				$id_menu = $db->insert('sys_menus', $menu);

				// Instancia el permiso
				$permiso = array(
					'rol_id' => '1',
					'menu_id' => $id_menu,
					'archivos' => ''
				);
				
				// Otorga el permiso al usuario principal
				$id_permiso = $db->insert('sys_permisos', $permiso);
				
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

				// Redirecciona la pagina
				redirect('?/generador-menus/principal');
			}
		} else {
			// Error 400
			require_once bad_request();
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