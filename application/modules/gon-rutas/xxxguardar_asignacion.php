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
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if(isset($_POST['id_asignacion2']) && isset($_POST['id_gondola2']) && isset($_POST['id_conductor2']) ) {
			// Obtiene los datos
			$id_ruta = $_POST['id_asignacion2'];
            $id_gondola = $_POST['id_gondola2'];
            $id_conductor = $_POST['id_conductor2'];
            
			
            $query = $db->select('*')
                        ->from('gon_conductor_gondola')
                        ->where('conductor_id', $id_conductor)
                        ->where('gondola_id', $id_gondola)
                        ->fetch_first();

            if($query){
                /*$db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'u',
                    'nivel' => 'l',
                    'detalle' => 'Se modificó el conductor o gondola de la ruta con identificador número ' . $id_ruta . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
                ));*/

                $gondolas = array(
                    'conductor_gondola_id' => $id_asignacion
                );
                $db->where('id_ruta', $id_ruta)->update('gon_rutas', $query['id_conductor_gondola']);
            }
            else{
                /*
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'u',
                    'nivel' => 'l',
                    'detalle' => 'Se modificó el conductor o gondolas de la ruta con identificador número ' . $id_ruta . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
                ));
                */
                $id_asignacion=$db->insert('gon_conductor_gondola', array(
                    'conductor_id' => $id_conductor
                    'gondola_id' => $id_gondola
                ));

                $gondolas = array(
                    'conductor_gondola_id' => $id_asignacion
                );
                $db->where('id_ruta', $id_ruta)->update('gon_rutas', $gondolas);
            }

            echo "1";
            //redirect('?/gon-gondolas/listar');

		} else {
			// Error 400
			//require_once bad_request();
			//exit;
		}
	/*} else {
		// Redirecciona la pagina
		redirect('?/gondolas/listar');
	}*/
} else {
	// Error 404
	//require_once not_found();
	//exit;
}

?>