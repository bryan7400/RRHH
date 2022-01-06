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
// if (is_post()) {
    
	// Verifica la existencia de datos
	// if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		$usuario = clear($_POST['usuario']);
        $contrasenia = clear($_POST['contrasenia']);
        //$id_profesor = clear($_POST['id_profesor']);
        //$id_aula_paralelo = clear($_POST['id_aula_paralelo']);
        //$id_profesor_materia = clear($_POST['id_profesor_materia']);
        $id_gestion = date("L");//no reconoce $_gestion['id_gestion'];
       $id_aula_asignacion = clear($_POST['id_aula_asignacion']);//


		// $usuario ='martha';
        // $contrasenia ='martha2019';
        // $id_aula_paralelo = 8; 

		// Encripta la contraseña para compararla en la base de datos
		$usuario = md5($usuario);
		$contrasenia = encrypt($contrasenia);
 
		// Obtiene los datos del usuario
		$usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		
		// Verifica la existencia del usuario 
		if ($usuario) {
			// Obtiene los productos
			$estudiantes_cursos = $db->query("SELECT e.id_estudiante,p.*  
         FROM ins_inscripcion z 
            INNER JOIN ins_gestion g ON z.gestion_id=g.id_gestion  
            INNER JOIN ins_estudiante e ON z.estudiante_id=e.id_estudiante
            INNER JOIN sys_persona p ON e.persona_id=p.id_persona  
            INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=z.aula_paralelo_id 
            
	    INNER JOIN int_aula_paralelo_asignacion_materia apam ON apam.aula_paralelo_id=ap.id_aula_paralelo
	    
            INNER JOIN ins_aula au ON au.id_aula=ap.aula_id  
            INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
            INNER JOIN ins_turno tu ON tu.id_turno=ap.turno_id
            INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
            INNER JOIN ins_tipo_estudiante te ON te.id_tipo_estudiante=z.tipo_estudiante_id 
        WHERE z.gestion_id=$id_gestion  
	       AND  apam.id_aula_paralelo_asignacion_materia=$id_aula_asignacion
            AND z.estado='A' 
            AND g.estado='A'
            AND ap.estado='A'
            AND pa.estado_paralelo='A'
            AND au.estado='A'
            AND ni.estado='A'
            AND apam.estado='A'

           ORDER BY   p.primer_apellido ASC,  p.segundo_apellido ASC")->fetch();
			//$estudiantes_cursos = $db->query("SELECT * FROM ws_vista_estudiante_aula est_cur WHERE id_aula_paralelo = $id_aula_paralelo")->fetch();
           // var_dump($estudiantes_cursos);exit();
			// Instancia el objeto
			$respuesta = array(
				'estado' => 's',
				'estudiantes' => $estudiantes_cursos 
			);

			// Devuelve los resultados
			echo json_encode($respuesta);
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'n'));
		}
	// } else {
	// 	// Devuelve los resultados
	// 	echo json_encode(array('estado' => 'n usuario'));
	// }
// } else {
// 	// Devuelve los resultados
// 	echo json_encode(array('estado' => 'npost'));
// }
?>