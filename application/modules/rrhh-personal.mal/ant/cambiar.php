<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	if (isset($_POST[get_csrf()])) {
		// Obtiene los parametros
		$tipo = (isset($_params[0])) ? $_params[0] : 0;
		$id_empleado = (isset($_params[1])) ? $_params[1] : 0;

		// Obtiene el empleado
		$empleado = $db->select('id_empleado')->from('per_empleados')->where('id_empleado', $id_empleado)->fetch_first();

		// Verifica si existen el empleado
		if ($empleado) {
			// Verifica si es antes o despues
			if ($tipo == 'antes') {
				$id_empleado = $db->query("select ifnull(max(id_empleado), (select max(id_empleado) from per_empleados)) as id_empleado from per_empleados where id_empleado < '$id_empleado'")->fetch_first();
				$id_empleado = $id_empleado['id_empleado'];
			} else {
				$id_empleado = $db->query("select ifnull(min(id_empleado), (select min(id_empleado) from per_empleados)) as id_empleado from per_empleados where id_empleado > '$id_empleado'")->fetch_first();
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
		// Redirecciona la pagina
		redirect('?/empleados/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>