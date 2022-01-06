<?php 
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL JORGE LUIS)
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
 if (is_post()) {       

        //Obtiene los datos
        $usuario              = clear($_POST['usuario']);
        $contrasenia          = clear($_POST['contrasenia']);
        $id_profesor          = clear($_POST['id_profesor']);
        $id_aula_paralelo     = clear($_POST['id_aula_paralelo']);
        $id_profesor_materia  = clear($_POST['id_profesor_materia']);
        $id_modo_calificacion = clear($_POST['id_modo_calificacion']);
        $id_area_calificacion = clear($_POST['id_area_calificacion']);
        $nombre_actividad         = clear($_POST['nombre_tarea']);
        $descripcion_actividad          = clear($_POST['observacion']);
        $nota                 = clear($_POST['nota']);
        $fecha_presentacion   = clear($_POST['fecha_presentacion']);

        //valores de prueba 
            // $usuario     = "martha"; 
            // $contrasenia = "martha2019";  
            // $id_user     = 5;
            // $id_aula_paralelo = 8;
            // $id_profesor_materia = 1;
            // $id_modo_calificacion = 1;
            // $id_area_calificacion = 1;
            // $id_actividad=1;
         
        // Encripta la contraseña para compararla en la base de datos
        $usuario = md5($usuario);
        $contrasenia = encrypt($contrasenia); 

        // Obtiene los datos del usuario
        $usuario = $db->select('gestion_id, persona_id, id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
        $id_gestion = $usuario['gestion_id'];
   
        // Verifica la existencia del usuario 
        if ($usuario) {

                //busca el id_modo_calificacion_area_calificacion
                $modo_area = $db->query("SELECT * 
                                        FROM vista_modo_calificacion_area_calificacion
                                        WHERE id_modo_calificacion = $id_modo_calificacion AND 
                                        id_area_calificacion = $id_area_calificacion")->fetch_first();

                //busca el id_aula_apralelo_profesor_materia
                $aula_paralelo_profesor_materia = $db->query("SELECT * 
                                        FROM int_aula_paralelo_profesor_materia
                                        WHERE aula_paralelo_id = $id_aula_paralelo AND 
                                        profesor_materia_id = $id_profesor_materia")->fetch_first();
            
                // Instancia de d-regsitrar-nota
                $instancia_actividad = array(
                   // 'id_actividad_materia_modo_area' => $id_actividad_materia_modo_area,
                    'nombre_actividad' => $nombre_actividad,
                    'descripcion_actividad' => $descripcion_actividad,
                    'fecha_presentacion' => $fecha_presentacion,
                    'modo_calificacion_area_calificacion_id' => $modo_area['id_modo_calificacion_area_calificacion'],
                    'aula_paralelo_profesor_materia_id' => $aula_paralelo_profesor_materia['id_aula_paralelo_profesor_materia'],
                );
            
                //Instancia para crear
                $crear = array( 'confirmado'           => 'N',
                                'estado'               => 'A',
                                'usuario_registro'     => $usuario['id_user'],
                                'fecha_registro'       => Date('Y-m-d'),
                                'usuario_modificacion' => '0',
                                'fecha_modificacion'   => '0000-00-00');
                $instacia_union = array_merge_recursive($instancia_actividad, $crear); //une las dos instancias
                 
                // Crea la actividad a calificar
                $id_estudiante_actividad_nota = $db->insert('cal_actividad_materia_modo_area', $instacia_union);
                

            if($id_estudiante_actividad_nota){

<<<<<<< HEAD
=======
                $to = "/topics/dispositivos";
                        $msj = $nombre_actividad ."\n".$descripcion_actividad;
                        // DATOS DE LA NOTIFICACION
                        $data = array(
                                    'title' => 'Educheck',
                                    'body' => $msj,
                                    );
            
                        //sendPushNotification($to,  $data)
                        sendPushNotification($to,  $data);

>>>>>>> luism
                $respuesta = array(
                    'estado'        => 's'
                );
                // Devuelve los resultados
                echo json_encode($respuesta);

            }else {
                // Devuelve los resultados
                echo json_encode(array('estado' => 'no se ha registrado actividad'));
            }

        }else {
            // Devuelve los resultados 
            echo json_encode(array('estado' => 'n login'));
        }
 }else{
     // Devuelve los resultados
    echo json_encode(array('estado' => 'n post'));
 }

<<<<<<< HEAD
=======
 function sendPushNotification($to = '', $data = array()) {
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
}

>>>>>>> luism
 ?>