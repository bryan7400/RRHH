<?php
// Verifica la peticion post
if (is_post()) {
	// Obtiene los parametros
	$tipo = (isset($params[0])) ? $params[0] : 0;
	$id_empleado = (isset($params[1])) ? $params[1] : 0;

	// Obtiene el empleado
	$empleado = $db->select('id_empleado')->from('sys_empleados')->where('id_empleado', $id_empleado)->fetch_first();

	// Verifica si existen el empleado
	if ($empleado) {
		// Verifica si es antes o despues
		if ($tipo == 'antes') {
			$id_empleado = $db->query("select ifnull(max(id_empleado), (select max(id_empleado) from sys_empleados)) as id_empleado from sys_empleados where id_empleado < '$id_empleado'")->fetch_first();
			$id_empleado = $id_empleado['id_empleado'];
		} else {
			$id_empleado = $db->query("select ifnull(min(id_empleado), (select min(id_empleado) from sys_empleados)) as id_empleado from sys_empleados where id_empleado > '$id_empleado'")->fetch_first();
			$id_empleado = $id_empleado['id_empleado'];
		}

		// Redirecciona la pagina
		redirect('?/empleados/ver/' . $id_empleado);
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