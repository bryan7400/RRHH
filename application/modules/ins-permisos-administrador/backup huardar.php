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

	 //var_dump($_POST);die;
	if (isset($_POST['estudiante_id']) ) {
			

		$nombre_archivo_documento = isset($_FILES["archivo_documento"]["name"]) ? ($_FILES["archivo_documento"]["name"]) : false;
		// Obtiene los datos
		$id_permiso = (isset($_POST['id_permiso'])) ? clear($_POST['id_permiso']) : 0;
		
		$estudiante_id = clear($_POST['estudiante_id']);
		$familiar_id = clear($_POST['familiar_id']);
		$categoria = clear($_POST['categoria']);
		$materia_id =(isset($_POST['materia_id'])) ? $_POST['materia_id'] : array();
		$horario_id =(isset($_POST['horario_id'])) ? $_POST['horario_id'] : array();
		$tipo_permiso = clear($_POST['tipo_permiso']);
		$grupo_permiso = clear($_POST['grupo_permiso']);

		$contrato_id = clear($_POST['contrato_id']);

		$seguimiento_permiso = clear($_POST['seguimiento_permiso']);
		$fecha_inicio = clear($_POST['fecha_inicio']);
		$fecha_final = clear($_POST['fecha_final']);
		$motivo = clear($_POST['motivo']);
		
		
		
        // obtiene la gestion
		



 		$archivo_documento_nombre = clear($_POST['archivo_documento_nombre']);


		$archivo_documentoedit = $db->query("SELECT * FROM ins_permisos WHERE archivo_documento='$archivo_documento_nombre'")->fetch_first();


	

$gestion=$_gestion['id_gestion'];

$_ver_permiso = $db->query("SELECT * FROM ins_permisos insp WHERE estudiante_id='$estudiante_id'
	AND   '$fecha_inicio' >= date(insp.fecha_inicio)  AND '$fecha_final' <= date(insp.fecha_final)")->fetch();

	






	if ($_ver_permiso) {
		// code...
	
		echo 3;
			

	}else {
		if (($nombre_archivo_documento != '') || ($archivo_documento_nombre != '')) {

		if ($nombre_archivo_documento != ''){
			$formatos_permitidos =  array('pdf', 'jpg', 'jpeg', 'png', 'docx');
 			$archivo_documento = $_FILES['archivo_documento']["name"];
 			$extension = pathinfo($nombre_archivo_documento, PATHINFO_EXTENSION);
 			$extension = strtolower($extension);

 			if (!in_array($extension, $formatos_permitidos)) {
 				$archivo_documentos_permitidos = 1;
 			} else {
 				$output_dir = "files/demoeducheck/permisos/";
 				$imagen =  date('dmY_His') . '_' . '.' . pathinfo($nombre_archivo_documento, PATHINFO_EXTENSION);;
 				if (!move_uploaded_file($_FILES['archivo_documento']["tmp_name"], $output_dir . $imagen)) {
 					$msg = 'No pudo subir el archivo_documento';
 					var_dump($msg);
 				} else {
 					$documentos_actividad = $documentos_actividad . $imagen . "@";
 				}
 			}

 			if ($archivo_documento_nombre != ''){
 				unlink("files/demoeducheck/permisos/".$archivo_documento_nombre);
 			} else {
 				
 			}

 			$archivo_documento = clear($imagen);
 			



		} else {


				$archivo_documento = clear($_POST['archivo_documento_nombre']);
			}

}


			


	// Instancia el cliente
			$permiso = array(
				'estudiante_id' => $estudiante_id,
				'familiar_id' => $familiar_id,
				'categoria' => $categoria,
				'materia_id' => implode(',', $materia_id),
				'horarios_id' => implode(',', $horario_id),
				'tipo_permiso' => $tipo_permiso,
				'grupo_permiso' => $grupo_permiso,
				'contrato_id' => $contrato_id,
				'seguimiento_permiso' => $seguimiento_permiso,
				'fecha_inicio' => $fecha_inicio,
				'fecha_final' => $fecha_final,
				'motivo' => $motivo,		
				'archivo_documento' => $archivo_documento
			);
			
			// Verifica si es creacion o modificacion
			if ($id_permiso > 0) {
				// Modifica el cliente
				$db->where('id_permiso', $id_permiso)->update('ins_permisos', $permiso);


				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el cliente con identificador número ' . $id_permiso . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				echo 1;
				// Redirecciona la pagina
				// redirect('?/cliente/ver/' . $id_permiso);
			} else {
				// Crea el cliente
				$id_permiso = $db->insert('ins_permisos', $permiso);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el cliente con identificador número ' . $id_permiso . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				echo 2;
				// Redirecciona la pagina
				// redirect('?/cliente/listar');
			}		








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