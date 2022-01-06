<?php
//http://localhost/PROYECTOS/32%20CHECKCODE/educhecka/?/sitio/app-p-listar-hijos
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARCO QUINO)
 */




//localhost/sitio/app-aprueba
// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
 if (is_post()) {
	
	//var_dump($_POST);exit;

	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		$usuario 				= clear($_POST['usuario']);
        $contrasenia 			= clear($_POST['contrasenia']);
		$persona_id_familiar 		= clear($_POST['persona_id_familiar']);//       
       
		// Encripta la contraseña para compararla en la base de datos
		$usuario    = md5($usuario);
		$contrasenia = encrypt($contrasenia);
		
		//obtiene el año actual
		$anio_actual = Date('Y');

		//obtiene los datos de la gestion actual
		$_gestion = $db->select('z.id_gestion, z.gestion')->from('ins_gestion z')->where('gestion', $anio_actual)->fetch_first();	
		
		// Obtiene los datos del usuario
		$usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();

		//obtiene los datos del modo de calificacion actual
		$fecha_actual = Date('Y-m-d');
		/*$_modo_calificacion = $db->select('mc.id_modo_calificacion, mc.fecha_inicio, mc.fecha_final, mc.descripcion')->from('cal_modo_calificacion mc')->where('mc.fecha_inicio <=', $fecha_actual)->where('mc.fecha_final >=', $fecha_actual)->where('mc.gestion_id', $_gestion['id_gestion'])->fetch_first();*/

		
		// Verifica la existencia del usuario 
		if ($usuario) {

			//Consultamos las areas de calificacion 
			$hijos = $db->query("SELECT e.id_estudiante,ins.id_inscripcion,ins.aula_paralelo_id,au.nombre_aula,pa.nombre_paralelo,ni.nombre_nivel,tu.nombre_turno, per.* 
from ins_estudiante_familiar ef INNER JOIN ins_familiar  f ON ef.familiar_id=f.id_familiar
INNER JOIN ins_estudiante  e ON e.id_estudiante=ef.estudiante_id
INNER JOIN sys_persona  per ON per.id_persona=e.persona_id
LEFT  JOIN ins_inscripcion ins ON ins.estudiante_id  = e.id_estudiante
LEFT  JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo  = ins.aula_paralelo_id
INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
INNER JOIN ins_turno tu ON tu.id_turno=ap.turno_id
 WHERE f.persona_id=".$persona_id_familiar)->fetch();			
			
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
	   echo json_encode(array('estado' => 'n usuario'));
	}
} else {
// 	// Devuelve los resultados
    echo json_encode(array('estado' => 'npost'));
}
