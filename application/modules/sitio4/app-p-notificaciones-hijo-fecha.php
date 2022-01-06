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
		$usuario          = clear($_POST['usuario']);
        $contrasenia      = clear($_POST['contrasenia']);
        $id_aula_paralelo = clear($_POST['id_aula_paralelo']);	
        $fecha            = clear($_POST['fecha']);
		// Encripta la contraseña para compararla en la base de datos
		$usuario = md5($usuario);
		$contrasenia = encrypt($contrasenia);
 
		// Obtiene los datos del usuario
		$usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		
		// Verifica la existencia del usuario 
		if ($usuario) {
            // Obtiene los productos
            $sql = "SELECT cam.nombre_actividad, cam.descripcion_actividad, cam.fecha_presentacion, cam.fecha_registro, ma.nombre_materia, CONCAT(pe.nombres,' ',pe.primer_apellido,' ',pe.segundo_apellido)AS nombre_profesor
            FROM int_aula_paralelo_profesor_materia AS iap
            INNER JOIN cal_actividad_materia_modo_area AS cam ON cam.aula_paralelo_profesor_materia_id = iap.id_aula_paralelo_profesor_materia
            INNER JOIN pro_profesor_materia AS pm ON pm.id_profesor_materia = iap.profesor_materia_id
            INNER JOIN pro_materia AS ma ON ma.id_materia = pm.materia_id
            INNER JOIN pro_profesor AS pr ON pr.id_profesor = pm.profesor_id
            INNER JOIN sys_persona AS pe ON pe.id_persona = pr.persona_id
            WHERE iap.aula_paralelo_id = '$id_aula_paralelo' AND date(cam.fecha_presentacion) = '$fecha'";

            //var_dump($sql);
			$notificaciones = $db->query("$sql")->fetch();
           // var_dump($estudiantes_cursos);exit();
			// Instancia el objeto
			$respuesta = array(
				'estado' => 's',
				'notificacion' => $notificaciones 
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