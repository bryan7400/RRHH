<?php
//Verifica la peticion ajax
if (is_ajax()) {

	// Verifica la existencia de datos
	if (isset($_POST['codigo_terminal'])) {

		$codigo = $_POST['codigo_terminal'];

		$terminales = $db->select('id_terminal')->from('pel_terminales')->where('codigo_terminal',$codigo)->fetch();
		
		// Verifica si existe coincidencias
		if ($terminales) {
			if (sizeof($terminales) > 0) {
				$response = array('valid' => false, 'message' => 'El código "' . $codigo . '" no está disponible');
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