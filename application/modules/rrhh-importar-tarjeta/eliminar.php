<?php
// Obtiene el id_empleado
$id_empleado = (sizeof($params) > 0) ? $params[0] : 0;

// Obtiene el empleado
$empleado = $db->from('sys_empleados')->where('id_empleado', $id_empleado)->fetch_first();

// Verifica si el empleado existe
if ($empleado) {
	// Elimina el empleado
	$db->delete()->from('sys_empleados')->where('id_empleado', $id_empleado)->limit(1)->execute();

	// Verifica si fue el empleado eliminado
	if ($db->affected_rows) {
		// Guarda el proceso
		$db->insert('sys_procesos', array(
			'fecha_proceso' => date('Y-m-d'),
			'hora_proceso'  => date('H:i:s'),
			'proceso'       => 'd',
			'nivel'         => 'm',
			'detalle'       => 'Se eliminó el empleado con identificador número ' . $id_empleado . '.',
			'direccion'     => $_location,
			'usuario_id'    => $_user['id_user']
		));

		$_SESSION[temporary] = array(
			'alert'   => 'success',
			'title'   => 'Eliminación exitosa!',
			'message' => 'El registro se eliminó satisfactoriamente.'
		);
		
	} else {
		$_SESSION[temporary] = array(
			'alert'   => 'danger',
			'title'   => 'Eliminación fallida!',
			'message' => 'El registro no pudo ser eliminado.'
		);
	}

	// Redirecciona a la pagina principal
	redirect('?/empleados/listar');
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>