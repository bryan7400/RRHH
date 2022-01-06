<?php
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   EDUCHECK (MARIBEL MARCO LUIS)
 */


//var_dump($_POST);exit();

// Define las cabeceras
header('Content-Type: application/json');

//Verifica la peticion post
if (is_post()) {

	// Verifica la existencia de datos
	if (isset($_POST['usuario']) && isset($_POST['contrasenia'])) {
	        
	       
		    $id_gestion   = clear($_POST['id_gestion']);    
	        $id_usuario   = clear($_POST['id_usuario']);  
	        $usuario    = md5(isset($_POST['usuario']));
    		$contrasenia = encrypt(isset($_POST['contrasenia']));

		    // Verifica la existencia del usuario 
		    if (true){    
            
            $archivos_permitidos = 0;
    
            // Obtiene los datos
            $id_asesor_curso_actividad = (isset($_POST['id_asesor_curso_actividad_a'])) ? clear($_POST['id_asesor_curso_actividad_a']) : 0;
            $id_area_calificacion 	   = (isset($_POST['id_area_calificacion'])) ? clear($_POST['id_area_calificacion']) : 0;
            $asignacion_docente_id     = (isset($_POST['asignacion_docente_id'])) ? clear($_POST['asignacion_docente_id']) : 0;
            $id_modo_calificacion      = (isset($_POST['id_modo_calificacion'])) ? clear($_POST['id_modo_calificacion']) : 0;
            $id_modo_calificacion_e    = (isset($_POST['id_modo_calificacion_e'])) ? clear($_POST['id_modo_calificacion_e']) : 0;
            $nombre_actividad          = $_POST['nombre_actividad']; //datetime_decode($_POST['fecha_inicio']);
            $descripcion_actividad     = $_POST['descripcion_actividad']; //datetime_decode($_POST['fecha_final']);
            $fecha_presentacion        = (isset($_POST['fecha_presentacion'])) ? (($_POST['fecha_presentacion'] != "") ? ($_POST['fecha_presentacion']) : "0000-00-00") : "0000-00-00";
            $tipo_actividad  		   = (isset($_POST['tipo_actividad'])) ? clear($_POST['tipo_actividad']) : "";
            $utilizar_generador        = (isset($_POST['utilizar_generador'])) ? clear($_POST['utilizar_generador']) : "NO";
            $tipo_extra                = (isset($_POST['tipo_extra'])) ? clear($_POST['tipo_extra']) : "-1";
            $estado_cartilla           = "NO";
    
            //TIPO DE ACTIVIDAD TIPO REUNION
            if ($tipo_actividad == "REUNION" || $tipo_actividad == "EXAMEN") {
                $url_doc = (isset($_POST['url_reunion'])) ? clear($_POST['url_reunion']) : "sin url";
                $url_doc = $url_doc . "@";
            } else {
                $url_doc = "";
                //Armamos el url para guardarlo
                /*for ($i = 1; $i <= 10; $i++) {
                    $url = clear($_POST['url_doc' . $i]);
                    if ($url != "") {
                        $url_doc = $url_doc . $url . "@";
                    }
                }*/
            }
    
    
            $fecha_examen              = (isset($_POST['fecha_examen'])) ? (($_POST['fecha_examen'] != "") ? ($_POST['fecha_examen']) : "0000-00-00") : "0000-00-00";
            $hora_inicio               = (isset($_POST['hora_inicio'])) ? (($_POST['hora_inicio'] != "") ? ($_POST['hora_inicio']) : "00:00:00") : "00:00:00";
            $hora_fin                  = (isset($_POST['hora_final'])) ? (($_POST['hora_final'] != "") ? ($_POST['hora_final']) : "00:00:00") : "00:00:00";
            $presentar_actividad       = (isset($_POST['presentar_actividad'])) ? clear($_POST['presentar_actividad']) : "NO";
    
            // actvidad programable
            $actividad_programable     = (isset($_POST['actividad_programable'])) ? clear($_POST['actividad_programable']) : "NO";
            $fecha_programable         = (isset($_POST['fecha_programable'])) ? (($_POST['fecha_programable'] != "") ? ($_POST['fecha_programable']) : "0000-00-00") : "0000-00-00";
            $hora_programable          = (isset($_POST['hora_programable'])) ? (($_POST['hora_programable'] != "") ? ($_POST['hora_programable']) : "00:00:00") : "00:00:00";
    
    
            if($presentar_actividad == "SI"){
                $estado_cartilla = "SI";
            }
    
            if ($tipo_actividad == "EXAMEN") {
                $presentar_actividad = "SI";
                $estado_cartilla = "SI";
                if($utilizar_generador == "SI"){
                    $url_doc = "";
                    
                }
            }
    
            $area_calificacion = $db->query("SELECT *
                                    FROM cal_area_calificacion AS cac
                                    WHERE cac.estado = 'A' AND cac.id_area_calificacion = $id_area_calificacion")->fetch_first();
    
            if ($area_calificacion['obtencion_nota'] == 'D') {
                $presentar_actividad = "SI";
                $estado_cartilla = "SI";
            }
    
            if ($tipo_actividad == "REUNION") {
                $presentar_actividad = "NO";
                $estado_cartilla = "NO";
            }
    
            //_________ manejo de archivo ________
            
            $documentos_actividad = "";
            $documentos_nuevos = "";
          
            //_____________________________________
    
                //var_dump($comunicados);die;
                // Verifica si es creacion o modificacion
                // mayor a 0 creacion
                if ($id_asesor_curso_actividad > 0) {
    
                    $asesor_curso_actividad = array(
                        'asignacion_asesor_id' => "0",
                        'asignacion_docente_id' => $asignacion_docente_id,
                        'modo_calificacion_id' => $id_modo_calificacion_e,
                        'area_calificacion_id' => $id_area_calificacion,
                        'materia_id' => "0",
                        'archivo' => $documentos_actividad,
                        'url_actividad' => $url_doc,
                        'nombre_actividad' => $nombre_actividad,
                        'descripcion_actividad' => $descripcion_actividad,
                        'fecha_presentacion_actividad' => $fecha_presentacion,
                        'modo_presentacion' => "0",
                        'fecha_registro' => Date('Y-m-d H:i:s'),
                        'usuario_registro' => $id_usuario,
                        'fecha_modificacion' => "0000-00-00",
                        'usuario_modificacion' => "0",
                        'estado_actividad' => 'A',
                        'tipo_actividad' => $tipo_actividad,
                        'fecha_examen' => "0000-00-00",
                        'hora_inicio' => "00:00:00",
                        'hora_fin' => $hora_fin,
                        'presentar_actividad' => $presentar_actividad,
                        'imagen' => "0",
                        'generador_examen' => $utilizar_generador,
                        'actividad_programable' => $actividad_programable,
                        'fecha_programable_actividad' => $fecha_programable,
                        'hora_programable_actividad' => $hora_fin
                    );
                    //:::::::::::::::  UPDATE  :::::::::::::::::
                    // Modifica el comunidados
                    $db->where('id_asesor_curso_actividad', $id_asesor_curso_actividad)->update('tem_asesor_curso_actividad', $asesor_curso_actividad);
    
                    // Guarda el proceso
                    $db->insert('sys_procesos', array(
                        'fecha_proceso' => date('Y-m-d'),
                        'hora_proceso' => date('H:i:s'),
                        'proceso' => 'u',
                        'nivel' => 'l',
                        'detalle' => 'Se modificó el la actividad con identificador número ' . $id_asesor_curso_actividad . '.',
                        'direccion' => $_location,
                        'usuario_id' => $id_usuario
                    ));
                    //Realizamos una consulta para poder 			
                    $resp['resp'] = "2"; // 2 es editar
                    $resp['archivos'] = $documentos_nuevos;
                    echo json_encode($resp);
                } else {
    
                    //Verificamos en donde crear la actividad ya sea en actividades normales o extracurriculares
                    
                    if ($tipo_extra == "SI") {
                        $asesor_curso_actividad = array(
                            'asignacion_asesor_id' => "0",
                            'asignacion_docente_id' => $asignacion_docente_id,
                            'modo_calificacion_id' => $id_modo_calificacion,
                            'area_calificacion_id' => $id_area_calificacion,
                            'materia_id' => "0",
                            'archivo' => $documentos_actividad,
                            'url_actividad' => $url_doc,
                            'nombre_actividad' => $nombre_actividad,
                            'descripcion_actividad' => $descripcion_actividad,
                            'fecha_presentacion_actividad' => $fecha_presentacion,
                            'modo_presentacion' => "0",
                            'fecha_registro' => Date('Y-m-d H:i:s'),
                            'usuario_registro' => $id_usuario,
                            'fecha_modificacion' => "0000-00-00",
                            'usuario_modificacion' => "0",
                            'estado_actividad' => 'A',
                            'estado_curso' => 'E',
                            'tipo_actividad' => $tipo_actividad,
                            'fecha_examen' => "0000-00-00",
                            'hora_inicio' => "00:00:00",
                            'hora_fin' => $hora_fin,
                            'presentar_actividad' => $presentar_actividad,
                            'generador_examen' => $utilizar_generador,
                            'imagen' => "0",
                            'actividad_programable' => $actividad_programable,
                            'fecha_programable_actividad' => $fecha_programable,
                            'hora_programable_actividad' => $hora_fin,
                            'estado_bloqueo' => 'NO',
                            'estado_cartilla' => $estado_cartilla
                            
                            
                        );
                    } else {
                        $asesor_curso_actividad = array(
                            'asignacion_asesor_id' => "0",
                            'asignacion_docente_id' => $asignacion_docente_id,
                            'modo_calificacion_id' => $id_modo_calificacion,
                            'area_calificacion_id' => $id_area_calificacion,
                            'materia_id' => "0",
                            'archivo' => $documentos_actividad,
                            'url_actividad' => $url_doc,
                            'nombre_actividad' => $nombre_actividad,
                            'descripcion_actividad' => $descripcion_actividad,
                            'fecha_presentacion_actividad' => $fecha_presentacion,
                            'modo_presentacion' => "0",
                            'fecha_registro' => Date('Y-m-d H:i:s'),
                            'usuario_registro' => $id_usuario,
                            'fecha_modificacion' => "0000-00-00",
                            'usuario_modificacion' => "0",
                            'estado_actividad' => 'A',
                            'estado_curso' => 'N',
                            'tipo_actividad' => $tipo_actividad,
                            'fecha_examen' => "0000-00-00",
                            'hora_inicio' => "00:00:00",
                            'hora_fin' => $hora_fin,
                            'presentar_actividad' => $presentar_actividad,
                            'imagen' => "0",
                            'generador_examen' => $utilizar_generador,
                            'actividad_programable' => $actividad_programable,
                            'fecha_programable_actividad' => $fecha_programable,
                            'hora_programable_actividad' => $hora_fin,
                            'estado_bloqueo' => 'NO',
                            'estado_cartilla' => $estado_cartilla
                        );
                    }
    
                    
                    //:::::::::::::::  CREATE  :::::::::::::::::
                    // Crea el comunidados
                    $id_temp_asesor_curso_actividad = $db->insert('tem_asesor_curso_actividad', $asesor_curso_actividad);
    
                    // Guarda el proceso
                    $db->insert('sys_procesos_virtual', array(
                        'fecha_proceso' => date('Y-m-d'),
                        'hora_proceso' => date('H:i:s'),
                        'proceso' => 'c',
                        'nivel' => 'l',
                        'detalle' => 'Se creó el documento subido desde la app con identificador número ' . $id_temp_asesor_curso_actividad . '.',
                        'direccion' => $_location,
                        'usuario_id' => $id_usuario
                    ));
    
                    //Verificamos si la actividad es de tipo examen para guardar en la tabla temp_curso_actividad_evaluacion
                    if ($tipo_actividad == "EXAMEN" && $utilizar_generador == "SI") {
                        $curso_actividad_evaluacion = array(
                            'asesor_curso_actividad_id' => $id_temp_asesor_curso_actividad,
                            'codigo_evaluacion' => "0000",
                            'link_evaluacion' => "https://",
                            'nota_evaluacion' => 0.0,
                            'fecha_evaluacion' => $fecha_presentacion,
                            'hora_evaluacion' => $hora_fin,
                            'con_tiempo' => "NO",
                            'tiempo_limite' => "0",
                            'fecha_registro' =>  Date('Y-m-d H:i:s'),
                            'fecha_modificacion' => "0000-00-00 00:00:00",
                            'estado_activacion' => "P",
                            'fecha_expiracion_evaluacion' => "0000-00-00",
                            'estado' => "A"
                        );
                        
                        $id_curso_actividad_evaluacion = $db->insert('temp_curso_actividad_evaluacion', $curso_actividad_evaluacion);
    
                        // Guarda el proceso de que se creo la evaluacion
                        $db->insert('sys_procesos_virtual', array(
                            'fecha_proceso' => date('Y-m-d'),
                            'hora_proceso' => date('H:i:s'),
                            'proceso' => 'c',
                            'nivel' => 'l',
                            'detalle' => 'Se creó el examen subido con identificador número ' . $id_curso_actividad_evaluacion . '.',
                            'direccion' => $_location,
                            'usuario_id' => $id_usuario
                        ));
                    }
    
                    //Realizamos una consulta para poder 
                    $doc_actividades = $db->query("SELECT taca.*
                                        FROM  tem_asesor_curso_actividad AS taca 
                                        WHERE taca.id_asesor_curso_actividad = '$id_temp_asesor_curso_actividad' AND estado_actividad = 'A'")->fetch_first();
                    $resp['resp'] = "1";
                    $resp['archivos'] = $doc_actividades['archivo'];
                    echo json_encode(array('estado' => 's'));
                }
            
            } else {
                echo json_encode(array('estado' => 'u'));
            }
            
	} else {
		// Devuelve los resultados
		echo json_encode(array('estado' => 'u'));
	}
} else {
	// Devuelve los resultados
	echo json_encode(array('estado' => 'p'));
}
