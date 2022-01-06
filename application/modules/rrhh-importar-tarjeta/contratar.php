<?php
// Verifica la peticion post
if (is_post()) {
	
	// Obtiene los parametros
	$id_empleado = (isset($params[0])) ? $params[0] : 0;

	// Obtiene el empleado
	$empleado = $db->select('id_empleado')->from('sys_empleados')->where('id_empleado', $id_empleado)->fetch_first();

	// Verifica si existen el empleado
	if ($empleado) {
		// Verifica la existencia de datos
		if (isset($_POST['fecha_contrato']) && isset($_POST['fecha_inicial']) && isset($_POST['observacion_inicial']) && isset($_POST['fecha_final']) && isset($_POST['observacion_final'])) {
			
			// Obtiene los datos
			$fecha_contrato      = clear($_POST['fecha_contrato']);
			$fecha_inicial       = clear($_POST['fecha_inicial']);
			$observacion_inicial = clear($_POST['observacion_inicial']);
			$fecha_final         = clear($_POST['fecha_final']);
			$observacion_final   = clear($_POST['observacion_final']);
			
			// Instancia el contrato
			$contrato = array(
				'fecha_contrato'      => date_encode($fecha_contrato) . ' ' . date('H:i:s'),
				'fecha_inicial'       => date_encode($fecha_inicial),
				'observacion_inicial' => $observacion_inicial,
				'fecha_final'         => (empty($fecha_final)) ? $fecha_final : date_encode($fecha_final),
				'observacion_final'   => $observacion_final,
				'empleado_id'         => $id_empleado,
				'usuario_id'          => $_user['id_user']
			);
			
			// Crea el contrato
			$db->insert('per_contratos', $contrato);

			// Redirecciona la pagina
			redirect(back());
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
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