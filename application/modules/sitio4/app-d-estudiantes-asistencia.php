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
		$contrasenia      = clear($_POST['contrasenia']);
        $usuario          = md5($usuario);
		$contrasenia      = encrypt($contrasenia);
		$id_gestion       = clear($_POST['id_gestion']);
		$tipo_area        = clear($_POST['tipo_area']);
		$tipo_extra       = clear($_POST['tipo_extra']);
		$fecha_asistencia = clear(date($_POST['fecha_asistencia']));
		
		//obtener el id_modo_calificacion
			//obtenemos la fecha de hoy
		$hoy = date('Y-m-d');
		$estado_fecha = "0";//Controla si la fecha no sea futura
		
		if($fecha_asistencia > $hoy){
		    $fecha_asistencia = $hoy;
		    $estado_fecha = 1;
		}
		
		//echo $fecha_asistencia;exit();

		// Obtenemos el modo calificacion
		$sql_modo_calificacion = "SELECT * FROM cal_modo_calificacion WHERE gestion_id ='$id_gestion' and fecha_inicio <= '$hoy' and fecha_final >= '$hoy' AND estado = 'A'";
		$modos_calificacion = $db->query($sql_modo_calificacion)->fetch_first();
		$id_modo_calificacion = ((isset($modos_calificacion['id_modo_calificacion']) ? $modos_calificacion['id_modo_calificacion'] : "0"));
		
        
        $usuario = $db->select('persona_id,id_user, username, email, avatar, rol_id, visible')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		// Verifica la existencia del usuario
		if ($usuario) {
		// Obtiene los datos:::::::::::::::::::::::::::::::::::::::
            
        $id_asignacion_docente = $_POST['id_asignacion_docente'];
         
            if ($tipo_extra == "SI") {
                
                $estudiantes_cursos = $db->query("SELECT e.id_estudiante, p.id_persona,p.nombres,p.primer_apellido,p.segundo_apellido,p.tipo_documento,p.numero_documento,p.complemento,p.expedido,p.genero,p.fecha_nacimiento,IF(p.foto != 'NULL', IF(p.foto !='',p.foto,''),'')AS foto
                                                    FROM ext_curso_asignacion AS ca
                                                    INNER JOIN ext_curso_inscripcion AS ci ON ci.curso_asignacion_id = ca.id_curso_asignacion   
                                                    INNER JOIN ins_estudiante AS e ON e.id_estudiante = ci.estudiante_id
                                                    INNER JOIN sys_persona AS p ON p.id_persona = e.persona_id 
                                                    WHERE ca.id_curso_asignacion = $id_asignacion_docente AND ci.estado = 'A' AND ci.gestion_id = $id_gestion
                                                    ORDER BY p.primer_apellido ASC")->fetch();
                 $sql_asistencias ="SELECT *
                            FROM int_asistencia_estudiante_materia as iaem
                            WHERE iaem.asignacion_docente_id =  $id_asignacion_docente AND iaem.estado_curso = 'E' AND iaem.modo_calificacion_id = $id_modo_calificacion";
                                                                    
            } else {
                
                 $sql_asistencias ="SELECT *
                            FROM int_asistencia_estudiante_materia as iaem
                            WHERE iaem.asignacion_docente_id =  $id_asignacion_docente AND estado_curso = 'N' AND iaem.modo_calificacion_id = $id_modo_calificacion";
                
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
            
            
            //Arnamos la asistencia de cada estudiante y la fecha 
            $res_asistencia = $db->query($sql_asistencias)->fetch();
            
            
            //Armamos el array de la asistencias
            $aAsistencia = array();
            foreach($res_asistencia AS $ka => $iAsistencia){
                $aAsistencia [$iAsistencia['estudiante_id']] =  $iAsistencia['json_asistencia'];
            }
            
            // var_dump($aAsistencia);
            // exit();
            
            // Armamos el array de las sistencias
            $aAsistenciaEstudiantes = array();
            foreach($aAsistencia AS $id_estudiante => $jsonAsistencia){
                
                //Eliminamos el ultimo caracter
			    $jsAsistencia = substr($jsonAsistencia, 0, -1);
			    $aJSFechaAsistencia = explode(",", $jsAsistencia);
                
                //recorremos el array de asistencia
                for($i = 0; $i < sizeof($aJSFechaAsistencia) ; $i++){
                    $aFechaAsistencia = explode("@",$aJSFechaAsistencia[$i]);
                    // Solo nos quedamos con la fecha
                    $afechaHora       = explode(" ",$aFechaAsistencia[0]);
                    $fecha = $afechaHora[0];
                    $valor_asistencia = $aFechaAsistencia[1];
                    
                    //asignamos un array
                    //$item_array = array($fecha => $valor_asistencia);
                    $aAsistenciaEstudiantes[$id_estudiante][$fecha] =  $valor_asistencia;
                }
            }
            
            // echo "<pre>";
            // var_dump($aAsistenciaEstudiantes);
            // echo "<pre>";
            // exit();
            
            
            //Aramamos el array para mostar los estudiantes y su fecha de asistencia y el valor de la asistencia
            
            $aEstudiantesAsistencia = array();
            $cont   = 0;
            $indice = -1;
            
            foreach($estudiantes_cursos AS $i => $estudiante ){
                $id_estudiante = $estudiante['id_estudiante'];
                $aEstudiantesAsistencia[$cont] = $estudiante;
                if(isset($aAsistenciaEstudiantes[$id_estudiante][$fecha_asistencia])){
                    $aEstudiantesAsistencia[$cont]['valor_asistencia'] =  $aAsistenciaEstudiantes[$id_estudiante][$fecha_asistencia];
                    $indice = array_search($fecha_asistencia,array_keys($aAsistenciaEstudiantes[$id_estudiante]));
                    $aEstudiantesAsistencia[$cont]['posicion_asistencia'] =  $indice;
                }else{
                    $aEstudiantesAsistencia[$cont]['valor_asistencia'] =  "p";
                    $aEstudiantesAsistencia[$cont]['posicion_asistencia'] =  -1;
                }
                $cont++;
                
            }
            
            
            echo json_encode(array('estado' => 's', 'estado_fecha' => $estado_fecha,  'tamanio' => count($res_asistencia), 'id_modo_calificacion' => $id_modo_calificacion,'indice' => $indice, 'estudiantes' => $aEstudiantesAsistencia)); 
          
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