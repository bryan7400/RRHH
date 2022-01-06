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
		// Obtiene el nombre del avatar
		$avatar = $db->from('sys_users')->where('id_user', $_user['id_user'])->fetch_first();
		$avatar = $avatar['avatar'];

		// Verifica si existe el antiguo avatar
		if ($avatar != '') {
			// Elimina el avatar
			file_delete(files . '/profiles/' . $avatar);
		}

		// Elimina el avatar del usuario
		$db->where('id_user', $_user['id_user'])->update('sys_users', array('avatar' => ''));

		// Guarda el proceso
		$db->insert('sys_procesos', array(
			'fecha_proceso' => date('Y-m-d'),
			'hora_proceso' => date('H:i:s'),
			'proceso' => 'u',
			'nivel' => 'l',
			'detalle' => 'Se modificó la imagen de perfil del usuario con identificador número ' . $_user['id_user'] . '.',
			'direccion' => $_location,
			'usuario_id' => $_user['id_user']
		));
	
		// Crea la notificacion
		set_notification('success', 'Modificación exitosa!', 'La información de tu perfil se modificó satisfactoriamente.');

		// Redirecciona la pagina
		redirect('?/perfil/mostrar');
	} else {
		// Redirecciona la pagina
		redirect('?/perfil/mostrar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>