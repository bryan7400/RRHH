<?php

// Verifica si es una peticion post
if (is_post()) {
	
	// Verifica la existencia de los datos enviados
	if (isset($_POST['codigo']) && isset($_POST['id_empleado']) && isset($_POST['nombres']) && isset($_POST['paterno']) && isset($_POST['materno']) && isset($_POST['genero']) && isset($_POST['fecha_nacimiento']) && isset($_POST['telefono']) && isset($_POST['cargo_id'])) {
		
		// Obtiene los datos del empleado
		$id_empleado      = trim($_POST['id_empleado']);
		$codigo           = trim($_POST['codigo']);
		$nombres          = trim($_POST['nombres']);
		$paterno          = trim($_POST['paterno']);
		$materno          = trim($_POST['materno']);
		$genero           = trim($_POST['genero']);
		$fecha_nacimiento = trim($_POST['fecha_nacimiento']);
		$ci 			  = trim($_POST['ci']);
		$direccion 		  = trim($_POST['direccion']);
		$telefono         = trim($_POST['telefono']);
		$cargo            = trim($_POST['cargo_id']);
		$procedencia      = trim($_POST['procedencia_id']);
		
		// Instancia el empleado
		$empleado = array(
			'codigo'           => $codigo,
			'nombres'          => $nombres,
			'paterno'          => $paterno,
			'materno'          => $materno,
			'genero'           => $genero,
			'fecha_nacimiento' => date_encode($fecha_nacimiento),
			'ci'         	   => $ci,
			'direccion'        => $direccion,
			'telefono'         => $telefono,
			'cargo_id'         => $cargo,
			'procedencia_id'   => $procedencia,
			'observacion' 	   => ''
		);
		
		// Verifica si es creacion o modificacion
		if ($id_empleado > 0) {
			// Genera la condicion
			$condicion = array('id_empleado' => $id_empleado);
			
			// Actualiza la informacion
			$db->where($condicion)->update('sys_empleados', $empleado);
			
			// Instancia la variable de notificacion
			$_SESSION[temporary] = array(
				'alert' => 'success',
				'title' => 'Actualización satisfactoria!',
				'message' => 'El registro se actualizó correctamente.'
			);
		} else {
			// Guarda la informacion
			$db->insert('sys_empleados', $empleado);
			
			// Instancia la variable de notificacion
			$_SESSION[temporary] = array(
				'alert' => 'success',
				'title' => 'Adición satisfactoria!',
				'message' => 'El registro se guardó correctamente.'
			);
		}
		
		// Redirecciona a la pagina principal
		redirect('?/empleados/listar');
	} else {
		// Error 401
		require_once bad_request();
		exit;
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>