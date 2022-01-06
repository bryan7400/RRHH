


<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  WEB SERVICES
 * @author  EDUCHECK MARIBEL JORGE LUIS
 */

header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {
	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		// Obtiene los datos
		$usuario = clear($_POST['usuario']);
		$contrasenia = clear($_POST['contrasenia']);
		//$id_ruta = clear($_POST['id_ruta']);

		// Encripta la contraseña para compararla en la base de datos
		$usuario = $usuario;//md5($usuario);
		$contrasenia = $contrasenia;//encrypt($contrasenia);
 
		//obtiene el año actual
		$anio_actual = Date('Y');

		//obtiene los datos de la gestion actual
		$_gestion = $db->select('z.id_gestion, z.gestion')->from('ins_gestion z')->where('gestion', $anio_actual)->fetch_first();

		//obtiene los datos del modo de calificacion actual
		$fecha_actual = Date('Y-m-d');
		 
		// Obtiene los datos del usuario
		$usuario = $db->select('id_user, username, email, avatar, rol_id, visible')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
		// Verifica la existencia del usuario
		if ($usuario) {
			 
			//$motivo = clear($_POST['motivo']);
			//$observacion = clear($_POST['observacion']);
			//$tiempo = clear($_POST['tiempo']);
			
			//$asignacion_docente_id = 0;
			$desc = clear($_POST['descripcion']);
			$fecha = clear($_POST['tiempo']);
			//$grupo = 0;
			//$id_comunicado = $id_comunicado_res;
			$msgExtra = clear($_POST['nombre_ruta']).' '.clear($_POST['nombre_conductor']);
			$nombre_dominio = clear($_POST['nombre_dominio']);
			$personas_id = clear($_POST['estudiantes_id']);
			$rol_id = 0; 
			$titulo = clear($_POST['titulo']);
			$id_usuario = clear($_POST['id_usuario']);
			$id_ruta = clear($_POST['id_ruta']);
			//$data = '{"dominio":"'.$egreso_id.'"}';
			
			//busca el ultimo registro para el codigo de comunicado 
        $codigo_mayor = $db->query("SELECT MAX(id_comunicado) as id_comunicado FROM ins_comunicados")->fetch_first();
        $id_anterior = $codigo_mayor['id_comunicado'];//id_comunicado mayor

        if(is_null($id_anterior)){
            //$id_anterior = 1;
            $nuevo_codigo = "C-1";            
        }else{
            //$where = "";
             //recupera los datos del ultimo registro
            $comunicado_mayor = $db->query("SELECT id_comunicado, codigo, nombre_evento FROM ins_comunicados WHERE id_comunicado = $id_anterior ")->fetch_first();
            $codigo_anterior = $comunicado_mayor['codigo']; //codigo anterior
            $separado = explode('-', $codigo_anterior);
            $nuevo_codigo = "C-" . (intval($separado[1]) + 1);
			//var_dump($nuevo_codigo);exit();
        }
			//guarar en la bd
			$evento = array('codigo'=> $nuevo_codigo,
                        'fecha_inicio'=> $fecha,
                        'fecha_final'=> $fecha,
                        'nombre_evento'=> $titulo,
                        'descripcion'=> $desc.'||'.$msgExtra,
                        'usuario_registro'=> $id_usuario,//conductor
                        'asignacion_docente_id'=> $id_ruta,//id_ruta
                        'color'=> '#ffffff',
                        'usuarios'=> '',//$cadena_usuarios, 
                        'estados'=> ''//$cadena_estados
					   ); 
        	$id_comunicado = $db->insert('ins_comunicados', $evento);
			
			
			
			//guardar en firebase
			$data = '{
			"asignacion_docente_id":"0",
			"desc":"'.$desc.'",
			"fecha":"'.$fecha.'",
			"grupo":"0",
			"id_comunicado":"'.$id_comunicado.'",
			"msgExtra":"'.$msgExtra.'",
			"nombre_dominio":"'.$nombre_dominio.'",
			"personas_id":"'.$personas_id.'",
			"rol_id":"0",
			"titulo":"'.$titulo.'"}';
			
			//	$url  = "https://educheckv2-77414/user.json";
			/*	$url  = "https://educheckv2-77414-default-rtdb.firebaseio.com/comunicados.json";

			*/
			//data = '{"dominio":"'.$dominio.'}';
			$url  = "https://educheckv2-52fd3-default-rtdb.firebaseio.com/".$nombre_dominio."-comunicados.json";
		 //https://educheckv2-52fd3-default-rtdb.firebaseio.com/

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/plain'));

				$response = curl_exec($ch);
			 
			//enviara a firebase
			
			$respuesta = array ("estado" =>"s","rutaEst"=>$response);

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