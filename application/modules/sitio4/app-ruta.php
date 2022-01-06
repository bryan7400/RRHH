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
//            $ruta = array();
            $ruta = $db->query("SELECT b.nombre as gondola, b.placa, b.capacidad, c.categoria, c.grupo_sanguineo, d.id_ruta, d.nombre as ruta, d.descripcion as puntos
                    FROM gon_conductor_gondola a
                    LEFT JOIN gon_gondolas b ON a.gondola_id = b.id_gondola
                    LEFT JOIN gon_conductor c ON a.conductor_id = c.id_conductor
                    LEFT JOIN gon_rutas d ON b.ruta_id = d.id_ruta
                    WHERE c.persona_id = ".$usuario['id_persona'])->fetch();

			$puntos = $db->select('id_punto, nombre_lugar, latitud, longitud')->from('gon_puntos')->where('ruta_id',$ruta[0]['id_ruta'])->fetch();
			//var_dump($usuario_datos['nombres']);
		
			// Instancia el objeto
			$ruta[0]['puntos'] = $puntos;

			$respuesta = array (
                "estado" =>"s",
                "ruta" => $ruta
            );

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