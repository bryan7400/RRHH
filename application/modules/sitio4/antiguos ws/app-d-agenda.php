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
		$usuario = clear($_POST['usuario']);
        $contrasenia = clear($_POST['contrasenia']); 
       //$id_profesor = clear($_POST['id_profesor']);	
        //$id_user=clear($_POST['id_user']);
        $id_persona=clear($_POST['id_persona']);
        $fecha_presentacion=clear($_POST['fecha']);

		// Encripta la contraseña para compararla en la base de datos
		$usuario = md5($usuario);
		$contrasenia = encrypt($contrasenia);
 
		// Obtiene los datos del usuario
		$usuario = $db->select('id_user, gestion_id')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
			
		// Verifica la existencia del usuario 
		if ($usuario) {

			$id_gestion=$usuario['gestion_id']; 

            // Obtiene las tareas/actividades del docente mas aula y paralelo
            //WHERE pr.id_profesor=$id_profesor
            //recibo nombre de persona
            //id_tares curso tarea fechas colores
            //filtrar 
          $consulta = "SELECT  act.*,au.*,pa.*,ni.* FROM cal_actividad_materia_modo_area act
            INNER JOIN int_aula_paralelo_asignacion_materia apam ON apam.id_aula_paralelo_asignacion_materia=act.aula_paralelo_asignacion_materia_id
            INNER JOIN pro_materia mat ON mat.id_materia=apam.materia_id   
            INNER JOIN per_asignaciones asi ON asi.`id_asignacion`=apam.`asignacion_id`
        
	       INNER JOIN ins_aula_paralelo ap ON apam.aula_paralelo_id=ap.id_aula_paralelo        
            INNER JOIN ins_aula au ON au.id_aula=ap.aula_id   
	       INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
	       INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
        WHERE asi.persona_id=$id_persona AND
            act.estado='A' AND
            apam.estado='A' AND
            apam.gestion_id=$id_gestion AND
	       DATE(act.fecha_presentacion)='$fecha_presentacion'"; 
            
            
             /* 
            $consulta = "SELECT cam.id_actividad_materia_modo_area, cam.nombre_actividad, cam.descripcion_actividad, cam.fecha_presentacion, cam.fecha_registro,
			ma.nombre_materia, a.nombre_aula, p.nombre_paralelo, ap.id_aula_paralelo,
			CONCAT(pe.nombres,' ',pe.primer_apellido,' ',pe.segundo_apellido)AS nombre_profesor, pr.id_profesor, pe.id_persona
			FROM int_aula_paralelo_profesor_materia AS iap
			INNER JOIN cal_actividad_materia_modo_area AS cam ON cam.aula_paralelo_profesor_materia_id = iap.id_aula_paralelo_profesor_materia
			INNER JOIN pro_profesor_materia AS pm ON pm.id_profesor_materia = iap.profesor_materia_id
			INNER JOIN int_aula_paralelo_profesor_materia aup ON pm.id_profesor_materia=aup.profesor_materia_id
			INNER JOIN ins_aula_paralelo ap ON aup.aula_paralelo_id=ap.id_aula_paralelo
			INNER JOIN ins_aula a ON ap.aula_id=a.id_aula
			INNER JOIN ins_paralelo p ON ap.paralelo_id=p.id_paralelo
			INNER JOIN pro_materia AS ma ON ma.id_materia = pm.materia_id
			INNER JOIN pro_profesor AS pr ON pr.id_profesor = pm.profesor_id
			INNER JOIN sys_persona AS pe ON pe.id_persona = pr.persona_id
			INNER JOIN sys_users AS u ON pe.id_persona = u.persona_id
			WHERE u.id_user=$id_user
			AND cam.estado='A'
			AND a.gestion_id=$id_gestion ORDER BY cam.fecha_presentacion DESC";*/
			$notificaciones = $db->query($consulta)->fetch();  
            
            
     /*       $auxiliar = array();
            foreach ($notificaciones as $val) {
            	$date = date_create($val['fecha_presentacion']);
            	//var_dump($date);exit();
            	$fecha_presentacion=date_format($date, 'G:iA');
				$array = (array) [
				    'id_actividad_materia_modo_area' => $val['id_actividad_materia_modo_area'],
				    'nombre_actividad' => $val['nombre_actividad'],
				    'descripcion_actividad' => $val['descripcion_actividad'],
				    'fecha_presentacion' => $val['fecha_presentacion'],
				    'hora_presentacion' => $fecha_presentacion,
					'fecha_registro' => $val['fecha_registro'],
				    'nombre_materia' => $val['nombre_materia'],
					'nombre_aula' => $val['nombre_aula'],
				    'nombre_paralelo' => $val['nombre_paralelo'],
				    'id_aula_paralelo' => $val['id_aula_paralelo'], 
				    'nombre_profesor' => $val['nombre_profesor'], 
				    'id_profesor' => $val['id_profesor'],
				    'id_persona' => $val['id_persona'],
				];
				array_push($auxiliar, $array);
            }*/

            // Obtiene los prdatos de la institución
			$sql = "SELECT * FROM sys_instituciones";
			$respuesta = $db->query($sql)->fetch_first();
			
           // var_dump($estudiantes_cursos);exit();
			// Instancia el objeto
			$respuesta = array(
				'estado'       => 's',
				'notificacion' => $notificaciones,
				'institucion'  => $respuesta['nombre']
			);

			// Devuelve los resultados
			echo json_encode($respuesta);
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'n'));
		}
	} else {
		// Devuelve los resultados
		echo json_encode(array('estado' => 'n usuario'));
	}
} else {
	// Devuelve los resultados
	echo json_encode(array('estado' => 'npost'));
}
?>