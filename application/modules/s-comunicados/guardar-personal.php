<?php


// Verifica la peticion post
if (is_post()) {
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
		if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_final']) && isset($_POST['nombre_evento'])) {           
			// Obtiene los datos
			$id_comunicado = (isset($_POST['id_comunicado'])) ? clear($_POST['id_comunicado']) : 0;            
			$nombre_evento = isset($_POST['nombre_evento']) ? $_POST['nombre_evento'] : '';            
			$descripcion_evento = isset($_POST['descripcion_evento'])? $_POST['descripcion_evento'] : '';
            $color = isset($_POST['color_evento'])? $_POST['color_evento'] : '#ffffff';
            $fecha_inicio = isset($_POST['fecha_inicio'])?   ($_POST['fecha_inicio']) : '0000-00-00 00:00:00';
            $prioridad = isset($_POST['prioridad'])?   ($_POST['prioridad']) : '1';                       
            $nombre_archivo = isset($_FILES['file_evento_p']['name'])?($_FILES['file_evento_p']['name']):false;
            
            //$nombre_dominio = $_institution['nombre_nombre'];
            $nombre_dominio = escape($_institution['nombre_dominio']);
            //var_dump($output_dir);exit();
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
             
			$fecha_final = isset($_POST['fecha_final'])?  ($_POST['fecha_final']) : '0000-00-00 00:00:00';
		    $fecha_ini = explode(' ', $fecha_inicio);
		    $fecha_fin = explode(' ', $fecha_final);
		    $fecha_inicio = date_encode($fecha_ini[0]). ' '.$fecha_ini[1];
		    $fecha_final =  date_encode($fecha_fin[0]). ' ' .$fecha_fin[1];
            $id_usuariosstr = implode(',', $_POST['id_user_array']);
            //$sad=implode(',', $id_personas);
            //var_dump($sad);exit();
            //:::::::::::::::::::::::::::::::: INICI ODE INSERSION O UPDATE  :::::::::::::::::::::::::::::
           
		
			//var_dump($comunicados);die;
			// Verifica si es creacion o modificacion
			if ($id_comunicado > 0) {
                //:::::::::: UPDATE ::::::::::::::
                if(!$nombre_archivo){ 
                  $imagen=NULL; 
                  }
               //if($nombre_archivo){
                    $comunicados = array(
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_final' => $fecha_final,
                    'nombre_evento' => $nombre_evento,
                    'descripcion' => $descripcion_evento,
                    'color' => $color,
                    'prioridad' => $prioridad,
                    'file'=>$imagen);
                  //}else{
                   /* $comunicados = array(
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_final' => $fecha_final,
                    'nombre_evento' => $nombre_evento,
                    'descripcion' => $descripcion_evento,
                    'color' => $color 
                    );*/

                  //}
                
				$busqueda = $db->query("SELECT * FROM sys_roles WHERE rol != 'Superusuario'")->fetch(); //busca a todos los roles
				
				/*$cadena_usuarios = "";
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
				$cadena_usuarios = trim($cadena_usuarios, ',');*/
                //$id_personastr = $id_personas;//implode(',', $id_personas);
				$registro_modificado = array(
                    'usuarios' => '',
                    'estados' => '',
                    'grupo'=>'0',
                    'persona_id' => ','.$id_usuariosstr.',',
                    'estado' => 'A',
                    'usuario_modificacion'=> $_user['id_user'],
                    'fecha_modificacion'=> Date('Y-m-d H:i:s'));
                    

				$instacia_union = array_merge_recursive($comunicados, $registro_modificado); //une las dos instancias
                
				// 

				// Modifica el comunidados
				$db->where('id_comunicado', $id_comunicado)->update('ins_comunicados', $instacia_union);
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el comunidados personal con identificador número ' . $id_comunicado . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
                 $estado=true;  $estresp='2';
			}
            else {
                
                //:::::::::::CREAR::::::::::::::
                 //if($nombre_archivo){
                    $comunicados = array(
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_final' => $fecha_final,
                    'nombre_evento' => $nombre_evento,
                    'descripcion' => $descripcion_evento,
                    'color' => $color,
                    'prioridad' => $prioridad,
                    'file'=>$imagen
                    );
                  //}else{

                    //}
				//busca el ultimo registro para el codigo de comunicado 
                
                //::::::::::::::::::::::::::::::::  CREA CODIGO :::::::::::::::::::::::::::::
				$codigo_mayor = $db->query("SELECT MAX(id_comunicado) as id_comunicado FROM ins_comunicados")->fetch_first();
				$id_anterior = $codigo_mayor['id_comunicado'];//id_comunicado mayor
				if(is_null($id_anterior)){
					$nuevo_codigo = "C-1";            
				}else{
					 //recupera los datos del ultimo registro
					$comunicado_mayor = $db->query("SELECT id_comunicado, codigo, nombre_evento FROM ins_comunicados WHERE id_comunicado = $id_anterior ")->fetch_first();
					$codigo_anterior = $comunicado_mayor['codigo']; //codigo anterior
					$separado = explode('-', $codigo_anterior);
					$nuevo_codigo = "C-" . ($separado[1] + 1);
				}
                 
				$nuevo_registro = array(
                    'codigo' => $nuevo_codigo,
                    'usuarios'=>'',
                    'estados'=>'',
                    'persona_id' => ','.$id_usuariosstr.',',
                    'grupo'=>'0',
                    'vista_personas_id' => ',',
                    'estado' => 'A',
                    'usuario_registro'=> $_user['id_user'],
                    'fecha_registro'=> Date('Y-m-d H:i:s'),
                    'usuario_modificacion'=> $_user['id_user'],
                    'fecha_modificacion'=> Date('Y-m-d H:i:s'));

				$instacia_union = array_merge_recursive($comunicados, $nuevo_registro); //une las dos instancias

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
                 $estado=true;   $estresp='1';//echo 1;
 
			}
			 if($estado){
            
                $instacia_union['id_comunicado']=$id_comunicado;
                $res=array(
                    'estresp'=>$estresp,
                    'datos'=>$instacia_union
                );
                echo json_encode($res);
            }
    			
			
            //RECORREMOS LAS PERSONAS RECIBIDAS
       /*      foreach($id_personas as $i=>$row)
                    {
                       //buscar persona id
                      $busqueda = $db->query("SELECT * FROM sys_users WHERE persona_id= $row  and active='s'")->fetch();
                      //$busqueda = $db->from('sys_users')->where('persona_id',$row)->fetch();  
                      $cant=0;
                    //recorremos las personas como usuarios encontradas
                        foreach($busqueda as $i=>$res){
				            $to =$res['token'];
                            $cant++;
                            $msj = $descripcion_evento;//'editado';//$nombre_actividad."\n".$descripcion_actividad."\n".$fecha_presentacion."\n";
                            $titulo = $nombre_evento;
                            // DATOS DE LA NOTIFICACION
                            $data = array(
                                        'title' => $titulo,
                                        'body' => $msj,
                                        'prioridad' => $prioridad,
                                        );
                             sendPushNotification($to,  $data);
                            //var_dump($to);exit();
                        }
    
                    }*/
            
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
	//API MAY   
	//$apiKey = 'AAAAqrNal5g:APA91bHrfUDKoTvp96RkLEfkr7qtawex-CW8SySaHMkPfZ0CiJxs0fAVnHhg2G8YGJcq0mCe8J8go3D2LNchQFrSSPjCpz8woCBz6JqluxBWepRRyMDcP4pDjhfpJYSeodEa1dAh6W1C';
	
	//API FMC
	//$apiKey = 'AAAA10z6XYM:APA91bGt--7gwK1OrPWw-Ys10_OvBNnXwvvzOovNT4rHEl6MIZ_uoY9eYr6RhWvt9i_f7LDrQQSGvNs5uD0wSO6ai9vIoA3M_LTLM68kr7PdRIMvJBt_HKvw1bwkBxFcmoJFKv82WtEb';
	
	//API PAPAPP   
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