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
		// Verifica la existencia de datos
		if (isset($_POST['nombres']) && isset($_POST['paterno']) && isset($_POST['materno']) && isset($_POST['genero']) && isset($_POST['fecha_nacimiento']) && isset($_POST['ci']) && isset($_POST['procedencia_id']) && isset($_POST['direccion']) && isset($_POST['telefono']) && isset($_POST['cargo_id']) && isset($_POST['sucursal_id']) && isset($_POST['observacion'])) {
			// Obtiene los datos
			$id_empleado = (isset($_POST['id_empleado'])) ? clear($_POST['id_empleado']) : 0;
			$nombres = clear($_POST['nombres']);
			$paterno = clear($_POST['paterno']);
			$materno = clear($_POST['materno']);
			$genero = clear($_POST['genero']);
			$fecha_nacimiento = clear($_POST['fecha_nacimiento']);
			$ci = clear($_POST['ci']);
			$procedencia_id = clear($_POST['procedencia_id']);
			$direccion = clear($_POST['direccion']);
			$telefono = clear($_POST['telefono']);
			$cargo_id = clear($_POST['cargo_id']);
			$sucursal_id = clear($_POST['sucursal_id']);
			$observacion = clear($_POST['observacion']);
			
			// Instancia el empleado
			$empleado = array(
				'nombres' => $nombres,
				'paterno' => $paterno,
				'materno' => $materno,
				'genero' => $genero,
				'fecha_nacimiento' => date_encode($fecha_nacimiento),
				'ci' => $ci,
				'procedencia_id' => $procedencia_id,
				'direccion' => $direccion,
				'telefono' => $telefono,
				'cargo_id' => $cargo_id,
				'sucursal_id' => $sucursal_id,
				'observacion' => $observacion
			);
			
			// Verifica si es creacion o modificacion
			if ($id_empleado > 0) {
				// Modifica el empleado
				$db->where('id_empleado', $id_empleado)->update('per_empleados', $empleado);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el empleado con identificador número ' . $id_empleado . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/empleados/ver/' . $id_empleado);
			} else {
				// Crea el empleado
				$id_empleado = $db->insert('per_empleados', $empleado);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el empleado con identificador número ' . $id_empleado . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/empleados/ver/' . $id_empleado);
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