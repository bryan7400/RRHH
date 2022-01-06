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
		$id_empleados = (isset($_params[0])) ? $_params[0] : 0;

		// Obtiene los datos
		$id_empleados = explode('-', $id_empleados);

		// Obtiene los empleados
		$empleados = $db->select('id_empleado')->from('per_empleados')->where_in('id_empleado', $id_empleados)->fetch_first();

		// Verifica si existen los empleados
		if ($empleados) {


			// Verifica la existencia de datos
			if (isset($_POST['tarjeta'])) {

				// Obtiene los datos
				//$fecha_salario = clear($_POST['fecha_salario']);
				$tarjeta = clear($_POST['tarjeta']);
				//$observacion = clear($_POST['observacion']);
				//var_dump($id_empleados);exit();

				// Instancia el salario
				$pin = array(
					'tarjeta' => $tarjeta,
				);

				// Recorre todos los ids
				// foreach ($id_empleados as $nro => $id_empleado) {
				// 	// Adiciona el id
				// 	$salario['empleado_id'] = $id_empleado;

					// Crea el salario
					// $db->insert('per_salarios', $salario);

					// Modifica el empleado
				    $ver=$db->where('id_empleado', $empleados['id_empleado'])->update('per_empleados', $pin);
				    //var_dump($ver);exit();
				//}

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