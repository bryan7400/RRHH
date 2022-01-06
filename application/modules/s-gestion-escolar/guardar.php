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
		if (isset($_POST['nombre_gestion']) && isset($_POST['inicio_gestion']) && isset($_POST['final_gestion'])) {
			// Obtiene los datos
			$id_gestion = (isset($_POST['id_gestion'])) ? clear($_POST['id_gestion']) : 0;
			$nombre_gestion = clear($_POST['nombre_gestion']);
			$inicio_gestion = clear($_POST['inicio_gestion']);
			$final_gestion = clear($_POST['final_gestion']);
			$inicio_vacaciones = clear($_POST['inicio_vacaciones']);
			$final_vacaciones = clear($_POST['final_vacaciones']);
			// Instancia el gestion
			$gestion = array(
				'gestion' => $nombre_gestion,
				'inicio_gestion' => date_encode($inicio_gestion),
				'final_gestion' => date_encode($final_gestion),
				'inicio_vacaciones' => date_encode($inicio_vacaciones),
				'final_vacaciones' => date_encode($final_vacaciones)
			);
			
			// Verifica si es creacion o modificacion
			if ($id_gestion > 0) {
				// Modifica la gestion
				$db->where('id_gestion', $id_gestion)->update('ins_gestion', $gestion);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el gestion con identificador número ' . $id_gestion . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/s-gestion-escolar/ver/' . $id_gestion);
				echo 1;
			} else {
				// Crea el gestion
				//array_push($gestion,'estado'=>'A');
				$id_gestion = $db->insert('ins_gestion', $gestion);



				$gestiones = $db->query("SELECT *
										FROM ins_gestion z
										WHERE estado='A' AND gestion=".($nombre_gestion-1)."
										")->fetch_first();


				if($gestiones){
					$asignaciones = $db->query("SELECT *
												FROM per_asignaciones a
												WHERE gestion_id=".$gestiones['id_gestion']." AND fecha_final='0000-00-00'
												")->fetch();

					foreach ($asignaciones as $value) {
		                $res = array(
		                    'fecha_asignacion' => date("Y-m-d H:i:s"),
		                    'observacion' => $value["observacion"],
		                    'horario_id' => $value["horario_id"],
		                    'persona_id' => $value["persona_id"],
		                    'cargo_id' => $value["cargo_id"],
		                    
		                    'user_id' => $_user['id_user'],
		                    'nivel_academico_id'=>$value["nivel_academico_id"],
		                    'materia_id' => $value["materia_id"],
		                    'sueldo_por_hora' => $value["sueldo_por_hora"],	                   
		                    'horas_academicas' => $value["horas_academicas"],

		                    'usuario_registro' => $_user['id_user'],
		                    'fecha_registro' => date('Y-m-d'),
		                    'usuario_modificacion' => $_user['id_user'],
		                    'fecha_modificacion' => date('Y-m-d'),
		                    'documento'=>$value["documento"],
		                    
		                    'codigo' => $value["codigo"],
		                    'gestion_id' => $id_gestion,
		                    'estado' => $value["estado"],
		                    'cuenta_bancaria' => $value["cuenta_bancaria"],
		                    'item_cns' => $value["item_cns"],
		                    
		                    'fecha_inicio'=>$value["fecha_inicio"],
		                    'fecha_final'=>$value["fecha_final"],
		                    'sueldo_total' => $value["sueldo_total"],
		                    'firma_id' => $value["firma_id"],
		                    'estado_real' => $value["estado_real"],
		                    
		                    'estado_contrato'=> $value["estado_contrato"],
		                    'fecha_estado_contrato'=> $value["fecha_estado_contrato"]
		                );
						$id_xxx = $db->insert('per_asignaciones', $res);		                
        			}					
				}
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el gestion con identificador número ' . $id_gestion . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				
				// Redirecciona la pagina
				//redirect('?/s-gestion-escolar/listar');
				echo 2;
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	//} else {
		// Redirecciona la pagina
		//redirect('?/s-gestion-escolar/listar');
		//echo 3;
	//}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>