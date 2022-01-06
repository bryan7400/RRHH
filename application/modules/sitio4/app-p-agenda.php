<?php
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL MARCO LUIS)
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
 if (is_post()) {
    
	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
	    
		//Obtiene los datos
		$usuario 		  = clear($_POST['usuario']);
        $contrasenia 	  = clear($_POST['contrasenia']);
		$id_aula_paralelo = clear($_POST['id_aula_paralelo']);
		$id_estudiante    = clear($_POST['id_estudiante']);
		$id_gestion       = clear($_POST['id_gestion']);
		$fecha            = clear($_POST['fecha']);
        
		$usuario    = md5($usuario);
		$contrasenia = encrypt($contrasenia);
		
		//preguntamos sobre la version
		$res_institucion = $db->query("SELECT * FROM sys_instituciones")->fetch_first();
		

		$sql_actividades_no_presentables = "select taca.id_asesor_curso_actividad, pm.nombre_materia, ifnull(pm.imagen_materia, '')as imagen_materia,taca.presentar_actividad, taca.fecha_presentacion_actividad , taca.tipo_actividad, taca.hora_fin, taca.fecha_examen , taca.fecha_extencion , taca.hora_extencion , taca.nombre_actividad , taca.descripcion_actividad, taca.fecha_registro
												from pro_asignacion_docente as pad
												inner join pro_materia pm on pm.id_materia = pad.materia_id 
												inner join tem_asesor_curso_actividad taca on taca.asignacion_docente_id = pad.id_asignacion_docente 
												where aula_paralelo_id = $id_aula_paralelo and pad.estado_docente = 'A' and pad.gestion_id = $id_gestion and taca.estado_curso = 'N' and taca.estado_actividad = 'A' and taca.presentar_actividad = 'NO' and taca.fecha_registro >= '$fecha'";
		$res_actividades_no_presentables = $db->query($sql_actividades_no_presentables)->fetch();
		
		$sql_actividades_si_presentables = "select taca.id_asesor_curso_actividad, pm.nombre_materia, ifnull(pm.imagen_materia, '')as imagen_materia,taca.presentar_actividad, taca.fecha_presentacion_actividad , taca.tipo_actividad, taca.hora_fin, taca.fecha_examen , taca.fecha_extencion , taca.hora_extencion , taca.nombre_actividad , taca.descripcion_actividad, taca.fecha_registro
												from pro_asignacion_docente as pad
												inner join pro_materia pm on pm.id_materia = pad.materia_id 
												inner join tem_asesor_curso_actividad taca on taca.asignacion_docente_id = pad.id_asignacion_docente 
												where aula_paralelo_id = $id_aula_paralelo and pad.estado_docente = 'A' and pad.gestion_id = $id_gestion and taca.estado_curso = 'N' and taca.estado_actividad = 'A' and taca.presentar_actividad = 'SI' and taca.fecha_registro >= '$fecha'";
		$res_actividades_si_presentables = $db->query($sql_actividades_si_presentables)->fetch();	

		
		$sql_actividades_no_presentables_extra = "select taca.id_asesor_curso_actividad, ec.nombre_curso , ifnull(ec.imagen_curso,'')as imagen_materia,taca.presentar_actividad, taca.fecha_presentacion_actividad , taca.tipo_actividad, taca.hora_fin, taca.fecha_examen , taca.fecha_extencion , taca.hora_extencion , taca.nombre_actividad , taca.descripcion_actividad, taca.fecha_registro
													from ext_curso_inscripcion eci 
													inner join ext_curso_asignacion eca on eca.id_curso_asignacion = eci.curso_asignacion_id
													inner join ext_curso ec on ec.id_curso = eca.curso_id 
													inner join tem_asesor_curso_actividad taca on taca.asignacion_asesor_id = eca.id_curso_asignacion 
													where eci.estudiante_id = $id_estudiante and eca.estado = 'A' and eca.gestion_id = $id_gestion AND taca.estado_curso = 'E' and eca.gestion_id = $id_gestion and taca.presentar_actividad = 'A' and taca.presentar_actividad = 'NO' and taca.fecha_registro >= '$fecha'";
		$res_actividades_no_presentables_extra = $db->query($sql_actividades_no_presentables_extra)->fetch();

		$sql_actividades_si_presentables_extra = "select taca.id_asesor_curso_actividad, ec.nombre_curso , ifnull(ec.imagen_curso,'')as imagen_materia,taca.presentar_actividad, taca.fecha_presentacion_actividad , taca.tipo_actividad, taca.hora_fin, taca.fecha_examen , taca.fecha_extencion , taca.hora_extencion , taca.nombre_actividad , taca.descripcion_actividad, taca.fecha_registro
													from ext_curso_inscripcion eci 
													inner join ext_curso_asignacion eca on eca.id_curso_asignacion = eci.curso_asignacion_id
													inner join ext_curso ec on ec.id_curso = eca.curso_id 
													inner join tem_asesor_curso_actividad taca on taca.asignacion_asesor_id = eca.id_curso_asignacion 
													where eci.estudiante_id = $id_estudiante and eca.estado = 'A' and eca.gestion_id = $id_gestion AND taca.estado_curso = 'E' and eca.gestion_id = $id_gestion and taca.presentar_actividad = 'A' and taca.presentar_actividad = 'SI' and taca.fecha_registro >= '$fecha'";
		$res_actividades_no_presentables_extra = $db->query($sql_actividades_no_presentables_extra)->fetch();

		// Obtiene los datos del usuario
		$usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		
		// Verifica la existencia del usuario 
		if ($usuario) {			
			
			$aActividades = array_merge($res_actividades_no_presentables, $res_actividades_si_presentables, $res_actividades_no_presentables_extra,$res_actividades_no_presentables_extra);

			// Instancia el objeto que devolvera la web service			
			$respuesta = array(
				'estado' => 's',
				'version_app' => $res_institucion['version_app_tutor'],
				'agenta_estudiante' => $aActividades
			);

			// Devuelve los resultados
			echo json_encode($respuesta);
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'n'));
		}
	} else {
	// 	// Devuelve los resultados
	   echo json_encode(array('estado' => 'n usuario'));
	}
} else {
// 	// Devuelve los resultados
    echo json_encode(array('estado' => 'npost'));
}
