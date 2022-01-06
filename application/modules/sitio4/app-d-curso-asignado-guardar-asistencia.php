<?php
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL JORGE LUIS)
 */

// Define las cabeceras
header('Content-Type: application/json');

//Verifica la peticion post
if (is_post()) {

	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
		//Obtiene los datos
		$usuario = clear($_POST['usuario']);
        $contrasenia = clear($_POST['contrasenia']);
        $id_gestion = date("L");
		// Encripta la contraseña para compararla en la base de datos
		$usuario = md5($usuario);
		$contrasenia = encrypt($contrasenia);
 
		// Obtiene los datos del usuario
		$usuario = $db->select('gestion_id')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
        
        //var_dump($usuario);exit;

		// Verifica la existencia del usuario 
		if ($usuario) {
            //Empesamos a hacer las consultas previas para poder introducir la asistencia
            //var_dump($_POST);exit;

            $asignacion_mat_id= $_POST['asignacion_mat_id'];
            $modo_calificacion_id= $_POST['modo_calificacion_id'];
            $estudiante_id= $_POST['estudiante_id'];//2@34@
            $asistencia= $_POST['asistencia'];////A@L@
            
            $check_asistencia = explode('@', $estudiante_id);//
            $asistencia = explode('@', $asistencia);//
            array_pop($check_asistencia);
            array_pop($asistencia);
        
            $aA = array();
            $ic = 0;
            foreach ($check_asistencia as $c ) {
                $aA[$c] = $asistencia[$ic];
                $ic++;  
            }

            $fecha_actual = Date('Y-m-d');
            $hora_actula = Date('H:i:s'); 
            $cont = count($check_asistencia);
            //$contador = 0;
            $contador_insert = 0;
            $cad_array = "";

            //var_dump($hora_actula);die;
            //Variable para recorrer la asistencia mendiante el checkbos
            $ic = 0;
            //Obtenemos la ultima fecha en la que se llamo la asistencia
            $sql_fecha ="SELECT * FROM int_asistencia_estudiante_materia WHERE  aula_paralelo_asignacion_materia_id = '$asignacion_mat_id'";
            $res_fecha = $db->query($sql_fecha)->fetch_first();

            
            if($res_fecha != null){
                $respuesta = array(
                    'estado' => 'a',				 
                );
                //var_dump($res_fecha);die;
            }else{
                //if(count($check_asistencia)>0){
                    for ($i=0; $i < $cont; $i++) {
                     
                        $sql_consultar ="SELECT * FROM int_asistencia_estudiante_materia WHERE  estudiante_id = '$check_asistencia[$i]' AND aula_paralelo_asignacion_materia_id = '$asignacion_mat_id'";
                        $proceso = $db->query($sql_consultar)->fetch_first();
                        
                        $cad_array = $fecha_actual . " " . $hora_actula . "@" . $aA[$check_asistencia[$i]] . ",";
                        //var_dump($cad_array);die;
                        if($proceso != null){

                        }else{
                            //$cad_array = $fecha_actual . "," . $;
                            $sql_AEM = "INSERT INTO int_asistencia_estudiante_materia (estudiante_id, modo_calificacion_id, json_asistencia, aula_paralelo_asignacion_materia_id, gestion_id) VALUES ('$check_asistencia[$i]', '$modo_calificacion_id','$cad_array', '$asignacion_mat_id', $id_gestion)";
                            $proceso = $db->query($sql_AEM)->execute();
                            $contador_insert = $contador_insert + 1;
                        }
                        //var_dump($proceso);die;
                    }
                    //var_dump($check_asistencia[$i]);exit();

                    //verifica que todos los registros se hayan guardado
                    if($cont == $contador_insert){
                        $respuesta = array(
                            'estado' => 's',
				            'nota' => 'Exito al guardar' 
                        );
                    }else{
                        $respuesta = array(
                            'estado' => 'x',				 
                        );
                    }          
            }        
			echo json_encode($respuesta);
		} else {
			// Devuelve los resultados
			echo json_encode(array('estado' => 'n'));
		}
	} else {
		// Devuelve los resultados
		echo json_encode(array('estado' => 'u'));
	}
} else {
	// Devuelve los resultados
	echo json_encode(array('estado' => 'p'));
}

?>