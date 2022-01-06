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
 if (is_post()) {
	
	//var_dump($_POST);exit;

	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		$usuario 	  = clear($_POST['usuario']);
        $contrasenia  = clear($_POST['contrasenia']);
		$id_gestion   = clear($_POST['id_gestion']);       
       
		// Encripta la contraseña para compararla en la base de datos
		$usuario    = md5($usuario);
		$contrasenia = encrypt($contrasenia);
		
		//Obtiene los datos del usuario
		$usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		$hoy = date('Y-m-d');

		// Verifica la existencia del usuario 
		if ($usuario) {
			
			// Listado areas de calificacion
            $modos = $db->query("SELECT *, IF(cmc.fecha_inicio <= '$hoy' AND  cmc.fecha_final >= '$hoy','A','I') AS en_uso
                                    FROM cal_modo_calificacion AS cmc
                                    WHERE cmc.gestion_id = $id_gestion AND cmc.estado = 'A'")->fetch();		
            
			
            // Listado areas de calificacion
            $areas = $db->query("SELECT * FROM cal_area_calificacion WHERE gestion_id = $id_gestion AND estado = 'A'")->fetch();		
            			
			// Instancia el objeto que devolvera la web service			
			$respuesta = array(
				'estado' => 's',
				'modo_calificacion' => $modos,
				'area_calificacion' => $areas					
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
