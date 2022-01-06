


<?php

/*
 *
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  WEB SERVICES
 * @author  EDUCHECK MARCO QUINO
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {
	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		// Obtiene los datos
		$usuario = clear($_POST['usuario']);
		$contrasenia = clear($_POST['contrasenia']);
		$id_ruta = clear($_POST['id_ruta']);

		// Encripta la contraseña para compararla en la base de datos
		$usuario = $usuario;//md5($usuario);
		$contrasenia = $contrasenia;//encrypt($contrasenia);

		//obtiene el año actual
		$anio_actual = Date('Y');

		//obtiene los datos de la gestion actual
		$_gestion = $db->select('z.id_gestion, z.gestion')->from('ins_gestion z')->where('gestion', $anio_actual)->fetch_first();

		//obtiene los datos del modo de calificacion actual
		$fecha_actual = Date('Y-m-d');
		$_modo_calificacion = $db->select('mc.id_modo_calificacion, mc.fecha_inicio, mc.fecha_final, mc.descripcion')->from('cal_modo_calificacion mc')->where('mc.fecha_inicio <=', $fecha_actual)->where('mc.fecha_final >=', $fecha_actual)->where('mc.gestion_id', $_gestion['id_gestion'])->fetch_first();



		// Obtiene los datos del usuario
		$usuario = $db->select('id_user, username, email, avatar, rol_id, visible')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		// Verifica la existencia del usuario
		if ($usuario) {
			// Obtener Rol 
			//$rol = $db->select('rol')->from('sys_roles')->where('id_rol', $usuario['rol_id'])->fetch_first();
//:::::::::::::::datos de retorno:::::::::::::::::::::::::::::::::::
			// ARMAR PUNTOS
		$puntos = $db->select('*')->from('gon_puntos')->where('ruta_id',$id_ruta)->fetch();
			
		$inst = $db->query("SELECT ins.latlng, ins.direccion FROM sys_instituciones ins ")->fetch_first();// AND ins.punto_id=1
			$ltng=explode(',',$inst['latlng']);
			$latitud=$ltng[0];
			$longitud=$ltng[1];
			$puntosadd=array();
			array_push($puntosadd,array(
				"id_punto"=> "0",
				"descripcion"=> "Colegio",
				"latitud"=> $latitud,
				"longitud"=> $longitud,
				"imagen_lugar"=> "foto.jpg",
				"nombre_lugar"=> $inst['direccion'],
				"estado"=> "1",
				"ruta_id"=> "0", 
				"tipo"=> "colegio"
			));
			foreach ($puntos as   $row) {
				 $row['tipo']='parada';
				array_push($puntosadd,$row);
			}
			 
	 

			$respuesta = array ("estado" =>"s","puntos"=>$puntosadd);

			// Devuelve los resultados
			echo json_encode($respuesta);
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'n'));
		}
	} else {
		// Devuelve los resultados
		echo json_encode(array('estado' => 'n'));
	}
} else {
	// Devuelve los resultados
	echo json_encode(array('estado' => 'n'));
}

?>