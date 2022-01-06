<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion ajax
if (is_ajax()) {

	//var_dump('expression');exit();
	//$pin='';
	// Obtiene la pin
	 $pin = $_POST['pin'];
	// $uno = $_POST['uno'];
	// $dos = $_POST['dos'];
	// $tres = $_POST['tres'];
	// $cuatro = $_POST['cuatro'];
	// $pin=$uno.$dos.$tres.$cuatro;
	//var_dump($pin);exit();

	// Verifica la pin
	//if ($pin) {
	if (preg_match('/^[a-z\d]+$/', $pin)) {
		// Obtiene el empleado
		$empleado = $db->select('id_empleado')->from('per_empleados')->where('tarjeta', $pin)->fetch_first();

		// Obtiene la empleado
		$id_empleado = ($empleado) ? $empleado['id_empleado'] : 0;

		// Envia respuesta
		echo json_encode($id_empleado);
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