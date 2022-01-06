<?php
// Verifica la peticion post
if (is_post()) {
	// Obtiene los parametros
	$id_empleado = (isset($params[0])) ? $params[0] : 0;

	// Obtiene los datos
	$id_empleado = explode('-', $id_empleado);

	// Obtiene los empleados
	$empleados = $db->select('id_empleado')->from('sys_empleados')->where_in('id_empleado', $id_empleado)->fetch();
	
	// Verifica si existen los empleados
	if ($empleados) {
		// Instancia el empleado
		$empleado = array('activo' => 'n');

		// Modifica el empleado
		$db->where_in('id_empleado', $id_empleado)->update('sys_empleados', $empleado);
		
		// Guarda el proceso
		$db->insert('sys_procesos', array(
			'fecha_proceso' => date('Y-m-d'),
			'hora_proceso'  => date('H:i:s'),
			'proceso'       => 'u',
			'nivel'         => 'm',
			'detalle'       => 'Se bloqueó a los empleados con identificador número ' . implode(', ', $id_empleado) . '.',
			'direccion'     => $_location,
			'usuario_id'    => $_user['id_user']
		));
		
		$_SESSION[temporary] = array(
			'alert'   => 'success',
			'title'   => 'Modificación exitosa!',
			'message' => ' empleados fueron bloqueados satisfactoriamente.'
		);
		
		// Redirecciona la pagina
		redirect(back());
	} else {
		// Error 404
		require_once not_found();
		exit;
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>