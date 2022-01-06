<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion ajax
if (is_ajax()) {
	// Instancia el objeto
	$datetime = array(
		'date' => date_decode(date('Y-m-d'), $_format),
		'day' => date('N'),
		'hours' => date('H'),
		'minutes' => date('i'),
		'seconds' => date('s')
	);
	
	// Define las cabeceras
	header('Content-Type: application/json');
	
	// Devuelve los resultados
	echo json_encode($datetime);
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>