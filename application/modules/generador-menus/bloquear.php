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
		$id_productos = (isset($_params[0])) ? $_params[0] : 0;

		// Obtiene los datos
		$id_productos = explode('-', $id_productos);

		// Obtiene los productos
		$productos = $db->select('id_producto')->from('inv_productos')->where_in('id_producto', $id_productos)->fetch();
		
		// Verifica si existen los productos
		if ($productos) {
			// Instancia el producto
			$producto = array('disponible' => 'n');

			// Modifica el producto
			$db->where_in('id_producto', $id_productos)->update('inv_productos', $producto);
		
			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d H:i:s'),
				'proceso' => 'u',
				'nivel' => 'm',
				'detalle' => 'Se bloqueó los productos con identificador número ' . implode(', ', $id_productos) . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));
		
			// Crea la notificacion
			set_notification('success', 'Modificación exitosa!', 'Los productos fueron bloqueados satisfactoriamente.');
			
			// Redirecciona la pagina
			redirect(back());
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/productos/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>