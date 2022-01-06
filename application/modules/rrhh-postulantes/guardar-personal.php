<?php
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (true) {

	// Obtiene los parametros
	$id_externo = (isset($_params[0])) ? $_params[0] : 0;
	$id_modificar = (isset($_params[1])) ? $_params[1] : 0;
	
	if($id_modificar==0){ //crear persona

		// Obtiene el gondolas
		$gondolas = $db->from('per_postulacion')->where('id_postulacion', $id_externo)->fetch_first();
		
		// Verifica si existe el gondolas
		if ($gondolas) {
			// Elimina el gondolas
			


			
			
			$verificar = $db->query("SELECT * FROM sys_persona 
			WHERE postulante_id = '$id_externo'")->fetch_first();

			$query = array(
                'personal' => 'P'
            );
            $db->where('id_postulacion', $id_externo)->update('per_postulacion', $query);

			// Verifica la eliminacion
			if ($verificar) {

				set_notification('danger', 'Eliminación fallida!', 'El registro no pudo ser eliminado.');
				echo 2;
			} else {
				// Crea la notificacion
				

				// Guarda el proceso
			$persona_db =	$db->insert('sys_persona', array(
					'nombres' => $gondolas['nombre'],
					'primer_apellido' => $gondolas['paterno'],
					'segundo_apellido' => $gondolas['materno'],
					
					'tipo_documento' => '1',
					'numero_documento' => $gondolas['ci'],
					'complemento' => '',
					
					'expedido' => $gondolas['expirado'],
					'genero' => $gondolas['genero'],
					'fecha_nacimiento' => $gondolas['fecha_nacimiento'],
					
					'direccion' => $gondolas['direccion'],
					'foto' => '',
					'nit' => '',
					'postulante_id'=>$id_externo
				));
				



				$id_postulacion = $id_externo; 
            $datos = $db->query("SELECT * FROM per_postulacion 
			WHERE id_postulacion = '$id_postulacion'")->fetch_first();


            $cargo_id = $datos['cargo_id'];
            $id_persona = $persona_db;
            $fecha_registro = $datos['fecha_registro'];
           
            $verficar = $db->query("SELECT * FROM per_asignaciones 

WHERE persona_id = '$id_persona' ")->fetch();
            // Verifica si es creacion o modificacion
             if ($verficar) {
                 // code...
                echo 2;

             }else{
                 // Instancia el horario

                $datos = array(
                    'persona_id' => $id_persona,
                    'cargo_id' => $cargo_id, 
                    'fecha_registro' => $fecha_registro,
                    'fecha_asignacion' => date('Y-m-d'),
                    'estado' => 'A',
                    'usuario_modificacion'=> $_user['id_user'],
                    'fecha_modificacion'=> date('Y-m-d')
                );
                // Crea el horario
                $id_rett = $db->insert('per_asignaciones', $datos);

                // Guarda el proceso
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso'  => date('H:i:s'),
                    'proceso'       => 'c',
                    'nivel'         => 'l',
                    'detalle'       => 'Se creó el feriado con identificador número ' . $id_rett . '.',
                    'direccion'     => $_location,
                    'usuario_id'    => $_user['id_user']
                ));


                 echo 1;

                 
             }



				// Crea la notificacion
				set_notification('success', 'Eliminación exitosa!', 'El registro se eliminó satisfactoriamente.');






			}
			
			// Redirecciona la pagina
			redirect('?/rrhh-postulantes/listar');
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	}
	else{
		$query = array(
            'personal' => 'A'
        );
        $db->where('id_postulacion', $id_modificar)->update('per_postulacion', $query);

		$query = array(
            'postulante_id' => $id_modificar
        );
        $db->where('id_persona', $id_externo)->update('sys_persona', $query);

		redirect('?/rrhh-postulantes/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>