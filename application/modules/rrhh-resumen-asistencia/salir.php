<?php
// Verifica la peticion post
// var_dump($_REQUEST);
// exit();


if (sizeof($_GET)>0) {
	
	// Obtiene los parametros
	$id_empleado   = (isset($_params[0])) ? $_params[0] : 0;
	$id_asistencia = (isset($_params[1])) ? $_params[1] : 0;
	$salida        = clear($_POST['salida']);

	// echo "<hr>";
	// var_dump ($id_empleado . " - " . $id_asistencia ." - ". $salida);
	// exit();
	$asistencia = $db->from('per_asistencias')->where('id_asistencia', $id_asistencia)->fetch_first();
	$fecha = $asistencia['fecha_asistencia'];

	$db->where(array('id_asistencia' => $id_asistencia, 'empleado_id' => $id_empleado))->update('per_asistencias', array('salida' => $fecha . ' ' . $salida));

	redirect(back());
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>