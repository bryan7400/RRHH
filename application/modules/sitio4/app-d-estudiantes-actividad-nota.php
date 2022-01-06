<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  WEB SERVICES
 * @author  Laiwett Oma 
 */

// Define las cabeceras
header('Content-Type: application/json');

// Verifica la peticion post
if (is_post()) {
    // Verifica la existencia de datos
    if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
        // Obtiene los datos
        $usuario     = clear($_POST['usuario']);
        $contrasenia = clear($_POST['contrasenia']);
        $usuario     = md5($usuario);
        $contrasenia = encrypt($contrasenia);

        $id_gestion = clear($_POST['id_gestion']);

        $usuario = $db->select('persona_id,id_user, username, email, avatar, rol_id, visible')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();
        // Verifica la existencia del usuario
        if ($usuario) {

            $id_usuario                = $usuario['id_user'];
            $id_asignacion_docente     = $_POST['id_asignacion_docente'];
            $id_asesor_curso_actividad = $_POST['id_asesor_curso_actividad'];
            $estado_curso              = $_POST['estado_curso'];
            
            // obtenemos la fecha y hora actual del sistema
            $fechaHoraActual = date('Y-m-d H:i:s');
            $fecha = date('Y-m-d');
            $hora = date('H:i:s');
            
            
            //URL de acceso a los archivos
            // $url_ip = escape($_institution['url_estudiante']);
            
            //Verificamos que se controle por fecha limite
            $con_fecha_limite = "SI";//escape($_institution['pv_fecha_limite']);
            
            //Estado del ponderado de la calificacion
            $ponderado_calificacion = "A"; //escape($_institution['ponderado_calificacion']);



            $pregunta_area = $db->query("SELECT pam.id_asignacion_docente, cac.obtencion_nota, pam.materia_id , pam.aula_paralelo_id, taca.estado_curso, pm.campo_area, cac.descripcion, cac.ponderado
            FROM tem_asesor_curso_actividad AS taca
            INNER JOIN cal_area_calificacion AS cac ON cac.id_area_calificacion = taca.area_calificacion_id
            INNER JOIN pro_asignacion_docente AS pam ON pam.id_asignacion_docente = taca.asignacion_docente_id
            INNER JOIN pro_materia AS pm ON pm.id_materia = pam.materia_id
            WHERE taca.id_asesor_curso_actividad = $id_asesor_curso_actividad AND pam.estado_docente = 'A' AND cac.estado = 'A' AND taca.estado_actividad = 'A'")->fetch_first();
            
            // echo $pregunta_area['estado_curso'];
            // exit();
    
            //$estudiantes_cursos = $db->query("SELECT  z.estudiante_id, p.id_persona,p.nombres,p.primer_apellido,p.segundo_apellido,p.tipo_documento,p.numero_documento,p.complemento,p.expedido,p.genero,p.fecha_nacimiento,IF(p.foto != 'NULL', IF(p.foto !='',p.foto,''),'')AS foto
            if ($pregunta_area['estado_curso'] == 'E') {
                $id_asignacion = $pregunta_area['id_asignacion_docente'];
                $estudiantes_area = $db->query("SELECT e.id_estudiante, CONCAT(p.primer_apellido,' ',p.segundo_apellido,' ',p.nombres) AS nombre_completo, IF(p.foto != 'NULL', IF(p.foto !='',p.foto,''),'')AS foto
                                        FROM ext_curso_asignacion AS ca
                                        INNER JOIN ext_curso_inscripcion AS ci ON ci.curso_asignacion_id = ca.id_curso_asignacion   
                                        INNER JOIN ins_estudiante AS e ON e.id_estudiante = ci.estudiante_id
                                        INNER JOIN sys_persona AS p ON p.id_persona = e.persona_id 
                                        WHERE ca.id_curso_asignacion = $id_asignacion AND ci.estado = 'A' AND ci.gestion_id = $id_gestion
                                        ORDER BY p.primer_apellido ASC")->fetch();

                $datos_materia = $db->query("SELECT aca.tipo_actividad, aca.nombre_actividad, aca.descripcion_actividad, aca.fecha_presentacion_actividad, aca.hora_fin, CONCAT(p.nombres,' ',p.primer_apellido,' ',p.segundo_apellido) AS nombre_completo, p.foto, c.nombre_curso as nombre_materia , c.nombre_detalle AS curso, ca.id_curso_asignacion AS id_aula_paralelo 
						FROM tem_asesor_curso_actividad AS aca
						INNER JOIN ext_curso_asignacion AS ca ON ca.id_curso_asignacion = aca.asignacion_docente_id
						INNER JOIN ext_curso AS c ON c.id_curso = ca.curso_id
						INNER JOIN per_asignaciones AS pa ON pa.id_asignacion = ca.asignacion_id
						INNER JOIN sys_persona AS p ON p.id_persona = pa.persona_id                  
						WHERE aca.id_asesor_curso_actividad = $id_asesor_curso_actividad")->fetch_first();

                $tipo_calificacion = "CUANTITATIVO";
                // echo "<pre>";
                // var_dump($estudiantes_area);
                // echo "</pre>";
                // exit();

            } else {
                $datos_materia = $db->query("SELECT aca.tipo_actividad, aca.nombre_actividad, aca.descripcion_actividad, aca.fecha_presentacion_actividad, aca.hora_fin, CONCAT(p.nombres,' ',p.primer_apellido,' ',p.segundo_apellido) AS nombre_completo, p.foto, pm.nombre_materia , CONCAT(ia.nombre_aula,' ',ip.nombre_paralelo,' ',ina.nombre_nivel) AS curso, iap.id_aula_paralelo AS id_aula_paralelo, ina.tipo_calificacion 
						FROM tem_asesor_curso_actividad AS aca
						INNER JOIN pro_asignacion_docente AS ad ON ad.id_asignacion_docente = aca.asignacion_docente_id
						INNER JOIN per_asignaciones AS pa ON pa.id_asignacion = ad.asignacion_id
						INNER JOIN sys_persona AS p ON p.id_persona = pa.persona_id
						INNER	JOIN	pro_materia AS pm ON pm.id_materia = ad.materia_id
						INNER JOIN	ins_aula_paralelo AS iap ON iap.id_aula_paralelo = ad.aula_paralelo_id
						INNER JOIN  ins_aula AS ia ON ia.id_aula = iap.aula_id
						INNER JOIN  ins_nivel_academico AS	ina ON ina.id_nivel_academico = ia.nivel_academico_id 
						INNER JOIN  ins_paralelo AS ip ON ip.id_paralelo = iap.paralelo_id
						WHERE aca.id_asesor_curso_actividad = $id_asesor_curso_actividad")->fetch_first();

                $tipo_calificacion = $datos_materia['tipo_calificacion'];
                //if ($pregunta_area['materia_id'] >= 1 && $pregunta_area['materia_id'] <= 4) {
                if ($pregunta_area['campo_area'] == "SI") {
                    //si esta entre el rango de 1 y 4 es una area
                    // 1 : CONTRUCCION
                    // 2 : CONTABILIDAD
                    // 3 : INFORMATICA
                    // 4 : SALUD
                    $id_aula_paralelo = $pregunta_area['aula_paralelo_id'];
                    $area = "";
                    switch ($pregunta_area['materia_id']) {
                        case '1':
                            $area = "CONSTRUCCION";
                            break;
                        case '2':
                            $area = "CONTABILIDAD";
                            break;
                        case '3':
                            $area = "INFORMATICA";
                            break;
                        case '4':
                            $area = "SALUD";
                            break;
                        default:
                            $area = "";
                            break;
                    }

                    // Listamos a todos los estudiantes del area
                    $estudiantes_area = $db->query("SELECT e.id_estudiante, CONCAT(p.primer_apellido,' ',p.segundo_apellido,' ',p.nombres) AS nombre_completo, IF(p.foto != 'NULL', IF(p.foto !='',p.foto,''),'')AS foto
                                    FROM ins_inscripcion AS i
                                    INNER JOIN ins_estudiante AS e ON e.id_estudiante = i.estudiante_id
                                    INNER JOIN sys_persona AS p ON p.id_persona = e.persona_id 
                                    WHERE i.aula_paralelo_id = '$id_aula_paralelo' AND i.estado = 'A' AND i.gestion_id = '$id_gestion' AND area= '$area'
                                    ORDER BY p.primer_apellido ASC")->fetch();
                } else {
                    $id_aula_paralelo = $pregunta_area['aula_paralelo_id'];
                    //Aca se realiza las actividades normales
                    $estudiantes_area = $db->query("SELECT e.id_estudiante, CONCAT(p.primer_apellido,' ',p.segundo_apellido,' ',p.nombres) AS nombre_completo, IF(p.foto != 'NULL', IF(p.foto !='',p.foto,''),'')AS foto
                                    FROM ins_inscripcion AS i
                                    INNER JOIN ins_estudiante AS e ON e.id_estudiante = i.estudiante_id
                                    INNER JOIN sys_persona AS p ON p.id_persona = e.persona_id 
                                    WHERE i.aula_paralelo_id = '$id_aula_paralelo' AND i.estado = 'A' AND i.gestion_id = '$id_gestion'
                                    ORDER BY p.primer_apellido ASC")->fetch();
                }
            }


            $actividades = $db->query("SELECT *
                    FROM tem_asesor_curso_actividad aca
                    WHERE aca.id_asesor_curso_actividad=$id_asesor_curso_actividad
                    ORDER BY aca.fecha_presentacion_actividad ASC")->fetch_first();
            //var_dump($actividades);exit();

            $estudiantes_presentaron = $db->query("SELECT eca.id_estudiante_curso_actividad, e.id_estudiante, eca.archivo, eca.nota, eca.nota_cualitativa, eca.estado_calificado, eca.observacion_docente , eca.fecha_registro, eca.hora_registro , CONCAT(p.primer_apellido,' ',p.segundo_apellido,' ',p.nombres) AS nombre_completo, eca.estado_presentacion
                                FROM tem_estudiante_curso_actividad eca
                                INNER JOIN ins_estudiante AS e ON e.id_estudiante = eca.estudiante_id
                                INNER JOIN sys_persona AS p ON p.id_persona = e.persona_id
                                WHERE eca.asesor_curso_actividad_id = $id_asesor_curso_actividad
                                ORDER BY eca.id_estudiante_curso_actividad ASC, p.primer_apellido ASC")->fetch();

            // echo "<pre>";
            // var_dump($estudiantes_presentaron);
            // echo "</pre>";
            // exit();


            //DATOS DE LOS ESTUDIANTES
            $fecha_actual = strtotime($fechaHoraActual);
            $fecha_presentacion = strtotime($datos_materia['fecha_presentacion_actividad'] . " " . $datos_materia['hora_fin']);


            $tipo_de_obtendion = $pregunta_area['obtencion_nota'];
            $area_de_calificacion = $pregunta_area['descripcion'];

            // A.  Ponderado normal  P. Ponderado al 100
            if ($ponderado_calificacion == "P") {
                $ponderado         = 100;
            } else {
                $ponderado         = $pregunta_area['ponderado'];
            }


            if ($pregunta_area['obtencion_nota'] == "E") {
                if ($fecha_actual > $fecha_presentacion) {

                    $estudiantes_area_materia = array();
                    $i = 0;
                    //REVISAMOS TODAS LAS TAREAS DEL CURSO
                    foreach ($estudiantes_area as $de => $estudiante) {

                        $estudiantes_area_materia[] = $estudiante;
                        //datos de los que si presentaron la tarea
                        $sw = 0;
                        foreach ($estudiantes_presentaron as $e => $valor) {

                            if ($estudiante['id_estudiante'] == $valor['id_estudiante']) {
                                $sw = 1;
                                //preguntamos si el estudiante presento la actividad
                                if ($valor['estado_presentacion'] != "") {

                                    $estudiantes_area_materia[$i]["id_estudiante_curso_actividad"] = $valor['id_estudiante_curso_actividad'];
                                    $estudiantes_area_materia[$i]["fecha_hora_registro"] = $valor['fecha_registro'] . " " . $valor['hora_registro'];

                                    if ($valor['archivo'] != "") {
                                        $html = "";
                                        $estudiantes_area_materia[$i]["archivo"] = $html;
                                    } else {
                                        $estudiantes_area_materia[$i]["archivo"] = "Presento sin Archivo(s)";
                                    }
                                    $estudiantes_area_materia[$i]["nota"] =  $valor['nota'];
                                    $estudiantes_area_materia[$i]["nota_cualitativa"] =  $valor['nota_cualitativa'];
                                    $estudiantes_area_materia[$i]["observacion_docente"] = $valor['observacion_docente'];
                                    $estudiantes_area_materia[$i]["estado_presentacion"] = $valor['estado_presentacion'];
                                } else {
                                    if ($con_fecha_limite == "SI") {
                                        $estudiantes_area_materia[$i]["id_estudiante_curso_actividad"] = $valor['id_estudiante_curso_actividad'];
                                        $estudiantes_area_materia[$i]["fecha_hora_registro"] = $valor['fecha_registro'] . " " . $valor['hora_registro'];
                                        $estudiantes_area_materia[$i]["archivo"] = "NO PRESENTO";
                                        $estudiantes_area_materia[$i]["nota"] =  $valor['nota'];
                                        $estudiantes_area_materia[$i]["nota_cualitativa"] =  $valor['nota_cualitativa'];
                                        $estudiantes_area_materia[$i]["observacion_docente"] = $valor['observacion_docente'];
                                        $estudiantes_area_materia[$i]["estado_presentacion"] = $valor['estado_presentacion'];
                                    } else if ($con_fecha_limite == "NO") {
                                        $estudiantes_area_materia[$i]["id_estudiante_curso_actividad"] = "0";
                                        $estudiantes_area_materia[$i]["fecha_hora_registro"] = "PENDIENTE";
                                        $estudiantes_area_materia[$i]["archivo"] = "PENDIENTE";
                                        $estudiantes_area_materia[$i]["nota"] = "";
                                        $estudiantes_area_materia[$i]["nota_cualitativa"] = "";
                                        $estudiantes_area_materia[$i]["observacion_docente"] = "";
                                        $estudiantes_area_materia[$i]["estado_presentacion"] = $valor['estado_presentacion'];
                                    }
                                }
                            }
                        }

                        //verificamos que mando o no la tarea
                        if ($sw == 0) {
                            // añadimos al estudiante como no entregado la tarea
                            // pero verificamos que las actividades sean controladas por tiempo o no
                            if ($con_fecha_limite == "SI") {
                                $tem_estudiante_curso_actividad = array(
                                    'estudiante_id' => $estudiante['id_estudiante'],
                                    'archivo' => "",
                                    'asesor_curso_actividad_id' => $id_asesor_curso_actividad,
                                    'fecha_registro' => $fecha,
                                    'hora_registro' => $hora,
                                    'nota' => "0",
                                    'nota_cualitativa' => "",
                                    'usuario_registro' => $id_usuario,
                                    'estado_calificado' => "NO",
                                    'observacion_docente' => "No presento, por tiempo limite",
                                    'ver_nota' => "NO",
                                    'estado_presentacion' => "NO"
                                );
                                $id_temp_estudiante_curso_actividad = $db->insert('tem_estudiante_curso_actividad', $tem_estudiante_curso_actividad);

                                $estudiantes_area_materia[$i]["id_estudiante_curso_actividad"] = $id_temp_estudiante_curso_actividad;
                                $estudiantes_area_materia[$i]["fecha_hora_registro"] = "Sin registro";
                                $estudiantes_area_materia[$i]["archivo"] = "NO PRESENTO";
                                $estudiantes_area_materia[$i]["nota"] = "";
                                $estudiantes_area_materia[$i]["nota_cualitativa"] = "";
                                $estudiantes_area_materia[$i]["observacion_docente"] = "No presento, por tiempo limite";
                                $estudiantes_area_materia[$i]["estado_presentacion"] = "NO";

                                // Guarda el proceso
                                $db->insert('sys_procesos_virtual', array(
                                    'fecha_proceso' => date('Y-m-d'),
                                    'hora_proceso' => date('H:i:s'),
                                    'proceso' => 'c',
                                    'nivel' => 'l',
                                    'detalle' => 'Se creó respuesta de actividad no presento con identificador número ' . $id_temp_estudiante_curso_actividad . '.',
                                    'direccion' => $_location,
                                    'usuario_id' => $id_usuario
                                ));
                            } else if ($con_fecha_limite == "NO") {

                                $estudiantes_area_materia[$i]["id_estudiante_curso_actividad"] = "0";
                                $estudiantes_area_materia[$i]["fecha_hora_registro"] = "Sin registro";
                                $estudiantes_area_materia[$i]["archivo"] = "PENDIENTE CON RETRASO";
                                $estudiantes_area_materia[$i]["nota"] = "";
                                $estudiantes_area_materia[$i]["nota_cualitativa"] = "";
                                $estudiantes_area_materia[$i]["observacion_docente"] = "";
                                $estudiantes_area_materia[$i]["estado_presentacion"] = "RETRASO";
                            }
                        }
                        $i++;
                    }
                } else {
                    //echo "HOLA MARIBEL";
                    $estudiantes_area_materia = array();
                    $i = 0;
                    foreach ($estudiantes_area as $key => $value) {
                        $estudiantes_area_materia[] = $value;
                        //datos de los que si presentaron la tarea
                        $sw = 0;
                        foreach ($estudiantes_presentaron as $e => $valor) {
                            if ($value['id_estudiante'] == $valor['id_estudiante']) {
                                $estudiantes_area_materia[$i]["id_estudiante_curso_actividad"] = $valor['id_estudiante_curso_actividad'];

                                $estudiantes_area_materia[$i]["fecha_hora_registro"] = $valor['fecha_registro'] . " " . $valor['hora_registro'];
                               
                                $docs = substr($valor['archivo'], 0, -1);
                                if ($docs != "") {
                                    $html =  $docs ;
                                } else {
                                    $html = "PENDIENTE";
                                }

                                $estudiantes_area_materia[$i]["archivo"] = $html;
                                $estudiantes_area_materia[$i]["nota"] =  $valor['nota'];
                                $estudiantes_area_materia[$i]["nota_cualitativa"] =  $valor['nota_cualitativa'];
                                $estudiantes_area_materia[$i]["observacion_docente"] = $valor['observacion_docente'];
                                $estudiantes_area_materia[$i]["estado_presentacion"] = "PUNTUAL";


                                $sw = 1;
                            }
                        }
                        if ($sw == 0) {
                            $estudiantes_area_materia[$i]["id_estudiante_curso_actividad"] = "0";
                            $estudiantes_area_materia[$i]["fecha_hora_registro"] = "";
                            $estudiantes_area_materia[$i]["archivo"] = "PENDIENTE";
                            $estudiantes_area_materia[$i]["nota"] = "0";
                            $estudiantes_area_materia[$i]["nota_cualitativa"] = "";
                            $estudiantes_area_materia[$i]["observacion_docente"] = "";
                            $estudiantes_area_materia[$i]["estado_presentacion"] = "";
                        }
                        $i++;
                    }
                }
            } else {

                //Cuando solo calificara los docentes y no corresponde una respuesta
                $estudiantes_area_materia = array();
                $i = 0;
                //REVISAMOS TODAS LAS TAREAS DEL CURSO
                foreach ($estudiantes_area as $de => $estudiante) {

                    $estudiantes_area_materia[] = $estudiante;
                    //datos de los que si presentaron la tarea
                    $sw = 0;
                    foreach ($estudiantes_presentaron as $e => $valor) {

                        if ($estudiante['id_estudiante'] == $valor['id_estudiante']) {
                            $sw = 1;
                            //preguntamos si el estudiante presento la actividad
                            if ($valor['estado_presentacion'] != "") {
                                $estudiantes_area_materia[$i]["id_estudiante_curso_actividad"] = $valor['id_estudiante_curso_actividad'];
                                $estudiantes_area_materia[$i]["fecha_hora_registro"] = $valor['fecha_registro'] . " " . $valor['hora_registro'];

                                if ($valor['archivo'] != "") {
                                    $html = $valor['archivo'];
                                    $estudiantes_area_materia[$i]["archivo"] = $html;
                                } else {
                                    $estudiantes_area_materia[$i]["archivo"] = "PRESENTO SIN ARCHIVO(S)";
                                }

                                $estudiantes_area_materia[$i]["nota"] =  $valor['nota'];
                                $estudiantes_area_materia[$i]["nota_cualitativa"] =  $valor['nota_cualitativa'];
                                $estudiantes_area_materia[$i]["observacion_docente"] = $valor['observacion_docente'];
                                $estudiantes_area_materia[$i]["estado_presentacion"] = $valor['estado_presentacion'];
                            } else {
                                if ($con_fecha_limite == "SI") {
                                    $estudiantes_area_materia[$i]["id_estudiante_curso_actividad"] = $valor['id_estudiante_curso_actividad'];
                                    $estudiantes_area_materia[$i]["fecha_hora_registro"] = $valor['fecha_registro'] . " " . $valor['hora_registro'];
                                    $estudiantes_area_materia[$i]["archivo"] = "NO PRESENTO";
                                    $estudiantes_area_materia[$i]["nota"] =  $valor['nota'];
                                    $estudiantes_area_materia[$i]["nota_cualitativa"] =  $valor['nota_cualitativa'];
                                    $estudiantes_area_materia[$i]["observacion_docente"] = $valor['observacion_docente'];
                                    $estudiantes_area_materia[$i]["estado_presentacion"] = $valor['estado_presentacion'];
                                } else if ($con_fecha_limite == "NO") {
                                    $estudiantes_area_materia[$i]["id_estudiante_curso_actividad"] = "0";
                                    $estudiantes_area_materia[$i]["fecha_hora_registro"] = "PENDIENTE";
                                    $estudiantes_area_materia[$i]["archivo"] = "PENDIENTE";
                                    $estudiantes_area_materia[$i]["nota"] = "";
                                    $estudiantes_area_materia[$i]["nota_cualitativa"] =  "";
                                    $estudiantes_area_materia[$i]["observacion_docente"] = "";
                                    $estudiantes_area_materia[$i]["estado_presentacion"] = $valor['estado_presentacion'];
                                }
                            }
                        }
                    }
                    //verificamos que mando o no la tarea
                    if ($sw == 0) {
                        // añadimos al estudiante para la evaluacion de la docente
                        // pero verificamos que las actividades sean controladas por tiempo o no
                        $tem_estudiante_curso_actividad = array(
                            'estudiante_id' => $estudiante['id_estudiante'],
                            'archivo' => "",
                            'asesor_curso_actividad_id' => $id_asesor_curso_actividad,
                            'fecha_registro' => $fecha,
                            'hora_registro' => $hora,
                            'nota' => "0",
                            'nota_cualitativa' =>  "",
                            'usuario_registro' => $id_usuario,
                            'estado_calificado' => "NO",
                            'observacion_docente' => "Calificacion de por parte del docente",
                            'ver_nota' => "NO",
                            'estado_presentacion' => "NO"
                        );
                        $id_temp_estudiante_curso_actividad = $db->insert('tem_estudiante_curso_actividad', $tem_estudiante_curso_actividad);

                        $estudiantes_area_materia[$i]["id_estudiante_curso_actividad"] = $id_temp_estudiante_curso_actividad;
                        $estudiantes_area_materia[$i]["fecha_hora_registro"] = now();
                        $estudiantes_area_materia[$i]["archivo"] = "";
                        $estudiantes_area_materia[$i]["nota"] = "";
                        $estudiantes_area_materia[$i]["nota_cualitativa"] = "";
                        $estudiantes_area_materia[$i]["observacion_docente"] = "";
                        $estudiantes_area_materia[$i]["estado_presentacion"] = "";

                        // Guarda el proceso
                        $db->insert('sys_procesos_virtual', array(
                            'fecha_proceso' => date('Y-m-d'),
                            'hora_proceso' => date('H:i:s'),
                            'proceso' => 'c',
                            'nivel' => 'l',
                            'detalle' => 'Se registro la al estudiante para la evaluacion por parte de docente con id ' . $id_temp_estudiante_curso_actividad . '.',
                            'direccion' => $_location,
                            'usuario_id' => $id_usuario
                        ));
                    }
                    $i++;
                }
            }
            echo json_encode(array('estado' => 's', 'estudiantes' => $estudiantes_area_materia));
            //respuestas :::::::::::::::::::::::::::
            //   echo json_encode($respuesta);
        } else {
            // Devuelve los resultados
            echo json_encode(array('estado' => 'no tiene usuario asignado'));
        }
    } else {
        // Devuelve los resultados
        echo json_encode(array('estado' => 'no hay datos'));
    }
} else {
    // Devuelve los resultados
    echo json_encode(array('estado' => 'n'));
}
