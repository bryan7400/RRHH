<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
 
 // Motrar todos los errores de PHP
error_reporting(-1);

// No mostrar los errores de PHP
error_reporting(0);

// Motrar todos los errores de PHP
error_reporting(E_ALL);

// Motrar todos los errores de PHP
ini_set('error_reporting', E_ALL);

// echo "<pre>";
// var_dump($_POST);
// echo "</pre>";
// exit();
// Verifica la peticion post
if (is_post()) {
    
    $nombre_dominio = escape($_institution['nombre_dominio']);    
    //echo $nombre_dominio;exit();   
    
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
	$id_gestion = $_gestion['id_gestion'];

	// Verifica la existencia de datos
	if (isset($_POST['nombres']) && isset($_POST['primer_apellido']) && isset($_POST['segundo_apellido']) && isset($_POST['tipo_documento']) && isset($_POST['numero_documento']) && isset($_POST['expedido']) && isset($_POST['genero']) && isset($_POST['fecha_nacimiento'])) {
		// Obtiene los datos
		$id_estudiante 		= (isset($_POST['id_estudiante'])) ? clear($_POST['id_estudiante']) : 0;
		$nombre_estudiante 	= clear($_POST['nombres']);
		//$nombre_imagen = clear($_POST['nombre_imagen']);
		$primer_apellido 	= clear($_POST['primer_apellido']);
		$segundo_apellido 	= clear($_POST['segundo_apellido']);
		$tipo_documento 	= clear($_POST['tipo_documento']);
		$numero_documento 	= clear($_POST['numero_documento']);
		$complemento		= clear($_POST['complemento']);
		$expedido 			= clear($_POST['expedido']);
		$nombre_imagen 		= clear($_POST['nombre_imagen']);
		$genero 			= clear($_POST['genero']);
		$fecha_nacimiento 	= date_encode($_POST['fecha_nacimiento']);

		$ue_procedencia = clear($_POST['ue_procedencia']);
		$observacion    = clear($_POST['observacion']);

		//Variables para ver el estado de la inscripcion
		$estado_inscripcion  = (isset($_POST['estado_inscripcion'])) ? clear($_POST['estado_inscripcion']) : "0";

		if ($estado_inscripcion == "RESERVA") {
			$estado_reserva = 1;
		} else {
			$estado_reserva = 0;
		}

		$fecha_limite_reserva = (isset($_POST['fecha_reserva'])) ? date_encode($_POST['fecha_reserva']) : now('Y:m:d');


		$monto_reserva        = (isset($_POST['monto_reserva']) and clear($_POST['monto_reserva']) != "") ? clear($_POST['monto_reserva']) : "0";
		$direccion 			  = clear($_POST['direccion']);
		$id_persona 		  = clear($_POST['id_persona']);

		//Variables de turno
		$id_turno = clear($_POST['id_turno']);

		// Instancia el estudiantes
		$persona = array(
			'nombres' => $nombre_estudiante,
			'primer_apellido' => $primer_apellido,
			'segundo_apellido' => $segundo_apellido,
			'tipo_documento' => $tipo_documento,
			'numero_documento' => $numero_documento,
			'complemento' => $complemento,
			'expedido' => $expedido,
			'genero' => $genero,
			'fecha_nacimiento' => $fecha_nacimiento,
			'direccion' => $direccion
		);

		// Verifica si es creacion o modificacion
		if ($id_estudiante > 0) {
			//	var_dump($id_persona);die;

			// Modifica el estudiantes

			$db->where('id_persona', $id_persona)->update('sys_persona', $persona);

			if ($nombre_imagen) {
				$ruta_temporal = 'files/'.$nombre_dominio.'/profiles/temporal/fotos/' . $nombre_imagen; //ruta temporal
				$nombre = md5(secret . random_string() . $id_estudiante); //encripta el nombre de la imagen a md5
				$ruta_destino = 'files/'.$nombre_dominio.'/profiles/estudiantes/' . $nombre . '.jpg'; //ruta de destino
				copy($ruta_temporal, $ruta_destino); //copia la imagen de la carpeta temporal a estudiante
				unlink($ruta_temporal); //elimina la imagen de la carpeta temporal
			} else {
				$nombre = (isset($_POST['foto_estudiante'])) ? clear($_POST['foto_estudiante']) : "";
			}

			$db->query("UPDATE sys_persona set foto = '$nombre' WHERE id_persona = $id_persona")->execute();


			// Guarda el proceso
			$db->insert('sys_procesos', array(
				'fecha_proceso' => date('Y-m-d'),
				'hora_proceso' => date('H:i:s'),
				'proceso' => 'u',
				'nivel' => 'l',
				'detalle' => 'Se modificó el estudiantes con identificador número ' . $id_estudiante . '.',
				'direccion' => $_location,
				'usuario_id' => $_user['id_user']
			));

			// Modificamos campos de unidad educativa de procedencia y observacion sobre la inscripcion
			$sqlUIns = "UPDATE ins_inscripcion SET ue_procedencia=' $ue_procedencia', observacion='$observacion' WHERE  estudiante_id = $id_estudiante";
			$db->query($sqlUIns)->execute();

			// Redirecciona la pagina
			//redirect('?/estudiantes/ver/' . $id_estudiante);
			$cadena = array(
				'id_estudiante' => $id_estudiante,
				'id_persona' => $id_persona,
				'id_turno' => $id_turno,
				'estado_inscripcion' => $estado_inscripcion,
				'estado_reserva' => $estado_reserva,
				'monto_reserva' => $monto_reserva,
				'fecha_reserva' => $fecha_limite_reserva,
				'ue_procedencia' => $ue_procedencia,
				'observacion' => $observacion,
				'estado' => 4
			);
			echo json_encode($cadena);
		} else {
			//busca que no  haya mas de una inscripcion el mismo año
			$busqueda = $db->query("SELECT COUNT(p.id_persona) as codigo_inscripcion 
										FROM sys_persona p
										LEFT JOIN ins_estudiante e ON e.persona_id = p.id_persona
										LEFT JOIN ins_inscripcion i ON i.estudiante_id = e.id_estudiante
										WHERE p.numero_documento = $numero_documento AND gestion_id = $id_gestion ")->fetch_first();
			$contador = $busqueda['codigo_inscripcion'];

			if ($contador > 0) {
				$cadena = array(
					'id_estudiante' => 0,
					'id_persona' => $id_persona,
					'id_turno' => $id_turno,
					'estado_inscripcion' => $estado_inscripcion,
					'estado_reserva' => "0",
					'monto_reserva' => "0",
					'fecha_reserva' => $fecha_limite_reserva,
					'ue_procedencia' => $ue_procedencia,
					'observacion' => $observacion,
					'estado' => 3
				);
				echo json_encode($cadena);
			} else {
				$codigo_mayor = $db->query("SELECT MAX(id_estudiante) as id_estudiante FROM ins_estudiante")->fetch_first();
				$id_anterior = $codigo_mayor['id_estudiante']; //id_comunicado mayor

				//Aqui generamos el codigo
				if (is_null($id_anterior)) {
					$nuevo_codigo = "M-1000";
				} else {
					//recupera los datos del ultimo registro
					$estudiante_mayor = $db->query("SELECT id_estudiante, codigo_estudiante FROM ins_estudiante WHERE id_estudiante = $id_anterior ")->fetch_first();
					$codigo_anterior = $estudiante_mayor['codigo_estudiante']; //codigo anterior
					$separado = explode('-', $codigo_anterior);
					$nuevo_codigo = "M-" . ($separado[1] + 1);
				}

				//registra los datos como persona
				$id_persona = $db->insert('sys_persona', $persona);

				//instancia estudiante
				$estudiante = array('codigo_estudiante' => $nuevo_codigo, 'persona_id' => $id_persona);

				// Crea el estudiante
				$id_estudiante = $db->insert('ins_estudiante', $estudiante);
				if ($nombre_imagen) {
					$ruta_temporal = 'files/'.$nombre_dominio.'/profiles/temporal/fotos/' . $nombre_imagen; //ruta temporal
					$nombre = md5(secret . random_string() . $id_estudiante); //encripta el nombre de la imagen a md5
					$ruta_destino = 'files/'.$nombre_dominio.'/profiles/estudiantes/' . $nombre . '.jpg'; //ruta de destino
					copy($ruta_temporal, $ruta_destino); //copia la imagen de la carpeta temporal a estudiante
					unlink($ruta_temporal); //elimina la imagen de la carpeta temporal
				} else {
					$nombre = "";
				}

				$db->query("UPDATE sys_persona set foto = '$nombre' WHERE id_persona = $id_persona")->execute();

				$id_estudiante_ant = (isset($_POST['id_estudiante_ant'])) ? clear($_POST['id_estudiante_ant']) : 0;
				if ($id_estudiante_ant > 0) {
					$db->query("UPDATE ins_datos_estudiante set estado = 'I' WHERE id_datos_estudiante = $id_estudiante_ant")->execute();
				}


				// Creamos el usuario y contraseña del estudiante

				$nro_rude = "0";
				$nombre_nivel = array();

				$find = array('Á','É','Í','Ó','Ú','Ñ','ñ','á','é','í','ó','ú');
				$repl = array('A','E','I','O','U','N','N','A','E','I','O','U');
				$nombre_estudiante  = str_replace($find, $repl, $nombre_estudiante);
				$primer_apellido    = str_replace($find, $repl, $primer_apellido);
				$segundo_apellido    = str_replace($find, $repl, $segundo_apellido);

				$nuevo_nombre_estudiante  = clear($nombre_estudiante);			

				if($primer_apellido!=''){
				   $username_apellido = clear($primer_apellido); 
				}else{
				   $username_apellido = clear($segundo_apellido); 
				}

				$nuevo_rude 	= substr(clear($nro_rude), 0, -1);				        	
				$resultado_rude = substr($nuevo_rude, 8);
				
				$nombre 			= explode(" ", $nuevo_nombre_estudiante);
				$username_nombre 	= $nombre[0];
				
				//$letra_nivel    	= $nombre_nivel[0];
				$letra_nivel    	= "";

				//contraseña generica para estidantes
				$contrasenia_estudiante = $username_nombre.'.'.$username_apellido;

				//tipo de contraseña segun tipo 
				$tipo_contrasenia = "EST4";

				if($tipo_contrasenia=='EST1'){ // solo RUDE   usuario PRIMER_NOMBRE.PRIMER_APELLIDO

					$password 	    = $fecha_php; //$nuevo_rude;
					$username       = strtoupper($username_nombre.'.'.$username_apellido);

				}elseif($tipo_contrasenia=='EST2'){ //  RUDE con *
					$password 	    = clear($nro_rude);
					$username       = strtoupper($username_nombre.'.'.$username_apellido);

				}elseif($tipo_contrasenia=='EST3'){ // con contrase�a desde el excel y usuario PRIMER_NOMBRE.PRIMER_APELLIDO

					$username       = strtoupper($username_nombre.'.'.$username_apellido);
					$password 	    = $contrasenia_estudiante;
					
				}elseif($tipo_contrasenia=='EST4'){ // con contrase�a desde el excel todo minusculas y usuario primer_nombre.primer_apellido

					$username       = strtoupper($username_nombre.'.'.$username_apellido);
					$password 	    = strtolower($contrasenia_estudiante);

				}elseif($tipo_contrasenia=='JN'){ // colegio Jesus de Nazaret

					$password 	    = 'JN'.$letra_nivel.$resultado_rude;
					$username       = strtoupper($username_nombre.'.'.$username_apellido);

				}else{
					var_dump('sin tipo de contrase�a');exit();
				}
				// Fin creacion del usuario y contraseña
				//////////////////////////////////////////////////////////////////////////

				// Instancia el usuario
				$usuario = array(
					'username'	=> $username,
					'password' 	=> encrypt($password),
					'email' 	=> '',
					'active' 	=> 's',
					'visible' 	=> 's',
					'rol_id' 	=> 5,
					'avatar'   => '',
                    'login_at'   => '0000-00-00 00:00:00',
                    'logout_at' => '0000-00-00 00:00:00',
					'persona_id'=> $id_persona,
					'gestion_id'=> $id_gestion
				);
				//var_dump($usuario);

				// Crea el usuario
				$id_usuario_ = $db->insert('sys_users', $usuario);

				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el estudiantes con identificador número ' . $id_estudiante . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				//var_dump($id_persona);die;
				$cadena = array(
					'id_estudiante' => $id_estudiante,
					'id_persona' => $id_persona,
					'id_turno' => $id_turno,
					'estado_inscripcion' => $estado_inscripcion,
					'estado_reserva' => $estado_reserva,
					'monto_reserva' => $monto_reserva,
					'fecha_reserva' => $fecha_limite_reserva,
					'ue_procedencia' => $ue_procedencia,
					'observacion' => $observacion,
					'estado' => 1
				);
				echo json_encode($cadena);
			}

			// echo "<pre>";
			// var_dump($cadena);exit();
			// echo "</pre>";

			// Crea la notificacion
			//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');

			// Redirecciona la pagina
			//redirect('?/estudiantes/listar');
		}
	} else {
		// Error 400
		//require_once bad_request();
		redirect('?/s-inscripciones/listar');
		exit;
	}	
} else {
	// Error 404
	require_once not_found();
	exit;
}
