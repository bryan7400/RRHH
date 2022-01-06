<?php
// Verifica la peticion post

if (is_post()) {
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_final']) && isset($_POST['nombre_evento']) && isset($_POST['descripcion_evento']) && isset($_POST['color_evento'])){
            // && isset($_POST['select_roles']))//se almacena como ayuda a rolescomp
			// Obtiene los datos
			$id_comunicado = (isset($_POST['id_comunicado'])) ? clear($_POST['id_comunicado']) : 0;
			$fecha_inicio = $_POST['fecha_inicio']; //datetime_decode($_POST['fecha_inicio']);
			$fecha_final = $_POST['fecha_final'] ; //datetime_decode($_POST['fecha_final']);
			$nombre_evento = clear($_POST['nombre_evento']);
			$descripcion = clear($_POST['descripcion_evento']);
			$color = clear($_POST['color_evento']);
			$rolesselect = $_POST['rolescomp'];//no recibe bien por el selectize select_roles
            $roles = explode(",", $rolesselect);//conviete en array
			$prioridad = $_POST['prioridad'];
			
			$nombre_dominio = escape($_institution['nombre_dominio']);
            
			$comunicados = array(
				'fecha_inicio' => $fecha_inicio,
				'fecha_final' => $fecha_final,
				'nombre_evento' => $nombre_evento,
				'descripcion' => $descripcion,
				'color' => $color,
				'persona_id' => '',
				'estado' => 'A',
                'prioridad' => $prioridad,
                'aula_paralelo_asignacion_materia_id' => 0,
                'modo_calificacion_id' => 0,
                'grupo' => 0,
                'vista_personas_id' => ','
			);
			 
            //_________ manejo de archivo ________
     $nombre_archivo = isset($_FILES['file_evento_p']['name'])?($_FILES['file_evento_p']['name']):false;

            if($nombre_archivo != false && $nombre_archivo != ''){
                $tipo_archivo = $_FILES['file_evento_p']['type'];
                $tamano_archivo = $_FILES['file_evento_p']['size'];

                if (!( ($tamano_archivo < 10000000))) {
                }else{
                    if ($nombre_archivo !='') {
                        //$output_dir = imgs . "/comunicados/";
                        $output_dir = files ."/".$nombre_dominio."/comunicados/";
                        $imagen = "control_".date('dmY').'-'.date('Hms').'-'.random_int(0, 999).'.'.pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                        if (!move_uploaded_file($_FILES['file_evento_p']["tmp_name"],$output_dir.$imagen)) {
                            $msg = 'No pudo subir el archivo';
                      }
                    }
                }                
            } else{
                    $imagen='';  
            } 

			// Verifica si es creacion o modificacion
			if ($id_comunicado > 0) {
               //:::::::::::::::  UPDATE  :::::::::::::::::          
               
              // _________CADENA USUARIOS ESTADOS ARRAY:___________
		/*		$busqueda = $db->query("SELECT * FROM sys_roles WHERE rol != 'Superusuario'")->fetch(); //busca a todos los roles 
				$cadena_usuarios = "";
				$cadena_estados = "";
				foreach ($busqueda as $key => $bus) {
					$id_rol = $bus['id_rol'];
					$rol = $bus['rol'];
					$cadena_usuarios = $cadena_usuarios . "," . $id_rol;				
					if (in_array($id_rol, $roles)) {
						$cadena_estados = $cadena_estados . "," . "SI";
					}else{
						$cadena_estados = $cadena_estados . "," . "NO";
					} 
				} 
				$cadena_estados = trim($cadena_estados, ',');
				$cadena_usuarios = trim($cadena_usuarios, ',');    */   
				
                if(!$nombre_archivo){ 
                    $imagen=NULL; 
                }
                $registro_modificado = array(
                    'usuarios' => ','.$rolesselect.',',//$cadena_usuarios.',',
                    'estados' => '',//$cadena_estados, 
                    'usuario_modificacion'=> $_user['id_user'],
                    'fecha_modificacion'=> Date('Y-m-d H:i:s'),
                    'file'=>$imagen);
                //une las dos instancias
				$instacia_union = array_merge_recursive($comunicados, $registro_modificado); 
				// Modifica el comunidados
				$db->where('id_comunicado', $id_comunicado)->update('ins_comunicados', $instacia_union);
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el comunidados con identificador número ' . $id_comunicado . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));	
				  $estado=true;  $estresp=2;
				//echo 2;
			} else {                
                //:::::::::::::::  CREATE  :::::::::::::::::                
				//busca el ultimo registro para el codigo de comunicado______________
				$codigo_mayor = $db->query("SELECT MAX(id_comunicado) as id_comunicado FROM ins_comunicados")->fetch_first();
				$id_anterior = $codigo_mayor['id_comunicado'];
				if(is_null($id_anterior)){
					$nuevo_codigo = "C-1";            
				}else{
					 //recupera los datos del ultimo registro
					$comunicado_mayor = $db->query("SELECT id_comunicado, codigo, nombre_evento FROM ins_comunicados WHERE id_comunicado = $id_anterior ")->fetch_first();
					$codigo_anterior = $comunicado_mayor['codigo']; //codigo anterior
					$separado = explode('-', $codigo_anterior);
					$nuevo_codigo = "C-" . ($separado[1] + 1);
				}
                //_________________________________________________                
                //_________ _____crear arrayas____ ____ _______ ___ ____
                
				/*$busqueda = $db->query("SELECT * FROM sys_roles WHERE rol != 'Superusuario'")->fetch();	
				$cadena_usuarios = "";
				$cadena_estados = "";
				foreach ($busqueda as $key => $bus) {
					$id_rol = $bus['id_rol'];
					$rol = $bus['rol'];

					$cadena_usuarios = $cadena_usuarios . "," . $id_rol;					
					if (in_array($id_rol, $roles)) {
						$cadena_estados = $cadena_estados . "," . "SI";
					}else{
						$cadena_estados = $cadena_estados . "," . "NO";
					}
				}
				$cadena_estados = trim($cadena_estados, ',');
				$cadena_usuarios = trim($cadena_usuarios, ',');    */    
				
				$nuevo_registro = array(
                    'usuarios' => ','.$rolesselect.',',//$cadena_usuarios.',',//id de roles
                    'estados' => '',//$cadena_estados,
                    'codigo' => $nuevo_codigo,
                    'usuario_registro'=> $_user['id_user'],
                    'fecha_registro'=> Date('Y-m-d H:i:s'),
                    'usuario_modificacion'=> $_user['id_user'],
                    'fecha_modificacion'=> Date('Y-m-d H:i:s'),
                    'file'=>$imagen);
				$instacia_union = array_merge_recursive($comunicados, $nuevo_registro);
                //var_dump($instacia_union);exit();
				// Crea el comunidados
				$id_comunicado = $db->insert('ins_comunicados', $instacia_union);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el comunidados con identificador número ' . $id_comunicado . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));				
				  $estado=true;   $estresp=1;//echo 1;
			}
            if($estado){
            /* $id_comunicado = array(
                    'file'=>$imagen );
               }
             $comunicados = array_merge_recursive($comunicados, $regImag); //une las dos instancias*/
            $instacia_union['id_comunicado']=$id_comunicado;
            $res=array(
                'estresp'=>'1',
                'datos'=>$instacia_union
            );
            echo json_encode($res);
        }
            //:::::::::::::::::::: NOTIFICACION  :::::::::::::::::::::::::::::
            //las notificaciones se eenvian desde el front
      /*  foreach($roles as $i=>$row)
            {
            //buscar persona id
            $busqueda = $db->query("SELECT * FROM sys_users WHERE rol_id= $row and active='s'")->fetch();
            $cant=0;
                foreach($busqueda as $i=>$res){
                    $to =$res['token']; 
                    $msj = $descripcion; 
                    $titulo = $nombre_evento;
                    // DATOS DE LA NOTIFICACION
                    $data = array(
                                'title' => $titulo,
                                'body' => $msj,
                                'prioridad' => $prioridad,
                                );
                     //sendPushNotification($to,  $data);  
                }
        	} */            
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	/*} else {
		// Redirecciona la pagina
		redirect('?/comunidados/listar');
	}*/
} else {
	// Error 404
	require_once not_found();
	exit;
}

/*function sendPushNotification($to = '', $data = array()) {
	
	$apiKey = 'AAAAFiAdTos:APA91bELQiOC1jGuKIn2kiUa8aSEqY6z3gY1MEuxZCl--rKkBZ5nDfalKA8NxIl-5nwX0GQ4kdeXU-9pw12aQZkLJBDxsXAyMCIs9uT6stfERkYg8Suv0SXJtWHjr9xcvjC62MkzYx9l';

	$fields = array(
					'to' => $to,
					'data' => $data,
					);
	
	$headers = array('Authorization: key='.$apiKey, 'Content-Type: application/json');
	
	$url = 'https://fcm.googleapis.com/fcm/send';
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));	
	//echo json_encode($fields);
	//echo "<br><br>RESPUESTA SERVIDOR: ";	
	$result = curl_exec($ch);	
	curl_close($ch);	
	return json_decode($result, true);
}*/

?>