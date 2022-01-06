<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

if ($_FILES["file_pregunta_r"]["name"]) {
	 
	 	$nombre_archivo = isset($_FILES["file_pregunta_r"]["name"]) ? ($_FILES["file_pregunta_r"]["name"]) : false;
                                             
	 	if ($nombre_archivo) {
	 		if ($nombre_archivo != '') {
				
				if($id_curso_actividad_evaluacion_pregunta> 0) {
				//editar  
				//buscar el nombre actual em la base de datos
				//eliminar el archivo anterior
					$datos=$db->query("SELECT pre.pregunta_imagen FROM temp_curso_actividad_evaluacion_pregunta as pre WHERE pre.id_curso_actividad_evaluacion_pregunta = $id_curso_actividad_evaluacion_pregunta")->fetch_first();
					$pregunta_imagen=$datos['pregunta_imagen'];
					unlink("files/".$nombre_dominio."/documentos_docentes_evaluaciones/".$pregunta_imagen);
				}
	 			//Verificamos las Extenciones
	 			$formatos_permitidos =  array('bmp', 'jpg', 'jpeg', 'png', 'gif');
	 			$archivo = $_FILES['file_pregunta_r']['name'];
	 			$extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
	 			$extension = strtolower($extension);

	 			if (!in_array($extension, $formatos_permitidos)) {
	 				$archivos_permitidos = 1;
	 			} else {
	 				$output_dir = "files/".$nombre_dominio."/documentos_docentes_evaluaciones/";
	 				$imagen =  date('dmY_His') . '_' . $curso_actividad_evaluacion_id . '.' . pathinfo($nombre_archivo, PATHINFO_EXTENSION);;
	 				if (!move_uploaded_file($_FILES['file_pregunta_r']["tmp_name"], $output_dir . $imagen)) {
	 					$msg = 'No pudo subir el archivo';
	 					var_dump($msg);
	 				} else {
	 					$documentos_actividad = $documentos_actividad . $imagen . "@";
	 				}
	 			}
	 		}
	 	} else {
	 		$documentos_actividad = (isset($_POST['file_pregunta_r'])) ? clear($_POST['file_pregunta_r']) : "";
	 	}
	// 	//}

	// 	//Eliminamos la ultima arroba
	 	$documentos_actividad = substr($documentos_actividad, 0, -1);

	 	$documentos_nuevos = $documentos_actividad;

	 	$doc_actividad = (isset($_POST['file_pregunta_r'])) ? clear($_POST['file_pregunta_r']) : "";
	 	if ($doc_actividad != "") {
	 		$doc = str_replace(",", "@", $doc_actividad);
	 		$documentos_actividad = $doc . "@" . $documentos_actividad;
	 	}
	 }