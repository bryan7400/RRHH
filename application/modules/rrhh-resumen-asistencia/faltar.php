<?php

// Verifica la peticion post
if (is_post()) {
	
	// Obtiene los parametros
	$id_empleado = (isset($params[0])) ? $params[0] : 0;
	$fecha_falta = (isset($params[1])) ? $params[1] : 0;
	$entrada     = clear($_POST['entrada']);
	$salida      = clear($_POST['salida']);

	$asistencia= array(
		'empleado_id'      => $id_empleado,
		'fecha_asistencia' => $fecha_falta,
		'entrada'          => $fecha_falta.' '.$entrada,
		'salida'           => $fecha_falta.' '.$salida,
		'estado'           => 'c'
	);

	$db->insert('per_asistencias',$asistencia);

	redirect(back());
	
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>