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
		if (isset($_POST['password'])) {
			// Obtiene los datos
			$password = $_POST['password'];

			// Instancia el usuario
			$usuario = array(
				'password' => encrypt($password)
			);

			// Modifica el usuario
			$db->where('id_user', $_user['id_user'])->update('sys_users', $usuario);

			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'h',
				'detalle' => 'Se modificó la contraseña del usuario con identificador número ' . $_user['id_user'] . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
			
			// Crea la notificacion
			set_notification('success', 'Modificación exitosa!', 'La información de tu perfil se modificó satisfactoriamente.');

			// Redirecciona la pagina
			redirect('?/perfil/mostrar');
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
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