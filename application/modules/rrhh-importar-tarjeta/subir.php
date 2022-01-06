<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (is_post()) {
	// Verifica la existencia de datos
	if (isset($_POST['id_empleado']) && isset($_POST['data']) && isset($_FILES['foto'])) {
		// Obtiene los datos
		$id_empleado = clear($_POST['id_empleado']);
		$data = get_object_vars(json_decode($_POST['data']));
		$foto = $_FILES['foto'];

		// Obtiene el nombre de la foto
		$nombre_foto = $db->from('sys_empleados')->where('id_empleado', $id_empleado)->fetch_first();
		$nombre_foto = $nombre_foto['foto'];

		// Obtiene los valores del archivo
		$nombre_temporal = $foto['tmp_name'];

		// Verifica si existe la nueva foto
		if ($nombre_temporal != '') {
			// Verifica si existe la antigua foto
			if ($nombre_foto != '') {
				// Elimina la foto
				file_delete(files . '/empleados/' . $nombre_foto);

				// Elimina la foto
				file_delete(files . '/empleados/small__' . $nombre_foto);
			}

			// Obtiene las dimensiones de la foto
			list($foto_width, $foto_height) = getimagesize($nombre_temporal);

			// Recalcula el angulo y las dimenciones de la foto
			if ($data['angle'] == 90 || $data['angle'] == 270) {
				$foto_angle = $data['angle'] + 180;
				$foto_width = $foto_width + $foto_height;
				$foto_height = $foto_width - $foto_height;
				$foto_width = $foto_width - $foto_height;
			} else {
				$foto_angle = $data['angle'];
			}

			// Redimensiona la foto segun la escala
			$foto_width = $foto_width * $data['scale'];
			$foto_height = $foto_height * $data['scale'];

			// Obtiene el contenido de la foto
			$foto = @file_get_contents($nombre_temporal);

			// Crea la foto
			$foto = imagecreatefromstring($foto);
			$foto = imagerotate($foto, $foto_angle, 0);
			$foto = imagescale($foto, $foto_width, $foto_height);
			$fondo = imagecolorallocate($foto, 255, 255, 255);
			imagefill($foto, 0, 0, $fondo);

			// Crea la foto grande
			$foto_grande = imagecreatetruecolor($data['w'], $data['h']);
			imagecopyresized($foto_grande, $foto, -$data['x'], -$data['y'], 0, 0, $foto_width, $foto_height, $foto_width, $foto_height);
			
			// Obtiene las rutas de las nuevas fotos
			$nombre_nuevo = md5(prefix . random_string() . $id_empleado) . '.jpg';
			$ruta_foto_grande = files . '/empleados/' . $nombre_nuevo;
			$ruta_foto_pequena = files . '/empleados/small__' . $nombre_nuevo;

			// Crea la foto pequeña
			$foto_pequena = imagescale($foto_grande, $data['w'] * 0.16, $data['h'] * 0.16);

			// Verifica si se creo la foto grande y la foto pequeño
			if (imagejpeg($foto_grande, $ruta_foto_grande, 90) && imagejpeg($foto_pequena, $ruta_foto_pequena, 90)) {
				// Destruimos los fotos temporales
				imagedestroy($foto_grande);
				imagedestroy($foto_pequena);

				// Modifica el empleado
				$db->where('id_empleado', $id_empleado)->update('sys_empleados', array('foto' => $nombre_nuevo));
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso'  => date('H:i:s'),
					'proceso'       => 'u',
					'nivel'         => 'l',
					'detalle'       => 'Se modificó la foto del empleado con identificador número ' . $id_empleado . '.',
					'direccion'     => $_location,
					'usuario_id'    => $_user['id_user']
				));
				
				$_SESSION[temporary] = array(
					'alert'   => 'success',
					'title'   => 'Modificación exitosa!',
					'message' => 'El registro se modificó satisfactoriamente.'
				);
			} else {
				// Crea la notificacion
				set_notification('danger', 'Advertencia!', 'Ocurrió un error al modificar el registro.');
				$_SESSION[temporary] = array(
					'alert'   => 'danger',
					'title'   => 'Advertencia!',
					'message' => 'Ocurrió un error al modificar el registro.'
				);
			}
		}

		// Redirecciona la pagina
		redirect(back());
	} else {
		// Error 400
		require_once bad_request();
		exit;
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>