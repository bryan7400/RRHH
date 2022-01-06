<?php
 
/**
 * FunctionPHP - Framework Functional PHP 
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */

// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['rol_id']) && isset($_POST['accion_usuario']) && isset($_POST['tipo_contrasenia']) && isset($_POST['password'])) {
 
			// Obtiene los datos
			$rol_id 			= clear($_POST['rol_id']);
            $passwordg 			= $_POST['password'];
            $accion_usuario 	= clear($_POST['accion_usuario']);
            $tipo_contrasenia 	= clear($_POST['tipo_contrasenia']);
            $gestion_id 		= $_gestion['id_gestion'];

			$codigo_u = '';
			
            if($rol_id==5){
	          $codigo_u = $password;
	        }else{
	          $codigo_u = '';
	        }

            //var_dump($_POST);exit();

            // ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            // // Verifica si es creacion o modificacion  C = Creacion y A = Actualizar ; P = Programado y CG = Contraseña generica  ||  D = Desbloquear ; B = Bloquear
            // ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::

            // Si el rol = 5 (ESTUDIANTE) :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            if($rol_id == 5 && $accion_usuario == 'C' && $tipo_contrasenia == 'P'){ //???????????????

            	
				$estudiantes=$db->query("SELECT * FROM ins_inscripcion i
				INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
				INNER JOIN ins_inscripcion_rude r ON i.estudiante_id=r.ins_estudiante_id
				inner join sys_persona sp on e.persona_id=sp.id_persona
				WHERE i.estado = 'A' AND i.gestion_id = $gestion_id and u.rol_id = 5 ")->fetch();

				foreach ($estudiantes as $key => $value) {

				    //$password 	= clear($value['nro_rude']);
					$nombre 	= explode(" ", $value['nombres']);
                    $username 	= $nombre[0];

					// Instancia el usuario
					$usuario = array(
						'username'	=> $username.'.'.$value['primer_apellido'],
						'password' 	=> encrypt($passwordg),
						'email' 	=> '',
						'active' 	=> 's',
						'avatar' 	=> '',
						'visible' 	=> 's',
						'rol_id' 	=> $rol_id,
						'persona_id'=> $value['persona_id'],
						'gestion_id'=> $gestion_id,
						'token' 	=> '',
						'imei' 		=> '',
						'login_at' 	=> '0000-00-00 00:00:00',
						'logout_at' => '0000-00-00 00:00:00',
						'codigo_u' 	=> $codigo_u,
					);

					// Crea el usuario
					//$id_usuario = $db->insert('sys_users', $usuario);

					// Guarda el proceso
					$db->insert('sys_procesos', array(
						'fecha_proceso'	=> date('Y-m-d'),
						'hora_proceso' 	=> date('H:i:s'),
						'proceso' 		=> 'c',
						'nivel' 		=> 'm',
						'detalle' 		=> 'Se creó el usuario con rol estudiante con identificador número ' . $id_usuario . '.',
						'direccion' 	=> $_location,
						'usuario_id' 	=> $_user['id_user']
					));
				}

				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

				// Redirecciona la pagina
				redirect('?/usuarios/listar');

            }else if ($rol_id == 5 && $accion_usuario == 'A' && $tipo_contrasenia == 'P') { //??????????????
            	
            	//var_dump('AQUIIIIIIIIIIIIIII');exit();

		    	$estudiantes = $db->query("SELECT * FROM ins_inscripcion i
				INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
				INNER JOIN ins_inscripcion_rude r ON i.estudiante_id=r.ins_estudiante_id
				inner join sys_persona sp on e.persona_id=sp.id_persona
				inner join sys_users u on sp.id_persona = u.persona_id
				WHERE i.estado = 'A' AND i.gestion_id = $gestion_id and u.rol_id = 5 ")->fetch();

                //var_dump('expressionfdfdfs');exit();

				foreach ($estudiantes as $key => $val) {

				    //$password 	= clear($value['nro_rude']);
				    $id_user	= $val['id_user'];

					// Instancia el usuario
					$usuario = array( 
						'password' 	=> encrypt($passwordg),						
						'codigo_u' 	=> $codigo_u,  
					);

                    // Modifica el usuario
				    $db->where('id_user', $id_user)->update('sys_users', $usuario);

					// Guarda el proceso
					// $db->insert('sys_procesos', array(
					// 	'fecha_proceso'	=> date('Y-m-d'),
					// 	'hora_proceso' 	=> date('H:i:s'),
					// 	'proceso' 		=> 'u',
					// 	'nivel' 		=> 'm',
					// 	'detalle' 		=> 'Se actualizó el usuario con rol estudiante con identificador número ' . $id_usuario . '.',
					// 	'direccion' 	=> $_location,
					// 	'usuario_id' 	=> $_user['id_user']
					// ));
				}

				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

				// Redirecciona la pagina
				redirect('?/usuarios/listar');

            }else if ($rol_id == 5 && $accion_usuario == 'C' && $tipo_contrasenia == 'CG') {
            	var_dump($_POST);exit();
				$estudiantes=$db->query("SELECT * FROM ins_inscripcion i
				INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
				INNER JOIN ins_inscripcion_rude r ON i.estudiante_id=r.ins_estudiante_id
				inner join sys_persona sp on e.persona_id=sp.id_persona
				WHERE i.estado = 'A' AND i.gestion_id = $gestion_id")->fetch();

				foreach ($estudiantes as $key => $value) {

		        	///////////////////////////////////////////////////////////////////////
		        	$nombre_estudiante  = $value['nombres'];
					if($value['primer_apellido']!=''){
                         $primer_apellido 	= $value['primer_apellido'];
					}else{
						 $primer_apellido 	= $value['segundo_apellido'];
					}

		        	$find = array('Á','É','Í','Ó','Ú','Ñ','ñ','á','é','í','ó','ú');
					$repl = array('A','E','I','O','U','N','N','A','E','I','O','U');

					$nombre_estudiante  = str_replace($find, $repl, $nombre_estudiante);
					$primer_apellido    = str_replace($find, $repl, $primer_apellido);

	                $nuevo_nombre_estudiante  = clear(strtoupper($nombre_estudiante));
	                $nuevo_primer_apellido    = clear(strtoupper($primer_apellido));

		        	//$nuevo_rude 	= substr(clear($nro_rude), 0, -1);				        	
		        	//$resultado_rude = substr($nuevo_rude, 8);
		        	
					$nombre 		= explode(" ", $nuevo_nombre_estudiante);
	                $username 		= $nombre[0];

	                //$letra_nivel  = $nombre_nivel[0];
	                //$password 	= $nuevo_rude;

	                if($value['nro_rude']!=''){
                        $nro_rude 	= clear($value['nro_rude']);
                    }else{
                        //$nro_rude 	= clear($value['numero_documento']);
                        $nro_rude 	= clear($value['numero_documento']).'M';
                    }

	                $password 	    = $nro_rude;
	                //////////////////////////////////////////////////////////////////////////

					// Instancia el usuario
					$usuario = array(
						'username'	=> $username.'.'.$nuevo_primer_apellido,
						'password' 	=> encrypt($password),
						'email' 	=> '',
						'active' 	=> 's',
						'avatar' 	=> '',
						'visible' 	=> 's',
						'rol_id' 	=> $rol_id,
						'persona_id'=> $value['persona_id'],
						'gestion_id'=> $gestion_id,
						'token' 	=> '',
						'imei' 		=> '',
						'login_at' 	=> '0000-00-00 00:00:00',
						'logout_at' => '0000-00-00 00:00:00',
						'codigo_u' 	=> $codigo_u,
					);

					// Crea el usuario
					$id_usuario = $db->insert('sys_users', $usuario);

					// Guarda el proceso
					// $db->insert('sys_procesos', array(
					// 	'fecha_proceso'	=> date('Y-m-d'),
					// 	'hora_proceso' 	=> date('H:i:s'),
					// 	'proceso' 		=> 'c',
					// 	'nivel' 		=> 'm',
					// 	'detalle' 		=> 'Se creó el usuario con rol estudiante con identificador número ' . $id_usuario . '.',
					// 	'direccion' 	=> $_location,
					// 	'usuario_id' 	=> $_user['id_user']
					// ));
				}

				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

				// Redirecciona la pagina
				redirect('?/usuarios/listar');

            }else if ($rol_id == 5 && $accion_usuario == 'A' && $tipo_contrasenia == 'CG') {
            	//var_dump('expression');
                var_dump($_POST);exit();
				$estudiantes=$db->query("SELECT u.id_user, r.nro_rude, u.rol_id, sp.numero_documento
				FROM ins_estudiante e 
				INNER JOIN ins_inscripcion_rude r ON e.id_estudiante = r.ins_estudiante_id
				inner join sys_persona sp on e.persona_id = sp.id_persona				
				inner join sys_users u on sp.id_persona = u.persona_id
				WHERE  u.rol_id = 5 ")->fetch();

				foreach ($estudiantes as $key => $value) {

                    ///////////////////////////////////////////////////////////////////////
		        	$nombre_estudiante  = $value['nombres'];
					if($value['primer_apellido']!=''){
                         $primer_apellido 	= $value['primer_apellido'];
					}else{
						 $primer_apellido 	= $value['segundo_apellido'];
					}

		        	$find = array('Á','É','Í','Ó','Ú','Ñ','ñ','á','é','í','ó','ú');
					$repl = array('A','E','I','O','U','N','N','A','E','I','O','U');

					$nombre_estudiante  = str_replace($find, $repl, $nombre_estudiante);
					$primer_apellido    = str_replace($find, $repl, $primer_apellido);

	                $nuevo_nombre_estudiante  = clear(strtoupper($nombre_estudiante));
	                $nuevo_primer_apellido    = clear(strtoupper($primer_apellido));

		        	//$nuevo_rude 	= substr(clear($nro_rude), 0, -1);				        	
		        	//$resultado_rude = substr($nuevo_rude, 8);
		        	
					$nombre 		= explode(" ", $nuevo_nombre_estudiante);
	                $username 		= $nombre[0];

	                //$letra_nivel  = $nombre_nivel[0];
	                //$password 	= $nuevo_rude;

					if($value['nro_rude']!=''){
                        $nro_rude 	= clear($value['nro_rude']);
                    }else{
                        //$nro_rude 	= clear($value['numero_documento']);
                        $nro_rude 	= clear($value['numero_documento']).'M';
                    }

	                $password 	    = $nro_rude;
	                //////////////////////////////////////////////////////////////////////////
				    
				    $id_user	= $value['id_user'];
				    $id_rol		= $value['rol_id'];

					// Instancia el usuario
					$usuario = array(
						'username'	=> $username.'.'.$nuevo_primer_apellido,
						'password' 	=> encrypt($password),
						//'avatar' 	=> '',
						'rol_id' 	=> $rol_id,
						'persona_id'=> $value['persona_id'],
						'gestion_id'=> $gestion_id,
						'codigo_u' 	=> $codigo_u,
					);

                    // Modifica el usuario
				    $db->where('id_user', $id_user)->update('sys_users', $usuario);

					// Guarda el proceso
						// $db->insert('sys_procesos', array(
						// 	'fecha_proceso'	=> date('Y-m-d'),
						// 	'hora_proceso' 	=> date('H:i:s'),
						// 	'proceso' 		=> 'u',
						// 	'nivel' 		=> 'm',
						// 	'detalle' 		=> 'Se actualizó el usuario con rol estudiante con identificador número ' . $id_usuario . '.',
						// 	'direccion' 	=> $_location,
						// 	'usuario_id' 	=> $_user['id_user']
						// ));
				    //var_dump($password,$id_user,$id_rol);
				}
				echo 1;
                //exit();
				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

				// Redirecciona la pagina
				redirect('?/usuarios/listar');

            }


            // Si el rol = 6 (TUTOR/FAMILIAR) ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            if($rol_id == 6 && $accion_usuario == 'C' && $tipo_contrasenia == 'P'){ //???????????????

            }else if ($rol_id == 6 && $accion_usuario == 'A' && $tipo_contrasenia == 'P') { //???????????????
            	//var_dump('AQUIIIIIIIIIIIIIII TUTOR');exit();

				$familiar_sql = "SELECT p.*,u.*, i.gestion_id
				FROM ins_estudiante_familiar ef
				INNER JOIN ins_familiar f ON ef.familiar_id = f.id_familiar
				INNER JOIN sys_persona p ON f.persona_id = p.id_persona
				inner join sys_users u on p.id_persona = u.persona_id
				INNER JOIN ins_inscripcion i ON ef.estudiante_id = i.estudiante_id
				WHERE i.gestion_id = $gestion_id
				and u.rol_id = 6 ";
                //var_dump('expressionfdfdfs');exit();
                $familiar=$db->query($familiar_sql)->fetch();

				foreach ($familiar as $key => $val) {

				    //$password 	= clear($value['nro_rude']);
				    $id_user	= $val['id_user'];

					// Instancia el usuario
					$usuario = array( 
						'password' 	=> encrypt($passwordg),						
						'codigo_u' 	=> $codigo_u,  
					);

                    // Modifica el usuario
				    $db->where('id_user', $id_user)->update('sys_users', $usuario);

					// Guarda el proceso
					// $db->insert('sys_procesos', array(
					// 	'fecha_proceso'	=> date('Y-m-d'),
					// 	'hora_proceso' 	=> date('H:i:s'),
					// 	'proceso' 		=> 'u',
					// 	'nivel' 		=> 'm',
					// 	'detalle' 		=> 'Se actualizó el usuario con rol estudiante con identificador número ' . $id_usuario . '.',
					// 	'direccion' 	=> $_location,
					// 	'usuario_id' 	=> $_user['id_user']
					// ));
				}

				// Crea la notificacion
				set_notification('success', 'Actualización exitosa!', 'El registro se creó satisfactoriamente.');

				// Redirecciona la pagina
				redirect('?/usuarios/listar');

            }else if ($rol_id == 6 && $accion_usuario == 'C' && $tipo_contrasenia == 'CG') {

				$familiar_sql ="SELECT p.*,u.*, i.gestion_id, IFNULL(u.id_user,0)id_usuario, IFNULL(u.rol_id,0)id_rol_usuario
				FROM ins_estudiante_familiar ef
                INNER JOIN ins_inscripcion i ON ef.estudiante_id = i.estudiante_id
                INNER JOIN ins_familiar f ON ef.familiar_id = f.id_familiar
                INNER JOIN sys_persona p ON f.persona_id = p.id_persona
                LEFT JOIN sys_users u ON p.id_persona = u.persona_id
                WHERE i.gestion_id = $gestion_id
                AND i.estado = 'A'
                AND i.estado_inscripcion != 'RETIRADO'
                AND i.estado_inscripcion != 'BAJA'
                GROUP BY f.id_familiar";
            	$familiar=$db->query($familiar_sql)->fetch();

                //var_dump($familiar);exit();

				if($familiar){

					foreach ($familiar as $value) {

	                    //////////////////////////////////////////////////////////////////////////
	                    $findt = array('Á','É','Í','Ó','Ú','Ñ','ñ','á','é','í','ó','ú');
						$replt = array('A','E','I','O','U','N','N','A','E','I','O','U');
	                    $nuevo_nombre_tutor = explode(" ", $value['nombres']);
	                    $username_nombre_tutor 	= $nuevo_nombre_tutor[0];

						$username_nombre_tutor  = str_replace($findt, $replt, $username_nombre_tutor);
						$paterno_tutor    = str_replace($findt, $replt, $value['primer_apellido']);
						$materno_tutor    = str_replace($findt, $replt, $value['segundo_apellido']);

	                    //////////////////////////////////////////////////////////////////////////
	                    if($paterno_tutor!=''){
	                       $username_apellido_tutor = clear($paterno_tutor); 
	                    }else{
	                       $username_apellido_tutor = clear($materno_tutor); 
	                    }

	                    if($value['numero_documento']){
	                    	//var_dump($value['numero_documento'].'el tutor cuenta con CI para generar su contraseña');
	                        $password_tutor = clear($value['numero_documento']);
	                        // }else if($fecha_php_tutor!=''){
	                        // $password_tutor = $fecha_php_tutor;
	                        // var_dump($ci_tutor.'fecha_php_tutor');
	                    }else{

	                        $password_tutor = strtoupper('T'.$username_nombre_tutor.$username_apellido_tutor);
	                        //var_dump($password_tutor.'El tutor no tiene CI su contraseña es por defecto el TUSERNAME');
	                    }


						// Instancia el usuario
						$usuario_tutores = array(
							'username'	=> strtoupper('T'.$username_nombre_tutor.'.'.$username_apellido_tutor),
							'password' 	=> encrypt($password_tutor),
							'email' 	=> clear(strtolower($value['email'])),
							'active' 	=> 's',
							'visible' 	=> 's',
							'rol_id' 	=> 6,
							'persona_id'=> $value['id_persona'],
							'gestion_id'=> $gestion_id,																			
							'avatar' 	=> '',
							'login_at' 	=> '0000-00-00 00:00:00',
							'logout_at' => '0000-00-00 00:00:00',
							'codigo_u' 	=> $codigo_u,
						);
						//var_dump($usuario);
                        if($value['id_usuario'] >0 && $value['id_rol_usuario'] == 6){
                        	// Modifica el usuario
						    $db->where('id_user', $value['id_usuario'])->update('sys_users', $usuario_tutores);
                        }else{
                        	// Crea el usuario
						    $id_usuario_tutor = $db->insert('sys_users', $usuario_tutores);
                        }
						
					}
		           
                    redirect('?/usuarios/listar');
                
		        }

            }else if ($rol_id == 6 && $accion_usuario == 'A' && $tipo_contrasenia == 'CG') {

            	$familiar_sql = "SELECT p.*,u.*, i.gestion_id
				FROM ins_estudiante_familiar ef
				INNER JOIN ins_familiar f ON ef.familiar_id = f.id_familiar
				INNER JOIN sys_persona p ON f.persona_id = p.id_persona
				inner join sys_users u on p.id_persona = u.persona_id
				INNER JOIN ins_inscripcion i ON ef.estudiante_id = i.estudiante_id
				WHERE i.gestion_id = $gestion_id
				and u.rol_id = 6 ";
            	$familiar=$db->query($familiar_sql)->fetch();

                var_dump($familiar);exit();

				if($familiar){

					foreach ($familiar as $value) {

	                    //////////////////////////////////////////////////////////////////////////
	                    $findt = array('Á','É','Í','Ó','Ú','Ñ','ñ','á','é','í','ó','ú');
						$replt = array('A','E','I','O','U','N','N','A','E','I','O','U');
	                    $nuevo_nombre_tutor = explode(" ", $value['nombres']);
	                    $username_nombre_tutor 	= $nuevo_nombre_tutor[0];

						$username_nombre_tutor  = str_replace($findt, $replt, $username_nombre_tutor);
						$paterno_tutor    = str_replace($findt, $replt, $value['primer_apellido']);
						$materno_tutor    = str_replace($findt, $replt, $value['segundo_apellido']);

	                    //////////////////////////////////////////////////////////////////////////
	                    if($paterno_tutor!=''){
	                       $username_apellido_tutor = clear($paterno_tutor); 
	                    }else{
	                       $username_apellido_tutor = clear($materno_tutor); 
	                    }

	                    if($value['numero_documento']){
	                    	//var_dump($value['numero_documento'].'el tutor cuenta con CI para generar su contraseña');
	                        $password_tutor = clear($value['numero_documento']);
	                        // }else if($fecha_php_tutor!=''){
	                        // $password_tutor = $fecha_php_tutor;
	                        // var_dump($ci_tutor.'fecha_php_tutor');
	                    }else{

	                        $password_tutor = strtoupper('T'.$username_nombre_tutor.$username_apellido_tutor);
	                        //var_dump($password_tutor.'El tutor no tiene CI su contraseña es por defecto el TUSERNAME');
	                    }


						// Instancia el usuario
						$usuario_tutores = array(
							'username'	=> strtoupper('T'.$username_nombre_tutor.'.'.$username_apellido_tutor),
							'password' 	=> encrypt($password_tutor),
							'email' 	=> clear(strtolower($value['email'])),
							'rol_id' 	=> 6,
							'persona_id'=> $value['id_persona'],
							'codigo_u' 	=> $codigo_u,
						);
						//var_dump($usuario);

						// Modifica el usuario
						$db->where('id_user', $value['id_user'])->update('sys_users', $usuario_tutores);
				  
                    }
		        }

            }

            // Si el rol != 6 || !=5 (ADMINISTRATIVO/DOCENTE) ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            if(($rol_id != 5 || $rol_id != 6) && $accion_usuario == 'C' && $tipo_contrasenia == 'P'){ //???????????????

            }else if (($rol_id != 5 || $rol_id != 6) && $accion_usuario == 'A' && $tipo_contrasenia == 'P') { //???????????????

                $docentes=$db->query("SELECT sp.numero_documento, sp.id_persona, u.persona_id, sp.nombres, u.id_user
				FROM sys_users u
				inner join sys_persona sp on u.persona_id=sp.id_persona
				WHERE u.rol_id != 5 AND u.rol_id != 6 and u.rol_id = $rol_id ")->fetch();

				foreach ($docentes as $key => $val) {

				    //$password 	= clear($value['nro_rude']);
				    $id_user	= $val['id_user'];

					// Instancia el usuario
					$usuario = array( 
						'password' 	=> encrypt($passwordg),						
						'codigo_u' 	=> $codigo_u,
					);

                    // Modifica el usuario
				    $db->where('id_user', $id_user)->update('sys_users', $usuario);

					// Guarda el proceso
					// $db->insert('sys_procesos', array(
					// 	'fecha_proceso'	=> date('Y-m-d'),
					// 	'hora_proceso' 	=> date('H:i:s'),
					// 	'proceso' 		=> 'u',
					// 	'nivel' 		=> 'm',
					// 	'detalle' 		=> 'Se actualizó el usuario con rol estudiante con identificador número ' . $id_usuario . '.',
					// 	'direccion' 	=> $_location,
					// 	'usuario_id' 	=> $_user['id_user']
					// ));
				}

				// Crea la notificacion
				set_notification('success', 'Actualización exitosa!', 'El registro se creó satisfactoriamente.');

				// Redirecciona la pagina
				redirect('?/usuarios/listar');

            }else if (($rol_id != 5 || $rol_id != 6) && $accion_usuario == 'C' && $tipo_contrasenia == 'CG') {
            	$docentes=$db->query("SELECT sp.numero_documento, sp.id_persona, u.persona_id, sp.nombres, u.id_user
				FROM sys_users u
				inner join sys_persona sp on u.persona_id=sp.id_persona
				WHERE u.rol_id = 3 ")->fetch();

				foreach ($docentes as $val) {
                    
                     $password = clear($val['numero_documento']);
                     $id_user=$val['id_user'];

					// Instancia el usuario
					$data = array(
						'password' 	=>  encrypt($password),
						'codigo_u' 	=> $codigo_u,
					);
					
                   // Modifica el usuario
				    $db->where('id_user', $id_user)->update('sys_users', $data);

					// Guarda el proceso
						// $db->insert('sys_procesos', array(
						// 	'fecha_proceso'	=> date('Y-m-d'),
						// 	'hora_proceso' 	=> date('H:i:s'),
						// 	'proceso' 		=> 'u',
						// 	'nivel' 		=> 'm',
						// 	'detalle' 		=> 'Se actualizo el usuario con rol docente con identificador número ' . $id_usuario . '.',
						// 	'direccion' 	=> $_location,
						// 	'usuario_id' 	=> $_user['id_user']
						// ));
				}

				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

				// Redirecciona la pagina
				redirect('?/usuarios/listar');

            }else if (($rol_id != 5 || $rol_id != 6) && $accion_usuario == 'A' && $tipo_contrasenia == 'CG') {
            	$docentes=$db->query("SELECT sp.numero_documento, sp.id_persona, u.persona_id, sp.nombres, u.id_user
				FROM sys_users u
				inner join sys_persona sp on u.persona_id=sp.id_persona
				WHERE u.rol_id = 3 ")->fetch();

				foreach ($docentes as $val) {
                    
                     $password = clear($val['numero_documento']);
                     $id_user=$val['id_user'];

					// Instancia el usuario
					$data = array(
						'password' 	=>  encrypt($password),
						'codigo_u' 	=> $codigo_u,
					);
					
                   // Modifica el usuario
				    $db->where('id_user', $id_user)->update('sys_users', $data);

					// Guarda el proceso
						// $db->insert('sys_procesos', array(
						// 	'fecha_proceso'	=> date('Y-m-d'),
						// 	'hora_proceso' 	=> date('H:i:s'),
						// 	'proceso' 		=> 'u',
						// 	'nivel' 		=> 'm',
						// 	'detalle' 		=> 'Se actualizo el usuario con rol docente con identificador número ' . $id_usuario . '.',
						// 	'direccion' 	=> $_location,
						// 	'usuario_id' 	=> $_user['id_user']
						// ));
				}

				// Crea la notificacion
				set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

				// Redirecciona la pagina
				redirect('?/usuarios/listar');

            }

            $usuarios = $db->query("SELECT u.id_user FROM sys_users u INNER JOIN sys_persona p ON p.id_persona=u.persona_id where u.rol_id = $rol_id ")->fetch();

            // Si es accion = (DESBLOQUEAR || BLOQUEAR) ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
            if($accion_usuario == 'D'){

            	// Verifica si existe el gestion
				if ($usuarios) {

					foreach ($usuarios as $key => $value) {

						// Desblouea a usuarios
							$usuario = array(
								'active'	=> 's',
							);

		                // Modifica el usuario
						$db->where('id_user', $value['id_user'])->update('sys_users', $usuario);
						
						// Verifica la eliminacion
						if ($db->affected_rows) {
							// Guarda el proceso
							$db->insert('sys_procesos', array(
								'fecha_proceso' => date('Y-m-d'),
								'hora_proceso' => date('H:i:s'),
								'proceso' => 'u',
								'nivel' => 'm',
								'detalle' => 'Se desbloqueó al usuario con identificador número ' . $value['id_user'] . '. (Masivo)',
								'direccion' => $_location,
								'usuario_id' => $_user['id_user']
							));
							
							// Crea la notificacion
							//echo 1;
						} else {
							// Crea la notificacion
							//echo 2;
						}

					}
					redirect('?/usuarios/listar');

				} else {
					// Error 400
					/*require_once bad_request();
					exit;*/
					echo 2; //no se encontro el registro que se quiere eliminar
				}

            }else if ( $accion_usuario == 'B') {

            	
            	// Verifica si existe el gestion
				if ($usuarios) {

					foreach ($usuarios as $key => $value) {

						// Desblouea a usuarios
							$usuario = array(
								'active'	=> 'n',
							);

		                // Modifica el usuario
						$db->where('id_user', $value['id_user'])->update('sys_users', $usuario);
						
						// Verifica la eliminacion
						if ($db->affected_rows) {
							// Guarda el proceso
							$db->insert('sys_procesos', array(
								'fecha_proceso' => date('Y-m-d'),
								'hora_proceso' => date('H:i:s'),
								'proceso' => 'u',
								'nivel' => 'm',
								'detalle' => 'Se bloqueó al usuario con identificador número ' . $value['id_user'] . '. (Masivo)',
								'direccion' => $_location,
								'usuario_id' => $_user['id_user']
							));
							
							// Crea la notificacion
							//echo 1;
						} else {
							// Crea la notificacion
							//echo 2;
						}

					}
					redirect('?/usuarios/listar');

				} else {
					// Error 400
					/*require_once bad_request();
					exit;*/
					echo 2; //no se encontro el registro que se quiere eliminar
				}

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