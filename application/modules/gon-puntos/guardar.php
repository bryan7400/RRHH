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
	if (true) {
		// Verifica la existencia de datos
		if (isset($_POST['nombre_lugar']) && isset($_POST['estado'])) {// isset($_POST['atencion']) &&
			// Obtiene los datos
            //require_once libraries . '/upload-class/class.upload.php';
//            $data = get_object_vars(json_decode($_POST['data']));
			$id_punto = (isset($_POST['id_punto'])) ? clear($_POST['id_punto']) : 0;
			$descripcion = clear($_POST['descripcion']);
			$nombre_lugar = clear($_POST['nombre_lugar']);
			$coordenadas = clear($_POST['coordenadas']);
            $estado = clear($_POST['estado']);
            $ruta_id = clear($_POST['id_ruta']);

			
			// Verifica si es creacion o modificacion
			if ($id_punto > 0) {
					// Instancia el puntos
				$coords = explode(',',$coordenadas);
                    $puntos = array(
                        'descripcion' => $descripcion,
                        'latitud' => $coords[0],
                        'longitud' => $coords[1],
                        //'imagen_lugar' => '',
                        'nombre_lugar' => $nombre_lugar,
                        //'estado' => $estado,
                        //'ruta_id' => $ruta_id, 
                        'usuario_modificacion' => $_user['id_user'],
                        'fecha_modificacion' =>date('Y-m-d H:i:s')
                    );
				 
				//var_dump($puntos);exit();
				// Modifica el puntos
				$db->where('id_punto', $id_punto)->update('gon_puntos', $puntos);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el puntos con identificador número ' . $id_punto . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				 echo json_encode(array('estado' => 's'));
				// Redirecciona la pagina
				//redirect('?/gon-puntos/ver/' . $id_punto);
			} else {
				// Crea el puntos
                /*if(isset($_FILES['imagen'])){
                    $imagen = $_FILES['imagen'];

                    $ruta = files . '/puntos/';

                    list($ancho, $alto) = getimagesize($imagen['tmp_name']);

                    $ancho = $ancho * 100;
                    $alto = $alto * 100;

                    // Define la extension de la imagen
                    $extension = 'jpg';

                    $imagen_final = md5(secret . random_string() . $nombre_lugar);

                    // Instancia la imagen
                    $imagen = new upload($imagen);

                    if ($imagen->uploaded) {
                        // Define los parametros de salida
                        $imagen->file_new_name_body = $imagen_final;
                        $imagen->image_resize = true;
                        $imagen->image_ratio_crop = true;

                        // Procesa la imagen
                        @$imagen->process($ruta);
                    }
                    $coords = explode(',',$atencion);
                    $puntos = array(
                        'descripcion' => $descripcion,
                        'latitud' => $coords[0],
                        'longitud' => $coords[1],
                        'imagen_lugar' => $imagen_final . '.' . $extension,
                        'nombre_lugar' => $nombre_lugar,
                        'estado' => $estado,
                        'ruta_id' => $ruta_id,
                        'usuario_registro' => $_user['id_user'],
                        'fecha_registro' => date('Y-m-d H:i:s'),
                        'usuario_modificacion' => 0,
                        'fecha_modificacion' => '0000-00-00 00:00:00'
                    );
                }else{*/
				$coords = explode(',',$coordenadas);
                    $puntos = array(
                        'descripcion' => $descripcion,
                        'latitud' => $coords[0],
                        'longitud' => $coords[1],
                        'imagen_lugar' => '',
                        'nombre_lugar' => $nombre_lugar,
                        'estado' => $estado,
                        'ruta_id' => $ruta_id,
                        'usuario_registro' => $_user['id_user'],
                        'fecha_registro' => date('Y-m-d H:i:s'),
                        'usuario_modificacion' => 0,
                        'fecha_modificacion' => '0000-00-00 00:00:00'
                    );
               // }
				$id_punto = $db->insert('gon_puntos', $puntos);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el puntos con identificador número ' . $id_punto . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
                echo json_encode(array('estado' => 's'));
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
        echo json_encode(array('estado' => 'n'));
//		redirect('?/puntos/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>