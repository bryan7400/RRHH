<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
$gestion=$_gestion['gestion'];
$documentos_actividad = "";
$archivos_permitidos = 0;
$nombre_dominio = escape($_institution['nombre_dominio']);
$permiso_subir = in_array('subir', $_views);
// Verifica la peticion post
if (is_post()) { 
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
	if (isset($_POST['titulo']) && isset($_FILES['archivo_documento']["name"] ) ) {
			

		$nombre_archivo_documento = isset($_FILES["archivo_documento"]["name"]) ? ($_FILES["archivo_documento"]["name"]) : false;
		// Obtiene los datos
		$id_agenda = (isset($_POST['id_agenda'])) ? clear($_POST['id_agenda']) : 0;
		
		$titulo = clear($_POST['titulo']);
		$tipo_agenda = clear($_POST['tipo_agenda']);
		$grupo = clear($_POST['grupo']);
		
		$descripcion = clear($_POST['descripcion']);
		$documento = clear($_POST['documento']);

		$prioridad = clear($_POST['prioridad']);
		$fecha_inicio = clear($_POST['fecha_inicio']);
		$fecha_final = clear($_POST['fecha_final']);
		
		
		
        // obtiene la gestion
		



 		$archivo_documento_nombre = clear($_POST['archivo_documento_nombre']);


		$archivo_documentoedit = $db->query("SELECT * FROM ins_agenda_institucional WHERE imagen='$archivo_documento_nombre'")->fetch_first();


	if (($nombre_archivo_documento != '') || ($archivo_documento_nombre != '')) {

		if ($nombre_archivo_documento != ''){
			$formatos_permitidos =  array( 'jpg', 'jpeg', 'png');
 			$archivo_documento = $_FILES['archivo_documento']["name"];
 			$extension = pathinfo($nombre_archivo_documento, PATHINFO_EXTENSION);
 			$extension = strtolower($extension);

 			if (!in_array($extension, $formatos_permitidos)) {
 				$archivo_documentos_permitidos = 1;
 			} else {
 				$output_dir = "files/demoeducheck/agenda/";
 				$imagen =  date('dmY_His') . '_' . '.' . pathinfo($nombre_archivo_documento, PATHINFO_EXTENSION);;
 				if (!move_uploaded_file($_FILES['archivo_documento']["tmp_name"], $output_dir . $imagen)) {
 					$msg = 'No pudo subir el imagen';
 					var_dump($msg);
 				} else {
 					$documentos_actividad = $documentos_actividad . $imagen . "@";
 				}
 			}

 			if ($archivo_documento_nombre != ''){
 				unlink("files/demoeducheck/agenda/".$archivo_documento_nombre);
 			} else {
 				
 			}

 			$archivo_documento = clear($imagen);
 			



		} else {


				$archivo_documento = clear($_POST['archivo_documento_nombre']);
			}

}





			// Instancia el cliente
			$agenda = array(
				'titulo' => $titulo,
				'tipo_agenda' => $tipo_agenda,
				'color' => $prioridad,
				'grupo' => $grupo,
				'descripcion' => $descripcion,
				'fecha_inicio' => $fecha_inicio,
				'fecha_final' => $fecha_final,
				'imagen' => $archivo_documento
			);
			
			// Verifica si es creacion o modificacion
			if ($id_agenda > 0) {
				// Modifica el cliente
				$db->where('id_agenda', $id_agenda)->update('ins_agenda_institucional', $agenda);


				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el cliente con identificador número ' . $id_agenda . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				echo 1;
				// Redirecciona la pagina
				// redirect('?/cliente/ver/' . $id_agenda);
			} else {
				// Crea el cliente
				$id_agenda = $db->insert('ins_agenda_institucional', $agenda);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el cliente con identificador número ' . $id_agenda . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				echo 2;
				// Redirecciona la pagina
				// redirect('?/cliente/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	//	echo 3;
	// } else {
	// 	// Redirecciona la pagina
	// 	redirect('?/cliente/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>