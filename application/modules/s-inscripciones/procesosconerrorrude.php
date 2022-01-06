<?php

//var_dump($_POST);die;
require_once libraries . '/phpexcel-2.1/controlador.php';

$boton = $_POST['boton'];

// Obtiene el id de la gestion actual
$id_gestion   = $_gestion['id_gestion'];
$fecha_actual = Date('Y-m-d H:i:s');

if ($boton == "listar_familiares") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['id_estudiante'])) {
        $id_estudiante = $_POST['id_estudiante'];
    } else {
        $id_estudiante = "";
    }
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    $familiar = $db->query("SELECT * FROM vista_estudiante_familiar WHERE id_estudiante = $id_estudiante")->fetch();
    echo json_encode($familiar);
}

if ($boton == "listar_tipo_documento") {
    $respuesta = $db->query("SELECT * FROM catalogo_detalle WHERE catalogo_id = 1")->fetch();
    echo json_encode($respuesta);
}

if ($boton == "listar_documentos") {
    $respuesta = $db->query("SELECT * FROM ins_tipo_documentos WHERE estado = 'A'")->fetch();
    echo json_encode($respuesta);
}

if ($boton == "datos_estudiante") {

    //var_dump($_POST);
    $id_estudiante = $_POST['id_estudiante'];

    $array = array();

    /*$datos_personales = $db->query("SELECT e.id_estudiante,
                                               e.codigo_estudiante,
                                               e.rude,
                                               e.aula_paralelo_id,
                                               p.nombres,
                                               p.primer_apellido,
                                               p.segundo_apellido,
                                               p.tipo_documento,
                                               p.numero_documento,
                                               p.complemento,
                                               p.genero,
                                               p.fecha_nacimiento,
                                               p.direccion,
                                               p.foto
                                        FROM ins_estudiante e 
                                        LEFT JOIN sys_persona p ON p.id_persona = e.persona_id
                                        WHERE e.id_estudiante = $id_estudiante")->fetch_first();*/
    $datos_personales = $db->query("SELECT e.id_estudiante, e.codigo_estudiante, e.persona_id, p.*,  i.*, ir.*, v.*
                FROM ins_estudiante AS e
                LEFT JOIN sys_persona p ON p.id_persona = e.persona_id
                LEFT JOIN ins_inscripcion AS i ON i.estudiante_id = e.id_estudiante
                LEFT JOIN ins_inscripcion_rude AS ir ON ir.ins_estudiante_id = i.estudiante_id
                LEFT JOIN ins_documentos AS d ON d.estudiante_id = e.id_estudiante
                LEFT JOIN ins_vacunas AS v ON v.estudiante_id = e.id_estudiante
                                        WHERE e.id_estudiante = $id_estudiante AND i.gestion_id = $id_gestion")->fetch_first();
    $array['datos_personales'] = $datos_personales;

    $vacunas = $db->query("SELECT *
                               FROM ins_vacunas WHERE estudiante_id = $id_estudiante")->fetch_first();
    $array['vacunas'] = $vacunas;

    $documentos = $db->query("SELECT *
                               FROM ins_documentos WHERE estudiante_id = $id_estudiante")->fetch();
    $array['documentos'] = $documentos;

    $familiar = $db->query("SELECT *
                               FROM ins_estudiante_familiar WHERE estudiante_id = $id_estudiante")->fetch();
    $array['familiares'] = $familiar;

    echo json_encode($array);
}

if ($boton == "buscar_datos_personales") {
    $id_familiar = $_POST['id_familiar'];
    $familiar = $db->query("SELECT * 
                            FROM ins_familiar  AS f
                            INNER JOIN sys_persona AS p ON p.id_persona = f.persona_id
                            INNER JOIN ins_estudiante_familiar AS ef ON ef.familiar_id = f.id_familiar
                            WHERE f.id_familiar = $id_familiar")->fetch_first();
    //var_dump($familiar);exit();
    echo json_encode($familiar);
}

if ($boton == "vacunas") {
    $id_estudiante = $_POST['id_estudiante'];
    if (isset($_POST['bcg'])) {
        $bcg = 'SI';
    } else {
        $bcg = 'NO';
    }

    if (isset($_POST['a1'])) {
        $a1 = "SI";
    } else {
        $a1 = "NO";
    }

    if (isset($_POST['a2'])) {
        $a2 = "SI";
    } else {
        $a2 = "NO";
    }

    if (isset($_POST['a3'])) {
        $a3 = "SI";
    } else {
        $a3 = "NO";
    }

    if (isset($_POST['p1'])) {
        $p1 = "SI";
    } else {
        $p1 = "NO";
    }

    if (isset($_POST['p2'])) {
        $p2 = "SI";
    } else {
        $p2 = "NO";
    }

    if (isset($_POST['p3'])) {
        $p3 = "SI";
    } else {
        $p3 = "NO";
    }

    if (isset($_POST['am1'])) {
        $am1 = "SI";
    } else {
        $am1 = "NO";
    }

    if (isset($_POST['srp1'])) {
        $srp1 = "SI";
    } else {
        $srp1 = "NO";
    }

    if (isset($_POST['o1'])) {
        $o1 = "SI";
    } else {
        $o1 = "NO";
    }

    if (isset($_POST['observaciones_vacunas'])) {
        $observaciones_vacunas = $_POST['observaciones_vacunas'];
    } else {
        $observaciones_vacunas = "";
    }


    $busqueda = $db->query("SELECT v.id_vacuna, v.estudiante_id 
								FROM ins_vacunas v
								WHERE v.estudiante_id = $id_estudiante ")->fetch_first();
    $id_vacuna = $busqueda['id_vacuna'];

    if ($id_vacuna != null) {
        // Modifica los datos del certificado de nacimiento
        $db->where('id_vacuna', $id_vacuna)->update('ins_vacunas', array(
            'estudiante_id' => $id_estudiante,
            'tuberculosis' => $bcg,
            'antipolio_1' => $a1,
            'antipolio_2' => $a2,
            'antipolio_3' => $a3,
            'pentavalente_1' => $p1,
            'pentavalente_2' => $p2,
            'pentavalente_3' => $p3,
            'antiamarilla' => $am1,
            'mmr_unica' => $srp1,
            'otra' => $o1,
            'observaciones' => $observaciones_vacunas,
            'estado' => 'A',
            'usuario_registro' => $_user['id_user'],
            'fecha_registro' => date('Y-m-d H:i:s')
        ));

        $cadena = array(
            'id_estudiante' => $id_estudiante,
            'estado' => 2
        );
    } else {
        //var_dump($_POST);die;
        // Guarda el proceso de vacunas
        $db->insert('ins_vacunas', array(
            'estudiante_id' => $id_estudiante,
            'tuberculosis' => $bcg,
            'antipolio_1' => $a1,
            'antipolio_2' => $a2,
            'antipolio_3' => $a3,
            'pentavalente_1' => $p1,
            'pentavalente_2' => $p2,
            'pentavalente_3' => $p3,
            'antiamarilla' => $am1,
            'mmr_unica' => $srp1,
            'otra' => $o1,
            'observaciones' => $observaciones_vacunas,
            'estado' => 'A',
            'usuario_registro' => $_user['id_user'],
            'fecha_registro' => date('Y-m-d H:i:s')
        ));
        //var_dump($id_persona);die;
        $cadena = array(
            'id_estudiante' => $id_estudiante,
            'estado' => 1
        );
    }

    echo json_encode($cadena);
}

if ($boton == "agregar_familiar") {
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";
    // $id_estudiante_editar = (isset($_POST['id_estudiante_editar'])) ? $_POST['id_estudiante_editar'] : 0;
    // var_dump($id_estudiante_editar);
    // exit;

    $id_familiar_e     = (isset($_POST['f_id_familiar'])) ? $_POST['f_id_familiar'] : 0;
    $id_persona_e      = (isset($_POST['f_id_persona'])) ? $_POST['f_id_persona'] : 0;
    $nombre_imagen     = clear($_POST['f_nombre_imagen']);
    $nombres           = $_POST['f_nombres'];
    $primer_apellido   = $_POST['f_primer_apellido'];
    $segundo_apellido  = $_POST['f_segundo_apellido'];
    $tipo_documento    = $_POST['f_tipo_documento'];
    $numero_documento  = $_POST['f_numero_documento'];
    $complemento       = $_POST['f_complemento'];
    $expedido          = $_POST['f_expedido'];
    $nit               = $_POST['f_nit'];
    $genero            = $_POST['f_genero'];
    $fecha_nacimiento  = date_encode($_POST['f_fecha_nacimiento_tutor']);
    $idioma_frecuente  = $_POST['f_idioma_frecuente'];
    $email             = $_POST['f_correo_electronico'];
    $telefono          = $_POST['f_telefono'];
    $profesion         = $_POST['f_profesion'];
    $direccion_oficina = $_POST['f_direccion_oficina'];
    $grado_instruccion = $_POST['f_grado_instruccion'];
    $parentesco        = $_POST['f_parentesco'];
    $tutor             = $_POST['f_tutor'];
    //var_dump($fecha_nacimiento);exit();
    //id_estudiante por el momento sin valor 
    $id_estudiante     = 0;
    //
    $id_estudiante_editar = (isset($_POST['id_estudiante_editar'])) ? $_POST['id_estudiante_editar'] : 0;

    //A«Ðadimos un nuevo familiar
    if ($id_estudiante_editar != "") {
        $persona = array(
            'nombres' => $nombres,
            'primer_apellido' => $primer_apellido,
            'segundo_apellido' => $segundo_apellido,
            'tipo_documento' => $tipo_documento,
            'numero_documento' => $numero_documento,
            'complemento' => $complemento,
            'expedido' => $expedido,
            'genero' => $genero,
            'fecha_nacimiento' => $fecha_nacimiento,
            'nit' => $nit
        );
        //Obtenemos el Id
        $id_persona = $db->insert('sys_persona', $persona);

        //var_dump($id_persona);exit;

        if ($id_persona) {

            if ($nombre_imagen) {
                $ruta_temporal = 'files/profiles/temporal/fotos/' . $nombre_imagen; //ruta temporal
                $nombre = md5(secret . random_string() .  $id_familiar_e); //encripta el nombre de la imagen a md5
                $ruta_destino = 'files/profiles/familiares/' . $nombre . '.jpg'; //ruta de destino
                copy($ruta_temporal, $ruta_destino); //copia la imagen de la carpeta temporal a estudiante
                unlink($ruta_temporal); //elimina la imagen de la carpeta temporal
            } else {
                $nombre = "";
            }

            $db->query("UPDATE sys_persona set foto = '$nombre' WHERE id_persona = $id_persona")->execute();
            //echo 1;
            $codigo_mayor = $db->query("SELECT MAX(id_familiar) as id_familiar FROM ins_familiar")->fetch_first();
            $id_anterior = $codigo_mayor['id_familiar']; //id_comunicado mayor

            if (is_null($id_anterior)) {
                $nuevo_codigo = "M-1";
            } else {
                //recupera los datos del ultimo registro para asignarle un codigo
                $familiar_mayor = $db->query("SELECT id_familiar, codigo_familia FROM ins_familiar WHERE id_familiar = $id_anterior ")->fetch_first();
                $codigo_anterior = $familiar_mayor['codigo_familia']; //codigo anterior
                $separado = explode('-', $codigo_anterior);
                $nuevo_codigo = "M-" . ($separado[1] + 1);
            }

            //var_dump($nuevo_codigo);die;
            //Armanos el todo los campos para crear el registro en la tabla familiar
            $familiar = array(
                'profesion' => $profesion,
                'direccion_oficina' => $direccion_oficina,
                'telefono_oficina' => $telefono,
                'idioma_frecuente' => $idioma_frecuente,
                'email' => $email,
                'grado_instruccion' => $grado_instruccion,
                'parentesco' => $parentesco,
                'persona_id' => $id_persona,
                'codigo_familia' => $nuevo_codigo,
                'estado' => 'A',
                'usuario_registro' => $_user['id_user'],
                'fecha_registro' => Date('Y-m-d H:i:s')
            );

            //var_dump($familiar);die;
            //Registramos el familiar y obtenemos el id del familiar
            $id_familiar = $db->insert('ins_familiar', $familiar);

            //Como estamos en editar estudiante agregamos el familiar al estudiante
            if ($id_familiar) {
                $valor_tutor = 0;
                $estudiante_familiar = array(
                    'familiar_id' => $id_familiar,
                    'estudiante_id' => $id_estudiante_editar,
                    'tutor' => $valor_tutor
                );
                $id_estudiante_familiar = $db->insert('ins_estudiante_familiar', $estudiante_familiar);

                $respuesta = array(
                    'id_familiar' => $id_familiar,
                    'valor_tutor' => $tutor,
                    'familiar'    =>   "",
                    'estado'      => 10
                );
            } else {
                $respuesta = array(
                    'id_familiar' => $id_familiar,
                    'valor_tutor' => $tutor,
                    'familiar'    =>   "",
                    'estado'      => 11
                );
            }           
            echo json_encode($respuesta);
        }
    } else {
        if ($id_persona_e > 0) {
            $persona = array(
                'nombres' => $nombres,
                'primer_apellido' => $primer_apellido,
                'segundo_apellido' => $segundo_apellido,
                'tipo_documento' => $tipo_documento,
                'numero_documento' => $numero_documento,
                'complemento' => $complemento,
                'expedido' => $expedido,
                'genero' => $genero,
                'fecha_nacimiento' => $fecha_nacimiento,
                'nit' => $nit
            );
            //Obtenemos el Id       
            $db->where('id_persona', $id_persona_e)->update('sys_persona', $persona);

            if ($nombre_imagen) {
                $ruta_temporal = 'files/profiles/temporal/fotos/' . $nombre_imagen; //ruta temporal
                $nombre = md5(secret . random_string() .  $id_familiar_e); //encripta el nombre de la imagen a md5
                $ruta_destino = 'files/profiles/familiares/' . $nombre . '.jpg'; //ruta de destino
                copy($ruta_temporal, $ruta_destino); //copia la imagen de la carpeta temporal a estudiante
                unlink($ruta_temporal); //elimina la imagen de la carpeta temporal
            } else {
                $nombre = "";
            }

            $db->query("UPDATE sys_persona set foto = '$nombre' WHERE id_persona = $id_persona_e")->execute();

            $familiar = array(
                'profesion' => $profesion,
                'direccion_oficina' => $direccion_oficina,
                'telefono_oficina' => $telefono,
                'idioma_frecuente' => $idioma_frecuente,
                'email' => $email,
                'grado_instruccion' => $grado_instruccion,
                'parentesco' => $parentesco,
                'persona_id' => $id_persona_e,
                'estado' => 'A',
                'usuario_modificacion' => $_user['id_user'],
                'fecha_modificacion' => Date('Y-m-d H:i:s')
            );
            $db->where('id_familiar', $id_familiar_e)->update('ins_familiar', $familiar);

            $respuesta = array(
                'id_familiar' => $id_familiar_e * 1,
                'valor_tutor' => 0,
                'familiar'    => 0,
                'estado'      => 2
            );
            echo json_encode($respuesta);
        } else {
            //Primero agregamos a la tabla sys_persona 
            $persona = array(
                'nombres' => $nombres,
                'primer_apellido' => $primer_apellido,
                'segundo_apellido' => $segundo_apellido,
                'tipo_documento' => $tipo_documento,
                'numero_documento' => $numero_documento,
                'complemento' => $complemento,
                'expedido' => $expedido,
                'genero' => $genero,
                'fecha_nacimiento' => $fecha_nacimiento,
                'nit' => $nit
            );
            //Obtenemos el Id
            $id_persona = $db->insert('sys_persona', $persona);

            //var_dump($id_persona);exit;

            if ($id_persona) {

                if ($nombre_imagen) {
                    $ruta_temporal = 'files/profiles/temporal/fotos/' . $nombre_imagen; //ruta temporal
                    $nombre = md5(secret . random_string() .  $id_familiar_e); //encripta el nombre de la imagen a md5
                    $ruta_destino = 'files/profiles/familiares/' . $nombre . '.jpg'; //ruta de destino
                    copy($ruta_temporal, $ruta_destino); //copia la imagen de la carpeta temporal a estudiante
                    unlink($ruta_temporal); //elimina la imagen de la carpeta temporal
                } else {
                    $nombre = "";
                }

                $db->query("UPDATE sys_persona set foto = '$nombre' WHERE id_persona = $id_persona")->execute();
                //echo 1;
                $codigo_mayor = $db->query("SELECT MAX(id_familiar) as id_familiar FROM ins_familiar")->fetch_first();
                $id_anterior = $codigo_mayor['id_familiar']; //id_comunicado mayor

                if (is_null($id_anterior)) {
                    $nuevo_codigo = "M-1";
                } else {
                    //recupera los datos del ultimo registro para asignarle un codigo
                    $familiar_mayor = $db->query("SELECT id_familiar, codigo_familia FROM ins_familiar WHERE id_familiar = $id_anterior ")->fetch_first();
                    $codigo_anterior = $familiar_mayor['codigo_familia']; //codigo anterior
                    $separado = explode('-', $codigo_anterior);
                    $nuevo_codigo = "M-" . ($separado[1] + 1);
                }

                //var_dump($nuevo_codigo);die;
                //Armanos el todo los campos para crear el registro en la tabla familiar
                $familiar = array(
                    'profesion' => $profesion,
                    'direccion_oficina' => $direccion_oficina,
                    'telefono_oficina' => $telefono,
                    'idioma_frecuente' => $idioma_frecuente,
                    'email' => $email,
                    'grado_instruccion' => $grado_instruccion,
                    'parentesco' => $parentesco,
                    'persona_id' => $id_persona,
                    'codigo_familia' => $nuevo_codigo,
                    'estado' => 'A',
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro' => Date('Y-m-d H:i:s')
                );

                //var_dump($familiar);die;
                //Registramos el familiar y obtenemos el id del familiar
                $id_familiar = $db->insert('ins_familiar', $familiar);

                if ($id_familiar) {
                    //armomos el array que devolvera el id_familiar
                    /*$respuesta = array('id_familiar' => $id_familiar,
                               'valor_tutor' => $tutor,                              
                               'estado'      => 1 );*/
                    if ($tutor == 1) {
                        $respuesta = array(
                            'id_familiar' => $id_familiar,
                            'valor_tutor' => $tutor,
                            'familiar'    =>   "<tr><td>" . $nombres . " " . $primer_apellido . " " . $segundo_apellido . "</td><td>SI</td></tr>",
                            'estado'      => 1
                        );
                    } else {
                        $respuesta = array(
                            'id_familiar' => $id_familiar,
                            'valor_tutor' => $tutor,
                            'familiar'    =>   "<tr><td>" . $nombres . " " . $primer_apellido . " " . $segundo_apellido . " </td><td>NO</td></tr>",
                            'estado'      => 1
                        );
                    }
                } else {
                    $respuesta = array(
                        'id_familiar' => "0",
                        'valor_tutor' => "0",
                        'familiar'    => "0",
                        'estado'      => 3
                    );
                }

                echo json_encode($respuesta);
            }
        }
    }
}


//obtiene el listado de cursos
if ($boton == "listar_niveles") {    
    $niveles = $db->query("SELECT * FROM ins_nivel_academico WHERE estado = 'A' AND gestion_id = $id_gestion")->fetch();
    echo json_encode($niveles);
}

//obtiene el listado de cursos
if ($boton == "listar_cursos") {
    //var_dump($_POST);exit();
    //obtiene el nivel
    $nivel = $_POST['nivel'];
    $turno = $_POST['turno'];

    //obtiene los cursos segun el nivel
    $cursos = $db->query("SELECT ap.id_aula_paralelo, ap.capacidad, a.nombre_aula , p.nombre_paralelo , p.descripcion
                            FROM ins_aula_paralelo AS ap
                            INNER JOIN ins_aula AS a ON a.id_aula = ap.aula_id
                            INNER JOIN ins_paralelo AS p ON p.id_paralelo = ap.paralelo_id
                            WHERE a.nivel_academico_id = $nivel AND a.estado = 'A' AND ap.turno_id = $turno
                            ORDER BY a.id_aula, p.id_paralelo")->fetch();
    echo json_encode($cursos);
}

//obtiene el listado de cursos
if ($boton == "listar_turnos") {    
    $turnos = $db->query("SELECT * FROM ins_turno WHERE estado = 'A' AND gestion_id = $id_gestion")->fetch();
    echo json_encode($turnos);
}

//obtiene el nro de varones y mujeres inscritos en un curso y gestion especifico
if ($boton == "nro_varones_mujeres") {
    //obtiene el nivel
    $id_aula_paralelo = $_POST['id_aula_paralelo'];
    //obtiene los cursos segun el nivel
    $nroVM = $db->query("SELECT IFNULL(SUM(p.genero= 'v'),0) AS nro_varones, IFNULL(SUM(p.genero= 'm'),0) AS nro_mujeres,  COUNT(i.id_inscripcion) AS inscritos, IFNULL(ap.capacidad,0) AS cupo_total
                            FROM ins_inscripcion AS i
                            INNER JOIN ins_estudiante e ON e.id_estudiante = i.estudiante_id
                            INNER JOIN sys_persona p ON p.id_persona = e.persona_id
                            INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo = i.aula_paralelo_id
                            WHERE i.aula_paralelo_id = $id_aula_paralelo AND i.gestion_id = $id_gestion AND i.estado = 'A'")->fetch();
    echo json_encode($nroVM);
}

//obtiene el listado de paralelos
if ($boton == "listar_paralelos") {
    $id_curso = $_POST['id_curso'];
    $paralelo = $db->query("SELECT * FROM vista_aula_paralelo WHERE id_aula = $id_curso")->fetch();
    echo json_encode($paralelo);
}

//obtiene el listado de vacantes segun curso/paralelo
if ($boton == "listar_vacantes") {
    $id_aula_paralelo = $_POST['id_aula_paralelo'];
    $consulta = $db->query("SELECT COUNT(aula_paralelo_id) as contador FROM ins_inscripcion WHERE aula_paralelo_id = $id_aula_paralelo")->fetch_first();
    $consulta_aula = $db->query("SELECT * FROM ins_aula_paralelo WHERE id_aula_paralelo = $id_aula_paralelo")->fetch_first();
    //obtiene el total de vacantes del curso paralelo        
    $vacantes = $consulta_aula['capacidad'] - $consulta['contador'];
    echo json_encode($vacantes);
}

if ($boton == "seleccionar_tutor") {
    //var_dump($_POST);die;
    $id_estudiante_familiar = $_POST['id_estudiante_familiar'];
    $id_estudiante = $_POST['id_estudiante'];
    $id_tutor = $_POST['id_tutor'];

    $tutor = array('tutor' => 1); //instancia tutor

    //selecciona al tutor
    $db->where('id_estudiante_familiar', $id_estudiante_familiar)->update('ins_estudiante_familiar', $tutor);
    //$consulta = "UPDATE ins_estudiante_familiar SET tutor = 1 WHERE id_estudiante_familiar = $id_estudiante_familiar";

    $familiar = array('tutor' => 0);
    //$consulta ="UPDATE ins_estudiante_familiar SET tutor = 0 WHERE id_estudiante_familiar <> $id_estudiante_familiar AND estudiante_id = $id_estudiante";
    $db->query("UPDATE ins_estudiante_familiar SET tutor = 0 WHERE id_estudiante_familiar <> $id_estudiante_familiar AND estudiante_id = $id_estudiante")->execute();

    echo 1;
}

if ($boton == "identificar_familiar") {
    //var_dump($_POST);die;
    $id_estudiante = $_POST['id_estudiante'];
    $id_familiar = $_POST['id_familiar'];

    if ($id_estudiante and $id_familiar) {
        $estudiante_familiar = array(
            'familiar_id' => $id_familiar,
            'estudiante_id' => $id_estudiante,
            'tutor' => 0
        );

        $id_estudiante_familiar = $db->insert('ins_estudiante_familiar', $estudiante_familiar);

        if ($id_estudiante_familiar) {
            echo 1;
        } else {
            echo 2;
        }
    } else {
        echo 3;
    }
}

if ($boton == "guardar_inscripcion") {
    //Capturamos el curso elegido
    //var_dump($_POST);exit();    
    $id_aula_paralelo   = $_POST['select_curso'];
    $id_turno           = $_POST['turno'];
    $id_nivel_academico = $_POST['nivel_academico'];
    $tipo_estudiante    = $_POST['tipo_estudiante'];
    $capacidad          = $_POST['vacantes'];
    $capacidad          = $capacidad - 1;
    $id_aula_paralelo_A = $_POST['id_aula_paralelo_A'];
    $estado             = 0;

    // $busqueda = $db->query("SELECT * FROM ins_aula_paralelo WHERE id_aula_paralelo = $id_aula_paralelo_A")->fetch_first();
    // //var_dump($busqueda);

    // if ($busqueda == null) {
    //     //Editamos el cupo del curso elegido para descontar el cupo
    //     $db->where('id_aula_paralelo', $id_aula_paralelo)->update('ins_aula_paralelo', array('capacidad' => $capacidad));
    //     $estado = 1;
    // } else {
    //     $capacidad = $busqueda['capacidad'] + 1;
    //     //Devolvemos el cupo del curso anterior
    //     $db->where('id_aula_paralelo', $id_aula_paralelo_A)->update('ins_aula_paralelo', array('capacidad' => $capacidad));
    //     //Editamos el cupo del curso elegido para descontar el cupo
    //     $db->where('id_aula_paralelo', $id_aula_paralelo)->update('ins_aula_paralelo', array('capacidad' => $capacidad));
    //     $estado = 2;
    // }

    // $respuesta = array(
    //     'id_aula_paralelo' => ($id_aula_paralelo * 1),
    //     'tipo_estudiante'  => ($tipo_estudiante * 1),
    //     'id_turno'         => ($id_turno * 1),
    //     'nivel_academico'  => ($id_nivel_academico * 1),
    //     'estado' => ($estado * 1)
    // );
    //var_dump($respuesta);
    $nro = $db->query("SELECT COUNT(i.id_inscripcion) AS inscritos
            FROM ins_inscripcion AS i
            WHERE i.aula_paralelo_id = $id_aula_paralelo")->fetch_first();

    $cap = $db->query("SELECT ap.capacidad
                        FROM ins_aula_paralelo AS ap
                        WHERE ap.id_aula_paralelo = $id_aula_paralelo")->fetch_first();

    
    if(($nro['inscritos']*1) <= ($cap['capacidad']*1)){
        $estado = 1;
         $respuesta = array(
            'id_aula_paralelo' => ($id_aula_paralelo * 1),
            'tipo_estudiante'  => ($tipo_estudiante * 1),
            'id_turno'         => ($id_turno * 1),
            'nivel_academico'  => ($id_nivel_academico * 1),
            'estado' => ($estado * 1)
        );
    }else{
        $estado = 0;
         $respuesta = array(
            'id_aula_paralelo' => ($id_aula_paralelo * 1),
            'tipo_estudiante'  => ($tipo_estudiante * 1),
            'id_turno'         => ($id_turno * 1),
            'nivel_academico'  => ($id_nivel_academico * 1),
            'estado' => ($estado * 1)
        );
    }

    echo json_encode($respuesta);
}

/****************************************************/
//      Metodo guardar una nueva inscripcion        //
/****************************************************/
if ($boton == "guardar_inscripcion_editar") {
    
    //Capturamos el curso elegido
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";
    // exit();

    $inscripcion = array(
        'aula_paralelo_id' => $_POST['select_curso'],
        'tipo_estudiante_id' => $_POST['tipo_estudiante'],
        'nivel_academico_id' => $_POST['nivel_academico'],
        'turno_id' => $_POST['turno'],  
    );

    $db->where('id_inscripcion', $_POST['inscripcion_id'])->update('ins_inscripcion', $inscripcion);

    //Introducimos los datos anteriores al Historial
    $id_aula_paralelo   = $_POST['a_id_curso'];   
    $tipo_estudiante    = $_POST['a_id_tipo_estudiante'];
    $id_inscripcion     = $_POST['inscripcion_id'];
    
    $inscripcion_Historico = array(        
        'inscripcion_id' => $id_inscripcion,
        'tipo_estudiante_id' => $tipo_estudiante,
        'gestion_id' => $id_gestion,
        'estado' => 'A',
        'usuario_registro' => $_user['id_user'],
        'fecha_registro' => Date('Y-m-d H:i:s'),
        'usuario_modificacion' => 0,
        'fecha_modificacion' => 0,
        'aula_paralelo_id' => $id_aula_paralelo,
        'fecha_limite' => Date('Y-m-d H:i:s'),
    );

    $id_inscripcion_historico = $db->insert('ins_inscripcion_historico', $inscripcion_Historico);
      
    $respuesta = array(
        'id_historico' => $id_inscripcion_historico,
        'estado' => 1
    );    

    echo json_encode($respuesta);
}

/****************************************************/
// el metodo guardar antiguo una inscripcion
/****************************************************/
if ($boton == "registrar_inscripcion_estudiante") {

    //var_dump($_POST);exit();

    $id_tipo_estudiante = $_POST['id_tipo_estudiante'];
    $id_nivel_academico = $_POST['id_nivel_academico'];
    $id_turno           = $_POST['id_turno'];

    //Datos para relacionar el estudiante con los tutores
    $id_estudiante      = $_POST['id_estudiante'];
    $id_aula_paralelo   = $_POST['id_aula_paralelo'];
    $id_familiar_tutor  = $_POST['id_familiar_tutor'];
    $ids_familiar       = $_POST['ids_familiar'];

    //Datos para hacer la ver si tiene o no reserva
    $estado_reserva     = $_POST['estado_reserva'];
    if ($estado_reserva == 0) {
        $estado_inscripcion = "INSCRITO";
    } else if ($estado_reserva == 1) {
        $estado_inscripcion = "RESERVA";
    }
    $fecha_limite_reserva = (isset($_POST['fecha_limite_reserva'])) ? date_encode($_POST['fecha_limite_reserva']) : "";
    $monto_reserva      = $_POST['monto_reserva'];


    $busqueda = $db->query("SELECT COUNT(codigo_inscripcion) as codigo_inscripcion FROM ins_inscripcion WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion")->fetch_first();
    $contador = $busqueda['codigo_inscripcion'];
    if ($contador > 0) {
        //echo 3;
        $respuesta = array(
            'id_inscripcion' => 0,
            'estado' => 0
        );
    } else {
        //echo 1;
        $inscripcion = array(
            'fecha_inscripcion' => Date('Y-m-d H:i:s'),
            'aula_paralelo_id' => $id_aula_paralelo,
            'estudiante_id' => $id_estudiante,
            'tipo_estudiante_id' => $id_tipo_estudiante,
            'nivel_academico_id' => $id_nivel_academico,
            'gestion_id' => $id_gestion,
            'codigo_inscripcion' => $id_estudiante . "-" . $_gestion['gestion'],
            'estado' => 'A',
            'estado_estudiante' => 'antiguo',
            'estado_inscripcion' => $estado_inscripcion,
            'estado_reserva' => $estado_reserva,
            'fecha_limite_reserva' => $fecha_limite_reserva,
            'monto_reserva' => $monto_reserva,
            'usuario_registro' => $_user['id_user'],
            'fecha_registro' => Date('Y-m-d H:i:s'),
            'turno_id' => $id_turno,
        );

        $id_inscripcion = $db->insert('ins_inscripcion', $inscripcion);
        $respuesta = array(
            'id_inscripcion' => $id_inscripcion,
            'estado' => 1
        );

        if ($id_inscripcion) {
            //Luego de Confirmarse la inscripcion procedemos a la relacion turor estudiante
            $id_fami = explode("/", $ids_familiar);
            $cant    = count($id_fami);
            $i       = 0;
            foreach ($id_fami as $key) {
                if ($key == $id_familiar_tutor) {
                    //Aqui verificamos quien es el tutor
                    $valor_tutor = 1;
                    $estudiante_familiar = array(
                        'familiar_id' => $key,
                        'estudiante_id' => $id_estudiante,
                        'tutor' => $valor_tutor
                    );
                    $id_estudiante_familiar = $db->insert('ins_estudiante_familiar', $estudiante_familiar);
                    $i++;
                } else {
                    //Aqui solo registramos al los demas tutores
                    $valor_tutor = 0;
                    $estudiante_familiar = array(
                        'familiar_id' => $key,
                        'estudiante_id' => $id_estudiante,
                        'tutor' => $valor_tutor
                    );
                    $id_estudiante_familiar = $db->insert('ins_estudiante_familiar', $estudiante_familiar);
                    $i++;
                }
            }

            //var_dump($cant."/".$i);die;
            if ($cant == $i) {
                $respuesta = array(
                    'id_inscripcion' => $id_inscripcion,
                    'estado' => 1
                );
            } else {
                $respuesta = array(
                    'id_inscripcion' => $id_inscripcion,
                    'estado' => 0
                );
            }
        } else {
            $respuesta = array(
                'id_inscripcion' => 0,
                'estado' => 0
            );
        }
    }
    //var_dump($busqueda);
    echo json_encode($respuesta);
}

if ($boton == "eliminar_familiar") {
    //var_dump($_POST);die;
    $id_estudiante_familiar = $_POST['id_estudiante_familiar'];
    if ($id_estudiante_familiar) {
        $db->delete()->from('ins_estudiante_familiar')->where('id_estudiante_familiar', $id_estudiante_familiar)->limit(1)->execute();
        if ($db->affected_rows) {
            echo 1;
        } else {
            echo 2;
        }
    } else {
        echo 3;
    }
}

if ($boton == "registrar_inscripcion_estudiante_rude") {
    //var_dump($_POST);die;    
    $id_estudiante       = $_POST['id_estudiante'];
    $id_inscripcion_rude = (isset($_POST['id_inscripcion_rude'])) ? clear($_POST['id_inscripcion_rude']) : 0;

    //$id_estudiante = "442";
    /*$aula_paralelo = $db->query("SELECT * FROM ins_aula_paralelo WHERE id_aula_paralelo = $id_aula_paralelo")->fetch_first();
        $id_aula_paralelo = $aula_paralelo['id_aula_paralelo'];*/

    //var_dump($_POST);die;
    //$busqueda = $db->query("SELECT COUNT(codigo_inscripcion) as codigo_inscripcion FROM ins_inscripcion WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion")->fetch_first();
    //$contador = $busqueda['codigo_inscripcion'];


    if ($id_inscripcion_rude < 0) {
        //para algo servira
    } else {
        //echo 1;
        //Date('Y-m-d H:i:s'),
        $vacio = "";
        $inscripcion_rude = array(
            'ins_estudiante_id' => $id_estudiante,
            'nac_pais' => $vacio,
            'nac_departamento' => $vacio,
            'nac_provincia' => $vacio,
            'nac_localidad' => $vacio,
            'nro_rude' => $vacio,
            'discapacidad' => $vacio,
            'nro_ibc' => $vacio,
            'tipo_discapacidad' => $vacio,
            'grado_discapacidad' => $vacio,
            'oficialia' => $vacio,
            'partida' => $vacio,
            'libro' => $vacio,
            'folio' => $vacio,
            'departamento' => $vacio,
            'provincia' => $vacio,
            'seccion' => $vacio,
            'localidad' => $vacio,
            'zona' => $vacio,
            'avenida' => $vacio,
            'nrovivienda' => $vacio,
            'telefono' => $vacio,
            'celular' => $vacio,
            '411' => $vacio,
            '412' => $vacio,
            '413' => $vacio,
            '421' => $vacio,
            '422' => $vacio,
            '423' => $vacio,
            '424' => $vacio,
            '431' => $vacio,
            '432' => $vacio,
            '433' => $vacio,
            '434' => $vacio,
            '435' => $vacio,
            '436' => $vacio,
            '441' => $vacio,
            '442' => $vacio,
            '451' => $vacio,
            '4511' => $vacio,
            '452' => $vacio,
            '4521' => $vacio,
            '453' => $vacio,
            '454' => $vacio,
            '455' => $vacio,
            '4551a' => $vacio,
            '461' => $vacio,
            '461a' => $vacio,
            '462' => $vacio,
            '471' => $vacio,
            '472' => $vacio,
            '472a' => $vacio,
            '51' => $vacio,
        );

        $id_inscripcion_rude = $db->insert('ins_inscripcion_rude', $inscripcion_rude);
        if ($id_inscripcion_rude) {
            $estado = 1;
        } else {
            $estado = 3;
        }
        //Creamos un array para devolver el id_ins_inscripcion_rude que se acaba de crear
        $cadena = array(
            'id_inscripcion_rude' => $id_inscripcion_rude,
            'estado' => $estado
        );
        echo json_encode($cadena);
    }
}

if ($boton == "guardar_certificado") {
    //var_dump($_POST);die;

    $nro_rude      = $_POST['nro_rude'];
    $nac_pais      = $_POST['pais'];
    $nac_departamento = $_POST['departamento'];
    $nac_provincia    = $_POST['provincia'];
    $nac_localidad    = $_POST['localidad'];
    $discapacidad  = $_POST['discapacidad'];
    $nro_ibc       = $_POST['nro_ibc'];
    $tipo_discapacidad  = $_POST['tipo_discapacidad'];
    $grado_discapacidad = $_POST['grado_discapacidad'];
    $oficialia     = $_POST['oficialia'];
    $libro         = $_POST['libro'];
    $partida       = $_POST['partida'];
    $folio         = $_POST['folio'];
    $departamento  = $_POST['departamento'];
    $provincia     = $_POST['provincia'];
    $seccion       = $_POST['seccion'];
    $localidad     = $_POST['localidad'];
    $zona          = $_POST['zona'];
    $avenida       = $_POST['avenida'];
    $nrovivienda   = $_POST['nrovivienda'];
    $telefono      = $_POST['telefono'];
    $celular       = $_POST['celular'];
    $id_estudiante = $_POST['id_estudiante'];
    $id_inscripcion_rude = (isset($_POST['id_inscripcion_rude'])) ? clear($_POST['id_inscripcion_rude']) : 0;

    //$id_estudiante = "442";
    /*$aula_paralelo = $db->query("SELECT * FROM ins_aula_paralelo WHERE id_aula_paralelo = $id_aula_paralelo")->fetch_first();
        $id_aula_paralelo = $aula_paralelo['id_aula_paralelo'];*/

    //var_dump($_POST);die;
    //$busqueda = $db->query("SELECT COUNT(codigo_inscripcion) as codigo_inscripcion FROM ins_inscripcion WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion")->fetch_first();
    //$contador = $busqueda['codigo_inscripcion'];

    if ($id_inscripcion_rude > 0) {
        /*echo "<pre>";
        var_dump($_POST);
        echo "</pre>";
        die;*/
        $vacio = "";
        $inscripcion_rude = array(
            'ins_estudiante_id' => $id_estudiante,
            'nac_pais' => $nac_pais,
            'nac_departamento' => $nac_departamento,
            'nac_provincia' => $nac_provincia,
            'nac_localidad' => $nac_localidad,
            'nro_rude' => $nro_rude,
            'discapacidad' => $discapacidad,
            'nro_ibc' => $nro_ibc,
            'tipo_discapacidad' => $tipo_discapacidad,
            'grado_discapacidad' => $grado_discapacidad,
            'oficialia' => $oficialia,
            'partida' => $partida,
            'libro' => $libro,
            'folio' => $folio,
            'departamento' => $departamento,
            'provincia' => $provincia,
            'seccion' => $seccion,
            'localidad' => $localidad,
            'zona' => $zona,
            'avenida' => $avenida,
            'nrovivienda' => $nrovivienda,
            'telefono' => $telefono,
            'celular' => $celular

        );
        // Modifica los datos del certificado de nacimiento
        $db->where('id_ins_inscripcion_rude', $id_inscripcion_rude)->update('ins_inscripcion_rude', $inscripcion_rude);
        $estado = 2;
        $cadena = array(
            'id_inscripcion_rude' => $id_inscripcion_rude,
            'estado' => $estado
        );
        echo json_encode($cadena);
    } else {
        //echo 1;
        //Date('Y-m-d H:i:s'),
        $vacio = "";
        $inscripcion_rude = array(
            'ins_estudiante_id' => $id_estudiante,
            'nac_pais' => $nac_pais,
            'nac_departamento' => $nac_departamento,
            'nac_provincia' => $nac_provincia,
            'nac_localidad' => $nac_localidad,
            'nro_rude' => $nro_rude,
            'discapacidad' => $discapacidad,
            'nro_ibc' => $nro_ibc,
            'tipo_discapacidad' => $tipo_discapacidad,
            'grado_discapacidad' => $grado_discapacidad,
            'oficialia' => $oficialia,
            'partida' => $partida,
            'libro' => $libro,
            'folio' => $folio,
            'departamento' => $departamento,
            'provincia' => $provincia,
            'seccion' => $seccion,
            'localidad' => $localidad,
            'zona' => $zona,
            'avenida' => $avenida,
            'nrovivienda' => $nrovivienda,
            'telefono' => $telefono,
            'celular' => $celular
        );

        $id_inscripcion_rude = $db->insert('ins_inscripcion_rude', $inscripcion_rude);
        if ($id_inscripcion_rude) {
            $estado = 1;
        } else {
            $estado = 3;
        }
        //Creamos un array para devolver el id_ins_inscripcion_rude que se acaba de crear
        $cadena = array(
            'id_inscripcion_rude' => $id_inscripcion_rude,
            'estado' => $estado
        );
        echo json_encode($cadena);
    }
}

if ($boton == "guardar_rude") {
    //  echo "<pre>";
    //  var_dump($_POST);
    //  echo "</pre>";
    //  die;
    $a       = (isset($_POST['a'])) ? $_POST['a'] : '';
    $b       = (isset($_POST['b'])) ? $_POST['b'] : '';
    $c       = (isset($_POST['c'])) ? $_POST['c'] : '';
    $d       = (isset($_POST['d'])) ? $_POST['d'] : '';
    $a_salud = (isset($_POST['salud'])) ? $_POST['salud'] : array();
    $salud   = implode(",", $a_salud); //array
    $e       = (isset($_POST['e'])) ? $_POST['e'] : '';
    $f       = (isset($_POST['f'])) ? $_POST['f'] : '';
    $g       = (isset($_POST['g'])) ? $_POST['g'] : '';
    $h       = (isset($_POST['h'])) ? $_POST['h'] : '';
    $i       = (isset($_POST['i'])) ? $_POST['i'] : '';
    $j       = (isset($_POST['j'])) ? $_POST['j'] : '';
    $k       = (isset($_POST['k'])) ? $_POST['k'] : '';
    $l       = (isset($_POST['l'])) ? $_POST['l'] : '';
    $a_internet = (isset($_POST['internet'])) ? $_POST['internet'] : array();
    $internet = implode(",", $a_internet); //array
    $m       = (isset($_POST['m'])) ? $_POST['m'] : '';
    $n       = (isset($_POST['n'])) ? $_POST['n'] : '';
    $a_mes   = (isset($_POST['mes'])) ? $_POST['mes'] : array();
    $mes     = implode(",", $a_mes); //array
    $a_trabajo = (isset($_POST['trabajo'])) ? $_POST['trabajo'] : array();
    $trabajo = implode(",", $a_trabajo); //array
    $o       = (isset($_POST['o'])) ? $_POST['o'] : '';
    $a_turno = (isset($_POST['turno'])) ? $_POST['turno'] : array();
    $turno   = implode(",", $a_turno); //array
    $p       = (isset($_POST['p'])) ? $_POST['p'] : '';
    $q       = (isset($_POST['q'])) ? $_POST['q'] : '';
    $a_pago  = (isset($_POST['pago'])) ? $_POST['pago'] : array();
    $pago    = implode(",", $a_pago); //array    
    $s       = (isset($_POST['s'])) ? $_POST['s'] : '';
    $s1      = (isset($_POST['461'])) ? $_POST['461'] : '';
    $t       = (isset($_POST['t'])) ? $_POST['t'] : '';
    $u       = (isset($_POST['u'])) ? $_POST['u'] : '';
    $a_abandono = (isset($_POST['abandono'])) ? $_POST['abandono'] : array();
    $abandono   = implode(",", $a_abandono); //array
    $v      = (isset($_POST['472a'])) ? $_POST['472a'] : '';
    $r      = (isset($_POST['r'])) ? $_POST['r'] : '';
    $id_inscripcion_rude = $_POST['id_inscripcion_rude'];
    //$id_inscripcion_rude = "5";       
    $contador = 0;
    if ($contador > 0) {
        echo 3;
    } else {
        //echo 1;
        //Date('Y-m-d H:i:s'),
        $vacio = "";
        $inscripcion_rude_update = array(
            '411' => $a,
            '412' => $b,
            '413' => $c,
            '421' => $d,
            '422' => $salud,
            '423' => $e,
            '424' => $f,
            '431' => $g,
            '432' => $h,
            '433' => $i,
            '434' => $j,
            '435' => $k,
            '436' => $l,
            '441' => $internet,
            '442' => $m,
            '451' => $n,
            '4511' => $mes,
            '452' => $trabajo,
            '4521' => $o,
            '453' => $turno,
            '454' => $p,
            '455' => $q,
            '4551a' => $pago,
            '461' => $s,
            '461a' => $s1,
            '462' => $t,
            '471' => $u,
            '472' => $abandono,
            '472a' => $v,
            '51' => $r
        );

        $db->where('id_ins_inscripcion_rude', $id_inscripcion_rude)->update('ins_inscripcion_rude', $inscripcion_rude_update);
        $estado = 1;
        //$id_inscripcion_rude = $db->insert('ins_inscripcion_rude', $inscripcion_rude);
        /*if ($id_inscripcion_rude) {
                $estado = 1;
            } else {
                $estado = 2;
            }*/
        //Creamos un array para devolver el id_ins_inscripcion_rude que se acaba de crear
        $cadena = array(
            'id_inscripcion_rude' => $id_inscripcion_rude,
            'estado' => $estado
        );
        echo json_encode($cadena);
    }
}

if ($boton == "guardar_concepto_pago") {
    //var_dump($_POST);die;
    $id_estudiante  = $_POST['id_estudiante'];
    //$id_inscripcion  = $_POST['id_inscripcion']; 
    $id_pensiones  = (isset($_POST['id_pensiones'])) ? $_POST['id_pensiones'] : array();
    $tipo_concepto = (isset($_POST['tipo_concepto'])) ? $_POST['tipo_concepto'] : array();

    $busqueda = $db->query("SELECT COUNT(codigo_inscripcion) as codigo_inscripcion, id_inscripcion FROM ins_inscripcion WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion")->fetch_first();

    // Obtiene datos de los pagos
    foreach ($id_pensiones as $nro => $elemento) {
        $pagos = $db->query("SELECT * FROM pen_pensiones p inner join pen_pensiones_detalle pd on p.id_pensiones=pd.pensiones_id where p.id_pensiones='$id_pensiones[$nro]' ORDER BY p.nombre_pension")->fetch();
        //var_dump($pagos);exit();
        $contador = $busqueda['codigo_inscripcion'];

        foreach ($pagos as $value) {
            $detalle_estudiante = array(
                'detalle_pension_id' => $value['id_pensiones_detalle'],
                'inscripcion_id' => $busqueda['id_inscripcion'],
                'tipo_concepto'  => $value['tipo_concepto'],
                'fecha_registro' => date('Y-m-d H:i:s'),
                'fecha_modificacion'   => '',
                'usuario_registro'     => $_user['id_user'], 
                'usuario_modificacion' => '',
                'cuota'                => $value['cuota'],
                'descuento_porcentaje' => $value['descuento_porcentaje'],
                'descuento_bs' => $value['descuento_bs'],
                'monto'        => $value['monto'],
                'mora_dia'     => $value['mora_dia'],
                'fecha_inicio' => $value['fecha_inicio'],
                'fecha_final'  => $value['fecha_final'],
            );
            //var_dump($detalle_estudiante);
            $id_pensiones_estudiante = $db->insert('pen_pensiones_estudiante', $detalle_estudiante);
        }
    }
    if ($id_pensiones_estudiante) {
        echo 1;
    } else {
        echo 2;
    }
}

if ($boton == "reporte_rude") {

    $id_estudiante       = $_REQUEST['id_estudiante'];
    $id_inscripcion_rude = $_REQUEST['id_inscripcion_rude'];
    $id_aula_paralelo    = $_REQUEST['id_inscripcion'];
    //var_dump($_REQUEST);exit();

    //$id_inscripcion_rude = $_REQUEST['id_inscripcion_rude'];

    $columna = array(
        '1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E', '6' => 'F', '7' => 'G', '8' => 'H', '9' => 'I', '10' => 'J', '11' => 'K', '12' => 'L', '13' => 'M', '14' => 'N', '15' => 'O', '16' => 'P', '17' => 'Q', '18' => 'R', '19' => 'S', '20' => 'T', '21' => 'U', '22' => 'V', '23' => 'W', '24' => 'X', '25' => 'Y', '26' => 'Z', '27' => 'AA', '27' => 'AA', '28' => 'AB', '29' => 'AC', '30' => 'AD', '31' => 'AE', '32' => 'AF', '33' => 'AG', '34' => 'AH', '35' => 'AI', '36' => 'AJ', '37' => 'AK', '38' => 'AL', '39' => 'AM', '40' => 'AN', '41' => 'AO', '42' => 'AP', '43' => 'AQ', '44' => 'AR', '45' => 'AS', '46' => 'AT', '47' => 'AU', '48' => 'AV', '49' => 'AW', '50' => 'AX'
    );

    //Colores RGB
    $aColores = array('1' => 'ECEA5C', '2' => '8AE245', '3' => 'F577F5', '4' => '537AF5', '5' => 'F35F7F', '6' => 'F752F5', '7' => 'AAFF00');


    $objPHPExcel = excel_iniciar("plantilla_rude.xls");
    //var_dump($objPHPExcel);die;  

    //Consultamos al estudiante
    $resEstudiante = $db->query("SELECT sp.nombres, sp.primer_apellido, sp.segundo_apellido, sp.numero_documento, sp.complemento,  sp.expedido, sp.fecha_nacimiento, sp.genero, sp.fecha_nacimiento
            FROM ins_inscripcion_rude AS ir
            INNER JOIN ins_estudiante AS ie ON ie.id_estudiante = ir.ins_estudiante_id
            INNER JOIN sys_persona AS sp ON sp.id_persona = ie.persona_id
            WHERE ir.id_ins_inscripcion_rude = $id_inscripcion_rude")->fetch();

    //Consultamos a los padres o tutores
    $resPadres = $db->query("SELECT p.numero_documento, p.complemento, p.primer_apellido, p.segundo_apellido, p.nombres, f.profesion,f.grado_instruccion,p.expedido,f.idioma_frecuente, p.fecha_nacimiento , f.parentesco
            FROM ins_estudiante_familiar AS ef
            INNER JOIN ins_familiar AS f ON f.id_familiar = ef.familiar_id
            INNER JOIN sys_persona AS p ON p.id_persona = f.persona_id
            WHERE ef.estudiante_id =$id_estudiante")->fetch();

    //Consultamos todos los datos del rude
    $resRUDE = $db->query("SELECT * FROM ins_inscripcion_rude WHERE id_ins_inscripcion_rude = $id_inscripcion_rude")->fetch();
    /*echo "<pre>";
        var_dump($resRUDE);exit;
        echo "</pre>"; */

    $total = sizeof($resRUDE);

    $filaExcel = 9;  //indice de fila en excel
    //si hay registros, colocar datos en las celdas de la hoja actual

    if ($total > 0) {

        $dep = $resEstudiante[0]['primer_apellido'];
        $col = 7;
        for ($i = 0; $i < strlen($resEstudiante[0]['primer_apellido']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '16', $dep[$i]);
            $col++;
        }

        $dep = $resEstudiante[0]['segundo_apellido'];
        $col = 7;
        for ($i = 0; $i < strlen($resEstudiante[0]['segundo_apellido']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '18', $dep[$i]);
            $col++;
        }

        $dep = $resEstudiante[0]['nombres'];
        $col = 7;
        for ($i = 0; $i < strlen($resEstudiante[0]['nombres']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '20', $dep[$i]);
            $col++;
        }

        $dep = $resEstudiante[0]['genero'];
        $col = 34;
        if ($dep == 'v') {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '18', "X");
            $col++;
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '20', "X");
            $col++;
        }



        $objPHPExcel->getActiveSheet()->setCellValue('B30', $resRUDE[0]['oficialia']);
        $objPHPExcel->getActiveSheet()->setCellValue('E30', $resRUDE[0]['partida']);
        $objPHPExcel->getActiveSheet()->setCellValue('I30', $resRUDE[0]['libro']);
        $objPHPExcel->getActiveSheet()->setCellValue('M69', $resRUDE[0]['folio']);

        $col = 17;
        $dep = $resEstudiante[0]['fecha_nacimiento'];
        $dep = date("d m Y", strtotime($dep));
        for ($i = 0; $i < strlen($resEstudiante[0]['fecha_nacimiento']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '30', $dep[$i]);
            $col++;
        }

        $col = 7;
        $dep = $resEstudiante[0]['numero_documento'];
        for ($i = 0; $i < strlen($resEstudiante[0]['numero_documento']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '36', $dep[$i]);
            $col++;
        }

        $col = 19;
        $dep = $resEstudiante[0]['complemento'];
        for ($i = 0; $i < strlen($resEstudiante[0]['complemento']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '36', $dep[$i]);
            $col++;
        }

        $col = 23;
        $dep = $resEstudiante[0]['expedido'];
        for ($i = 0; $i < strlen($resEstudiante[0]['expedido']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '36', $dep[$i]);
            $col++;
        }

        $col = 4;
        $dep = $resRUDE[0]['nac_pais'];
        for ($i = 0; $i < strlen($resRUDE[0]['nac_pais']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '24', $dep[$i]);
            $col++;
        }

        $col = 4;
        $dep = $resRUDE[0]['nac_departamento'];
        for ($i = 0; $i < strlen($resRUDE[0]['nac_departamento']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '26', $dep[$i]);
            $col++;
        }

        $col = 17;
        $dep = $resRUDE[0]['nac_provincia'];
        for ($i = 0; $i < strlen($resRUDE[0]['nac_provincia']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '24', $dep[$i]);
            $col++;
        }

        $col = 17;
        $dep = $resRUDE[0]['nac_localidad'];
        for ($i = 0; $i < strlen($resRUDE[0]['nac_localidad']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '26', $dep[$i]);
            $col++;
        }

        //Fin de estudiante


        $dep = $resRUDE[0]['departamento'];
        /*$resDep = $db->query("SELECT d.nombre
                            FROM sys_departamentos AS d           
                            WHERE d.id_departamento = $dep")->fetch_first();*/
        //$dep = $resDep['nombre'];
        $col = 7;
        for ($i = 0; $i < strlen($dep); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '42', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['provincia'];
        /*$resDep = $db->query("SELECT d.nombre
                            FROM sys_provincias AS d           
                            WHERE d.id_provincia = $dep")->fetch_first();*/
        //$dep = $resDep['nombre'];
        $col = 7;
        for ($i = 0; $i < strlen($dep); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '44', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['seccion'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['seccion']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '46', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['localidad'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['localidad']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '48', $dep[$i]);
            $col++;
        }


        $dep = $resRUDE[0]['zona'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['zona']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '50', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['avenida'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['avenida']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '52', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['nrovivienda'];
        $col = 7;
        for ($i = 0; $i < strlen($resRUDE[0]['nrovivienda']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '54', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['telefono'];
        $col = 22;
        for ($i = 0; $i < strlen($resRUDE[0]['telefono']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '54', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['celular'];
        $col = 37;
        for ($i = 0; $i < strlen($resRUDE[0]['celular']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '54', $dep[$i]);
            $col++;
        }

        /*$objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['411']);
            $objPHPExcel->getActiveSheet()->setCellValue('B79',$resRUDE[0]['412']);
            $objPHPExcel->getActiveSheet()->setCellValue('I62',$resRUDE[0]['413']);*/

        if ($resRUDE[0]['421'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('AR61', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('AR63', "X");
        }

        /***/
        /*$aSalud = explode(",",$resRUDE[0]['422']);
        for ($i=0; $i < sizeof($aSalud) ; $i++) { 
            if($aSalud[$i] == ""){
                $objPHPExcel->getActiveSheet()->setCellValue('AK83', "X");
            }
        }*/
        $objPHPExcel->getActiveSheet()->setCellValue('B69', $resRUDE[0]['422']);
        /***/


        $objPHPExcel->getActiveSheet()->setCellValue('B69', $resRUDE[0]['423']);

        if ($resRUDE[0]['424'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('AK83', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('AQ83', "X");
        }

        if ($resRUDE[0]['431'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('D89', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('H89', "X");
        }

        if ($resRUDE[0]['432'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('D93', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('H93', "X");
        }

        if ($resRUDE[0]['433'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('D97', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('H97', "X");
        }

        if ($resRUDE[0]['434'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('S89', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('W89', "X");
        }

        if ($resRUDE[0]['435'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('S95', "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue('W95', "X");
        }

        /****** */
        if ($resRUDE[0]['51'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('V125', "X");
        }
        if ($resRUDE[0]['51'] == "2") {
            $objPHPExcel->getActiveSheet()->setCellValue('AB125', "X");
        }
        if ($resRUDE[0]['51'] == "3") {
            $objPHPExcel->getActiveSheet()->setCellValue('AH125', "X");
        }
        if ($resRUDE[0]['51'] == "4") {
            $objPHPExcel->getActiveSheet()->setCellValue('AM125', "X");
        }
        if ($resRUDE[0]['51'] == "5") {
            $objPHPExcel->getActiveSheet()->setCellValue('AR125', "X");
        }
        /***** */

        //Consultamos a los padres o tutores
        $resPadres = $db->query("SELECT p.*, f.*
        FROM ins_estudiante_familiar AS ef
        INNER JOIN ins_familiar AS f ON f.id_familiar = ef.familiar_id
        INNER JOIN sys_persona AS p ON p.id_persona = f.persona_id
        WHERE ef.estudiante_id =$id_estudiante")->fetch();


        foreach ($resPadres as $key => $value) {
            //var_dump($value['parentesco']);exit();
            if ($value['parentesco'] == "PADRE") {

                $dep = $value['numero_documento'];
                $col = 11;
                for ($i = 0; $i < strlen($value['numero_documento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '159', $dep[$i]);
                    $col++;
                }
                $col = 19;
                $dep = $value['complemento'];
                for ($i = 0; $i < strlen($value['complemento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '159', $dep[$i]);
                    $col++;
                }

                $col = 22;
                $dep = $value['expedido'];
                for ($i = 0; $i < strlen($value['expedido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '159', $dep[$i]);
                    $col++;
                }

                $col = 11;
                $dep = $value['primer_apellido'];
                for ($i = 0; $i < strlen($value['primer_apellido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '161', $dep[$i]);
                    $col++;
                }
                $col = 11;
                $dep = $value['segundo_apellido'];
                for ($i = 0; $i < strlen($value['segundo_apellido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '163', $dep[$i]);
                    $col++;
                }
                $col = 11;
                $dep = $value['nombres'];
                for ($i = 0; $i < strlen($value['nombres']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '165', $dep[$i]);
                    $col++;
                }

                $col = 11;
                $dep = $value['idioma_frecuente'];
                for ($i = 0; $i < strlen($value['idioma_frecuente']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '167', $dep[$i]);
                    $col++;
                }

                $col = 11;
                $dep = $value['profesion'];
                for ($i = 0; $i < strlen($value['profesion']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '169', $dep[$i]);
                    $col++;
                }

                $col = 11;
                $dep = $value['grado_instruccion'];
                for ($i = 0; $i < strlen($value['grado_instruccion']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '171', $dep[$i]);
                    $col++;
                }

                $col = 12;
                $dep = $value['fecha_nacimiento'];
                $dep = date("d m Y", strtotime($dep));
                for ($i = 0; $i < strlen($value['fecha_nacimiento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '173', $dep[$i]);
                    $col++;
                }
            }
            if ($value['parentesco'] == "MADRE") {
                $dep = $value['numero_documento'];
                $col = 33;
                for ($i = 0; $i < strlen($value['numero_documento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '159', $dep[$i]);
                    $col++;
                }
                $col = 41;
                $dep = $value['complemento'];
                for ($i = 0; $i < strlen($value['complemento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '159', $dep[$i]);
                    $col++;
                }
                $col = 45;
                $dep = $value['expedido'];
                for ($i = 0; $i < strlen($value['expedido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '159', $dep[$i]);
                    $col++;
                }
                $col = 33;
                $dep = $value['primer_apellido'];
                for ($i = 0; $i < strlen($value['primer_apellido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '161', $dep[$i]);
                    $col++;
                }
                $col = 33;
                $dep = $value['segundo_apellido'];
                for ($i = 0; $i < strlen($value['segundo_apellido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '163', $dep[$i]);
                    $col++;
                }
                $col = 33;
                $dep = $value['nombres'];
                for ($i = 0; $i < strlen($value['nombres']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '165', $dep[$i]);
                    $col++;
                }

                $col = 33;
                $dep = $value['idioma_frecuente'];
                for ($i = 0; $i < strlen($value['idioma_frecuente']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '167', $dep[$i]);
                    $col++;
                }

                $col = 33;
                $dep = $value['profesion'];
                for ($i = 0; $i < strlen($value['profesion']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '169', $dep[$i]);
                    $col++;
                }

                $col = 33;
                $dep = $value['grado_instruccion'];
                for ($i = 0; $i < strlen($value['grado_instruccion']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '171', $dep[$i]);
                    $col++;
                }

                $col = 35;
                $dep = $value['fecha_nacimiento'];
                $dep = date("d m Y", strtotime($dep));
                for ($i = 0; $i < strlen($value['fecha_nacimiento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '173', $dep[$i]);
                    $col++;
                }
            }
            if ($value['parentesco'] == "TUTOR") {
                $dep = $value['numero_documento'];
                $col = 11;
                for ($i = 0; $i < strlen($value['numero_documento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '178', $dep[$i]);
                    $col++;
                }
                $col = 19;
                $dep = $value['complemento'];
                for ($i = 0; $i < strlen($value['complemento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '178', $dep[$i]);
                    $col++;
                }
                $col = 22;
                $dep = $value['expedito'];
                for ($i = 0; $i < strlen($value['expedito']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '178', $dep[$i]);
                    $col++;
                }
                $col = 11;
                $dep = $value['primer_apellido'];
                for ($i = 0; $i < strlen($value['primer_apellido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '180', $dep[$i]);
                    $col++;
                }
                $col = 11;
                $dep = $value['segundo_apellido'];
                for ($i = 0; $i < strlen($value['segundo_apellido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '182', $dep[$i]);
                    $col++;
                }
                $col = 11;
                $dep = $value['nombres'];
                for ($i = 0; $i < strlen($value['nombres']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '184', $dep[$i]);
                    $col++;
                }

                $col = 11;
                $dep = $value['idioma_frecuente'];
                for ($i = 0; $i < strlen($value['idioma_frecuente']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '186', $dep[$i]);
                    $col++;
                }

                $col = 11;
                $dep = $value['profesion'];
                for ($i = 0; $i < strlen($value['profesion']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '188', $dep[$i]);
                    $col++;
                }

                $col = 11;
                $dep = $value['grado_instruccion'];
                for ($i = 0; $i < strlen($value['grado_instruccion']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '188', $dep[$i]);
                    $col++;
                }

                $objPHPExcel->getActiveSheet()->setCellValue('K192', "TIO");

                $col = 12;
                $dep = $value['fecha_nacimiento'];
                $dep = date("d m Y", strtotime($dep));
                for ($i = 0; $i < strlen($value['fecha_nacimiento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '195', $dep[$i]);
                    $col++;
                }
            }
        }

        /*$objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['436']);
            
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['441']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['442']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['451']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['4511']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['452']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['4521']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['453']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['454']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['455']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['4551a']);
            $objPHPExcel->getActiveSheet()->setCellValue('B69',$resRUDE[0]['51']);*/



        //$objPHPExcel->getActiveSheet()->setCellValue('B' . $filaNotas,$fila_estudiante['apellidos'].' '.$fila_estudiante['nombres']);

    }

    //exit;     
    //-------------------------------------------------- finalizar
    //mostrar la primera hoja de excel
    //seleccionar una hoja
    $objPHPExcel->setActiveSheetIndex(0);   //la primera Hoja de excel se numera como 0
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '');
    excel_finalizar($objPHPExcel, "Rude.xls");
}

if ($boton == "listar_paices") {
    $nivel = $_POST['nivel'];
    $pais = $db->select('z.*')->from('sys_paises z')->order_by('id_pais')->fetch();
    echo json_encode($pais);
}

if ($boton == "listar_departamentos") {
    //obtiene el nivel
    $nivel = $_POST['nivel'];
    $id_pais = $_POST['idpais'];

    $departamento = $db->select('z.*')->from('sys_departamentos z')->where('piases_id', $id_pais)->order_by('nombre')->fetch(); //corregir columna is pais ->where('piases_id',$id_pais)
    echo json_encode($departamento);
}

if ($boton == "listar_provincias") {
    //obtiene el nivel
    $nivel = $_POST['nivel'];
    $id_pais = $_POST['idpais'];

    $departamento = $db->select('z.*')->from('sys_provincias z')->where('departamento_id', $id_pais)->order_by('nombre')->fetch(); //corregir columna is pais ->where('piases_id',$id_pais)
    echo json_encode($departamento);
}

if ($boton == "listar_localidades") {
    //obtiene el nivel
    $nivel = $_POST['nivel'];
    $idprovincia = $_POST['idpais'];

    $localidad = $db->select('z.*')->from('sys_localidades z')->where('provincia_id', $idprovincia)->order_by('nombre')->fetch(); //corregir columna is pais ->where('piases_id',$id_pais)

    echo json_encode($localidad);
}

if ($boton == "reserva") {
    //var_dump($_POST);exit();
    $reserva = $db->query("SELECT*
                            FROM  pen_pensiones p
                            INNER JOIN pen_pensiones_detalle pd ON p.id_pensiones=pd.pensiones_id
                            WHERE p.nombre_pension = 'RESERVA'")->fetch_first();
    if ($reserva) {
        echo 1;
    } else {
        echo 0;
    }
}

if ($boton == "inscribir_reserva") {
    //var_dump($_POST);exit();
    $id_estudiante = $_POST['id_estudiante'];
    $inscripcion = array(
        'estado_inscripcion' => 'INSCRITO',
        'estado_reserva' => '0',
        'usuario_modificacion' => $_user['id_user'],
        'fecha_modificacion' => $fecha_actual
    );

    $reserva = $db->where('estudiante_id', $id_estudiante)->update('ins_inscripcion', $inscripcion);
    if ($reserva) {
        echo 1;
    } else {
        echo 0;
    }
}

if ($boton == "guardar_documentos") {
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";
    // exit();
    //Rescatamos las variables
    $aCopia          = (isset($_POST['copia'])) ? $_POST['copia'] : array();
    $aOriginales     = (isset($_POST['original'])) ? $_POST['original'] : array();
    $aObservaciones  = (isset($_POST['observacion'])) ? $_POST['observacion'] : array();
    $id_estudiante   = $_POST['id_estudiante'];
    //$id_estudiante   = 7;
    $estado = 0;

    foreach ($aObservaciones as $key => $value) {
        // echo ($key." => ".$value);
        // echo ("<br>");
        $fecha_recepcion_c = "";
        $fecha_recepcion_o = "";
        $condicion_documento_copia = "";
        $condicion_documento_original = "";

        $flagRecepcion = 0;
        $c = (isset($aCopia[$key]) ? $aCopia[$key] : "");
        $o = (isset($aOriginales[$key]) ? $aOriginales[$key] : "");
        if ($c != "") {
            $copia = "X";
            $flagRecepcion = 1;
        } else {
            $copia = "";
        }
        if ($o != "") {
            $original = "X";
            $flagRecepcion = 2;
        } else {
            $original = "";
        }

        if ($flagRecepcion != 0) {
            if ($flagRecepcion == 1 && $flagRecepcion == 2) {
                $fecha_recepcion_c = Date('Y-m-d H:i:s');
                $fecha_recepcion_o = Date('Y-m-d H:i:s');
                $condicion_documento_copia = "RECEPCIONADO";
                $condicion_documento_original = "RECEPCIONADO";
            } else if ($flagRecepcion == 1) {
                $fecha_recepcion_c = Date('Y-m-d H:i:s');
                $condicion_documento_copia = "RECEPCIONADO";
            } else if ($flagRecepcion == 2) {
                $fecha_recepcion_o = Date('Y-m-d H:i:s');
                $condicion_documento_original = "RECEPCIONADO";
            }
        }
        // echo ("<br>");


        $busqueda = $db->query("SELECT	COUNT(d.tipo_documento_id) AS nro
                                    FROM	ins_documentos AS d
                                    WHERE d.estudiante_id = $id_estudiante AND d.tipo_documento_id = $key")->fetch_first();
        $nro = $busqueda['nro'];

        if ($nro > 0) {
            //Actualizar registro
            $documentos = array(
                'nombre_documento' => $aObservaciones[$key],
                'estudiante_id' => $id_estudiante,
                'copia' => $copia,
                'original' => $original,
                'fecha_recepcion_copia' => $fecha_recepcion_c,
                'fecha_devolucion_copia' => "",
                'fecha_recepcion_original' => $fecha_recepcion_o,
                'fecha_devolucion_original' => "",
                'condicion_documento_copia' => $condicion_documento_copia,
                'condicion_documento_original' => $condicion_documento_original,
                'tipo_documento_id' => $key,
                'usuario_registro' => $_user['id_user'],
                'fecha_registro' => Date('Y-m-d H:i:s'),
                'usuario_modificacion' => $_user['id_user'],
                'fecha_modificacion' => Date('Y-m-d H:i:s')
            );
            $resDocumentos = $db->where(array('estudiante_id' => $id_estudiante, 'tipo_documento_id' => $key))->update('ins_documentos', $documentos);
            //$db->query("UPDATE `bdmaranata`.`ins_documentos` SET `original`='X' WHERE  `id_documentos`=16")->execute();
            if ($resDocumentos) {
                $estado = 1;
            } else {
                $estado = 0;
            }
        } else {
            //Registro nuevo
            $documentos = array(
                'nombre_documento' => $aObservaciones[$key],
                'estudiante_id' => $id_estudiante,
                'copia' => $copia,
                'original' => $original,
                'fecha_recepcion_copia' => $fecha_recepcion_c,
                'fecha_devolucion_copia' => "",
                'fecha_recepcion_original' => $fecha_recepcion_o,
                'fecha_devolucion_original' => "",
                'condicion_documento_copia' => $condicion_documento_copia,
                'condicion_documento_original' => $condicion_documento_original,
                'tipo_documento_id' => $key,
                'usuario_registro' => $_user['id_user'],
                'fecha_registro' => Date('Y-m-d H:i:s'),
                'usuario_modificacion' => 0,
                'fecha_modificacion' => Date('Y-m-d H:i:s')
            );
            $resDocumentos = $db->insert('ins_documentos', $documentos);
        }
        if ($resDocumentos) {
            $estado = 1;
        } else {
            $estado = 0;
        }
    }
    echo $estado;
}

// Funcion reserva de cupo
if ($boton == "reservar_cupo_imprimir_comprobante") {
    //var_dump($_POST);exit();

    $id_estudiante  = $_POST['id_estudiante'];
    $id_inscripcion  = $_POST['id_inscripcion'];

    $consulta = $db->query("SELECT CONCAT(z.primer_apellido,' ',z.segundo_apellido,' ',z.nombres) nombre_completo, e.codigo_estudiante, p.numero_documento, 
    CONCAT(z.nombre_aula,' ',z.nombre_paralelo,' ',z.nombre_nivel) curso, z.nombre_tipo_estudiante, e.id_estudiante, f.*
    FROM vista_inscripciones z
    INNER JOIN ins_estudiante e ON z.estudiante_id=e.id_estudiante
    INNER JOIN sys_persona p ON e.persona_id=p.id_persona
    LEFT JOIN 
    (SELECT CONCAT(pp.nombres,' ', pp.primer_apellido,' ', pp.segundo_apellido) nombres_familiar, pp.numero_documento, ef.estudiante_id
    FROM ins_familiar f 
    INNER JOIN sys_persona pp ON f.persona_id=pp.id_persona
    INNER JOIN ins_estudiante_familiar ef ON ef.familiar_id=f.id_familiar
    WHERE ef.tutor = 1
    GROUP BY ef.estudiante_id
    ) f ON e.id_estudiante=f.estudiante_id
    WHERE z.gestion_id = $id_gestion
    and z.id_inscripcion = $id_inscripcion
    ORDER BY z.primer_apellido ASC")->fetch_first();
    //var_dump($consulta);exit();

    $concepto = $db->query("SELECT *
    FROM pen_pensiones p
    INNER JOIN pen_pensiones_detalle pd ON p.id_pensiones = pd.pensiones_id
    WHERE p.estado = 'A'
    AND pd.estado_detalle = 'A'
    AND p.nombre_pension LIKE 'reserva'")->fetch_first();

    // Instancia de pagos por estudiante y concepto mas cuotas correspondientes
    $detalle_estudiante = array(
        'detalle_pension_id' => $concepto['id_pensiones_detalle'],
        'inscripcion_id' => $id_inscripcion,
        'tipo_concepto'  => $concepto['tipo_concepto'],
        'fecha_registro' => date('Y-m-d H:i:s'),
        'fecha_modificacion'   => '',
        'usuario_registro'     => $_user['id_user'],
        'usuario_modificacion' => '',
        'cuota'                => $concepto['cuota'],
        'descuento_porcentaje' => $concepto['descuento_porcentaje'],
        'descuento_bs' => $concepto['descuento_bs'],
        'monto'        => $concepto['monto'],
        'mora_dia'     => $concepto['mora_dia'],
        'fecha_inicio' => $concepto['fecha_inicio'],
        'fecha_final'  => $concepto['fecha_final'],
        'estado_modificado'  => 'NO'

    );
    // Guarda la informacion
    $id_pensiones_estudiante = $db->insert('pen_pensiones_estudiante', $detalle_estudiante);

    $concepto_estudiante = $db->query("SELECT pe.id_pensiones_estudiante
    FROM pen_pensiones p
    INNER JOIN pen_pensiones_detalle pd ON p.id_pensiones = pd.pensiones_id
    INNER JOIN pen_pensiones_estudiante pe ON pd.id_pensiones_detalle = pe.detalle_pension_id
    WHERE p.estado = 'A'
    AND pd.estado_detalle = 'A'
    AND p.nombre_pension LIKE 'reserva'
    AND pe.inscripcion_id = $id_inscripcion")->fetch_first();

    // Obtiene los datos del adelanto  
    $nit_ci         = trim($consulta['numero_documento']);
    $nombre_cliente = trim($consulta['nombres_familiar']);
    $monto_total    = trim($_POST['monto_reserva']);

    // Obtiene el numero correlativo de la factura
    $nro_factura = $db->query("select count(id_adelanto) + 1 as nro_factura from pen_adelantos_estudiante_general where tipo = 'ADELANTO'")->fetch_first();
    $nro_factura = $nro_factura['nro_factura'];

    // Define la variable de subtotales
    $subtotales = array();

    // Obtiene la moneda
    $moneda = $db->from('inv_monedas')->where('oficial', 'S')->fetch_first();
    $moneda = ($moneda) ? $moneda['moneda'] : '';

    // Obtiene los datos del monto total
    // $conversor = new NumberToLetterConverter();
    // $monto_textual = explode('.', $monto_total);
    // $monto_numeral = $monto_textual[0];
    // $monto_decimal = $monto_textual[1];
    // $monto_literal = ucfirst(strtolower(trim($conversor->to_word($monto_numeral))));

    // Instancia el adelanto
    $adelantos = array(
        'fecha_adelanto'   => date('Y-m-d'),
        'hora_adelanto'    => date('H:i:s'),
        'tipo'             => 'ADELANTO',
        'descripcion'      => 'Adelanto de conceptos de pagos con recibo',
        'nro_factura'      => $nro_factura,
        'monto_total'      => $monto_total,
        'acuenta_total'    => 0,
        'tipo_pago'        => 'EFECTIVO',
        'nit_ci'           => $nit_ci,
        'nombre_cliente'   => mb_strtoupper($nombre_cliente, 'UTF-8'),
        'telefono'         => '',
        'direccion'        => '',
        'observacion'      => '',
        'nro_registros'    => 1,
        'estudiante_id'    => $id_estudiante,
        'documento_pago'   => 'RECIBO',
        'usuario_registro' => $_user['id_user'],
        'fecha_registro'   => date('Y-m-d H:i:s')
    );

    // Guarda la informacion del adelanto
    $adelanto_id = $db->insert('pen_adelantos_estudiante_general', $adelantos);

    // Valida si el adelanto se registro correctamente
    if ($adelanto_id) {

        // Recorre los items
        //foreach ($pensiones as $nro => $elemento) {

        // Instancia del detalle de adelantos
        $detalle = array(

            'pensiones_estudiante_id' => $concepto_estudiante['id_pensiones_estudiante'],
            'adelanto_id'             => $adelanto_id,
            'monto'                   => $monto_total,
            'descuento'               => 0,
            'usuario_modificacion'    => 0,
            'fecha_modificacion'      => '0000-00-00 00:00:00'
        );

        // Guarda la informacion
        $id_detalle = $db->insert('pen_adelantos_estudiante_detalle', $detalle);
        //}               
    }

    // Valida si existen items registrados
    if ($id_detalle) {

        // Envia respuesta
        //echo json_encode($adelanto_id);
        //redirect('?/s-pagos-adelantos/imprimir-recibo/'.$id_detalle);
        //header('Location: ?/s-pagos-adelantos/imprimir-recibo/'.$id_detalle);
        // Envia respuesta
        echo json_encode($adelanto_id);
    } else {

        // Elimina datos de la 'pen_adelantos_estudiante_general' 
        $db->delete()->from('pen_adelantos_estudiante_general')->where('id_adelanto', $adelanto_id)->limit(1)->execute();

        // Envia respuesta
        echo json_encode(0);
    }
}

if ($boton == "cargar_idioma") {
    // Obtiene los idiomas frecuentes
    $idiomas = $db->select('idioma_frecuente')->from('ins_familiar')->group_by('idioma_frecuente')->where('idioma_frecuente!=', '')->fetch();
    echo json_encode($idiomas);
}
// Fin de reserva
