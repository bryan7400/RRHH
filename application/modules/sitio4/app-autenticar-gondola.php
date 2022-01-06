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

		// Encripta la contrase«Ða para compararla en la base de datos
		$usuarioRe = md5($usuario);
		$contraseniaRe = encrypt($contrasenia);

		//obtiene el a«Ðo actual
		$anio_actual = Date('Y');

		//obtiene los datos de la gestion actual
		$_gestion = $db->select('z.id_gestion, z.gestion')->from('ins_gestion z')->where('gestion', $anio_actual)->fetch_first();

		//obtiene los datos del modo de calificacion actual
		$fecha_actual = Date('Y-m-d');
		$_modo_calificacion = $db->select('mc.id_modo_calificacion, mc.fecha_inicio, mc.fecha_final, mc.descripcion')->from('cal_modo_calificacion mc')->where('mc.fecha_inicio <=', $fecha_actual)->where('mc.fecha_final >=', $fecha_actual)->where('mc.gestion_id', $_gestion['id_gestion'])->fetch_first();



		// Obtiene los datos del usuario
		$usuario = $db->select('id_user, username, email, avatar, rol_id, visible')->from('sys_users')->open_where()->where('md5(username)', $usuarioRe)->or_where('md5(email)', $usuarioRe)->close_where()->where(array('password' => $contraseniaRe, 'active' => 's'))->fetch_first();
		// Verifica la existencia del usuario
		if ($usuario) {
			// Obtener Rol 
			//$rol = $db->select('rol')->from('sys_roles')->where('id_rol', $usuario['rol_id'])->fetch_first();
 			$institucion = $db->select('z.nombre_dominio, z.nombre,z.latlng')->from('sys_instituciones z')->fetch_first();
		    $id_usuario = $usuario['id_user'];
		     
			
			$sqlUsuario    ="SELECT cond.id_conductor,cgon.gondola_id, per.nombres ,CONCAT(per.primer_apellido,' ',per.segundo_apellido)AS apellidos 
                    	,cond.categoria,cond.grupo_sanguineo,cond.lentes,cond.audifonos
                    	,cgon.id_conductor_gondola
						,rut.nombre as nombreruta,rut.coordenadas,rut.id_ruta
						,su.id_user, su.rol_id, su.persona_id
						,rol.*
						FROM sys_persona per
						INNER JOIN per_asignaciones asi ON asi.persona_id=per.id_persona
						INNER JOIN gon_conductor cond ON cond.asignacion_id=asi.id_asignacion
						INNER JOIN gon_conductor_gondola cgon ON cgon.conductor_id=cond.id_conductor 
						INNER JOIN gon_gondolas gon ON gon.id_gondola=cgon.gondola_id 
						 INNER JOIN gon_rutas AS rut ON rut.conductor_gondola_id=cgon.id_conductor_gondola
						INNER JOIN sys_users AS su ON su.persona_id=per.id_persona
						INNER JOIN sys_roles AS rol ON rol.id_rol =su.rol_id
                    		WHERE su.id_user = '$id_usuario' AND su.active = 's'";	
			/*$sqlUsuario    ="SELECT su.id_user, su.rol_id, su.persona_id,cond.id_conductor,cgon.gondola_id, sp.nombres ,CONCAT(sp.primer_apellido,' ',sp.segundo_apellido)AS apellidos, su.avatar, sr.rol
                    	,cond.categoria,cond.grupo_sanguineo,cond.lentes,cond.audifonos
						,rut.nombre as nombreruta,rut.coordenadas,rut.id_ruta
                    		FROM sys_users AS su
                    		INNER JOIN sys_persona AS sp ON sp.id_persona = su.persona_id
                    		INNER JOIN sys_roles AS sr ON sr.id_rol = su.rol_id
                    		INNER JOIN gon_conductor AS cond ON cond.persona_id=sp.id_persona 
                    	 
                    		INNER JOIN gon_rutas AS rut ON rut.conductor_gondola_id=cond.id_conductor 
                    		
                    		INNER JOIN gon_conductor_gondola AS cgon ON cgon.id_conductor_gondola=rut.conductor_gondola_id
                    			
                    		WHERE su.id_user = '$id_usuario' AND su.active = 's' AND su.visible = 's'";	*/	
			
			/*$sqlUsuario    ="SELECT su.id_user, su.rol_id, su.persona_id, sp.nombres ,CONCAT(sp.primer_apellido,' ',sp.segundo_apellido)AS apellidos, su.avatar, sr.rol
								FROM sys_users AS su
								INNER JOIN sys_persona AS sp ON sp.id_persona = su.persona_id
								INNER JOIN sys_roles AS sr ON sr.id_rol = su.rol_id
								WHERE su.id_user = '$id_usuario' AND su.active = 's' AND su.visible = 's'";*/
								
			$usuario_datos = $db->query($sqlUsuario)->fetch_first();

			//var_dump($usuario_datos['nombres']);
		
			// Instancia el objeto
			$usuario = array(
				'estado' => 's',
				'id_usuario' => $usuario['id_user'], 
				'usuario' => $usuario['username'],
				'correo' => $usuario['email'],
				'id_rol' => $usuario_datos['rol_id'],
				'rol' => $usuario_datos['rol'],
				'persona_id' => $usuario_datos['persona_id'],
				'nombres' => $usuario_datos['nombres'],
				'apellidos' => $usuario_datos['apellidos'],
				'avatar' => apache_server . '/' . (($usuario['avatar'] == '') ? name_project . '/assets/imgs/avatar.jpg' : name_project . '/files/profiles/' . $usuario['avatar']),
				'id_modo_calificacion' => $_modo_calificacion['id_modo_calificacion'],
				'descripcion' => $_modo_calificacion['descripcion'],
				'id_gestion' => $_gestion['id_gestion'],   
				'grupo_sanguineo' => $usuario_datos['grupo_sanguineo'],
				'categoria' => $usuario_datos['categoria'],
				'lentes' => $usuario_datos['lentes'],
				'audifonos' => $usuario_datos['audifonos'],
				'u' => $usuarioRe,
				'c' => $contraseniaRe,
				'id_ruta' => $usuario_datos['id_ruta'],
				'nombreruta' => $usuario_datos['nombreruta'],
				'coordenadas' => $usuario_datos['coordenadas'] ,
				'id_conductor' => $usuario_datos['id_conductor'] ,
				'gondola_id' => $usuario_datos['gondola_id'],
				'nombre_dominio' => $institucion['nombre_dominio'],
				'latlng' => $institucion['latlng'],
				'nombre_colegio' => $institucion['nombre']
				
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