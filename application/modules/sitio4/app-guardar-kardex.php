
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
        $usuario = clear($_POST['usuario']);
        $contrasenia = clear($_POST['contrasenia']);
        //$id_gestion =clear($_POST['id_gestion']);
        $id_gestion = clear($_POST['id_gestion']);
        // Encripta la contraseña para compararla en la base de datos
        $usuario = md5($usuario);
        $contrasenia = encrypt($contrasenia);

        // Obtiene los datos del usuario
        $usuario = $db->select('*')->from('sys_users')->open_where()->where('md5(username)', $usuario)->or_where('md5(email)', $usuario)->close_where()->where(array('password' => $contrasenia, 'active' => 's'))->fetch_first();

        // Obtenemos el modo calificacion
        $hoy = date('Y-m-d');
		$sql_modo_calificacion = "SELECT * FROM cal_modo_calificacion WHERE gestion_id ='$id_gestion' and fecha_inicio <= '$hoy' and fecha_final >= '$hoy' AND estado = 'A'";
		$modos_calificacion = $db->query($sql_modo_calificacion)->fetch_first();
		$id_modo_calificacion = ((isset($modos_calificacion['id_modo_calificacion']) ? $modos_calificacion['id_modo_calificacion'] : "0"));
		$id_usuario = clear($_POST['id_usuario']);
        
        
        
        
        // Verifica la existencia del usuario 
        if ($usuario) {

            $tipo_kardex = $_POST['tipo_kardex'];

            if ($tipo_kardex == "citacion") {

                $id_citacion = $_POST['id_citacion'];
                $motivo = $_POST['motivo_ci'];
                $fecha_asistencia = $_POST['fecha_ci'];
                $asignacion_docente_id = $_POST['id_asignacion_docente'];
                $id_estudiante = $_POST['id_estudiante'];
                $modo_calificacion_id = $id_modo_calificacion;
                
                if($_POST['tipo_extra'] == "SI"){
                    $estado_curso = "E";    
                }else{
                    $estado_curso = "N";
                }
                
                if (isset($id_citacion) && $id_citacion != '') {
                    //editar::::::::::::::::::::::::::::::::
                    $datcitacion = array(
                        'motivo' => $motivo,
                        'fecha_asistencia' => $fecha_asistencia,
                        'usuario_modificacion' =>  $id_usuario,
                        'fecha_modificacion' => date('Y-m-d H:i:s')
                    );
                    //$db->insert('arc_sanciones', $felicitacion);
                    $db->where('id_citacion', $id_citacion)->update('arc_citaciones', $datcitacion);
                    echo 3; //edit corrcetmnetr
                } else {
                    //crear:::::::::::::::::
                    $sql_inscripcion = $db->query("SELECT id_inscripcion, estudiante_id
        										FROM ins_inscripcion 
        										WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion")->fetch_first();
                    $id_inscripcion = $sql_inscripcion['id_inscripcion'];

                   
                    $sql_archivo = $db->query("SELECT *
        								   FROM arc_archivo 
        								   WHERE inscripcion_id = $id_inscripcion")->fetch_first();
                    $valor = $sql_archivo['id_archivo'];

                    if (isset($valor)) {
                        //Si existe el estudiante solo recuperamos el id para poder añadir la citacion
                        $citacion = array(
                            'asignacion_docente_id' => $asignacion_docente_id,
                            'motivo' => $motivo,
                            'fecha_envio' => date('Y-m-d'),
                            'fecha_asistencia' => $fecha_asistencia,
                            'archivo_id' => $valor,
                            'estado' => 'A',
                            'usuario_registro' => $id_usuario,
                            'fecha_registro' => date('Y-m-d H:i:s'),
                            'usuario_modificacion' => '0',
                            'fecha_modificacion' => date('Y-m-d H:i:s'),
                            'modo_calificacion_id' => $id_modo_calificacion
                        );
                        $db->insert('arc_citaciones', $citacion);

                        if ($db->affected_rows) {
                             echo json_encode(array('estado' => 'c'));
                        } else {
                             echo json_encode(array('estado' => 'n'));
                        }
                    } else {
                        //Creamos el archivo para el estudiante
                        $archivo = array(
                            'inscripcion_id' => $id_inscripcion,
                            'estado' => $estado_curso
                        );

                        $id_archivo = $db->insert('arc_archivo', $archivo);
                      
                        $citacion = array(
                            'asignacion_docente_id' => $id_asignacion_docente,
                            'motivo' => $motivo,
                            'fecha_envio' => date('Y-m-d'),
                            'fecha_asistencia' => $fecha_asistencia, //$fecha_traer_tutor,
                            'archivo_id' => $id_archivo,
                            'estado' => 'A',
                            'usuario_registro' => $id_usuario,
                            'fecha_registro' => date('Y-m-d H:i:s'),
                            'usuario_modificacion' => '0',
                            'fecha_modificacion' => date('Y-m-d H:i:s'),
                            'modo_calificacion_id' => $id_modo_calificacion
                        );
                        $db->insert('arc_citaciones', $citacion);
                        if ($db->affected_rows) {
                            echo json_encode(array('estado' => 'c'));
                        } else {
                            echo json_encode(array('estado' => 'n'));
                        }
                    }
                } //fin crear
            }

            if ($tipo_kardex == "felicitacion") {

                //var_dump($_POST);exit();

                $motivo = $_POST['motivo'];
                $id_felicitacion = $_POST['id_felicitacion'];
                $descripcion = $_POST['descripcion'];
                
                $asignacion_docente_id = $_POST['id_asignacion_docente'];
                $id_estudiante = $_POST['id_estudiante'];
                $modo_calificacion_id = $id_modo_calificacion;
                
                if($_POST['tipo_extra'] == "SI"){
                    $estado_curso = "E";    
                }else{
                    $estado_curso = "N";
                }
                

                if (isset($id_felicitacion) && $id_felicitacion != '') {
                    //editar::::::::::::::::::::::::::::::::
                    $datFelicitacion = array(
                        'motivo' => $motivo,
                        'descripcion' => $descripcion,
                        'usuario_modificacion' =>  $id_usuario,
                        'fecha_modificacion' => date('Y-m-d H:i:s')
                    );
                   
                    $db->where('id_felicitaciones', $id_felicitacion)->update('arc_felicitaciones', $datFelicitacion);
                    echo json_encode(array('estado' => 'u')); 
                } else {
                    //crear:::::::::::::::::
                    //obtener id inscripcion, depende de si se inscribio en extracurricular o en curso normal
                    if ($estado_curso != 'E') {
                        //NORmAL
                        $sql_inscripcion = $db->query("SELECT id_inscripcion, estudiante_id
										FROM ins_inscripcion 
										WHERE estudiante_id = $id_estudiante and gestion_id=$id_gestion and estado='A'")->fetch_first(); // AND gestion_id = $id_gestion")->fetch_first();
                        //echo 'Normal';
                    } else {
                        //extrayemos el id inscripcion EXTRACURRCULAR
                        $sql_inscripcion = $db->query("SELECT id_curso_inscripcion as id_inscripcion, estudiante_id
									FROM ext_curso_inscripcion 
									WHERE estudiante_id = $id_estudiante and gestion_id=$id_gestion and estado='A'")->fetch_first();
                        //echo 'Extra';
                    }

                    if ($sql_inscripcion) {

                        $id_inscripcion = $sql_inscripcion['id_inscripcion'];
                        
                        //Buscamos el id_archivo del estudiante
                        $sql_archivo = $db->query("SELECT *
								   FROM arc_archivo 
								   WHERE inscripcion_id = $id_inscripcion")->fetch_first();
                        $valor = $sql_archivo['id_archivo'];
                       

                        //Preguntamos si existe el archivo 1 si existiese y 0 no existe
                        if (isset($valor)) {
                            //Si existe el estudiante solo recuperamos el id para poder añadir su sancion

                            $felicitacion = array(
                                'asignacion_docente_id' => $asignacion_docente_id,
                                'motivo' => $motivo,
                                'descripcion' => $descripcion,
                                'fecha_felicitacion' => date('Y-m-d'),
                                'archivo_id' => $valor,
                                'estado' => 'A',
                                'usuario_registro' => $id_usuario,
                                'fecha_registro' => date('Y-m-d H:i:s'),
                                'usuario_modificacion' => '0',
                                'fecha_modificacion' => date('Y-m-d H:i:s'),
                                'modo_calificacion_id' => $id_modo_calificacion
                            );
                            $db->insert('arc_felicitaciones', $felicitacion);

                            if ($db->affected_rows) {
                                echo json_encode(array('estado' => 'c'));
                            } else {
                                echo json_encode(array('estado' => 'n'));
                            }
                        } else {
                            //Creamos el archivo para el estudiante
                            $archivo = array(
                                'inscripcion_id' => $id_inscripcion,
                                'estado' => 1,
                                'estado_curso' => $estado_curso
                            );
                           			

                            $id_archivo = $db->insert('arc_archivo', $archivo);

                            $felicitacion = array(
                                'asignacion_docente_id' => $asignacion_docente_id,
                                'motivo' => $motivo,
                                'descripcion' => $descripcion,
                                'fecha_felicitacion' => date('Y-m-d'),
                                'archivo_id' => $id_archivo,
                                'estado' => 'A',
                                'usuario_registro' => $id_usuario,
                                'fecha_registro' => date('Y-m-d H:i:s'),
                                'usuario_modificacion' => '0',
                                'fecha_modificacion' => date('Y-m-d H:i:s'),
                                'modo_calificacion_id' => $modo_calificacion_id
                            );
                            $db->insert('arc_felicitaciones', $felicitacion);

                            if ($db->affected_rows) {
                                echo json_encode(array('estado' => 'c'));
                            } else {
                                echo json_encode(array('estado' => 'n'));
                            }
                        }
                    } else {
                        echo json_encode(array('estado' => 'e'));
                    }
                } //fin crear::::::
            }

            if ($tipo_kardex == "sancion") {
                
                $asignacion_docente_id = $_POST['id_asignacion_docente'];
                $id_sancion = $_POST['id_sancion'];
                $motivo = $_POST['motivo'];
                $fecha_sancion = date('Y-m-d');
                $dias_suspencion = $_POST['dias'];
                $traer_tutor = $_POST['traer_tutor'];
                $fecha_traer_tutor = $_POST['fecha_asistir'];
                $modo_calificacion_id = $id_modo_calificacion;
                $asistio_tutor = "0";
                $fecha_asistio_tutor = date('Y-m-d');
                if($_POST['tipo_extra'] == "SI"){
                    $estado_curso = "E";    
                }else{
                    $estado_curso = "N";
                }
                
                if (isset($id_sancion) && $id_sancion != '') {
                    
                    //Editar
                    $sancion = array(         //'asignacion_docente_id'=> $profesor_materia_id,
                        'motivo' => $motivo,
                        //'fecha_sancion'=> date('Y-m-d'),
                        'dias_suspencion' => $dias_suspencion,
                        'traer_tutor' => $traer_tutor,
                        'fecha_traer_tutor' => $fecha_traer_tutor,
                        //'asistio_tutor'=> $asistio_tutor,
                        //'fecha_asistio_tutor'=> $fecha_asistio_tutor,
                        //'archivo_id' => $id_archivo,
                        //'estado'=> 'A',
                        //'usuario_registro'=> $_user['id_user'],
                        //'fecha_registro'=> date('Y-m-d H:i:s'),
                        'usuario_modificacion' =>  $id_usuario,
                        'fecha_modificacion' => date('Y-m-d H:i:s')
                    );
                    //$db->insert('arc_sanciones', $felicitacion);
                    $db->where('id_sancion', $id_sancion)->update('arc_sanciones', $sancion);
                    
                     echo json_encode(array('estado' => 'e'));
                    
                } else {
                    //Nuevo
                    $asignacion_docente_id = $_POST['id_asignacion_docente'];
                    $id_estudiante         = $_POST['id_estudiante'];

                    $sql_inscripcion = $db->query("SELECT id_inscripcion, estudiante_id
                                            FROM ins_inscripcion 
                                            WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion")->fetch_first();
                    $id_inscripcion = $sql_inscripcion['id_inscripcion'];

                    
                    $sql_archivo = $db->query("SELECT *
                                       FROM arc_archivo 
                                       WHERE inscripcion_id = $id_inscripcion")->fetch_first();
                    $valor = $sql_archivo['id_archivo'];

                    if (isset($valor)) {
                        //Si existe el estudiante solo recuperamos el id para poder añadir su sancion

                        $sancion = array(
                            'asignacion_docente_id' => $asignacion_docente_id,
                            'motivo' => $motivo,
                            'fecha_sancion' => date('Y-m-d'),
                            'dias_suspencion' => $dias_suspencion,
                            'traer_tutor' => $traer_tutor,
                            'fecha_traer_tutor' => $fecha_traer_tutor,
                            'asistio_tutor' => $asistio_tutor,
                            'fecha_asistio_tutor' => $fecha_asistio_tutor,
                            'archivo_id' => $valor,
                            'estado' => 'A',
                            'usuario_registro' => $id_usuario,
                            'fecha_registro' => date('Y-m-d H:i:s'),
                            'usuario_modificacion' => '0',
                            'fecha_modificacion' => date('Y-m-d H:i:s'),
                            'modo_calificacion_id' => $id_modo_calificacion
                        );
                        $db->insert('arc_sanciones', $sancion);

                        if ($db->affected_rows) {
                            echo json_encode(array('estado' => 'c'));
                        } else {
                            echo json_encode(array('estado' => 'n'));
                        }
                    } else {
                        //Creamos el archivo para el estudiante
                        $archivo = array(
                            'inscripcion_id' => $id_inscripcion,
                            'estado' => $estado_curso
                        );
                        //$sqlArchivo = "INSERT INTO arc_archivo (inscripcion_id, estado) VALUES ('{$id_estudiante_ins}', '1');";			

                        $id_archivo = $db->insert('arc_archivo', $archivo);

                        $sancion = array(
                            'asignacion_docente_id' => $asignacion_docente_id,
                            'motivo' => $motivo,
                            'fecha_sancion' => date('Y-m-d'),
                            'dias_suspencion' => $dias_suspencion,
                            'traer_tutor' => $traer_tutor,
                            'fecha_traer_tutor' => $fecha_traer_tutor,
                            'asistio_tutor' => $asistio_tutor,
                            'fecha_asistio_tutor' => $fecha_asistio_tutor,
                            'archivo_id' => $id_archivo,
                            'estado' => 'A',
                            'usuario_registro' => $id_usuario,
                            'fecha_registro' => date('Y-m-d H:i:s'),
                            'usuario_modificacion' => '0',
                            'fecha_modificacion' => date('Y-m-d H:i:s'),
                            'modo_calificacion_id' => $id_modo_calificacion
                        );
                        $db->insert('arc_sanciones', $sancion);

                        if ($db->affected_rows) {
                             echo json_encode(array('estado' => 'c'));
                        } else {
                             echo json_encode(array('estado' => 'n'));
                        }
                    }
                }
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

?>