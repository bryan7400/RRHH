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
	if (isset($_POST['archivo'])) {
		// Obtiene los datos
		$archivo = clear($_POST['archivo']);

		// Verifica si existe el archivo
		if (file_exists(storages . '/' . $archivo)){
			// Elimina el archivo
			unlink(storages . '/' . $archivo);

			// Crea la notificacion
			//set_notification('success', 'Asignación exitosa!', 'La tarjeta se asignó satisfactoriamente.');

			// Envia respuesta
			echo json_encode(1);
		} else {
			// Envia respuesta
			echo json_encode(0);
		}
	} else {
		// Envia respuesta
		echo json_encode(0);
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>