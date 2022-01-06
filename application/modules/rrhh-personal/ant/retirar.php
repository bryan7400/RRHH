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
		$id_empleado = (isset($_params[0])) ? $_params[0] : 0;

		// Obtiene el empleado
		$empleado = $db->select('id_empleado')->from('per_empleados')->where('id_empleado', $id_empleado)->fetch_first();

		// Obtiene la fecha de hoy
		$hoy = now();

		// Verifica si existen el empleado
		if ($empleado) {
			// Verifica la existencia de datos
			if (isset($_POST['fecha_final']) && isset($_POST['observacion_final'])) {
				// Obtiene los datos
				$fecha_final = clear($_POST['fecha_final']);
				$observacion_final = clear($_POST['observacion_final']);

				// Instancia el contrato
				$contrato = array(
					'fecha_final' => date_encode($fecha_final),
					'observacion_final' => $observacion_final
				);

				// Obtiene el anterior contrato
				$anterior = $db->query("select a.* from per_contratos a left join per_contratos b on a.empleado_id = b.empleado_id and a.fecha_contrato < b.fecha_contrato where b.fecha_contrato is null and a.empleado_id = '$id_empleado'")->fetch_first();

				// Modifica el contarto
				$db->where('id_contrato', $anterior['id_contrato'])->update('per_contratos', $contrato);

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
		// Redirecciona la pagina
		redirect('?/empleados/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>