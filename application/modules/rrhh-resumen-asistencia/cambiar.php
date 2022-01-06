<?php

// var_dump($_GET);
// exit();

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (sizeof($_GET)>0) {
	
		// var_dump($_GET);
		// exit();
		// Obtiene los parametros
		$tipo = (isset($_params[0])) ? $_params[0] : 0;
		$id_empleado = (isset($_params[1])) ? $_params[1] : 0;
		$fecha = (isset($_params[2])) ? $_params[2] : 0;

		// var_dump($tipo . " - " . $id_empleado. " - ".$fecha);
		// exit();

		// Obtiene el empleado
		//$empleado = $db->select('id_empleado')->from('sys_empleados')->where('id_empleado', $id_empleado)->fetch_first();
		$sqlEmpleado = "SELECT	*
						FROM	per_asignaciones AS a
						INNER JOIN sys_persona AS p ON p.id_persona = a.persona_id
						INNER JOIN per_cargos AS c ON c.id_cargo = a.cargo_id
						WHERE a.estado = 'A'";
		$empleado = $db->query($sqlEmpleado)->fetch_first();

		// Verifica si existen el empleado
		if ($empleado) {
			// Verifica si es antes o despues
			if ($tipo == 'antes') {
				// Resta un dia a la fecha
				$fecha = remove_day($fecha);
			} else {
				// Adiciona un dia a la fecha
				$fecha = add_day($fecha);
			}

			// Redirecciona la pagina
			redirect('?/rrhh-resumen-asistencia/mostrar/' . $id_empleado . '/' . str_replace('/', '-', first_month_day($fecha, $_format)) . '/' . str_replace('/', '-', last_month_day($fecha, $_format)));
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