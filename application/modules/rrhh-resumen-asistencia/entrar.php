<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// var_dump($_GET);
// exit();
 
// Verifica la peticion post
if (sizeof($_GET)>0) {
	// Verifica la cadena csrf
	// if (isset($_GET[get_csrf()])) {

	// 	// var_dump($_GET);
	// 	// exit();

		// Obtiene los parametros
		$id_empleado = (isset($_params[0])) ? $_params[0] : 0;
		$id_asistencia = (isset($_params[1])) ? $_params[1] : 0;
		$entrada = clear($_POST['entrada']);

		$asistencia = $db->from('per_asistencias')->where('id_asistencia', $id_asistencia)->fetch_first();
		$fecha = explode(' ', $asistencia['entrada']);
		$fecha = array_shift($fecha);

		$db->where(array('id_asistencia' => $id_asistencia, 'empleado_id' => $id_empleado))->update('per_asistencias', array('entrada' => $fecha . ' ' . $entrada));

		redirect(back());
	// } else {
	// 	// Redirecciona la pagina
	// 	redirect('?/empleados/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>