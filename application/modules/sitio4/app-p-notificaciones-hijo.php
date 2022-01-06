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

    //	Verifica la existencia de datos
    if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
        //	Obtiene los datos
        $hoy = date('Y-m-d'); 
        $usuario          = clear($_POST['usuario']);
        $contrasenia      = clear($_POST['contrasenia']);
        $id_aula_paralelo = clear($_POST['id_aula_paralelo']);
        $id_user          = clear($_POST['id_user_estudiante']);
        $rol_actual       = clear($_POST['id_rol_estudiante']);
        $id_gestion       = clear($_POST['id_gestion']);
        $fecha           = (clear($_POST['fecha'])!= "")?$_POST['fecha']:$hoy;
        
        

        // Encripta la contraseña para compararla en la base de datos
        $usuario = md5($usuario);
        $contrasenia = encrypt($contrasenia);

        // Obtiene los datos del usuario
        $usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
        
        // Verifica la existencia del usuario 
        if ($usuario) {
            
              $estudiante=$db->query("SELECT * FROM sys_users ux 
                INNER JOIN sys_persona per ON ux.persona_id=per.id_persona
                INNER JOIN ins_estudiante ie ON ie.persona_id = per.id_persona    
                INNER JOIN ins_inscripcion ins ON ins.estudiante_id=ie.id_estudiante
                INNER JOIN ins_nivel_academico na ON ins.nivel_academico_id = na.id_nivel_academico
                WHERE ux.id_user = $id_user AND ins.gestion_id = $id_gestion ")->fetch_first();
            
                $id_estudiante    = $estudiante['id_estudiante'];
                $id_aula_paralelo = $estudiante['aula_paralelo_id'];
                $id_nivel_academico = $estudiante['nivel_academico_id'];
                $acronimo			= $estudiante['acronimo_nivel'];
                $area             = $estudiante['area'];
                $genero           = $estudiante['genero']; 
            
            $asigancion_docentes = $db->query("SELECT *
                FROM pro_asignacion_docente where aula_paralelo_id=$id_aula_paralelo")->fetch();
            
            //armando para la busqueda de diferentes docentes
            $sqlAsignacionesdocente = '(com.`asignacion_docente_id`= 0';
            //$estadoarmad=true;
            foreach ($asigancion_docentes as $key => $rows) {
                $sqlAsignacionesdocente .= ' or  com.`asignacion_docente_id`=' . $rows['id_asignacion_docente'];
            }
            $sqlAsignacionesdocente .= ')';

            $comunicados = $db->query("SELECT com.*,su.username 
                            FROM ins_comunicados com  
                            LEFT JOIN sys_users su ON su.id_user=com.usuario_registro 
                            WHERE DATE(com.fecha_registro) = '$fecha' AND (com.persona_id LIKE '%,$id_user,%' OR
                            com.usuarios LIKE '%,$rol_actual,%' OR
                            com.grupo='t' OR com.grupo='$genero')AND 
                            com.fecha_final>=date_sub(CURDATE(), INTERVAL 2 DAY) and
                            com.estado='A' and  $sqlAsignacionesdocente
                            ORDER BY com.fecha_inicio desc")->fetch(); 

            $com = array();
            foreach ($comunicados as $val) {
                $grupo = $val['grupo']; //tipo todos,si
                $vista_personas_id = $val['vista_personas_id'];
                $usuarios = $val['usuarios'];
                $persona_id = $val['persona_id'];
                $agregado = false;
                if ($grupo == 't' || $grupo == 'm' || $grupo == 'v') {
                    //array_push($com,$val);
                    $agregado = true;
                } else {

                    $arr_p = explode(',', $persona_id);
                    $arr_u = explode(',', $usuarios);

                    if (in_array($id_user, $arr_p) || in_array($rol_actual, $arr_u)) {

                        $agregado = true;
                    }
                }

                if ($agregado) {
                    array_push($com, $val);
                    //MARCAR COMO LEIDOs
                    $arr_v = explode(',', $vista_personas_id);
                    if (!in_array($id_user, $arr_v)) {
                        $id_comunicado = $val['id_comunicado'];
                        $sqlp = "UPDATE ins_comunicados
                SET vista_personas_id = CONCAT(vista_personas_id,'$id_user,')
                where id_comunicado= $id_comunicado;";
                        $leido = $db->query($sqlp)->execute();
                    }
                }
            }


            $respuesta = array(
                'estado' => 's',
                'notificacion' => $com
            );

            // Devuelve los resultados
            echo json_encode($respuesta);
        } else {
            // Devuelve los resultados
            echo json_encode(array('estado' => 'n'));
        }
    } else {
        // Devuelve los resultados
        echo json_encode(array('estado' => 'n usuario'));
    }
} else {
    // Devuelve los resultados
    echo json_encode(array('estado' => 'npost'));
}
