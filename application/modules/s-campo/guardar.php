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
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['nombre_campo']) && isset($_POST['descripcion_campo'])) {
			// Obtiene los datos
			$id_campo      = (isset($_POST['id_campo'])) ? clear($_POST['id_campo']) : 0;
			$nombre_campo  = clear($_POST['nombre_campo']);
			$descripcion_campo     = clear($_POST['descripcion_campo']);
            $orden_campo = clear($_POST['orden_campo']);
            $gestion_id = $_gestion['id_gestion'];
			
			// Instancia el materia
			$materia = array(
				'nombre_campo' => $nombre_campo,
				'descripcion_campo' => $descripcion_campo,
				'orden_campo' => $orden_campo,	
				'gestion_id' => $gestion_id,	
			);
			
			// Verifica si es creacion o modificacion
			if ($id_campo > 0) {
				// Modifica el materia
				$db->where('id_campo', $id_campo)->update('pro_campo', $materia);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el campo con identificador número ' . $id_campo . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				// set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				// redirect('?/materia/ver/' . $id_campo);
				echo 1;
			} else {
				// Crea el materia
				$id_campo = $db->insert('pro_campo', $materia);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el campo con identificador número ' . $id_campo . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']  
				));
				
				// Crea la notificacion
				// set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				// redirect('?/materia/listar');
				echo 2;
			}
		}else{
			// Error 400
			require_once bad_request();
			exit;
		}
	// } else {
	// 	// Redirecciona la pagina
	// 	redirect('?/materia/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>