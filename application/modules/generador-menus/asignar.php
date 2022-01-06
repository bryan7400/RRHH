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
		$producto_id = (isset($_params[0])) ? $_params[0] : 0;

		// Obtiene el producto
		$producto = $db->select('id_producto')->from('inv_productos')->where('id_producto', $producto_id)->fetch_first();

		// Verifica si existen los productos
		if ($producto) {
			// Verifica la existencia de datos
			if (isset($_POST['unidad_id']) && isset($_POST['precio'])) {
				// Obtiene los datos
				$unidad_id = clear($_POST['unidad_id']);
				$precio = clear($_POST['precio']);

				// Obtiene la asignacion
				$asignacion = $db->from('inv_asignaciones')->where(array('producto_id' => $producto_id, 'unidad_id' => $unidad_id))->fetch_first();

				// Verifica si la asignacion ya fue registrada
				if ($asignacion) {
					// Crea la notificacion
					set_notification('success', 'Asignación exitosa!', 'La unidad se asignó anteriormente.');
				} else {
					// Instancia la asignacion
					$asignacion = array(
						'producto_id' => $producto_id,
						'unidad_id' => $unidad_id
					);

					// Crea la asignacion
					$db->insert('inv_asignaciones', $asignacion);

					// Guarda el proceso
					$db->insert('sys_procesos', array(
						'fecha_proceso' => date('Y-m-d H:i:s'),
						'proceso' => 'u',
						'nivel' => 'l',
						'detalle' => 'Se asignó la unidad con identificador número ' . $unidad_id . ' al producto con identificador número ' . $producto_id . '.',
						'direccion' => $_location,
						'usuario_id' => $_user['id_user']
					));

					// Crea la notificacion
					set_notification('success', 'Asignación exitosa!', 'La unidad se asignó satisfactoriamente.');
				}

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
		redirect(back());
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>