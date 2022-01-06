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
		if (isset($_POST['tipo_descuento']) && isset($_POST['porcentaje']) && isset($_POST['descuento']) && isset($_POST['gestion_id'])) {
			// Obtiene los datos
			$id_tipo_descuento = (isset($_POST['id_tipo_descuento'])) ? clear($_POST['id_tipo_descuento']) : 0;
			$tipo_descuento = clear($_POST['tipo_descuento']);
			$porcentaje = clear($_POST['porcentaje']);
			$descuento = clear($_POST['descuento']);
			$gestion_id = clear($_POST['gestion_id']);
			
			// Instancia el tipo_descuento
			$tipo_descuento = array(
				'tipo_descuento' => $tipo_descuento,
				'porcentaje' => $porcentaje,
				'descuento' => $descuento,
				'gestion_id' => $gestion_id
			);
			
			// Verifica si es creacion o modificacion
			if ($id_tipo_descuento > 0) {
				// Modifica el tipo_descuento
				$db->where('id_tipo_descuento', $id_tipo_descuento)->update('pen_tipo_descuento', $tipo_descuento);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el tipo descuento con identificador número ' . $id_tipo_descuento . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/tipo_descuento/ver/' . $id_tipo_descuento);
			} else {
				// Crea el tipo_descuento
				$id_tipo_descuento = $db->insert('pen_tipo_descuento', $tipo_descuento);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el tipo descuento con identificador número ' . $id_tipo_descuento . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/tipo_descuento/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/tipo_descuento/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>