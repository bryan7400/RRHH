<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  WEB SERVICES
 * @author  EDUCHECK MARIBEL JORGE LUIS
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {
	// Verifica la existencia de datos
	if (isset($_POST['id_user'])) {
		// Obtiene los datos
		$id_user = clear($_POST['id_user']);

		// Obtiene los datos del usuario
		$usuario = $db->query("SELECT *
                FROM sys_users a
                LEFT JOIN sys_persona b ON a.persona_id = b.id_persona
                WHERE a.id_user = '$id_user' AND a.rol_id = 8")->fetch_first();
		// Verifica la existencia del usuario
		if ($usuario['id_persona']) {
			// Obtener Rol 
            $ruta = $db->query("SELECT a.id_inscripcion, h.id_punto, h.nombre_lugar, h.latitud, h.longitud, c.nombres, c.primer_apellido, c.segundo_apellido, c.numero_documento, e.descripcion, f.nombre_aula, g.nombre_turno
                    FROM ins_inscripcion a
                    LEFT JOIN ins_estudiante b ON a.estudiante_id = b.id_estudiante
                    LEFT JOIN sys_persona c ON b.persona_id = c.id_persona
                    LEFT JOIN ins_aula_paralelo d ON a.aula_paralelo_id = d.id_aula_paralelo
                    LEFT JOIN ins_paralelo e ON d.paralelo_id = e.id_paralelo
                    LEFT JOIN ins_aula f ON d.aula_id = f.id_aula
                    LEFT JOIN ins_turno g ON d.turno_id = g.id_turno
                    LEFT join gon_puntos h ON a.punto_id = h.id_punto
                    LEFT JOIN gon_rutas i ON h.ruta_id = i.id_ruta
                    LEFT JOIN gon_gondolas j ON i.id_ruta = j.ruta_id
                    LEFT JOIN gon_conductor_gondola k ON j.id_gondola = k.gondola_id
                    LEFT JOIN gon_conductor l ON k.conductor_id = l.id_conductor
                    WHERE l.persona_id = ".$usuario['id_persona'])->fetch();

//			$puntos = $db->select('id_punto, nombre_lugar, latitud, longitud')->from('gon_puntos')->where('ruta_id',$ruta['id_ruta'])->fetch();
			//var_dump($usuario_datos['nombres']);
		
			// Instancia el objeto
//			$ruta['puntos'] = $puntos;

			$respuesta = array ("estado" =>"s","estudiantes"=>$ruta);

			// Devuelve los resultados
			echo json_encode($respuesta);
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