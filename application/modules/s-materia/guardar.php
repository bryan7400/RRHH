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
	if (isset($_POST['nombre_materia']) && isset($_POST['descripcion']) && isset($_POST['id_campo'])) {
		// Obtiene los datos
		$id_materia      = (isset($_POST['id_materia'])) ? clear($_POST['id_materia']) : 0;
		$id_campo        = (isset($_POST['id_campo'])) ? clear($_POST['id_campo']) : 0;
		$nombre_materia  = clear($_POST['nombre_materia']);
		$descripcion     = clear($_POST['descripcion']);
		$orden           = clear($_POST['orden']);
		$nivel_academico = (isset($_POST['nivel_academico'])) ? $_POST['nivel_academico'] : array();
		$color           = isset($_POST['color']) ? $_POST['color'] : '#ffffff';
		$codigo_materia  = isset($_POST['codigo_materia']) ? $_POST['codigo_materia'] : $nombre_materia;

		if ($_FILES['inputFile1']['name'] != "") {
			$nombre_img = $_FILES['inputFile1']['name'];
			$tipo = $_FILES['inputFile1']['type'];
			$tamano = $_FILES['inputFile1']['size'];
			$extension = pathinfo($nombre_img, PATHINFO_EXTENSION);
         	
			//Si existe imagen y tiene un tamaño correcto
			if (($nombre_img == !NULL) && ($_FILES['inputFile1']['size'] = 200000)) {
				//indicamos los formatos que permitimos subir a nuestro servidor
				if (($_FILES["inputFile1"]["type"] == "image/gif")
					|| ($_FILES["inputFile1"]["type"] == "image/jpeg")
					|| ($_FILES["inputFile1"]["type"] == "image/jpg")
					|| ($_FILES["inputFile1"]["type"] == "image/png")
					|| ($_FILES["inputFile1"]["type"] == "image/GIF")
					|| ($_FILES["inputFile1"]["type"] == "image/JPEG")
					|| ($_FILES["inputFile1"]["type"] == "image/JPG")
					|| ($_FILES["inputFile1"]["type"] == "image/PNG")
				) {
					// Ruta donde se guardarán las imágenes que subamos
					$nombre_nuevo = md5(secret . random_string()) . "_ic_" . $_POST['id_materia'].'.'. pathinfo($nombre_img, PATHINFO_EXTENSION); 
					$directorio = 'files/' . $nombre_dominio . '/profiles/materias/'; //ruta de destino
					// Muevo la imagen desde el directorio temporal a nuestra ruta indicada anteriormente
					move_uploaded_file($_FILES['inputFile1']['tmp_name'], $directorio . $nombre_img);
					rename($directorio . $nombre_img, $directorio . $nombre_nuevo);
				} else {
					//si no cumple con el formato
					echo 3;
					exit();
				}

				// Instancia el materia
				$materia = array(
					'campo_id' => $id_campo,
					'nombre_materia' => $nombre_materia,
					'descripcion' => $descripcion,
					'orden' => $orden,
					'nivel_academico_id' => implode(",", $nivel_academico),
					'usuario_registro' => $_user['id_user'],
					'fecha_registro' => date('Y-m-d'),
					'usuario_modificacion' => $_user['id_user'],
					'fecha_modificacion' => date('Y-m-d'),
					'color_materia' => $color,
					'cod_materia' => $codigo_materia,
					'icono_materia' => $nombre_nuevo
				);
			} else {
				//si existe la variable pero se pasa del tamaño permitido
				if ($nombre_img == !NULL) echo 3; exit();
			}
		} else {
			// Instancia el materia
			$materia = array(
				'campo_id' => $id_campo,
				'nombre_materia' => $nombre_materia,
				'descripcion' => $descripcion,
				'orden' => $orden,
				'nivel_academico_id' => implode(",", $nivel_academico),
				'usuario_registro' => $_user['id_user'],
				'fecha_registro' => date('Y-m-d'),
				'usuario_modificacion' => $_user['id_user'],
				'fecha_modificacion' => date('Y-m-d'),
				'color_materia' => $color,
				'cod_materia' => $codigo_materia
			);
		}

		// Verifica si es creacion o modificacion
		if ($id_materia > 0) {

			$db->where('id_materia', $id_materia)->update('pro_materia', $materia);

			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'l',
				'detalle' => 'Se modificó el materia con identificador número ' . $id_materia . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));


			echo 1;
		} else {
			// Crea el materia
			$id_materia = $db->insert('pro_materia', $materia);

			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'c',
				'nivel' => 'l',
				'detalle' => 'Se creó el materia con identificador número ' . $id_materia . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));

			echo 2;
		}
	} else if (isset($_POST['id_materia']) && isset($_POST['accion']) == "editarImagen" && isset($_POST['imagen_materia'])) {
		//Actualizamos la imagen de la materia
		if (isset($_POST['imagen_materia'])) {
			$ruta_temporal = 'files/' . $nombre_dominio . '/profiles/temporal/fotos/' . $_POST['imagen_materia']; //ruta temporal
			$nombre = md5(secret . random_string() . $_POST['id_materia']); //encripta el nombre de la imagen a md5
			$ruta_destino = 'files/' . $nombre_dominio . '/profiles/materias/' . $nombre . '.jpg'; //ruta de destino
			copy($ruta_temporal, $ruta_destino); //copia la imagen de la carpeta temporal a estudiante
			unlink($ruta_temporal); //elimina la imagen de la carpeta temporal
		} else {
			$nombre = "";
		}
		$db->where('id_materia', $_POST['id_materia'])->update('pro_materia', array('imagen_materia' => $nombre));
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
