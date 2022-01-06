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
		if (isset($_POST['id_user']) && isset($_POST['password'])) {
			// Obtiene los datos
			$id_usuario = clear($_POST['id_user']);
			$password = $_POST['password'];

			// Instancia el usuario
			$usuario = array(
				'password' => encrypt($password)
			);

			// Modifica el usuario
			$db->where('id_user', $id_usuario)->update('sys_users', $usuario);
			
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'h',
				'detalle' => 'Se modificó la contraseña del usuario con identificador número ' . $id_usuario . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
			
			// Crea la notificacion
			set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');

			// Redirecciona la pagina
			redirect('?/usuarios/ver/' . $id_usuario);
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
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