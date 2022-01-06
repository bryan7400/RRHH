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
	
		// Verifica la existencia de datos
		if (isset($_POST['gestion_n']) ||isset($_POST['cargo_id']) || isset($_POST['accion_persona']) ) {
			
			
			// Obtiene los datos
			$cargo_id 			= clear($_POST['cargo_id']);
			$gestion_n 			= $_POST['gestion_n'];
            $accion_persona 	= clear($_POST['accion_persona']);
            $gestion_id 		= $_gestion['id_gestion'];

            

            // ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            // // Verifica si es creacion o modificacion  C = Creacion y A = Actualizar ; P = Programado y CG = Contraseña generica  ||  D = Desbloquear ; B = Bloquear
            // ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

            // Si el rol = 5 (ESTUDIANTE) ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::	
	

			if ($accion_persona == 'R' ) {
            	//var_dump('expression');
                
				

			$estudiantes = $db->query("SELECT asi.*, ca.cargo, e.*, p.*,p.celular AS celu , e.celular AS celular,p.email AS correo , e.email AS email
			FROM sys_persona e 
			INNER JOIN per_asignaciones asi ON asi.persona_id = e.id_persona                                 
			INNER JOIN per_postulacion p ON p.id_postulacion = e.postulante_id                                 
			LEFT JOIN per_cargos ca ON asi.cargo_id = ca.id_cargo

			WHERE p.estado = 'A'
			")->fetch();

				foreach ($estudiantes as $key => $value) {

                    ///////////////////////////////////////////////////////////////////////
					$gestion_id=$value['gestion_id'];
					$gestion_nueva = $db->query("SELECT * FROM ins_gestion WHERE id_gestion=$gestion_n")->fetch_first(); 
					
					$gestion_datos = $db->query("SELECT * FROM ins_gestion WHERE id_gestion=$gestion_id")->fetch_first(); 
					
		        
				    $id_asignacion	= $value['id_asignacion'];
					$year_ini = date('Y', strtotime($value["fecha_inicio"]));
					$year_fin = date('Y', strtotime($value["fecha_final"]));
					
				    
					
					if($cargo_id== $value['cargo_id']){

					
					$documento 		= $value['documento'];

					$fecha_inicio	= str_replace($year_ini,$gestion_nueva['gestion'],$value["fecha_inicio"]);
					$fecha_final	= str_replace($year_fin,$gestion_nueva['gestion'],$value["fecha_final"]);
					$gestion_id_nueva		= $gestion_nueva['id_gestion'];
						
					$documento_actual=str_replace($gestion_datos['gestion'],$gestion_nueva['gestion'],$documento);				
					

					
					$actual_asignacion = array(
					
						//'avatar' 	=> '',
						'gestion_id'=> $gestion_id_nueva,
						'documento'=> $documento_actual,
						'fecha_inicio'=> $fecha_inicio,
						'fecha_final'=> $fecha_final,

						
					);
					echo $documento_actual;
                    // Modifica el actual_asignacion
				    $db->where('id_asignacion', $id_asignacion)->update('per_asignaciones', $actual_asignacion);

				}	
					
				
					}
				    
					

					// Guarda el proceso
						// $db->insert('sys_procesos', array(
						// 	'fecha_proceso'	=> date('Y-m-d'),
						// 	'hora_proceso' 	=> date('H:i:s'),
						// 	'proceso' 		=> 'u',
						// 	'nivel' 		=> 'm',
						// 	'detalle' 		=> 'Se actualizó el usuario con rol estudiante con identificador número ' . $id_usuario . '.',
						// 	'direccion' 	=> $_location,
						// 	'usuario_id' 	=> $_user['id_user']
						// ));
				    //var_dump($password,$id_user,$id_rol);
				}
                //exit();
				// Crea la notificacion
				
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

				// Redirecciona la pagina
				
				
		redirect('?/rrhh-personal/personal');
	
	
		} else {
		// Redirecciona la pagina
		
		redirect('?/rrhh-personal/personal');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>