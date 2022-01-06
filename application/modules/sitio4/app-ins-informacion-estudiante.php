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
	$nacimiento_estudiante = $hijos["fecha_nacimiento"];
	$ci_estudiante = $hijos["numero_documento"];							
		

	$array_final = array();

	$informacion_estudiante = $db->query("SELECT * FROM ins_informacion_estudiante  WHERE estado = 'A' 
		AND estudiante_id = '$estudiante' 
		ORDER BY categoria_informacion ASC")->fetch();


	$amigo_estudiantes = $db->query("SELECT * FROM ins_informacion_estudiante  WHERE estado = 'A' AND estudiante_id = '$estudiante'AND  
	categoria_informacion='AMIGOS' ORDER BY nombre DESC LIMIT	2")->fetch();

	
	$pasatiempo_estudiantes = $db->query("SELECT * FROM ins_informacion_estudiante  WHERE estado = 'A' AND estudiante_id = '$estudiante'AND  
	categoria_informacion='PASATIEMPOS' ORDER BY nombre")->fetch();

	$comidas_estudiantes = $db->query("SELECT * FROM ins_informacion_estudiante  WHERE estado = 'A' AND estudiante_id = '$estudiante'AND  
	categoria_informacion='COMIDA' ORDER BY nombre")->fetch();




			// Instancia el objeto que devolvera la web service	
	
$array_amigos = array();
		foreach ($amigo_estudiantes as $key => $amigo_estudiante) {

				

				
				$amigos = array(
				'nombre' => $amigo_estudiante['nombre'],
				'celular' =>  $amigo_estudiante['celular']
					// code...
					
					
								
			);	

					array_push($array_amigos, $amigos);
		}	

		


			


		$pasatiempos = ''; 
		foreach($pasatiempo_estudiantes as $nro => $pasatiempo_estudiante){ 

		$pasatiempos .= $pasatiempo_estudiante['nombre'].','; 
		}


		$comidas = ''; 
		foreach($comidas_estudiantes as $nro => $comida_estudiante){ 

		$comidas .= $comida_estudiante['nombre'].','; 
		}



		$amigos_lista = array(
				'nombre' => 'Amigos de mi hijo',
				'lista_amigos' => $array_amigos,
					
			);

		$array__lista_amigos = array();
		array_push($array__lista_amigos, $amigos_lista);

		$registro_informacion = array(
				'id_estudiante' => $estudiante,
				'cumpleanios' => $nacimiento_estudiante,
				'ci' => $ci_estudiante,
				'amigos' => $array__lista_amigos,
				'pasatiempos' => $pasatiempos,
				'comidass' => $comidas
			);

		if ($registro_informacion== "") {
				$respuesta = array(
				'estado' => 'n',
				'error' => 'no hay datos'
					// code...
			
								
			);
		}else{

			array_push($array_final, $registro_informacion);
			$respuesta = array(
				'estado' => 's',
				'informacion_Estudiante' => $array_final
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
