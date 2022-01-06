<?php
/** 
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL JORGE LUIS)
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {
    
	//Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		$usuario     = clear($_POST['usuario']);
        $contrasenia = clear($_POST['contrasenia']); 
        $fecha       =clear($_POST['fecha']);
        $id_gestion  = clear($_POST['id_gestion']); 

		// Encripta la contraseña para compararla en la base de datos
		$usuario = md5($usuario);
		$contrasenia = encrypt($contrasenia);
		
			//preguntamos sobre la version
		$res_institucion = $db->query("SELECT * FROM sys_instituciones")->fetch_first();
 
		// Obtiene los datos del usuario
		$usuario = $db->select('*')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
			
		// Verifica la existencia del usuario 
		if ($usuario) {


            $id_persona = $usuario['persona_id'];
            
           

            //Tenemos todas las materias del docente buscamos ahora sus actividades

          $consulta = "SELECT  act.*,au.*,pa.*,ni.*, cac.* 
                        FROM tem_asesor_curso_actividad act
                        INNER JOIN cal_area_calificacion cac ON cac.id_area_calificacion = act.area_calificacion_id                       
                        INNER JOIN pro_asignacion_docente apam ON apam.id_asignacion_docente = act.asignacion_docente_id
                        INNER JOIN pro_materia mat ON mat.id_materia=apam.materia_id   
                        INNER JOIN per_asignaciones asi ON asi.id_asignacion=apam.asignacion_id
            	       INNER JOIN ins_aula_paralelo ap ON apam.aula_paralelo_id=ap.id_aula_paralelo        
                        INNER JOIN ins_aula au ON au.id_aula=ap.aula_id   
            	       INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
            	       INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
            	       
                        WHERE asi.persona_id=$id_persona AND
                        act.estado_actividad='A' AND
                        apam.estado_docente='A' AND
                        apam.gestion_id=$id_gestion AND
                        act.estado_curso = 'N' AND 
            	       DATE(act.fecha_presentacion_actividad) >= '$fecha'
            	       ORDER BY act.fecha_registro DESC";
          
			$actividades_normales = $db->query($consulta)->fetch();  
			
			//Tenemos todas las actividades realizadas por las materias extracurriculares
			
			$sql_actividades_extracurriculares = "SELECT  *
                                                    FROM tem_asesor_curso_actividad AS aca
                                                    INNER JOIN cal_area_calificacion cac ON cac.id_area_calificacion = aca.area_calificacion_id
                                                    INNER JOIN ext_curso_asignacion AS eca ON eca.id_curso_asignacion = aca.asignacion_docente_id
                                                    INNER JOIN ext_curso AS ec ON ec.id_curso = eca.curso_id
                                                    INNER JOIN per_asignaciones AS pa ON pa.id_asignacion = eca.asignacion_id
                                                    INNER JOIN sys_persona AS p ON p.id_persona = pa.persona_id
                                                    WHERE aca.estado_actividad = 'A' AND aca.estado_curso = 'E' AND eca.gestion_id = $id_gestion AND eca.estado = 'A' AND DATE(aca.fecha_presentacion_actividad) >= '$fecha' AND p.id_persona = '$id_persona'
                                                    ORDER BY aca.fecha_registro DESC";
                                                    
			$actividades_extracurriculares = $db->query($sql_actividades_extracurriculares)->fetch();
			
			
			$actividades = array_merge($actividades_normales, $actividades_extracurriculares);
            

           
			$respuesta = array(
				'estado'         => 's',
				'version_app'    => $res_institucion['version_app_tutor'],
				'codigo_sesion'  => $usuario['codigo_sesion'],
				'actividades'    => $actividades
			);

			// Devuelve los resultados
			echo json_encode($respuesta);
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'n'));
		}
	} else {
		// Devuelve los resultados
		echo json_encode(array('estado' => 'nu'));
	}
} else {
	// Devuelve los resultados
	echo json_encode(array('estado' => 'np'));
}
?>