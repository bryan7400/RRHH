<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARCO QUINO)
 */

 
header('Content-Type: application/json'); 
// Verifica la peticion post
 if (is_post()) {
 
	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		
		 $hoy = date('Y-m-d'); 
        
		$usuario 		 = clear($_POST['usuario']);
        $contrasenia 	 = clear($_POST['contrasenia']);
		$id_inscripcion  = clear($_POST['id_inscripcion']);
		$id_gestion      = clear($_POST['id_gestion']);
        $fecha           = (clear($_POST['fecha'])!= "")?$_POST['fecha']:$hoy;
        
		
       
		// Encripta la contraseña para compararla en la base de datos
		$usuario    = md5($usuario);
		$contrasenia = encrypt($contrasenia);
	
		// Obtiene los datos del usuario
		$usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		
		$sql_modo_calificacion = "SELECT * FROM cal_modo_calificacion WHERE gestion_id ='$id_gestion' and fecha_inicio <= '$hoy' and fecha_final >= '$hoy' AND estado = 'A'";
		$modos_calificacion = $db->query($sql_modo_calificacion)->fetch_first();
		
		$id_modo_calificacion = ((isset($modos_calificacion['id_modo_calificacion'])?$modos_calificacion['id_modo_calificacion']:"0"));

	
		// Verifica la existencia del usuario 
		if ($usuario) {
			//Consultamos las areas de calificacion 
			$sql_felicitaciones ="SELECT  CONCAT('F')AS tipo_kardex,fe.fecha_felicitacion AS fecha ,fe.id_felicitaciones AS id_evento,fe.motivo,ar.inscripcion_id,'fe' AS tipo,(fe.descripcion) AS descripcion , pm.nombre_materia, CONCAT(sp.nombres,' ',sp.primer_apellido,' ',sp.segundo_apellido)AS nombre_docente , CONCAT('') AS traer_tutor
                                	FROM arc_felicitaciones fe
                                	INNER JOIN arc_archivo ar ON ar.id_archivo= fe.archivo_id
                                	INNER JOIN pro_asignacion_docente AS pad ON pad.id_asignacion_docente = fe.asignacion_docente_id
                                	INNER JOIN pro_materia AS pm ON pm.id_materia = pad.materia_id
                                	INNER JOIN per_asignaciones AS pa ON pa.id_asignacion = pad.asignacion_id
                                	INNER JOIN sys_persona AS sp ON sp.id_persona = pa.persona_id
                                	WHERE ar.inscripcion_id = $id_inscripcion and fe.modo_calificacion_id = $id_modo_calificacion AND DATE(fe.fecha_registro) = '$fecha'     
                                	ORDER BY fe.fecha_registro DESC";
                                	
            //echo $sql_felicitaciones;
            
                                	
         	$felicitaciones = $db->query($sql_felicitaciones)->fetch();
         
			$sql_citacion ="SELECT  CONCAT('C')AS tipo_kardex,ci.fecha_envio AS fecha,id_citacion AS id_evento,motivo,ar.inscripcion_id,'ci'AS tipo,CONCAT ('El tutor debe apersonarse para la fecha: ' ,ci.fecha_asistencia)AS descripcion, pm.nombre_materia, CONCAT(sp.nombres,' ',sp.primer_apellido,' ',sp.segundo_apellido)AS nombre_docente, CONCAT('') AS traer_tutor
                            	FROM arc_citaciones ci
                            	INNER JOIN arc_archivo ar ON ar.id_archivo= ci.archivo_id
                            	INNER JOIN pro_asignacion_docente AS pad ON pad.id_asignacion_docente = ci.asignacion_docente_id
                            	INNER JOIN pro_materia AS pm ON pm.id_materia = pad.materia_id
                            	INNER JOIN per_asignaciones AS pa ON pa.id_asignacion = pad.asignacion_id
                            	INNER JOIN sys_persona AS sp ON sp.id_persona = pa.persona_id
                            	WHERE ar.inscripcion_id = $id_inscripcion and ci.modo_calificacion_id=$id_modo_calificacion AND DATE(ci.fecha_registro) = '$fecha' 
                            	ORDER BY ci.fecha_registro DESC";
			$citacion = $db->query($sql_citacion)->fetch();
         
					
			$sql_sancion = "SELECT  CONCAT('S')AS tipo_kardex,sa.fecha_sancion AS fecha,id_sancion AS id_evento,motivo,ar.inscripcion_id, sa.dias_suspencion as tipo, sa.fecha_traer_tutor as descripcion, pm.nombre_materia, CONCAT(sp.nombres,' ',sp.primer_apellido,' ',sp.segundo_apellido)AS nombre_docente, sa.traer_tutor
                            	FROM arc_sanciones sa
                            	INNER JOIN arc_archivo ar ON ar.id_archivo= sa.archivo_id
                            	INNER JOIN pro_asignacion_docente AS pad ON pad.id_asignacion_docente = sa.asignacion_docente_id
                            	INNER JOIN pro_materia AS pm ON pm.id_materia = pad.materia_id
                            	INNER JOIN per_asignaciones AS pa ON pa.id_asignacion = pad.asignacion_id
                            	INNER JOIN sys_persona AS sp ON sp.id_persona = pa.persona_id
                            	WHERE ar.inscripcion_id = $id_inscripcion and sa.modo_calificacion_id=$id_modo_calificacion AND DATE(sa.fecha_registro) = '$fecha'
                            	ORDER BY sa.fecha_registro DESC";
					
			$sancion = $db->query($sql_sancion)->fetch();
			
			$aKardex = array_merge($felicitaciones, $citacion, $sancion);
            
			// Instancia el objeto que devolvera la web service			
			$respuesta = array(
				'estado' => 's',
				'kardex' => $aKardex					
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
