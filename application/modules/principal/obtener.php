<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion ajax
//if (is_ajax()) {
	// Define las cabeceras
	header('Content-Type: application/json');

	$response = array(
		'draw' => 1,
		'recordsFiltered' => 15,
		'recordsTotal' => 15,
		'data' => array(
			array(
				'Tiger Nixon',
				'System Architect',
				'Edinburgh',
				'5421',
				'2011/04/25',
				'$320,800'
			),
			array(
				'Garrett Winters',
				'Accountant',
				'Tokyo',
				'8422',
				'2011/07/25',
				'$170,750'
			)
		)
	);

	/*{
		"data":[
			[
				"Tiger Nixon",
				"System Architect",
				"Edinburgh",
				"5421",
				"2011\/04\/25",
				"$320,800"
			],
			[
				"Garrett Winters",
				"Accountant",
				"Tokyo",
				"8422",
				"2011\/07\/25",
				"$170,750"
			]
		]
	}*/

	// Devuelve los resultados
	echo json_encode($response);
/*} else {
	// Error 404
	require_once not_found();
	exit;
}*/

?>