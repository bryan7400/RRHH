<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK Maribel Marco Luis
 */

header('Content-Type: application/json');



// SELECT*
// FROM ins_inscripcion i
// INNER JOIN pro_asignacion_docente ad ON i.aula_paralelo_id = ad.aula_paralelo_id
// WHERE i.estudiante_id =742





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
                                    WHERE f.persona_id=$id_persona AND ins.estado = 'A' AND ins.gestion_id = $id_gestion AND su.estado = 'A' AND su.visible = 's'")->fetch();			
			
			// Instancia el objeto que devolvera la web service			
			$respuesta = array(
				'estado' => 's',
				'hijos' => $hijos					
			);

			// Devuelve los resultados
			echo json_encode($respuesta);
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
