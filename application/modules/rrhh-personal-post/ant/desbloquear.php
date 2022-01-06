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

		// Obtiene los datos
		$id_empleado = explode('-', $id_empleado);

		// Obtiene los empleados
		$empleados = $db->select('id_empleado')->from('per_empleados')->where_in('id_empleado', $id_empleado)->fetch();
		
		// Verifica si existen los empleados
		if ($empleados) {
			// Instancia el empleado
			$empleado = array('activo' => 's');

			// Modifica el empleado
			$db->where_in('id_empleado', $id_empleado)->update('per_empleados', $empleado);
			
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'm',
				'detalle' => 'Se desbloqueó a los empleados con identificador número ' . implode(', ', $id_empleado) . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
			
			// Crea la notificacion
			set_notification('success', 'Modificación exitosa!', 'Los empleados fueron desbloqueados satisfactoriamente.');
			
			// Redirecciona la pagina
			redirect(back());
		} else {
			// Error 404
			require_once not_found();
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