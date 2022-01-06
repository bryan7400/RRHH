<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion ajax
if (is_ajax()) {
	// Verifica la existencia de datos
	if (isset($_POST['username'])) {
		// Obtiene los datos del usuario
		$username = $_POST['username'];

		// Obtiene los usuarios con el valor buscado
		$usuario = $db->select('id_user, username')->from('sys_users')->where('username', $username)->fetch_first();

		// Verifica si existe coincidencias
		if ($usuario) {
			$response = array('valid' => false, 'message' => 'El nombre "' . $usuario['username'] . '" no esta disponible');
		} else {
			$response = array('valid' => true);
		}

		// Define las cabeceras
		header('Content-Type: application/json');

		// Devuelve los resultados
		echo json_encode($response);
	} else {
		// Error 400
		require_once bad_request();
		exit;
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>