<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
//var_dump($_POST);exit();
// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['nombre'])) {
			// Obtiene los datos
			$id_ruta = (isset($_POST['id_ruta'])) ? clear($_POST['id_ruta']) : 0;
			$ruta = clear($_POST['nombre']);
			$descripcion = clear($_POST['descripcion']);
			$coordenadas = (isset($_POST['wayt'])) ? clear($_POST['wayt']) : 0;

			// Verifica si es creacion o modificacion
			if ($id_ruta > 0) {
				$rutas = array(
					'nombre' => $ruta,
					'descripcion' => $descripcion,					
					'coordenadas' => $coordenadas,	
					'usario_modificacion' => 0,
					'fecha_modificacion' => '0000-00-00 00:00:00'
				);
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
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/rutas/ver/' . $id_ruta);
				
				echo "1";

			} else {
				$rutas = array(
					'nombre' => $ruta,
					'descripcion' => $descripcion,				
					'punto_id' => '0',
					'coordenadas' => $coordenadas,

					'estado' => '1',
					'usario_registro' => $_user['id_user'],
					'fecha_registro' => date('Y-m-d H:i:s'),

					'usario_modificacion' => 0,
					'fecha_modificacion' => '0000-00-00 00:00:00',
					'conductor_gondola_id'=>'-1'
				);

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
				
				echo "1";

				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/gon-rutas/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	//} else {
		// Redirecciona la pagina
	//	redirect('?/gon-rutas/listar');
	//}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>