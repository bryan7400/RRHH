<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
//if (is_post()) {
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Obtiene los parametros
		$id_punto = (isset($_params[0])) ? $_params[0] : 0;
		$id_ruta = (isset($_params[1])) ? $_params[1] : 0;
		
		// Obtiene el puntos
		$puntos = $db->from('gon_puntos')->where('id_punto', $id_punto)->fetch_first();
		
		// Verifica si existe el puntos
		if ($puntos) {
			// Elimina el puntos
			//$db->delete()->from('gon_puntos')->where('id_punto', $id_punto)->limit(1)->execute();
			$id_rutares=$db->where('id_punto', $id_punto)->update('gon_puntos', array('estado' => '0'));
			// Verifica la eliminacion
			if ($db->affected_rows) {
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'd',
					'nivel' => 'm',
					'detalle' => 'Se eliminó el puntos con identificador número ' . $id_punto . '.',
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
			redirect('?/gon-rutas/ver/'.$id_ruta);
			exit;
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	//} else {
		// Redirecciona la pagina
	//	redirect('?/puntos/listar');
	//}
//} else {
	// Error 404
//	require_once not_found();
//	exit;
//}

?>