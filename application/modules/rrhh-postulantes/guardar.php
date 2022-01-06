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
		if (isset($_POST['ruta']) && isset($_POST['capacidad']) && isset($_POST['placa']) && isset($_POST['tipo_gondola']) ) {
			// Obtiene los datos
			$id_gondola = (isset($_POST['id_gondola'])) ? clear($_POST['id_gondola']) : 0;
            $ruta = clear($_POST['ruta']);
            $descripcion = clear($_POST['descripcion']);
			$capacidad = clear($_POST['capacidad']);
			$placa = clear($_POST['placa']);
			$tipo_gondola = clear($_POST['tipo_gondola']);
			
			// Instancia el gondolas
            if($id_gondola > 0){
                $gondolas = array(
                    'nombre' => $ruta,
                    'descripcion' => $descripcion,
                    'capacidad' => $capacidad,
                    'placa' => $placa,
                    'tipo_gondola' => $tipo_gondola,
                    'usuario_modificacion' => $_user['id_user'],
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                );
                $db->where('id_gondola', $id_gondola)->update('gon_gondolas', $gondolas);

                // Guarda el proceso
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'u',
                    'nivel' => 'l',
                    'detalle' => 'Se modificó el gondolas con identificador número ' . $id_gondola . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
                ));

                // Crea la notificacion
                //set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');

                // Redirecciona la pagina
                echo "1";
                //redirect('?/gon-gondolas/ver/' . $id_gondola);
            }else{
                $gondolas = array(
                    'nombre' => $ruta,
                    'capacidad' => $capacidad,
                    'descripcion' => $descripcion,
                    'placa' => $placa,
                    'tipo_gondola' => $tipo_gondola,
                    'ruta_id' => 0,
                    'estado' => 1,
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro' => date('Y-m-d H:i:s'),
                    'usuario_modificacion' => $_user['id_user'],
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                );
                $id_gondola = $db->insert('gon_gondolas', $gondolas);

                // Guarda el proceso
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'c',
                    'nivel' => 'l',
                    'detalle' => 'Se creó el gondolas con identificador número ' . $id_gondola . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
                ));

                // Crea la notificacion
                //set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

                // Redirecciona la pagina
                echo "1";
                //redirect('?/gon-gondolas/listar');





            }
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/gondolas/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>