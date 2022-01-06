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
	if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['data']) && isset($_FILES['avatar'])) {
			// Obtiene los datos
			$id_usuario = $_user['id_user'];
			$data = get_object_vars(json_decode($_POST['data']));
			$avatar = $_FILES['avatar'];

			// Importa la libreria para subir el avatar
			require_once libraries . '/upload-class/class.upload.php';

			// Define la ruta
			$ruta = files . '/profiles/';

			// Obtiene el nombre del avatar
			$usuario = $db->from('sys_users')->where('id_user', $id_usuario)->fetch_first();

			// Obtiene el nombre del avatar inicial
			$avatar_inicial = $usuario['avatar'];

			// Verifica si el avatar existe
			if ($avatar_inicial != '') {
				// Elimina el avatar
				file_delete($ruta . $avatar_inicial);
			}

			// Obtiene las dimensiones del avatar
			list($ancho, $alto) = getimagesize($avatar['tmp_name']);

			// Redimensiona el avatar segun la escala
			$ancho = $ancho * $data['scale'];
			$alto = $alto * $data['scale'];

			// Define la extension del avatar
			$extension = 'jpg';

			// Define el nombre del avatar final
			$avatar_final = md5(secret . random_string() . $id_usuario);

			// Instancia el avatar
			$avatar = new upload($avatar);

			// Verifica si el avatar puede ser subida
			if ($avatar->uploaded) {
				// Define los parametros de salida
				$avatar->file_new_name_body = $avatar_final;
				$avatar->image_resize = true;
				$avatar->image_ratio_crop = true;
				$avatar->image_x = $ancho;
				$avatar->image_y = $alto;
				$avatar->image_rotate = $data['angle'];
				$avatar->image_convert = $extension;
				$avatar->jpeg_quality = 95;
				$avatar->image_background_color = '#fff';
						
				// Recorta el avatar de acuerdo a la rotacion
				switch ($data['angle']) {
					case 90:
						$avatar->image_crop = ($alto - $data['x'] - $data['w']) . ' ' . ($ancho - $data['y'] - $data['h']) . ' ' . $data['x'] . ' ' . $data['y'];
						break;
					case 180:
						$avatar->image_crop =  $data['y'] . ' ' . $data['x'] . ' ' . ($alto - $data['y'] - $data['h']) . ' ' . ($ancho - $data['x'] - $data['w']);
						break;
					case 270:
						$avatar->image_crop = $data['x'] . ' ' . $data['y'] . ' ' . ($alto - $data['x'] - $data['w']) . ' ' . ($ancho - $data['y'] - $data['h']);
						break;
					default:
						$avatar->image_crop =  $data['y'] . ' ' . ($ancho - $data['x'] - $data['w']) . ' ' . ($alto - $data['y'] - $data['h']) . ' ' . $data['x'];
						break;
				}

				// Procesa el avatar
				@$avatar->process($ruta);

				// Verifica si el proceso fue exitoso
				if ($avatar->processed) {
					// Limpia el avatar temporal
					$avatar->clean();

					// Modifica el usuario
					$db->where('id_user', $id_usuario)->update('sys_users', array('avatar' => $avatar_final . '.' . $extension));

					// Guarda el proceso
					$db->insert('sys_procesos', array(
						'fecha_proceso' => date('Y-m-d'),
						'hora_proceso' => date('H:i:s'),
						'proceso' => 'u',
						'nivel' => 'l',
						'detalle' => 'Se modificó el avatar del usuario con identificador número ' . $_user['id_user'] . '.',
						'direccion' => $_location,
						'usuario_id' => $_user['id_user']
					));
				
					// Crea la notificacion
					set_notification('success', 'Modificación exitosa!', 'La información de tu perfil se modificó satisfactoriamente.');
				} else {
					// Crea la notificacion
					set_notification('danger', 'Modificación fallida!', 'Ocurrió un error al modificar la información de tu perfil.');
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
		// Redirecciona la pagina
		redirect('?/perfil/mostrar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>