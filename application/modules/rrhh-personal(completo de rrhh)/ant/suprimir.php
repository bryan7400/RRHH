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
		
		// Obtiene el nombre de la foto
		$nombre_foto = $db->from('per_empleados')->where('id_empleado', $id_empleado)->fetch_first();
		$nombre_foto = $nombre_foto['foto'];

		// Verifica si la antigua foto existe
		if ($nombre_foto != '') {
			// Elimina la foto
			file_delete(files . '/empleados/' . $nombre_foto);

			// Elimina la foto
			file_delete(files . '/empleados/small__' . $nombre_foto);
		}

		// Elimina la foto del empleado
		$db->where('id_empleado', $id_empleado)->update('per_empleados', array('foto' => ''));

		// Guarda el proceso
		$db->insert('sys_procesos', array(
			'fecha_proceso' => date('Y-m-d'),
			'hora_proceso' => date('H:i:s'),
			'proceso' => 'u',
			'nivel' => 'l',
			'detalle' => 'Se modificó la foto del empleado con identificador número ' . $id_empleado . '.',
			'direccion' => $_location,
			'usuario_id' => $_user['id_user']
		));
	
		// Crea la notificacion
		set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');

		// Redirecciona la pagina
		redirect(back());
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