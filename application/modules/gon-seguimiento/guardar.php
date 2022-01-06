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
		if (isset($_POST['nombre']) && isset($_POST['descripcion']) && isset($_POST['punto_id']) && isset($_POST['estado']) && isset($_POST['usario_registro']) && isset($_POST['fecha_registro']) && isset($_POST['usario_modificacion']) && isset($_POST['fecha_modificacion'])) {
			// Obtiene los datos
			$id_ruta = (isset($_POST['id_ruta'])) ? clear($_POST['id_ruta']) : 0;
			$nombre = clear($_POST['nombre']);
			$descripcion = clear($_POST['descripcion']);
			$punto_id = clear($_POST['punto_id']);
			$estado = clear($_POST['estado']);
			$usario_registro = clear($_POST['usario_registro']);
			$fecha_registro = clear($_POST['fecha_registro']);
			$usario_modificacion = clear($_POST['usario_modificacion']);
			$fecha_modificacion = clear($_POST['fecha_modificacion']);
			
			// Instancia el rutas
			$rutas = array(
				'nombre' => $nombre,
				'descripcion' => $descripcion,
				'punto_id' => $punto_id,
				'estado' => $estado,
				'usario_registro' => $usario_registro,
				'fecha_registro' => $fecha_registro,
				'usario_modificacion' => $usario_modificacion,
				'fecha_modificacion' => $fecha_modificacion
			);
			
			// Verifica si es creacion o modificacion
			if ($id_ruta > 0) {
				// Modifica el rutas
				$db->where('id_ruta', $id_ruta)->update('gon_rutas', $rutas);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el rutas con identificador número ' . $id_ruta . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/rutas/ver/' . $id_ruta);
			} else {
				// Crea el rutas
				$id_ruta = $db->insert('gon_rutas', $rutas);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el rutas con identificador número ' . $id_ruta . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/rutas/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/rutas/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>