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
		$usuario 	 = clear($_POST['usuario']);
        $contrasenia = clear($_POST['contrasenia']);
		$id_gestion  = clear($_POST['id_gestion']);       
       
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
		$_modo_calificacion = $db->select('mc.id_modo_calificacion, mc.fecha_inicio, mc.fecha_final, mc.descripcion')->from('cal_modo_calificacion mc')->where('mc.fecha_inicio <=', $fecha_actual)->where('mc.fecha_final >=', $fecha_actual)->where('mc.gestion_id', $_gestion['id_gestion'])->fetch_first();

        $aModoCalificacion = array();
        $aModoCalificacion = $_modo_calificacion;
		
		// Verifica la existencia del usuario 
		if ($usuario) {
			
			// Instancia el objeto que devolvera la web service			
			$respuesta = array(
				'estado' => 's',
				'modo_calificacion' => $aModoCalificacion					
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
