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
		if (isset($_POST['categoria2']) && isset($_POST['lentes2']) && isset($_POST['audifonos2']) ) {
			// Obtiene los datos
			 $id_conductor = (isset($_POST['id_conductor'])) ? clear($_POST['id_conductor']) : 0;
            //$id_persona = (isset($_POST['id_persona2'])) ? clear($_POST['id_persona2']) : 0;
            $id_personal = (isset($_POST['sel_personal_crear'])) ? clear($_POST['sel_personal_crear']) : 0;
            
            $categoria = clear($_POST['categoria2']);
            $lentes = clear($_POST['lentes2']);
			$audifonos = clear($_POST['audifonos2']);
			$grupo = clear($_POST['grupo_sanguineo2']);
			$f_emision = clear($_POST['f_emision2']);
            $f_vencimiento = clear($_POST['f_vencimiento2']);
			
            //$nombres = clear($_POST['nombres2']);
            //$paterno = clear($_POST['paterno2']);
            //$materno = clear($_POST['materno2']);

            // Instancia el gondolas
            if($id_conductor > 0){
            
                //$gondolas = array(
                //    'nombres'  => $nombres,
                //    'primer_apellido' => $paterno,
                //    'segundo_apellido' => $materno
                //);
                //$db->where('id_persona', $id_conductor)->update('sys_persona', $gondolas);

                $gondolas = array(
                    'categoria'    => $categoria,
                    'lentes'   => $lentes,
                    'audifonos'    => $audifonos,
                    'grupo_sanguineo'  => $grupo,
                    'fecha_emision'    => date_encode($f_emision),
                    'fecha_vencimiento'    => date_encode($f_vencimiento),
                    //'asignacion_id' => $id_personal
                );
                $db->where('id_conductor', $id_conductor)->update('gon_conductor', $gondolas);

                // Guarda el proceso
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'u',
                    'nivel' => 'l',
                    'detalle' => 'Se modificó el conductor con identificador número ' . $id_conductor . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
                ));

                // Crea la notificacion
                //set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');

                // Redirecciona la pagina
                echo "1";
                //redirect('?/gon-gondolas/ver/' . $id_gondola);
            }else{
                //$gondolas = array(
                //    'nombres'  => $nombres,
                //    'primer_apellido' => $paterno,
                //    'segundo_apellido' => $materno,
                //    'tipo_documento' => '',
                //    'numero_documento' => '', 
                //    'complemento' => '',
                //    'expedido' => '',
                //    'genero' => '',
                //    'fecha_nacimiento' => '',  
                //    'direccion'   => '', 
                //    'foto' => '',    
                //    'nit' => ''
                //);
                //$id_persona=$db->insert('sys_persona', $gondolas);

                $gondolas = array(
                    'categoria'    => $categoria,
                    'lentes'   => $lentes,
                    'audifonos'    => $audifonos,
                    'grupo_sanguineo'  => $grupo,
                    'fecha_emision'    => date_encode($f_emision),
                    'fecha_vencimiento'    => date_encode($f_vencimiento),
                    'asignacion_id' => $id_personal
                );
                $id_conductor = $db->insert('gon_conductor', $gondolas);

                // Guarda el proceso
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'c',
                    'nivel' => 'l',
                    'detalle' => 'Se creó el gondolas con identificador número ' . $id_conductor . '.',
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
	//} else {
		// Redirecciona la pagina
	//	redirect('?/gon_conductor/listar');
	//}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>