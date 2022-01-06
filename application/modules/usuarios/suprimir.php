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
		$id_usuario = (isset($_params[0])) ? $_params[0] : 0;

		// Obtiene el nombre del avatar
		$avatar = $db->from('sys_users')->where('id_user', $id_usuario)->fetch_first();
		$avatar = $avatar['avatar'];

		// Verifica si el antiguo avatar existe
		if ($avatar != '') {
			// Verifica si existe el antiguo avatar
			if ($avatar != '') {
				// Elimina el avatar
				file_delete(files . '/profiles/' . $avatar);
			}
		}

		// Elimina el avatar del usuario
		$db->where('id_user', $id_usuario)->update('sys_users', array('avatar' => ''));

		// Guarda el proceso
		$db->insert('sys_procesos', array(
			'fecha_proceso' => date('Y-m-d'),
			'hora_proceso' => date('H:i:s'),
			'proceso' => 'u',
			'nivel' => 'l',
			'detalle' => 'Se modificó la imagen del usuario con identificador número ' . $id_usuario . '.',
			'direccion' => $_location,
			'usuario_id' => $_user['id_user']
		));
	
		// Crea la notificacion
		set_notification('success', 'Modificación exitosa!', 'La imagen del usuario se modificó satisfactoriamente.');

		// Redirecciona la pagina
		redirect('?/usuarios/ver/' . $id_usuario);
	} else {
		// Redirecciona la pagina
		redirect('?/usuarios/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>