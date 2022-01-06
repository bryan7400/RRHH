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
		if (isset($_POST['username']) && isset($_POST['password'])) {
			//var_dump($_POST);exit();
			// Obtiene los datos
			$username = clear($_POST['username']); 
			$password = $_POST['password'];
			$remember = (isset($_POST['remember'])) ? 1 : 0;
			$locale = clear($_POST['locale']);
			$id_gestion = 1;

			// Encripta la contraseña para compararla en la base de datos
			$username = md5($username);
			$password = encrypt($password);

			// Obtiene los datos del usuario
			$usuario = $db->select('id_user, rol_id, visible')->from('sys_users')->open_where()->where('md5(username)', $username)->or_where('md5(email)', $username)->close_where()->where(array('password' => $password, 'active' => 's'))->fetch_first();
            
			$gestion = $db->select('id_gestion, gestion')->from('ins_gestion')->open_where()->where('id_gestion', $id_gestion)->close_where()->fetch_first();
			//var_dump($gestion);exit();

			// Verifica la existencia del usuario
			if ($usuario) {
				
				// Obtiene el id del usuario
				$id_usuario = $usuario['id_user'];
				$id_rol = $usuario['rol_id'];

				// Obtiene el estado
				$estado = $usuario['visible'];
			
				// Instancia la variable de sesion con los datos del usuario
				$_SESSION[user] = $usuario;

				// Instancia la variable de sesion con la ubicacion
				$_SESSION[locale] = $locale;

				// Instancia la variable de sesion con el tiempo de inicio de sesion
				$_SESSION[time] = time();

				// Instancia la variable de sesion con los datos de la gestion
				$_SESSION[gest] = $gestion;
				//var_dump($_SESSION[gest]);exit();

				// Verifica si fue marcado la casilla recuerdame
				if ($remember == 1) {
					setcookie(remember, $username . '|' . $password . '|' . $locale, time() + (60 * 60 * timecookie)); 
				} else {
					setcookie(remember, '', time());
				}

				// Instancia el usuario
				$usuario = array(
					'login_at' => date('Y-m-d H:i:s'),
					'logout_at' => '0000-00-00 00:00:00'
				);

				// Actualiza el ultimo ingreso del usuario
				$db->where('id_user', $id_usuario)->update('sys_users', $usuario);

				// Verifica el estado
				if ($estado == 's') {
					// Guarda el proceso
					$db->insert('sys_procesos', array(
						'fecha_proceso' => date('Y-m-d'),
						'hora_proceso' => date('H:i:s'),
						'proceso' => 'r',
						'nivel' => 'h',
						'detalle' => 'Se autenticó los datos del usuario con identificador número ' . $id_usuario . '.',
						'direccion' => $_location,
						'usuario_id' => $id_usuario,
					));
				}

			
				// // Redirecciona la pagina
				// redirect(index_private);
                
                if($id_rol == 1 || $id_rol == 4){
					// Redirecciona la pagina
					redirect('?/sitio/cursos');
                }else{
					// Redirecciona la pagina
					redirect('?/sitio/portal');
                }

			} else {
				// Crea la notificacion
				set_notification('warning', 'Atención!', 'La información enviada no coincide con los registros, asegurese de escribir correctamente sus datos.');

				// Redirecciona al modulo index con error
				redirect('?/sitio/portal');
			}
		} else {
			// Redirecciona al modulo index
			redirect('?/sitio/portal');
		}
	/*} else {
		// Crea la notificacion
		set_notification('danger', 'Advertencia!', 'Usted realizó una acción no permitida, se bloqueará la ip de la terminal que esta accesando al sitio.');

		// Redirecciona al modulo index
		redirect('?/sitio/portal');
	}*/
} else {
	// Redirecciona al modulo index
	redirect('?/sitio/portal');
}

?>