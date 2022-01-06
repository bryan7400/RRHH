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
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		// Obtiene los datos
		$usuario = clear($_POST['usuario']);
		$contrasenia = clear($_POST['contrasenia']);

		// Encripta la contraseña para compararla en la base de datos
		$usuario = md5($usuario);
		$contrasenia = encrypt($contrasenia);

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

			$id_usuario = $usuario['id_user'];
            $username   = $usuario['username'];
            
            // Creamos el codigo de sesion			
			$codigo_sesion =  Date('Ymd').$id_usuario.Date('His').ord($username);
			$sql_codigo_sesion = "UPDATE sys_users SET codigo_sesion='$codigo_sesion' WHERE id_user = $id_usuario";
			$res_codigo_sesion = $db->query($sql_codigo_sesion)->execute();
			
			//Consultamos datos del usuario logueado
			$sqlUsuario = "SELECT su.id_user, su.rol_id, su.persona_id, sp.nombres ,CONCAT(sp.primer_apellido,' ',sp.segundo_apellido)AS apellidos, su.avatar, sr.rol, su.token, su.codigo_sesion
								FROM sys_users AS su
								INNER JOIN sys_persona AS sp ON sp.id_persona = su.persona_id
								INNER JOIN sys_roles AS sr ON sr.id_rol = su.rol_id
								WHERE su.id_user = '$id_usuario' AND su.active = 's' AND su.visible = 's'";
			$usuario_datos = $db->query($sqlUsuario)->fetch_first();

			//var_dump($usuario_datos['nombres']);
		
			// Instancia el objeto
			$usuario = array(
				'estado' => 's',
				'id_usuario' => $usuario['id_user'],
				'usuario' => $usuario['username'],
				'correo' => $usuario['email'],
				'id_rol' => $usuario['rol_id'],
				'rol' => $usuario_datos['rol'],
				'persona_id' => $usuario_datos['persona_id'],
				'nombres' => $usuario_datos['nombres'],
				'apellidos' => $usuario_datos['apellidos'],
				'avatar' => apache_server . '/' . (($usuario['avatar'] == '') ? name_project . '/assets/imgs/avatar.jpg' : name_project . '/files/profiles/' . $usuario['avatar']),
				'id_modo_calificacion' => $_modo_calificacion['id_modo_calificacion'],
				'descripcion' => $_modo_calificacion['descripcion'],
				'id_gestion' => $_gestion['id_gestion'],
				'token' => $usuario_datos['token'],
				'codigo_sesion' => $codigo_sesion
			);

			$respuesta = array ("estado" =>"s","usuario"=>$usuario);

			// Devuelve los resultados
			echo json_encode($usuario);
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