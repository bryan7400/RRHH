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

        // Verifica la existencia de datos
        // if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
        //Obtiene los datos
        $usuario     = clear($_POST['usuario']);
        $contrasenia = clear($_POST['contrasenia']);
        $id_persona     = clear($_POST['id_persona']);

        /* $usuario ='martha';
        $contrasenia ='martha2019';
         $id_user = 5; */
        // Encripta la contraseña para compararla en la base de datos
        $usuario = md5($usuario);
        $contrasenia = encrypt($contrasenia); 

        // Obtiene los datos del usuario
        $usuario = $db->select('id_user')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();

        // Verifica la existencia del usuario 
        if ($usuario) {

            // Obtiene los productos
            $cursos_asignados = $db->query("SELECT ni.nombre_nivel, pa.nombre_paralelo,mat.*,apam.*,au.* FROM pro_materia mat 
	INNER JOIN int_aula_paralelo_asignacion_materia apam ON apam.materia_id=mat.id_materia
	INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=apam.aula_paralelo_id
	INNER JOIN ins_aula au ON au.id_aula=ap.aula_id
    INNER JOIN ins_nivel_academico ni ON ni.id_nivel_academico=au.nivel_academico_id
    INNER JOIN ins_paralelo pa ON pa.id_paralelo=ap.paralelo_id
	INNER JOIN per_asignaciones asi ON asi.id_asignacion=apam.asignacion_id
	INNER JOIN sys_persona pe ON asi.persona_id=pe.id_persona 
	WHERE asi.persona_id=$id_persona")->fetch();
            
            
            
            /*     $cursos_asignados = $db->query("SELECT ap_pm.id_aula_paralelo_profesor_materia,
                                                ap_pm.id_aula_paralelo,
                                                ap_pm.id_profesor_materia,
                                                ap_pm.id_profesor,
                                                ap_pm.nombre_aula,
                                                ap_pm.nombre_paralelo,
                                                na.nombre_nivel,
                                                ap_pm.nombre_materia,
                                                t.nombre_turno,
                                                ap_pm.imagen,
                                                ap_pm.capacidad
                                            FROM sys_users us 
                                            LEFT JOIN sys_persona per ON per.id_persona = us.persona_id
                                            LEFT JOIN pro_profesor pro ON pro.persona_id = per.id_persona
                                            LEFT JOIN vista_aula_paralelo_profesor_materia ap_pm ON ap_pm.id_profesor = pro.id_profesor
                                            INNER JOIN ins_aula_paralelo ap ON ap_pm.id_aula_paralelo = ap.id_aula_paralelo
                                            INNER JOIN ins_aula a ON ap.aula_id = a.id_aula                                        
                                            INNER JOIN ins_nivel_academico na ON na.id_nivel_academico = a.nivel_academico_id
                                            INNER JOIN ins_turno t ON ap.turno_id = t.id_turno 
                                            WHERE us.id_user = '" . $id_user. "'
                                            ORDER BY ap_pm.nombre_aula ASC")->fetch();*/
            // var_dump($estudiantes_cursos);exit();
            // Instancia el objeto
            $respuesta = array(
                'estado' => 's',
                'cursos_asignados' => $cursos_asignados
            );

            // Devuelve los resultados
            echo json_encode($respuesta);
        } else {
            // Devuelve los resultados
            echo json_encode(array('estado' => 'n login'));
        }
 }else{
     // Devuelve los resultados
    echo json_encode(array('estado' => 'n post'));
 }
