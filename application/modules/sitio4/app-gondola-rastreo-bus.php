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
	//if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		// Obtiene los datos
		//$usuario = clear($_POST['usuario']);
		//$contrasenia = clear($_POST['contrasenia']);

		// Encripta la contrase«Ða para compararla en la base de datos
		//$usuarioRe = md5($usuario);
		//$contraseniaRe = encrypt($contrasenia);

		//obtiene el a«Ðo actual
		$anio_actual = Date('Y');

		//obtiene los datos de la gestion actual
		$_gestion = $db->select('z.id_gestion, z.gestion')->from('ins_gestion z')->where('gestion', $anio_actual)->fetch_first();

		//obtiene los datos del modo de calificacion actual
		$fecha_actual = Date('Y-m-d');
		 
 
		// Obtiene los datos del usuario
		///$usuario = $db->select('id_user, username, email, avatar, rol_id, visible')->from('sys_users')->open_where()->where('md5(username)', $usuarioRe)->or_where('md5(email)', $usuarioRe)->close_where()->where(array('password' => $contraseniaRe, 'active' => 's'))->fetch_first();
		// Verifica la existencia del usuario
		//if ($usuario) {
			// Obtener Rol 
		 //creamos el ingresar del array a firebase
		//	$egreso_id='2';
		$dominio= clear($_POST['dominio']);
		$lat = clear($_POST['lat']);
		$lng = clear($_POST['lng']);
	
		$data = '{"dominio":"'.$dominio.'","lat":"'.$lat.'","lng":"'.$lng.'"}';
		$url  = "https://educheckv2-52fd3-default-rtdb.firebaseio.com/".$dominio.".json";//77414
		//https://educheckv2-52fd3-default-rtdb.firebaseio.com/comunicados.json
		//$url  = "https://educheckv2-77414-default-rtdb.firebaseio.com/ratreobus.json";

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));

				$response = curl_exec($ch);
			$res='';
			 if(curl_errno($ch)){
				 $res='n';
			 }else{
				 $res='s';
			 }
			//enviara a firebase
			
			$respuesta = array ("estado" =>$res,"rutaEst"=>$response);
 
			// Devuelve los resultados
			echo json_encode($respuesta);
		//} else {
			// Devuelve los resultados
			//echo json_encode(array('estado' => 'n'));
		//}
	//} else {
		// Devuelve los resultados
	//	echo json_encode(array('estado' => 'n'));
	//}
} else {
	// Devuelve los resultados
	echo json_encode(array('estado' => 'n'));
}

?>