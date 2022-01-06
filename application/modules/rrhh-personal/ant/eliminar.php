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
		$empleados = $db->select('id_empleado')->from('per_empleados')->where_in('id_empleado', $id_empleados)->fetch();
		
		// Verifica si existen los empleados
		if ($empleados) {
			// Elimina las fotos de los empleados
			foreach ($id_empleados as $id_empleado) {
				// Obtiene el foto
				$foto = $db->from('per_empleados')->where('id_empleado', $id_empleado)->fetch_first();
				$foto = $foto['foto'];

				// Verifica si la foto existe
				if ($foto != '') {
					// Elimina la foto
					file_delete(files . '/empleados/' . $foto);

					// Elimina la foto
					file_delete(files . '/empleados/small__' . $foto);
				}
			}

			// Elimina los empleados
			$db->delete()->from('per_empleados')->where_in('id_empleado', $id_empleados)->execute();
			
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Elimina los dependientes
				$db->delete()->from('per_contratos')->where_in('empleado_id', $id_empleados)->execute();
				$db->delete()->from('per_salarios')->where_in('empleado_id', $id_empleados)->execute();
				$db->delete()->from('per_asignaciones')->where_in('empleado_id', $id_empleados)->execute();
				$db->delete()->from('per_asistencias')->where_in('empleado_id', $id_empleados)->execute();

				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'h',
					'detalle' => 'Se eliminó a los empleados con identificador número ' . implode(', ', $id_empleados) . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Eliminación exitosa!', 'Los empleados fueron eliminados satisfactoriamente.');
			} else {
				// Crea la notificacion
				set_notification('primary', 'Eliminación fallida!', 'Los empleados no fueron eliminados.');
			}
			
			// Redirecciona la pagina
			redirect('?/empleados/listar');
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