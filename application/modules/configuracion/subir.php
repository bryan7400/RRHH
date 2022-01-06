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
		if (isset($_FILES['logotipo'])) {
			// Obtiene los datos
			$id_institucion = $_institution['id_institucion'];
			$logotipo = $_FILES['logotipo'];

			// Importa la libreria para subir el logotipo
			require_once libraries . '/upload-class/class.upload.php';

			// Define la ruta
			$ruta = files . '/logos/';

			// Obtiene el logotipo
			$institucion = $db->from('sys_instituciones')->where('id_institucion', $id_institucion)->fetch_first();

			// Obtiene el logotipo inicial
			$logotipo_inicial = $institucion['logotipo'];

			// Verifica si el logotipo existe
			if ($logotipo_inicial != '') {
				// Elimina el logotipo
				file_delete($ruta . $logotipo_inicial);
			}

			// Define la extension del logotipo
			$extension = 'jpg';

			// Define el logotipo final
			$logotipo_final = md5(secret . random_string() . $id_institucion);

			// Instancia el logotipo
			$logotipo = new upload($logotipo);

			// Verifica si el logotipo puede ser subido
			if ($logotipo->uploaded) {
				// Define los parametros de salida
				$logotipo->file_new_name_body = $logotipo_final;
				$logotipo->image_resize = true;
				$logotipo->image_ratio_x = true;
				$logotipo->image_y = 320;
				$logotipo->image_convert = $extension;
				$logotipo->jpeg_quality = 95;
				$logotipo->image_background_color = '#fff';

				// Procesa el logotipo
				@$logotipo->process($ruta);

				// Verifica si el proceso fue exitoso
				if ($logotipo->processed) {
					// Limpia el logotipo temporal
					$logotipo->clean();

					// Modifica la institucion
					$db->where('id_institucion', $id_institucion)->update('sys_instituciones', array('logotipo' => $logotipo_final . '.' . $extension));

					// Guarda el proceso
					$db->insert('sys_procesos', array(
						'fecha_proceso' => date('Y-m-d'),
						'hora_proceso' => date('H:i:s'),
						'proceso' => 'u',
						'nivel' => 'm',
						'detalle' => 'Se modificó el logotipo de la institución.',
						'direccion' => $_location,
						'usuario_id' => $_user['id_user']
					));

					// Crea la notificacion
					set_notification('success', 'Modificación exitosa!', 'El logotipo de la intitución se modificó satisfactoriamente.');
				} else {
					// Crea la notificacion
					set_notification('danger', 'Modificación fallida!', 'Ocurrió un error al modificar el logotipo de la institución.');
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
		redirect('?/configuracion/principal');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>