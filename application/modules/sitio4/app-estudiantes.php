<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  WEB SERVICES
 * @author  MARCO ANTONIO QUINO CHOQUETA
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {
	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		// Obtiene los datos
		$usuario = clear($_POST['usuario']);
		$contrasenia = clear($_POST['contrasenia']);
        $usuario = md5($usuario);
		$contrasenia = encrypt($contrasenia);
		$id_gestion = clear($_POST['id_gestion']);
		$tipo_area = clear($_POST['tipo_area']);
		$tipo_extra = clear($_POST['tipo_extra']);
		
        
        $usuario = $db->select('persona_id,id_user, username, email, avatar, rol_id, visible')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		// Verifica la existencia del usuario
		if ($usuario) {
		// Obtiene los datos:::::::::::::::::::::::::::::::::::::::
            
        $id_asignacion_docente = $_POST['id_asignacion_docente'];//id_asignacion_docente
      
            // $estudiantes_cursos = $db->query("SELECT  z.estudiante_id, p.id_persona,p.nombres,p.primer_apellido,p.segundo_apellido,p.tipo_documento,p.numero_documento,p.complemento,p.expedido,p.genero,p.fecha_nacimiento,IF(p.foto != 'NULL', IF(p.foto !='',p.foto,''),'')AS foto
            //     FROM ins_inscripcion z 
            //     INNER JOIN ins_gestion g ON z.gestion_id=g.id_gestion  
            //     INNER JOIN ins_estudiante e ON z.estudiante_id=e.id_estudiante
            //     INNER JOIN sys_persona p ON e.persona_id=p.id_persona  
            //     INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=z.aula_paralelo_id  
            //     INNER JOIN pro_asignacion_docente apam ON apam.aula_paralelo_id=ap.id_aula_paralelo
            //     WHERE z.gestion_id=$id_gestion
        	   //     AND  apam.`id_asignacion_docente`=$id_asignacion_docente
            //         AND z.estado='A' 
            //         AND g.estado='A'
            //         AND ap.estado='A'
            //     ORDER BY   p.primer_apellido ASC,  p.segundo_apellido ASC")->fetch();
            
            
            if ($tipo_extra == "SI") {
                
                $estudiantes_cursos = $db->query("SELECT e.id_estudiante, p.id_persona,p.nombres,p.primer_apellido,p.segundo_apellido,p.tipo_documento,p.numero_documento,p.complemento,p.expedido,p.genero,p.fecha_nacimiento,IF(p.foto != 'NULL', IF(p.foto !='',p.foto,''),'')AS foto
                                                    FROM ext_curso_asignacion AS ca
                                                    INNER JOIN ext_curso_inscripcion AS ci ON ci.curso_asignacion_id = ca.id_curso_asignacion   
                                                    INNER JOIN ins_estudiante AS e ON e.id_estudiante = ci.estudiante_id
                                                    INNER JOIN sys_persona AS p ON p.id_persona = e.persona_id 
                                                    WHERE ca.id_curso_asignacion = $id_asignacion_docente AND ci.estado = 'A' AND ci.gestion_id = $id_gestion
                                                    ORDER BY p.primer_apellido ASC")->fetch();
            } else {
                $datos_materia = $db->query("SELECT aca.tipo_actividad, aca.nombre_actividad, aca.descripcion_actividad, aca.fecha_presentacion_actividad, aca.hora_fin, CONCAT(p.nombres,' ',p.primer_apellido,' ',p.segundo_apellido) AS nombre_completo, p.foto, pm.id_materia , pm.nombre_materia , CONCAT(ia.nombre_aula,' ',ip.nombre_paralelo,' ',ina.nombre_nivel) AS curso, iap.id_aula_paralelo AS id_aula_paralelo, ina.tipo_calificacion 
            						FROM tem_asesor_curso_actividad AS aca
            						INNER JOIN pro_asignacion_docente AS ad ON ad.id_asignacion_docente = aca.asignacion_docente_id
            						INNER JOIN per_asignaciones AS pa ON pa.id_asignacion = ad.asignacion_id
            						INNER JOIN sys_persona AS p ON p.id_persona = pa.persona_id
            						INNER	JOIN	pro_materia AS pm ON pm.id_materia = ad.materia_id
            						INNER JOIN	ins_aula_paralelo AS iap ON iap.id_aula_paralelo = ad.aula_paralelo_id
            						INNER JOIN  ins_aula AS ia ON ia.id_aula = iap.aula_id
            						INNER JOIN  ins_nivel_academico AS	ina ON ina.id_nivel_academico = ia.nivel_academico_id 
            						INNER JOIN  ins_paralelo AS ip ON ip.id_paralelo = iap.paralelo_id
            						WHERE ad.id_asignacion_docente = $id_asignacion_docente")->fetch_first();
            
                $tipo_calificacion = $datos_materia['tipo_calificacion'];
                
                if ($tipo_area == "SI") {
                    //si esta entre el rango de 1 y 4 es una area
                    // 1 : CONTRUCCION
                    // 2 : CONTABILIDAD
                    // 3 : INFORMATICA
                    // 4 : SALUD
                    $id_aula_paralelo = $datos_materia['id_aula_paralelo'];
                    $area = "";
                    switch ($datos_materia['id_materia']) {
                        case '1':
                            $area = "CONSTRUCCION";
                            break;
                        case '2':
                            $area = "CONTABILIDAD";
                            break;
                        case '3':
                            $area = "INFORMATICA";
                            break;
                        case '4':
                            $area = "SALUD";
                            break;
                        default:
                            $area = "";
                            break;
                    }
            
                    // Listamos a todos los estudiantes del area
                    $estudiantes_cursos = $db->query("SELECT e.id_estudiante,p.id_persona,p.nombres,p.primer_apellido,p.segundo_apellido,p.tipo_documento,p.numero_documento,p.complemento,p.expedido,p.genero,p.fecha_nacimiento,IF(p.foto != 'NULL', IF(p.foto !='',p.foto,''),'')AS foto
                                                FROM ins_inscripcion AS i
                                                INNER JOIN ins_estudiante AS e ON e.id_estudiante = i.estudiante_id
                                                INNER JOIN sys_persona AS p ON p.id_persona = e.persona_id 
                                                WHERE i.aula_paralelo_id = '$id_aula_paralelo' AND i.estado = 'A' AND i.gestion_id = '$id_gestion' AND area= '$area'
                                                ORDER BY p.primer_apellido ASC")->fetch();
                } else {
                    $id_aula_paralelo = $datos_materia['id_aula_paralelo'];
                    //Aca se realiza las actividades normales
                    
                    $sql_estudiante_normales = "SELECT e.id_estudiante, p.id_persona,p.nombres,p.primer_apellido,p.segundo_apellido,p.tipo_documento,p.numero_documento,p.complemento,p.expedido,p.genero,p.fecha_nacimiento,IF(p.foto != 'NULL', IF(p.foto !='',p.foto,''),'')AS foto
                                                FROM ins_inscripcion AS i
                                                INNER JOIN ins_estudiante AS e ON e.id_estudiante = i.estudiante_id
                                                INNER JOIN sys_persona AS p ON p.id_persona = e.persona_id 
                                                WHERE i.aula_paralelo_id = '$id_aula_paralelo' AND i.estado = 'A' AND i.gestion_id = '$id_gestion'
                                                ORDER BY p.primer_apellido ASC";
                                                
                    //echo $sql_estudiante_normales; exit();                                                
                    
                    $estudiantes_cursos = $db->query($sql_estudiante_normales)->fetch();
                }
            }
            
             echo json_encode(array('estado' => 's', 'estudiantes' => $estudiantes_cursos)); 
          
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'no tiene usuario asignado'));
		}
		} else {
		// Devuelve los resultados
		echo json_encode(array('estado' => 'no hay datos'));
	}
} else {
	// Devuelve los resultados
	echo json_encode(array('estado' => 'n'));
}

?>