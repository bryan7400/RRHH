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
	// if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['nombre_pension']) && isset($_POST['descripcion']) && isset($_POST['monto']) && isset($_POST['mora_dia']) && isset($_POST['fecha_inicio']) && isset($_POST['fecha_final']) && isset($_POST['tipo_estudiante_id']) && isset($_POST['nivel_academico_id']) && isset($_POST['gestion_id'])) {
			// Obtiene los datos
			$id_pensiones = (isset($_POST['id_pensiones'])) ? clear($_POST['id_pensiones']) : 0;
			$nombre_pension = clear($_POST['nombre_pension']);
			$descripcion = clear($_POST['descripcion']);
			$monto = clear($_POST['monto']);
			$mora_dia = clear($_POST['mora_dia']);
			$fecha_inicio = clear($_POST['fecha_inicio']);
			$fecha_final = clear($_POST['fecha_final']);
			$tipo_estudiante_id = clear($_POST['tipo_estudiante_id']);
			$nivel_academico_id = clear($_POST['nivel_academico_id']); 
			$gestion_id = clear($_POST['gestion_id']);
			
			// Instancia el spensiones
			$spensiones = array(
				'nombre_pension' => $nombre_pension,
				'descripcion' => $descripcion,
				'monto' => $monto,
				'mora_dia' => $mora_dia,
				'fecha_inicio' => date_encode($fecha_inicio),
				'fecha_final' => date_encode($fecha_final),
				'tipo_estudiante_id' => $tipo_estudiante_id,
				'nivel_academico_id' => $nivel_academico_id,
				'gestion_id' => $gestion_id
			);
			
			// Verifica si es creacion o modificacion
			if ($id_pensiones > 0) {
				// Modifica el spensiones
				$db->where('id_pensiones', $id_pensiones)->update('pen_pensiones', $spensiones);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el pensiones con identificador número ' . $id_pensiones . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/spensiones/ver/' . $id_pensiones);
			} else {
				// Crea el spensiones
				$id_pensiones = $db->insert('pen_pensiones', $spensiones);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el pensiones con identificador número ' . $id_pensiones . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/spensiones/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	// } else {
	// 	// Redirecciona la pagina
	// 	redirect('?/spensiones/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>