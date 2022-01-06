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
		// Obtiene los parametros
		$id_usuario = (isset($_POST['id_usuario'])) ? $_POST['id_usuario'] : 0;

		//obtiene la fecha actual
		$fecha_actual = date('Y-m-d H:i:s');

		// Verifica si existe el gestion
		if ($id_usuario > 0 ) {

			// Obtiene los usuarios
		    $avatar = $db->from('sys_users')->where('id_user', $id_usuario)->fetch_first();


			// Verifica si existen los usuarios
			if ($avatar) {

				// Elimina los avatares de los usuarios
					$usuario = array(
						'codigo_sesion'	=> '',
					);

                // Modifica el usuario
				$db->where('id_user', $id_usuario)->update('sys_users', $usuario);
				
				// Verifica la eliminacion
				if ($db->affected_rows) {
					// Guarda el proceso
					$db->insert('sys_procesos', array(
						'fecha_proceso' => date('Y-m-d'),
						'hora_proceso' => date('H:i:s'),
						'proceso' => 'u',
						'nivel' => 'm',
						'detalle' => 'Se reseteó el código de usuario con identificador número ' . $id_usuario . '.',
						'direccion' => $_location,
						'usuario_id' => $_user['id_user']
					));
					
					// Crea la notificacion
					echo 1;
				} else {
					// Crea la notificacion
					echo 2;
				}
			} else {
				// Crea la notificacion
				echo 2;
			}

		} else {
			// Error 400
			/*require_once bad_request();
			exit;*/
			echo 2; //no se encontro el registro que se quiere eliminar
		}
	//} else {
		// Redirecciona la pagina
		//redirect('?/gestiones/listar');
	//}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>