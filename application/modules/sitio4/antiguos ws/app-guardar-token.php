<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  WEB SERVICES
 * @author  EDUCHECK MARIBEL JORGE LUIS
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {
	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		// Obtiene los datos
		$usuario     = clear($_POST['usuario']);
        $contrasenia = clear($_POST['contrasenia']);
        $token       = clear($_POST['token']);
        $imei        = clear($_POST['imei']);

		// Encripta la contraseña para compararla en la base de datos
		$usuario = md5($usuario);
		$contrasenia = encrypt($contrasenia);

		// Obtiene los datos del usuario
		$usuario = $db->select('id_user, username, email, avatar, rol_id, visible')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		// Verifica la existencia del usuario
		if ($usuario) {
            
            //Guardamos el token el usuario correspondiente
            $id_usuario = $usuario['id_user'];
            
			$db->where('id_user', $id_usuario)->update('sys_users', array('token'=>$token,'imei'=>$imei));
			

			//armamos el array para ver la confirmacion y que devolveremos
			$usuario = array(
				'estado' => 's',
				'id_usuario' => $usuario['id_user'],
			);		

			// Devuelve los resultados
			echo json_encode($usuario);
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'n'));
		}
	} else {
		// Devuelve los resultados
		echo json_encode(array('estado' => 'n'));
	}
} else {
	// Devuelve los resultados
	echo json_encode(array('estado' => 'n'));
}

?>