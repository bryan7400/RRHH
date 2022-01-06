<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

$nombre_dominio = escape($_institution['nombre_dominio']);

// echo "<pre>";
// // var_dump($_POST);
// var_dump($_FILES);
// echo "</pre>";
// exit();


// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
	// Verifica la existencia de datos
	if (isset($_POST['id_persona']) && isset($_POST['accion']) == "editarImagen" && isset($_POST['foto'])) {
		//Actualizamos la imagen de la materia
		if (isset($_POST['foto'])) {
			$directorio = 'files/' . $nombre_dominio . '/profiles/personal/';
			 //ruta temporal
			$nombre = md5(secret . random_string() . $_POST['id_persona']); //encripta el nombre de la imagen a md5
			
			 //copia la imagen de la carpeta temporal a estudiante
			rename($directorio .$_POST['foto'], $directorio . $nombre. '.jpg');
			

			//elimina la imagen de la carpeta temporal
		} else {
			$nombre = "";
		}
		$db->where('id_persona', $_POST['id_persona'])->update('sys_persona', array('foto' => $nombre));
		echo 1;
	} else {
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
