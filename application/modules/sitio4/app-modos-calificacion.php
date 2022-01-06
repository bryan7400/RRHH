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
    
	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		$usuario     = clear($_POST['usuario']);
        $contrasenia = clear($_POST['contrasenia']);
        $id_gestion  = clear($_POST['id_gestion']);
        
        $hoy = date('Y-m-d');    
    
		// Encripta la contrase«Ða para compararla en la base de datos
		$usuario = md5($usuario);
		$contrasenia = encrypt($contrasenia);
 
		// Obtiene los datos del usuario
		$usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		
		// Verifica la existencia del usuario 
		if ($usuario) {
			
			
			//$sql_modo_calificacion = "SELECT * FROM cal_modo_calificacion WHERE gestion_id ='$id_gestion' and fecha_inicio <= '$hoy' and fecha_final >= '$hoy' AND estado = 'A'";
			$sql_modo_calificacion = "SELECT * FROM cal_modo_calificacion WHERE gestion_id ='$id_gestion' AND estado = 'A'";
			//echo $sql_modo_calificacion;
			$modos_calificacion = $db->query($sql_modo_calificacion)->fetch();
            
			// Instancia el objeto
			$respuesta = array(
				'estado' => 's',
				'modo_calificacion' => $modos_calificacion 
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
?>