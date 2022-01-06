<?php
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL JORGE LUIS)
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
// if (is_post()) {
    
	// Verifica la existencia de datos
	// if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		$usuario = clear($_POST['usuario']);
        $contrasenia = clear($_POST['contrasenia']);
        $usuario_receptor = clear($_POST['usuario_receptor']);

			// Encripta la contraseña para compararla en la base de datos
		$usuario = md5($usuario);
		$contrasenia = encrypt($contrasenia);
 
		// Obtiene los datos del usuario
		$usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		
		// Verifica la existencia del usuario 
		if ($usuario) {
			// Obtiene los productos
			$notificaciones = $db->query("SELECT * FROM not_notificaciones n WHERE  usuario_receptor = $usuario_receptor AND salida_servidor = '0'")->fetch();
           // var_dump($estudiantes_cursos);exit();
			// Instancia el objeto
			$respuesta = array(
				'estado' => 's',
				'notificacion' => $notificaciones 
			);

			// Devuelve los resultados
			echo json_encode($respuesta);
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'n'));
		}
	// } else {
	// 	// Devuelve los resultados
	// 	echo json_encode(array('estado' => 'n usuario'));
	// }
// } else {
// 	// Devuelve los resultados
// 	echo json_encode(array('estado' => 'npost'));
// }
?>