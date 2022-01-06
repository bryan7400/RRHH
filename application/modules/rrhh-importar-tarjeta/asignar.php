<?php
// Verifica la peticion post
if (is_post()) {
	// Obtiene los parametros
	$id_empleados = (isset($params[0])) ? $params[0] : 0;

	// Obtiene los datos
	$id_empleados = explode('-', $id_empleados);

	// Obtiene los empleados
	$empleados = $db->select('id_empleado')->from('sys_empleados')->where_in('id_empleado', $id_empleados)->fetch();

	// Verifica si existen los empleados
	if ($empleados) {
		// Verifica la existencia de datos
		if (isset($_POST['fecha_asignacion']) && isset($_POST['horario_id']) && isset($_POST['observacion'])) {
			// Obtiene los datos
			$fecha_asignacion = clear($_POST['fecha_asignacion']);
			$horario_id = $_POST['horario_id'];
			$observacion = clear($_POST['observacion']);

			// Instancia la asignacion
			$asignacion = array(
				'fecha_asignacion' => date_encode($fecha_asignacion) . ' ' . date('H:i:s'),
				'observacion'      => $observacion,
				'user_id'          => $_user['id_user']
			);

			// Recorre todos los ids
			foreach ($id_empleados as  $id_empleado) {
				// Adiciona campos
				$asignacion['empleado_id'] = $id_empleado;
				$asignacion['horario_id'] = implode(',', $horario_id);

				// Crea la asignacion
				$db->insert('per_asignaciones', $asignacion);
			}

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