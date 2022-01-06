<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL MARCO LUIS)
 */

// Define las cabeceras
header('Content-Type: application/json');

//Verifica la peticion post
if (is_post()) {

    // Verifica la existencia de datos
    if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
        //Obtiene los datos
        $usuario     = clear($_POST['usuario']);
        $contrasenia = clear($_POST['contrasenia']);
        $id_gestion  = clear($_POST['id_gestion']);
        $id_asignacion_docente = $_POST['id_asignacion_docente'];
        $id_modo_calificacion  = $_POST['id_modo_calificacion'];
        $estudiante  = $_POST['estudiante'];
        $asistencia  = $_POST['asistencia'];
        $indice      = $_POST['indice'];
        $tipo_extra  = $_POST['tipo_extra'];

        if ($tipo_extra == "SI") {
            $estado_curso = "E";
        } else if ($tipo_extra == "NO") {
            $estado_curso = "N";
        }

        $estudiante = substr($estudiante, 0, -1);
        $aEstudiante = explode('@', $estudiante);

        $asistencia = substr($asistencia, 0, -1);
        $aAsistencia = explode('@', $asistencia);
        
        $fecha_asistencia = $_POST['fecha_asistencia'];

        $usuario = md5($usuario);
        $contrasenia = encrypt($contrasenia);
        // Obtiene los datos del usuario
        $usuario = $db->select('*')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
        
        
        $hora_actula  = date('h:i:s');

        // Verifica la existencia del usuario 
        if ($usuario) {
            //Preguntamos las asistencia de los estudiantes y luego verificamos si es UPDATE O INSERT
            for ($i = 0; $i < sizeof($aAsistencia); $i++) {

                $sql_consultar = "SELECT * FROM int_asistencia_estudiante_materia WHERE  estudiante_id = '$aEstudiante[$i]' AND asignacion_docente_id = '$id_asignacion_docente' AND modo_calificacion_id = $id_modo_calificacion AND estado_curso = '$estado_curso'";
                $proceso = $db->query($sql_consultar)->fetch_first();
                $id_asistencia_estudiante_materia = $proceso['id_asistencia_estudiante_materia'];

                if ($proceso) {
                    
                    $jsAsistencia    = $proceso['json_asistencia'];
                    $jsAsistencia    = substr($jsAsistencia, 0, -1);//Aqui quitamos la ultima ',' de la cadena de asistencias (JSON)
                    $a_jsAsistencia  = explode(",", $jsAsistencia);//Separamos la cadena de asistencia por ',' y se convierte en array

                    if (isset($aAsistencia[$indice])) {
                        $cad_asistencia = $fecha_asistencia . " " . $hora_actula . "@" . $aAsistencia[$i];
                        $a_jsAsistencia[$indice] = $cad_asistencia; // Array de la asistencias que teniamos
                        $jsAsistencia = implode(",", $a_jsAsistencia);
                        $jsAsistencia = $jsAsistencia . ",";
                    } else {
                        $cad_asistencia = $fecha_asistencia . " " . $hora_actula . "@" . $aAsistencia[$i] . ",";
                        $jsAsistencia = $jsAsistencia .",". $cad_asistencia;
                    }
                    $sql_AEM = "UPDATE int_asistencia_estudiante_materia SET json_asistencia='$jsAsistencia' WHERE  id_asistencia_estudiante_materia = $id_asistencia_estudiante_materia";
                    $proceso = $db->query($sql_AEM)->execute();

                } else {
                    $cad_array = $fecha_asistencia . " " . $hora_actula . "@" . $aAsistencia[$i] . ",";
                    $jsAsistencia = $cad_array;
                    $sql_AEM = "INSERT INTO int_asistencia_estudiante_materia (estudiante_id, asignacion_docente_id, modo_calificacion_id, json_asistencia, gestion_id, estado_curso) VALUES ('$aEstudiante[$i]', '$id_asignacion_docente', '$id_modo_calificacion','$cad_array', $id_gestion, '$estado_curso')";
                    $proceso = $db->query($sql_AEM)->execute();
                }
            } 
            if($indice > -1){
                echo json_encode(array('estado' => 'e'));
            }else if($indice == -1){
                echo json_encode(array('estado' => 'a')); 
            }            
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
