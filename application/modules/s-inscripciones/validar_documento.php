<?php

/**
 * SimplePHP - Simple Framework PHP
 * 
 * @package  SimplePHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica si es una peticion ajax
if (is_ajax()) {
    
	// Verifica la existencia de los datos enviados
	if (isset($_POST['numero_documento'])) {
		// Obtiene los datos del producto
		$numero_documento = trim($_POST['numero_documento']);

		// Obtiene los productos con el valor buscado
		$persona_est = $db->select('id_persona, numero_documento')->from('sys_persona')->where('numero_documento', $numero_documento)->fetch_first();

		// Verifica si existe coincidencias
		if ($persona_est) {
			$response = array('valid' => false, 'message' => 'El numero de identidad "' . $producto['codigo'] . '" ya fue registrado');
		} else {
			$response = array('valid' => true);
		}

		// Devuelve los resultados
		echo json_encode($response);
	} else {
		// Error 401
		require_once bad_request();
		exit;
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>