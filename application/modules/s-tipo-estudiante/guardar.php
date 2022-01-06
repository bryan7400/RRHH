<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post 
if (is_ajax()) {
	//$csrf = set_csrf();
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['nombre_tipo']) && isset($_POST['descripcion_tipo'])) {
		    
			// Obtiene los datos
			$id_tipo            = (isset($_POST['id_tipo'])) ? clear($_POST['id_tipo']) : 0;
			$nombre_tipo        = clear($_POST['nombre_tipo']);
			$descripcion_tipo   = clear($_POST['descripcion_tipo']);
			$monto_beca         = clear($_POST['monto_beca']);
			$descuento          = clear($_POST['descuento']);
			$gestion_id         = $_gestion['id_gestion'];
			
			// Instancia el tipo estudiante
			$tipo = array(
				'nombre_tipo_estudiante' => $nombre_tipo,
				'descripcion'   => $descripcion_tipo,
				'monto_beca'    => $monto_beca,
				'descuento_beca'     => $descuento,
				'fecha_registro' => date('Y-m-d H:i:s'),
				'gestion_id' => $gestion_id
			);
			
			// Verifica si es creacion o modificacion
			if ($id_tipo > 0) {
				// Modifica el tipo estudiante
				$db->where('id_tipo_estudiante', $id_tipo)->update('ins_tipo_estudiante', $tipo);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el tipo estudiante con identificador número ' . $id_tipo . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				echo 1;
				// Redirecciona la pagina
				//redirect('?/tipo estudiante/ver/' . $id_tipo);
			} else {
				// Crea el tipo estudiante
				$id_tipo = $db->insert('ins_tipo_estudiante', $tipo);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el tipo estudiante con identificador número ' . $id_tipo . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				echo 2;
				// Redirecciona la pagina
				//redirect('?/tipo estudiante/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	// } else {
	// 	// Redirecciona la pagina
	// 	//redirect('?/tipo estudiante/listar');
	// 	// Error 404
	// 	require_once not_found();
	// 	exit;
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>