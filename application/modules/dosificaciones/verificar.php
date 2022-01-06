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
	if (isset($_POST['sucursal_id'])) {
		// Obtiene los datos
		$sucursal_id = $_POST['sucursal_id'];

		// Obtiene la dosificacion
		$dosificacion = $db->from('inv_dosificaciones')->where('institucion_id', $sucursal_id)->order_by('fecha_limite', 'desc')->fetch_first();

		// Verifica las condiciones
		if ($dosificacion) {
			$vigencia = (now() > $dosificacion['fecha_limite']) ? 0 : intval(date_diff(date_create(now()), date_create($dosificacion['fecha_limite']))->format('%a')) + 1;
			if ($vigencia > 5) {
				$response = array('valid' => false, 'message' => 'La dosificación de esta sucursal sigue en vigencia, faltan ' . ($vigencia - 5) . ' días para que pueda realizar una nueva dosificación');
			} else {
				$response = array('valid' => true);
			}
		} else {
			$response = array('valid' => true);
		}

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