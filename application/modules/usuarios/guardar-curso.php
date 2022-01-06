<?php  

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
//var_dump($_POST);exit();
// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['curso']) && isset($_POST['email']) && isset($_POST['rol_id'])) {

			// Obtiene los datos
			$id_usuario	= (isset($_POST['id_user'])) ? clear($_POST['id_user']) : 0;
			$curso	    = clear($_POST['curso']);
			//$password 	= $_POST['password'];
			$email 		= clear($_POST['email']);
			$active 	= (isset($_POST['active'])) ? clear($_POST['active']) : 's';
			$rol_id 	= clear($_POST['rol_id']);
			//$persona_id = clear($_POST['empleado']);
            $gestion_id = 1;

			// Verifica si es creacion o modificacion
			if ($id_usuario > 0) {
				$password 	= '';

				// Verifica si existe la contrasena
				if ($password == '') {
					// Instancia el usuario
					$usuario = array(
						'username'	=> $username,
						'email' 	=> $email,
						'active' 	=> $active,
						'persona_id'=> $persona_id,
						'gestion_id'=> $gestion_id
					);
				} else {

					// Instancia el usuario
					$usuario = array(
						'username'	=> $username,
						'password' 	=> encrypt($password),
						'email' 	=> $email,
						'active' 	=> $active,
						'rol_id' 	=> $rol_id,
						'persona_id'=> $persona_id,
						'gestion_id'=> $gestion_id
					);
				}

				// Modifica el usuario
				$db->where('id_user', $id_usuario)->update('sys_users', $usuario);

				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso'	=> date('Y-m-d'),
					'hora_proceso' 	=> date('H:i:s'),
					'proceso' 		=> 'u',
					'nivel' 		=> 'm',
					'detalle' 		=> 'Se modificó el usuario con identificador número ' . $id_usuario . '.',
					'direccion' 	=> $_location,
					'usuario_id' 	=> $_user['id_user']
				));

				// Crea la notificacion
				set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				
				// Redirecciona la pagina
				redirect('?/usuarios/ver/' . $id_usuario);
			} else {

				$estudiantes=$db->query("SELECT *
				FROM ins_inscripcion i
				INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
				INNER JOIN ins_inscripcion_rude r ON i.estudiante_id=r.ins_estudiante_id
				inner join sys_persona sp on e.persona_id=sp.id_persona
				WHERE i.estado = 'A'
				AND i.aula_paralelo_id = $curso
				AND i.gestion_id = $gestion_id")->fetch();
				//var_dump($estudiantes);exit();

				foreach ($estudiantes as $key => $value) {

				    $password 	= $value['nro_rude'];
					$nombre 	= explode(" ", $value['nombres']);
                    $username 	= $nombre[0];

					// Instancia el usuario
					$usuario = array(
						'username'	=> $username.'.'.$value['primer_apellido'],
						'password' 	=> encrypt($password),
						'email' 	=> $email,
						'active' 	=> 's',
						'visible' 	=> 's',
						'rol_id' 	=> $rol_id,
						'persona_id'=> $value['persona_id'],
						'gestion_id'=> $gestion_id
					);
					//var_dump($usuario);

					// Crea el usuario
					$id_usuario = $db->insert('sys_users', $usuario);

					// Guarda el proceso
					$db->insert('sys_procesos', array(
						'fecha_proceso'	=> date('Y-m-d'),
						'hora_proceso' 	=> date('H:i:s'),
						'proceso' 		=> 'c',
						'nivel' 		=> 'm',
						'detalle' 		=> 'Se creó el usuario con identificador número ' . $id_usuario . '.',
						'direccion' 	=> $_location,
						'usuario_id' 	=> $_user['id_user']
					));
				}
				//exit();

				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

				// Redirecciona la pagina
				redirect('?/usuarios/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	} else {
		// Redirecciona la pagina
		redirect('?/usuarios/listar');
	}
} else {
	// Error 404
	require_once not_found();
	exit;
}

?>