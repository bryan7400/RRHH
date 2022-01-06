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
		if (isset($_POST['nombre_materia']) && isset($_POST['descripcion'])) {
			// Obtiene los datos
			$id_materia      = (isset($_POST['id_materia'])) ? clear($_POST['id_materia']) : 0;
			$nombre_materia  = clear($_POST['nombre_materia']);
			$descripcion     = clear($_POST['descripcion']);
			//$nivel_academico = (isset($_POST['nivel_academico'])) ? $_POST['nivel_academico'] : array();			
			
			// Instancia el materia
			$documento = array(
				'nombre' => $nombre_materia,
				'descripcion' => $descripcion,
				//'orden' => 0,
				//'nivel_academico_id' => implode(",", $nivel_academico),
                'estado'=>'A',
				'usuario_registro' => $_user['id_user'],
				'fecha_registro' => date('Y-m-d'),
				'usuario_modificacion' => $_user['id_user'],
				'fecha_modificacion' => date('Y-m-d')				
			);
			
			// Verifica si es creacion o modificacion
			if ($id_materia > 0) {
				// Modifica el materia
				//$db->where('id_materia', $id_materia)->update('pro_materia', $materia);
				$db->where('id_tipo_documento', $id_materia)->update('ins_tipo_documentos', $documento);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el documento con identificador número ' . $id_materia . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				// set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				// redirect('?/materia/ver/' . $id_materia);
				echo 1;
			} else {
				// Crea el materia
				$id_documento = $db->insert('ins_tipo_documentos', $documento);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el documento con identificador número ' . $id_documento . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				// set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				// redirect('?/materia/listar');
				echo 2;
			}
			
		} else if(isset($_POST['id_materia'])&&isset($_POST['accion'])=="editarImagen"&&isset($_POST['imagen_materia'])) {
			//Actualizamos la imagen de la materia
			if(isset($_POST['imagen_materia'])){
				$ruta_temporal = 'files/profiles/temporal/fotos/' . $_POST['imagen_materia']; //ruta temporal
				$nombre = md5(secret . random_string() . $_POST['id_materia']); //encripta el nombre de la imagen a md5
				$ruta_destino = 'files/profiles/materias/' . $nombre . '.jpg'; //ruta de destino
				copy($ruta_temporal, $ruta_destino); //copia la imagen de la carpeta temporal a estudiante
				unlink($ruta_temporal); //elimina la imagen de la carpeta temporal
			}else{
				$nombre = "";
			}
			$db->where('id_materia', $_POST['id_materia'])->update('pro_materia', array('imagen_materia' => $nombre));
			echo 1;

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