<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK Maribel Marco Luis
 */


header('Content-Type: application/json');

// Verifica la peticion post
 if (is_post()) {
	

	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia']) && isset($_POST['id_gestion'])) {
		//Obtiene los datos
		$usuario 				= clear($_POST['usuario']);
        $contrasenia 			= clear($_POST['contrasenia']);
		$id_gestion     		= clear($_POST['id_gestion']);
		$dia     		= clear($_POST['dia']);




    $turno=isset($_POST['turno'])?$_POST['turno']:0;//turno
    $nivel=isset($_POST['nivel'])?$_POST['nivel']:0;//turno
    $paralelo=isset($_POST['paralelo'])?$_POST['paralelo']:0;//paralelo
   $hora_id=isset($_POST['hora_inicio'])?$_POST['hora_inicio']:0;//     

   
       
		// Encripta la contraseña para compararla en la base de datos
		$usuario    = md5($usuario);
		$contrasenia = encrypt($contrasenia);
		
		
		// Obtiene los datos del usuario
		$usuario = $db->select('*')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();

        $id_persona = 	$usuario['persona_id'];

		//obtiene los datos del modo de calificacion actual
		$fecha_actual = Date('Y-m-d');
		
		// Verifica la existencia del usuario 
		if ($usuario) {

			//Consultamos las areas de calificacion 
					
					$hijos = $db->query("SELECT e.id_estudiante,ins.id_inscripcion,ins.aula_paralelo_id,au.nombre_aula,pa.nombre_paralelo,ni.nombre_nivel,tu.nombre_turno, per.id_persona, per.nombres, per.primer_apellido, per.segundo_apellido, per.tipo_documento, per.numero_documento, per.complemento, per.expedido, per.genero, per.fecha_nacimiento, per.direccion, IF(per.foto != 'NULL', IF(per.foto !='',per.foto,''),'')AS foto,f.id_familiar, su.id_user, su.rol_id
                                    from ins_estudiante_familiar ef INNER JOIN ins_familiar  f ON ef.familiar_id=f.id_familiar
                                    INNER JOIN ins_estudiante  e ON e.id_estudiante=ef.estudiante_id
                                    INNER JOIN sys_persona  per ON per.id_persona=e.persona_id
                                    INNER JOIN sys_users su ON su.persona_id = per.id_persona
                                    LEFT  JOIN ins_inscripcion ins ON ins.estudiante_id  = e.id_estudiante
                                    LEFT  JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo  = ins.aula_paralelo_id
                                    INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
                                    INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
                                    INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
                                    INNER JOIN ins_turno tu ON tu.id_turno=ap.turno_id
                                    WHERE f.persona_id=$id_persona AND ins.estado = 'A' AND ins.gestion_id = $id_gestion AND su.estado = 'A' AND su.visible = 's'")->fetch_first();			
			

			

			$aula = $hijos["aula_paralelo_id"];





		 $sql="";
    if($paralelo){
        $sql.=" and  b.paralelo_id=$paralelo";
    }
    if($aula){
        $sql.=" and  apam.aula_paralelo_id=$aula";
    }
 


$consulta="
SELECT d.*, hd.*, z.*, m.*,hd.`complemento`,apam.*,

    (SELECT GROUP_CONCAT(CONCAT(per.nombres ,' ', per.primer_apellido,' ', per.segundo_apellido)
	SEPARATOR ' | ')AS nombres_completo  
    FROM  per_asignaciones asig,sys_persona  per
	WHERE 
	asig.persona_id=per.id_persona AND
	apam.asignacion_id=asig.id_asignacion 
	)AS nombres_doc,
	
    (SELECT GROUP_CONCAT(CONCAT(SUBSTRING(per.nombres, 1, 1),' ', SUBSTRING(per.primer_apellido, 1, 1),' ', SUBSTRING(per.segundo_apellido, 1, 1))
	SEPARATOR ' | ')AS nombres_completo  
    FROM  per_asignaciones asig,sys_persona  per
	WHERE 
	asig.persona_id=per.id_persona AND
	apam.asignacion_id=asig.id_asignacion  
	)AS iniciales


  FROM ins_horario_profesor_materia z,
  
	ins_aula_paralelo b,
	ins_aula c,
	ins_nivel_academico d,
	ins_paralelo e,
	ins_turno tu,
	pro_materia m,
	ins_horario_dia hd,
	ins_gestion ge,
	int_aula_paralelo_asignacion_materia apam
WHERE apam.aula_paralelo_id=b.id_aula_paralelo AND 
	tu.id_turno=b.turno_id AND
	b.aula_id=c.id_aula AND
	c.nivel_academico_id= d.id_nivel_academico AND
	b.paralelo_id= e.id_paralelo   AND
	apam.`materia_id`=m.`id_materia` AND
	z.`horario_dia_id`=hd.`id_horario_dia` AND 
	apam.`id_aula_paralelo_asignacion_materia`=z.`aula_paralelo_asignacion_materia_id` AND 
	 
	z.estado='A'AND 
	b.estado='A' AND
	ge.estado='A'AND 
	c.estado='A'AND 
	e.estado_paralelo='A' AND 
	tu.estado='A'AND 
	d.estado='A'AND
	hd.estado='A'AND
	m.estado='A' AND
	apam.estado='A'
	 
     ".$sql." AND
   z.gestion_id=$id_gestion 
   GROUP BY `id_aula_paralelo_asignacion_materia`
	ORDER BY z.`horario_dia_id`  
 ";   

 $inscritos = $db->query($consulta)->fetch();





		$array_lunes = array();	


$inscritos_nombres=array();			
foreach ($inscritos as $nro => $inscrito){
		
	if ($inscrito['dia_semana_id'] == $dia ){




			

			$inscritos_nombres = array(
				
				'Dia' => $inscrito['dia_semana_id'],
				
				'nombre_materia' => $inscrito["nombre_materia"],
				'nombre_docente' => $inscrito["nombres_doc"],
				'hora_ini' => $inscrito["hora_ini"],
				'hora_fin' => $inscrito["hora_fin"]
					// code...
			
								
			);


			array_push($array_lunes, $inscritos_nombres);


			

	}

	

		
}





$agenda = array(
				'Dia' => $dia,
				'Actividad' => $array_lunes					
			);






$array_final = array();	
array_push($array_final, $agenda);

		if ($inscritos_nombres) {
				
			

			$respuesta = array(
				'Actividades' => $agenda,
					// code...
			
								
			);
			$respuesta2 = array(
				'estado' => 's',
				'Agenda' => $array_final				
					// code...
								
			);
		}else{

			
			$respuesta2 = array(
				'estado' => 'n',
				'error' => $inscritos_nombres
					// code...
								
			);	

		}	








			

			echo json_encode($respuesta2);


			// Devuelve los resultados
			
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'n'));
		}
	} else {
	// 	// Devuelve los resultados
	   echo json_encode(array('estado' => 'nfp'));
	}
} else {
// 	// Devuelve los resultados
    echo json_encode(array('estado' => 'np'));
}
