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
			




			

	$estudiante = $hijos["id_estudiante"];



	$array_final = array();

	$informacion_estudiante = $db->query("SELECT * FROM ins_registro_medico_estudiante  WHERE estado = 'A' 
		AND estudiante_id = '$estudiante' 
		ORDER BY categoria_medico ASC")->fetch();


	$peso_inicial = $db->query("SELECT * FROM ins_registro_medico_estudiante  WHERE estado = 'A' AND estudiante_id = '$estudiante'AND  
	categoria_medico='PESO' ORDER BY fecha_peso DESC LIMIT 1,1")->fetch_first();

	$peso_final = $db->query("SELECT * FROM ins_registro_medico_estudiante  WHERE estado = 'A' AND estudiante_id = '$estudiante'AND  
	categoria_medico='PESO' ORDER BY fecha_peso DESC LIMIT 1")->fetch_first();

	$estatura_inicial = $db->query("SELECT * FROM ins_registro_medico_estudiante  WHERE estado = 'A' AND estudiante_id = '$estudiante'AND  
	categoria_medico='ESTATURA' ORDER BY fecha_estatura DESC LIMIT 1,1")->fetch_first();
	$estatura_final = $db->query("SELECT * FROM ins_registro_medico_estudiante  WHERE estado = 'A' AND estudiante_id = '$estudiante'AND  
	categoria_medico='ESTATURA' ORDER BY fecha_estatura DESC LIMIT 1")->fetch_first();

	$alergia_estudiantes = $db->query("SELECT * FROM ins_registro_medico_estudiante  WHERE estado = 'A' AND estudiante_id = '$estudiante'AND  
	categoria_medico='ALERGIA' ORDER BY alergia")->fetch();

	$vacuna_estudiantes = $db->query("SELECT * FROM ins_registro_medico_estudiante  WHERE estado = 'A' AND estudiante_id = '$estudiante'AND  
	categoria_medico='VACUNA' ORDER BY vacuna")->fetch();


	$sangre_estudiante = $db->query("SELECT *  FROM ins_registro_medico_estudiante  WHERE estado = 'A' AND estudiante_id = '$estudiante'AND  
	categoria_medico='SANGRE' ORDER BY tipo_sangre")->fetch_first();




	




			// Instancia el objeto que devolvera la web service	
	$pesos="";
	$pesodiff=0;

	$pesodiff = $peso_final["peso"] - $peso_inicial["peso"];
	$peso_fecha_final = $peso_final["fecha_peso"];
	$peso_final_estudiante = $peso_final["peso"];
	$peso_fecha_inicial = $peso_inicial["fecha_peso"];
	$peso_inicial_estudiante = $peso_inicial["peso"];

				
				

			$estaturas="";
			$estaturadiff=0;


	$estaturadiff = $estatura_final["estatura"] - $estatura_inicial["estatura"];
	$estatura_fecha_final = $estatura_final["fecha_estatura"];
	$estatura_final_estudiante = $estatura_final["estatura"];
	$estatura_fecha_inicial = $estatura_inicial["fecha_estatura"];
	$estatura_inicial_estudiante = $estatura_inicial["estatura"];





		$alergias = ''; 
		foreach($alergia_estudiantes as $nro => $alergia_estudiante){ 

		$alergias .= $alergia_estudiante['alergia'].','; 
		}


		$vacunas = ''; 
		foreach($vacuna_estudiantes as $nro => $vacuna_estudiante){ 

		$vacunas .= $vacuna_estudiante['vacuna'].','; 
		}



		$sangre_estudiante = $sangre_estudiante["tipo_sangre"];




		




		$array__lista_peso = array();
		array_push($array__lista_peso, $pesos);
		$array__lista_estatura = array();
		array_push($array__lista_estatura, $estaturas);

		
		$registro_medico = array(
				'id_estudiante' => $estudiante,
				'peso_inicial' => $peso_inicial_estudiante,
				'peso_fecha_inicial' => $peso_fecha_inicial,
				'peso_final' => $peso_final_estudiante	,
				'peso_fecha_final' => $peso_fecha_final,
				'peso_diferencia' => number_format($pesodiff,2),
				'estatura_inicial' => $estatura_inicial_estudiante,
				'estatura_fecha_inicial' => $estatura_fecha_inicial,
				'estatura_final' => $estatura_final_estudiante	,
				'estatura_fecha_final' => $estatura_fecha_final,
				'estatura_diferencia' => number_format($estaturadiff,2),
				'alergias' => $alergias,
				'vacunas' => $vacunas,
				'sangre' => $sangre_estudiante	
			);

		if ($registro_medico== "" ) {
				$respuesta = array(
				'estado' => 'n',
				'error' => 'no hay datos'
					// code...
			
								
			);
		}else{

			array_push($array_final, $registro_medico);
			$respuesta = array(
				'estado' => 's',
				'registro_medico' => $array_final
					// code...
			
								
			);


		}	

			echo json_encode($respuesta	);
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
