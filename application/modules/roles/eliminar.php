<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

$id_rol = (isset($_params[0])) ? $_params[0] : 0;
// Verifica la cadena csrf
if (isset($id_rol)) {
	// Obtiene los parametros
	$id_rol = (isset($_params[0])) ? $_params[0] : 0;

// 	// Obtiene el rol
// 	$rol = $db->from('sys_roles')->where('id_rol', $id_rol)->fetch_first();

	// Verifica si existe el rol
	if ($id_rol) {
        
        $db->query("UPDATE sys_roles SET estado='I' WHERE id_rol=$id_rol")->execute();

		// Verifica la eliminacion
		if ($db->affected_rows) {
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'd',
				'nivel' => 'h',
				'detalle' => 'Se eliminó (Desactivo) el rol con identificador número ' . $id_rol . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));

			// Crea la notificacion
			set_notification('success', 'Eliminación exitosa!', 'El registro se eliminó satisfactoriamente.');
		} else {
			// Crea la notificacion
			set_notification('danger', 'Eliminación fallida!', 'El registro no pudo ser eliminado.');
		}

		// Redirecciona la pagina
		redirect('?/roles/listar');
	} else {
		// Error 400
		require_once bad_request();
		exit;
	}
} else {
	// Redirecciona la pagina
	redirect('?/roles/listar');
}

?>