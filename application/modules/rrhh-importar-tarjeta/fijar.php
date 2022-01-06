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
		if (isset($_POST['fecha_salario']) && isset($_POST['salario']) && isset($_POST['observacion'])) {
			// Obtiene los datos
			$fecha_salario = clear($_POST['fecha_salario']);
			$salario = clear($_POST['salario']);
			$observacion = clear($_POST['observacion']);

			// Instancia el salario
			$salario = array(
				'fecha_salario' => date_encode($fecha_salario) . ' ' . date('H:i:s'),
				'salario'       => $salario,
				'observacion'   => $observacion,
				'user_id'    	=> $_user['id_user']
			);

			// Recorre todos los ids
			foreach ($id_empleados as $nro => $id_empleado) {
				// Adiciona el id
				$salario['empleado_id'] = $id_empleado;

				// Crea el salario
				$db->insert('per_salarios', $salario);
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