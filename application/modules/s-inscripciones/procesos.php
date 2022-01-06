<?php

//var_dump($_POST);die; listar_cursos
require_once libraries . '/phpexcel-2.1/controlador.php';
//nombre de domino
$nombre_dominio = escape($_institution['nombre_dominio']);

$boton = $_POST['boton'];

// Obtiene el id de la gestion actual
$id_gestion   = $_gestion['id_gestion'];
$fecha_actual = Date('Y-m-d H:i:s');

if ($boton == "registrar_retirar_baja") {

    //var_dump($_POST);die;
    $id_estudiante      = $_POST['id_estudiante_rb'];
    $estado_inscripcion = $_POST['estado_rb'];
    $fecha_estado       = $_POST['fecha_rb'];
    $descripcion        = $_POST['descripcion_rb'];

    if($estado_inscripcion == "BAJA"){
        $a_inscripcion = array(
            'estado_inscripcion' => 'BAJA',
            'observacion_estado_inscripcion' => $descripcion,
            'fecha_baja' => $fecha_estado,
        	'estado' => 'A'
        );

        $db->insert('sys_procesos', array(
            'fecha_proceso' => date('Y-m-d'),
            'hora_proceso' => date('H:i:s'),
            'proceso' => 'd',
            'nivel' => 'm',
            'detalle' => 'Se dio de Baja a estudiante con identificador número ' . $id_estudiante . '.',
            'direccion' => $_location,
            'usuario_id' => $_user['id_user']
        ));
    }

    if($estado_inscripcion == "RETIRADO"){
        $a_inscripcion = array(
            'estado_inscripcion' => 'RETIRADO',
            'observacion_estado_inscripcion' => $descripcion,
            'fecha_retirado' => $fecha_estado,
        	'estado' => 'A'
        );

        $db->insert('sys_procesos', array(
            'fecha_proceso' => date('Y-m-d'),
            'hora_proceso' => date('H:i:s'),
            'proceso' => 'd',
            'nivel' => 'm',
            'detalle' => 'Se retiro a estudiante con identificador número ' . $id_estudiante . '.',
            'direccion' => $_location,
            'usuario_id' => $_user['id_user']
        ));
    }

    $a_condicion = array(
        'estudiante_id' => $id_estudiante,
        'gestion_id' => $id_gestion
    );

    $res_i  = $db->where($a_condicion)->update('ins_inscripcion', $a_inscripcion);
    $res_hi = $db->where($a_condicion)->update('ins_inscripcion_historial', $a_inscripcion);

    if ($res_hi) {
        echo '1';
    } else {
        echo '0';
    }   
}


if ($boton == "guardar_area") {

    //var_dump($_POST);die;
    if (isset($_POST['id_estudiante_area'])) {
        $id_estudiante = $_POST['id_estudiante_area'];
    } else {
        $id_estudiante = "0";
    }

    if (isset($_POST['area'])) {
        $area = $_POST['area'];
    } else {
        $area = "";
    }
    $sql = "UPDATE ins_inscripcion SET area='$area' WHERE  estudiante_id=$id_estudiante ";
    $area = $db->query($sql)->execute();
    if ($area) {
        echo ('4');
    } else {
        echo ('0');
    }
}

if ($boton == "buscar_curso_inscribir") {

    // var_dump($_POST);
    // die;

    $id_turno = $_POST['id_turno'];
    $id_nivel_academico = $_POST['id_nivel_academico'];
    $id_aula_paralelo = $_POST['id_aula_paralelo'];
    $id_tipo_estudiante = $_POST['id_tipo_estudiante'];


    $turno = $db->query("SELECT * 
                        FROM ins_turno 										
                        WHERE estado='A' AND gestion_id='$id_gestion' AND id_turno =" . $id_turno)->fetch_first();

    $nivel_academico = $db->query("SELECT * 
                        FROM ins_nivel_academico 										
                        WHERE estado='A' AND gestion_id='$id_gestion' AND id_nivel_academico =" . $id_nivel_academico)->fetch_first();

    $tipo_estudiante = $db->query("SELECT * 
                        FROM ins_tipo_estudiante 										
                        WHERE estado='A' AND gestion_id='$id_gestion' AND id_tipo_estudiante =" . $id_tipo_estudiante)->fetch_first();

    $aula_paralelo = $db->query("SELECT CONCAT(a.nombre_aula,' ', p.descripcion)AS curso_inscrito, na.nombre_nivel
                        FROM ins_aula_paralelo AS ap
                        INNER JOIN ins_aula AS a ON ap.aula_id = a.id_aula
                        INNER JOIN ins_paralelo AS p ON ap.paralelo_id = p.id_paralelo
                        INNER JOIN ins_nivel_academico AS na ON a.nivel_academico_id = na.id_nivel_academico
                        WHERE ap.id_aula_paralelo = '$id_aula_paralelo' AND ap.estado = 'A'")->fetch_first();

    if ($aula_paralelo) {
        $respuesta = array(
            'curso'    => $aula_paralelo['curso_inscrito'],
            'nivel'    => $aula_paralelo['nombre_nivel'],
            'turno'    => $turno['nombre_turno'],
            'tipo'    => $tipo_estudiante['nombre_tipo_estudiante'],
            'estado'      => 1
        );
        echo json_encode($respuesta);
    } else {
        $respuesta = array(
            'curso'    => $aula_paralelo['curso_inscrito'],
            'nivel'    => $aula_paralelo['nombre_nivel'],
            'turno'    => $turno['nombre_turno'],
            'tipo'    => $id_tipo_estudiante['nombre_tipo_estudiante'],
            'estado'      => 2
        );
        echo json_encode($respuesta);
    }
}

if ($boton == "listar_familiares") {
    //Obtiene los estudiantes
    //var_dump($_POST);die;
    if (isset($_POST['id_estudiante'])) {
        $id_estudiante = $_POST['id_estudiante'];
    } else {
        $id_estudiante = "";
    }
    //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
    //$familiar = $db->query("SELECT * FROM vista_estudiante_familiar WHERE id_estudiante = $id_estudiante")->fetch();
    $familiar = $db->query("SELECT * FROM vista_estudiante_familiar vf inner join ins_familiar f on vf.id_familiar=f.id_familiar WHERE vf.id_estudiante = $id_estudiante AND f.estado='A' ")->fetch();
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

    $datos_personales = $db->query("SELECT e.id_estudiante, e.codigo_estudiante, e.persona_id, p.*,  i.*, ir.*, v.*
                FROM ins_estudiante AS e
                LEFT JOIN sys_persona p ON p.id_persona = e.persona_id
                LEFT JOIN ins_inscripcion AS i ON i.estudiante_id = e.id_estudiante
                LEFT JOIN ins_inscripcion_rude AS ir ON ir.ins_estudiante_id = i.estudiante_id
                LEFT JOIN ins_documentos AS d ON d.estudiante_id = e.id_estudiante
                LEFT JOIN ins_vacunas AS v ON v.estudiante_id = e.id_estudiante
                                        WHERE e.id_estudiante = $id_estudiante AND i.gestion_id = $id_gestion AND i.estado = 'A'")->fetch_first();
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

    // echo "<pre>";
    // var_dump($array);
    // echo "</pre>";
    // exit();

    echo json_encode($array);
}

if ($boton == "datos_estudiante_ant") {

    //var_dump($_POST);
    $id_estudiante = $_POST['id_estudiante'];

    //$array = array();    
    $datos_estudiante_ant = $db->query("SELECT * FROM ins_datos_estudiante d WHERE estado = 'A' AND id_datos_estudiante=" . $id_estudiante)->fetch();
    echo json_encode($datos_estudiante_ant);
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
    $nombres_telefono  = $_POST['f_nom_telefono'];
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

    //Añadimos un nuevo familiar
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

        /********************************************************************** */

        $findt = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'ñ', 'á', 'é', 'í', 'ó', 'ú');
        $replt = array('A', 'E', 'I', 'O', 'U', 'N', 'N', 'A', 'E', 'I', 'O', 'U');
        $nuevo_nombre_tutor = explode(" ", $nombres);
        $username_nombre_tutor     = $nuevo_nombre_tutor[0];

        $username_nombre_tutor  = str_replace($findt, $replt, $username_nombre_tutor);
        $paterno_tutor    = str_replace($findt, $replt, $primer_apellido);
        $materno_tutor    = str_replace($findt, $replt, $segundo_apellido);


        if ($paterno_tutor != '') {
            $username_apellido_tutor = clear($paterno_tutor);
        } else {
            $username_apellido_tutor = clear($materno_tutor);
        }

        if ($numero_documento) {
            $password_tutor = clear($numero_documento);
        } else if ($$fecha_nacimiento != '') {
            $password_tutor = $$fecha_nacimiento;
            //  var_dump($ci_tutor.'$fecha_nacimiento');
        } else {
            $password_tutor = strtoupper('T' . $username_nombre_tutor . $username_apellido_tutor);
            //var_dump($password_tutor . 'PASSWOR TUTOR');
        }


        // Instancia el usuario
        $usuario_tutores = array(
            'username'    => strtoupper('T' . $username_nombre_tutor . '.' . $username_apellido_tutor),
            'password'     => encrypt($password_tutor),
            'email'     => clear(strtolower($email)),
            'active'     => 's',
            'visible'     => 's',
            'rol_id'     => 6,
            'avatar'   => '',
            'login_at'   => '0000-00-00 00:00:00',
            'logout_at' => '0000-00-00 00:00:00',
            'persona_id' => $id_persona,
            'gestion_id' => $id_gestion
        );
        //var_dump($usuario);

        // Crea el usuario
        $id_usuario_tutor_ = $db->insert('sys_users', $usuario_tutores);
        /********************************************************************** */


        //var_dump($id_persona);exit;

        if ($id_persona) {

            if ($nombre_imagen) {
                $ruta_temporal = 'files/'.$nombre_dominio.'/profiles/temporal/fotos/' . $nombre_imagen; //ruta temporal
                $nombre = md5(secret . random_string() .  $id_familiar_e); //encripta el nombre de la imagen a md5
                $ruta_destino = 'files/'.$nombre_dominio.'/profiles/familiares/' . $nombre . '.jpg'; //ruta de destino
                copy($ruta_temporal, $ruta_destino); //copia la imagen de la carpeta temporal a estudiante
                unlink($ruta_temporal); //elimina la imagen de la carpeta temporal
            } else {
                $nombre = (isset($_POST['foto_tutor'])) ? clear($_POST['foto_tutor']) : "";
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
                'nombres_telefono_oficina' => $nombres_telefono,
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
                $ruta_temporal = 'files/'.$nombre_dominio.'/profiles/temporal/fotos/' . $nombre_imagen; //ruta temporal
                $nombre = md5(secret . random_string() .  $id_familiar_e); //encripta el nombre de la imagen a md5
                $ruta_destino = 'files/'.$nombre_dominio.'/profiles/familiares/' . $nombre . '.jpg'; //ruta de destino
                copy($ruta_temporal, $ruta_destino); //copia la imagen de la carpeta temporal a estudiante
                unlink($ruta_temporal); //elimina la imagen de la carpeta temporal
            } else {
                $nombre = (isset($_POST['foto_tutor'])) ? clear($_POST['foto_tutor']) : "";
            }

            $db->query("UPDATE sys_persona set foto = '$nombre' WHERE id_persona = $id_persona_e")->execute();

            $familiar = array(
                'profesion' => $profesion,
                'direccion_oficina' => $direccion_oficina,
                'telefono_oficina' => $telefono,
                'nombres_telefono_oficina' => $nombres_telefono,
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


            /********************************************************************** */

            $findt = array('Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ', 'ñ', 'á', 'é', 'í', 'ó', 'ú');
            $replt = array('A', 'E', 'I', 'O', 'U', 'N', 'N', 'A', 'E', 'I', 'O', 'U');
            $nuevo_nombre_tutor = explode(" ", $nombres);
            $username_nombre_tutor     = $nuevo_nombre_tutor[0];

            $username_nombre_tutor  = str_replace($findt, $replt, $username_nombre_tutor);
            $paterno_tutor    = str_replace($findt, $replt, $primer_apellido);
            $materno_tutor    = str_replace($findt, $replt, $segundo_apellido);


            if ($paterno_tutor != '') {
                $username_apellido_tutor = clear($paterno_tutor);
            } else {
                $username_apellido_tutor = clear($materno_tutor);
            }

            if ($numero_documento) {
                $password_tutor = clear($numero_documento);
            } else if ($$fecha_nacimiento != '') {
                $password_tutor = $$fecha_nacimiento;
                //  var_dump($ci_tutor.'$fecha_nacimiento');
            } else {
                $password_tutor = strtoupper('T' . $username_nombre_tutor . $username_apellido_tutor);
                //var_dump($password_tutor . 'PASSWOR TUTOR');
            }


            // Instancia el usuario
            $usuario_tutores = array(
                'username'    => strtoupper('T' . $username_nombre_tutor . '.' . $username_apellido_tutor),
                'password'     => encrypt($password_tutor),
                'email'     => clear(strtolower($email)),
                'active'     => 's',
                'visible'     => 's',
                'rol_id'     => 6,
                'avatar'   => '',
                'login_at'   => '0000-00-00 00:00:00',
                'logout_at' => '0000-00-00 00:00:00',
                'persona_id' => $id_persona,
                'gestion_id' => $id_gestion
            );
            //var_dump($usuario);

            // Crea el usuario
            $id_usuario_tutor_ = $db->insert('sys_users', $usuario_tutores);
            /********************************************************************** */

            //var_dump($id_persona);exit;

            if ($id_persona) {

                if ($nombre_imagen) {
                    $ruta_temporal = 'files/'.$nombre_dominio.'/profiles/temporal/fotos/' . $nombre_imagen; //ruta temporal
                    $nombre = md5(secret . random_string() .  $id_familiar_e); //encripta el nombre de la imagen a md5
                    $ruta_destino = 'files/'.$nombre_dominio.'/profiles/familiares/' . $nombre . '.jpg'; //ruta de destino
                    copy($ruta_temporal, $ruta_destino); //copia la imagen de la carpeta temporal a estudiante
                    unlink($ruta_temporal); //elimina la imagen de la carpeta temporal
                } else {
                    $nombre = (isset($_POST['foto_tutor'])) ? clear($_POST['foto_tutor']) : "";
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
                    'nombres_telefono_oficina' => $nombres_telefono,
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
    $cursos = $db->query("SELECT ap.id_aula_paralelo, ap.capacidad, a.nombre_aula , p.nombre_paralelo , p.descripcion , na.nombre_nivel
                            FROM ins_aula_paralelo AS ap
                            INNER JOIN ins_aula AS a ON a.id_aula = ap.aula_id
                            INNER JOIN ins_nivel_academico AS na ON na.id_nivel_academico = a.nivel_academico_id
                            INNER JOIN ins_paralelo AS p ON p.id_paralelo = ap.paralelo_id
                            WHERE a.nivel_academico_id = $nivel AND ap.estado = 'A' AND a.estado = 'A' AND ap.turno_id = $turno
                            ORDER BY a.id_aula, p.id_paralelo")->fetch();
    echo json_encode($cursos);
}

//obtiene el listado de cursos listar_cursos
if ($boton == "listar_cursos_editar") {

    //var_dump($_POST);exit();
    //obtiene el nivel
    $nivel = $_POST['nivel'];
    $turno = $_POST['turno'];
    $aula_paralelo_id = $_POST['aula_paralelo_id'];

    //Buscamos el curso asignado para poder sacar los paralelos
    $nombre_curso = "SELECT ap.id_aula_paralelo, ap.capacidad, a.nombre_aula , p.nombre_paralelo , p.descripcion
                    FROM ins_aula_paralelo AS ap
                    INNER JOIN ins_aula AS a ON a.id_aula = ap.aula_id
                    INNER JOIN ins_paralelo AS p ON p.id_paralelo = ap.paralelo_id                            
                    WHERE a.nivel_academico_id = $nivel AND ap.estado = 'A' AND a.estado = 'A' AND ap.turno_id =  $turno AND ap.id_aula_paralelo =  $aula_paralelo_id";
    $resNombreCurso = $db->query($nombre_curso)->fetch_first();
    //var_dump($resNombreCurso['nombre_aula']);exit();

    $nombre_aula = $resNombreCurso['nombre_aula'];
    //obtiene los cursos segun el nivel
    // $cursos = $db->query(
    //     "SELECT ap.id_aula_paralelo, ap.capacidad, a.nombre_aula , p.nombre_paralelo , p.descripcion
    //                         FROM ins_aula_paralelo AS ap
    //                         INNER JOIN ins_aula AS a ON a.id_aula = ap.aula_id
    //                         INNER JOIN ins_paralelo AS p ON p.id_paralelo = ap.paralelo_id
    //                         WHERE a.nivel_academico_id = $nivel AND ap.estado = 'A' AND a.estado = 'A' AND ap.turno_id = $turno AND a.nombre_aula = '$nombre_aula'"
    // )->fetch();

    $cursos = $db->query(
        "SELECT ap.id_aula_paralelo, ap.capacidad, a.nombre_aula , p.nombre_paralelo , p.descripcion
                            FROM ins_aula_paralelo AS ap
                            INNER JOIN ins_aula AS a ON a.id_aula = ap.aula_id
                            INNER JOIN ins_paralelo AS p ON p.id_paralelo = ap.paralelo_id
                            WHERE a.nivel_academico_id = $nivel AND ap.estado = 'A' AND a.estado = 'A' AND ap.turno_id = $turno AND a.gestion_id=$id_gestion"
    )->fetch();
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
    $consulta_aula = $db->query("SELECT * 
                                    FROM ins_aula_paralelo AS iap
                                    INNER JOIN ins_aula AS ia ON ia.id_aula = iap.aula_id
                                    WHERE iap.id_aula_paralelo = $id_aula_paralelo ")->fetch_first();
    //obtiene el total de vacantes del curso paralelo        
    $vacantes = $consulta_aula['capacidad'] - $consulta['contador'];
    echo json_encode($vacantes);
}

if ($boton == "seleccionar_tutor") {
    //var_dump($_POST);die;
    $id_estudiante_familiar = $_POST['id_estudiante_familiar'];
    $id_estudiante = $_POST['id_estudiante'];
    $id_tutor = $_POST['id_tutor']; // es el id familiar de la relacien estudinate_familiar

    $tutor = array('tutor' => 1); //instancia tutor

    //selecciona al tutor
    $db->where('id_estudiante_familiar', $id_estudiante_familiar)->update('ins_estudiante_familiar', $tutor);
    //$consulta = "UPDATE ins_estudiante_familiar SET tutor = 1 WHERE id_estudiante_familiar = $id_estudiante_familiar";

    // $familiar = array('tutor' => 0);
    // //$consulta ="UPDATE ins_estudiante_familiar SET tutor = 0 WHERE id_estudiante_familiar <> $id_estudiante_familiar AND estudiante_id = $id_estudiante";

    // $res = $db->query("UPDATE ins_estudiante_familiar SET tutor = 0 WHERE id_estudiante_familiar <> $id_estudiante_familiar AND estudiante_id = $id_estudiante")->execute();

    if ($db->affected_rows) {
        echo 1;
    } else {
        echo 2;
    }
}

if ($boton == "borrar_tutor") {
    //var_dump($_POST);die;
    $id_estudiante_familiar = $_POST['id_estudiante_familiar'];
    // $id_estudiante = $_POST['id_estudiante'];
    $id_tutor = $_POST['id_tutor']; // es el id familiar de la relacien estudinate_familiar
    $familiar = array('tutor' => 0);
    //$consulta ="UPDATE ins_estudiante_familiar SET tutor = 0 WHERE id_estudiante_familiar <> $id_estudiante_familiar AND estudiante_id = $id_estudiante";
    $db->query("UPDATE ins_estudiante_familiar SET tutor = 0 WHERE id_estudiante_familiar = $id_estudiante_familiar AND familiar_id = $id_tutor")->execute();

    //var_dump ($db);

    if ($db->affected_rows) {
        echo 1;
    } else {
        echo 2;
    }
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

   
    $nro = $db->query("SELECT COUNT(i.id_inscripcion) AS inscritos
            FROM ins_inscripcion AS i
            WHERE i.aula_paralelo_id = $id_aula_paralelo")->fetch_first();

    $cap = $db->query("SELECT ap.capacidad
                        FROM ins_aula_paralelo AS ap
                        WHERE ap.id_aula_paralelo = $id_aula_paralelo")->fetch_first();


    if (($nro['inscritos'] * 1) <= ($cap['capacidad'] * 1)) {
        $estado = 1;
        $respuesta = array(
            'id_aula_paralelo' => ($id_aula_paralelo * 1),
            'tipo_estudiante'  => ($tipo_estudiante * 1),
            'id_turno'         => ($id_turno * 1),
            'nivel_academico'  => ($id_nivel_academico * 1),
            'estado' => ($estado * 1)
        );
    } else {
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

    $estudiante_id     = $_POST['estudiante_id'];
    //Capturamos el curso elegido
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";
    // exit();
    if ($estudiante_id > 0) {


        //consultamos la inscripcion
        $estudiante_id     = $_POST['estudiante_id'];
        $sql_inscripcion = "SELECT *
        FROM ins_inscripcion
        WHERE estudiante_id = $estudiante_id AND gestion_id = $id_gestion AND estado = 'A'";

        $res_inscripcion = $db->query($sql_inscripcion)->fetch_first();

        // editamos en la tabla inscripcion

        $a_inscripcion = array(
            'aula_paralelo_id' => $_POST['select_curso'],
            'tipo_estudiante_id' => $_POST['a_id_tipo_estudiante'],
            'nivel_academico_id' => $_POST['a_id_nivel_academico'],
            'turno_id' => $_POST['a_id_turno'],
        );

        $condicion = array(
            'estudiante_id' => $_POST['estudiante_id'],
            'gestion_id' => $id_gestion,
            'estado' => 'A'
        );

        $db->where($condicion)->update('ins_inscripcion', $a_inscripcion);

        // editamos en la tabla ins_inscripcion_historial

        $a_inscripcion_historial = array(
            'aula_paralelo_id' => $_POST['select_curso'],
            'tipo_estudiante_id' => $_POST['a_id_tipo_estudiante'],
            'nivel_academico_id' => $_POST['a_id_nivel_academico'],
            'turno_id' => $_POST['a_id_turno'],
        );

        $condicion = array(
            'inscripcion_id' => $res_inscripcion['id_inscripcion'],
            'estudiante_id' => $_POST['estudiante_id'],
            'gestion_id' => $id_gestion,
            'estado' => 'A'
        );

        $db->where($condicion)->update('ins_inscripcion_historial', $a_inscripcion_historial);


        //Introducimos los datos anteriores al Historial
        $id_aula_paralelo   = $_POST['a_id_curso'];
        $tipo_estudiante    = $_POST['a_id_tipo_estudiante'];
        $estudiante_id     = $_POST['estudiante_id'];



        $inscripcion_historico = array(
            'inscripcion_id' => $res_inscripcion['id_inscripcion'],
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

        $id_inscripcion_historico = $db->insert('ins_inscripcion_historico', $inscripcion_historico);

        $respuesta = array(
            'id_historico' => $id_inscripcion_historico,
            'estado' => 1
        );

        echo json_encode($respuesta);
    } else {
        $respuesta = array(
            'id_historico' => 0,
            'estado' => 2
        );

        echo json_encode($respuesta);
    }
}

/****************************************************/
// el metodo guardar antiguo una inscripcion
/****************************************************/
if ($boton == "registrar_inscripcion_estudiante") {

    // echo "<pre>";
    // var_dump($_POST);exit();
    // echo "</pre>";

    $id_tipo_estudiante = $_POST['id_tipo_estudiante'];
    $id_nivel_academico = $_POST['id_nivel_academico'];
    $id_turno           = $_POST['id_turno'];

    //Datos para relacionar el estudiante con los tutores
    $id_estudiante      = $_POST['id_estudiante'];
    $id_aula_paralelo   = $_POST['id_aula_paralelo'];
    $id_familiar_tutor  = $_POST['id_familiar_tutor'];
    $ids_familiar       = $_POST['ids_familiar'];

    //Datos de la inscripcion que corresponden a la de inscripcion
    $ue_procedencia     = $_POST['ue_procedencia'];
    $observacion        = $_POST['observacion'];
    $estado_inscripcion = $_POST['estado_inscripcion'];

    //Datos para para ver si tiene o no reserva
    $estado_reserva     = $_POST['estado_reserva'];

    $fecha_limite_reserva = (isset($_POST['fecha_limite_reserva'])) ? date_encode($_POST['fecha_limite_reserva']) : "0000-00-00";
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
            'ue_procedencia' => $ue_procedencia,
            'estado_estudiante' => 'nuevo',
            'estado_inscripcion' => $estado_inscripcion,
            'observacion' => $observacion,
            'estado_reserva' => $estado_reserva,
            'fecha_limite_reserva' => $fecha_limite_reserva,
            'monto_reserva' => $monto_reserva,
            'usuario_registro' => $_user['id_user'],
            'fecha_registro' => Date('Y-m-d H:i:s'),
            'usuario_modificacion' => '0',
            'fecha_modificacion' => '0000-00-00 00:00:00',
            'turno_id' => $id_turno,
        );

        $id_inscripcion = $db->insert('ins_inscripcion', $inscripcion);


        //Guardamos a ins_historial
        $inscripcion_historial  = array(
            'inscripcion_id' => $id_inscripcion,
            'fecha_inscripcion' => Date('Y-m-d H:i:s'),
            'aula_paralelo_id' => $id_aula_paralelo,
            'estudiante_id' => $id_estudiante,
            'tipo_estudiante_id' => $id_tipo_estudiante,
            'nivel_academico_id' => $id_nivel_academico,
            'gestion_id' => $id_gestion,
            'codigo_inscripcion' => $id_estudiante . "-" . $_gestion['gestion'],
            'estado' => 'A',
            'ue_procedencia' => $ue_procedencia,
            'estado_estudiante' => 'antiguo',
            'estado_inscripcion' => $estado_inscripcion,
            'observacion' => $observacion,
            'estado_reserva' => $estado_reserva,
            'fecha_limite_reserva' => $fecha_limite_reserva,
            'monto_reserva' => $monto_reserva,
            'usuario_registro' => $_user['id_user'],
            'fecha_registro' => Date('Y-m-d H:i:s'),
            'usuario_modificacion' => '0',
            'fecha_modificacion' => '0000-00-00 00:00:00',
            'turno_id' => $id_turno
        );

        $id_inscripcion_historial = $db->insert('ins_inscripcion_historial', $inscripcion_historial);

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

    // echo "<pre>";
    // var_dump($respuesta);
    // echo "</pre>";
    // exit();
    //var_dump($busqueda);
    echo json_encode($respuesta);
}

if ($boton == "eliminar_familiar") {
    //var_dump($_POST);die;
    $id_estudiante_familiar = $_POST['id_estudiante_familiar'];
    $id_familiar            = $_POST['id_familiar'];


    if ($id_estudiante_familiar) {
        //$db->delete()->from('ins_estudiante_familiar')->where('id_estudiante_familiar', $id_estudiante_familiar)->limit(1)->execute();
        $sql = "SELECT  *
            FROM ins_estudiante_familiar AS ief
            WHERE ief.id_estudiante_familiar = $id_estudiante_familiar AND ief.familiar_id = $id_familiar";

        $res = $db->query($sql)->fetch_first();

        if ($res['tutor'] == '1') {
            echo 2; //No se puede eliminar es tutor
        } else {
            $aFamiliar = array('estado' => 'I',);
            $db->where('id_familiar', $id_familiar)->update('ins_familiar', $aFamiliar);
            echo 1; //Se dio de baja con exito
        }

        // if ($db->affected_rows) {
        //     echo 1;
        // } else {
        //     echo 2;
        // }
    } else {
        echo 3; //no llego el id_familiar_estudiante
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

    $nro_rude      = (isset($_POST['nro_rude'])) ? clear($_POST['nro_rude']) : "";
    $nac_pais      = (isset($_POST['n_pais'])) ? clear($_POST['n_pais']) : "";
    $nac_departamento = (isset($_POST['n_departamento'])) ? clear($_POST['n_departamento']) : "";
    $nac_provincia    = (isset($_POST['n_provincia'])) ? clear($_POST['n_provincia']) : "";
    $nac_localidad    = (isset($_POST['n_localidad'])) ? clear($_POST['n_localidad']) : "";
    $discapacidad  = (isset($_POST['discapacidad'])) ? clear($_POST['discapacidad']) : "";
    $nro_ibc       = (isset($_POST['nro_ibc'])) ? clear($_POST['nro_ibc']) : "";
    $tipo_discapacidad  = (isset($_POST['tipo_discapacidad'])) ? clear($_POST['tipo_discapacidad']) : "";
    $grado_discapacidad = (isset($_POST['grado_discapacidad'])) ? clear($_POST['grado_discapacidad']) : "";
    $oficialia     = (isset($_POST['oficialia'])) ? clear($_POST['oficialia']) : "";
    $libro         = (isset($_POST['libro'])) ? clear($_POST['libro']) : "";
    $partida       = (isset($_POST['partida'])) ? clear($_POST['partida']) : "";
    $folio         = (isset($_POST['folio'])) ? clear($_POST['folio']) : "";
    $departamento  = (isset($_POST['departamento'])) ? clear($_POST['departamento']) : "";
    $provincia     = (isset($_POST['provincia'])) ? clear($_POST['provincia']) : "";
    $seccion       = (isset($_POST['seccion'])) ? clear($_POST['seccion']) : "";
    $localidad     = (isset($_POST['localidad'])) ? clear($_POST['localidad']) : "";
    $zona          = (isset($_POST['zona'])) ? clear($_POST['zona']) : "";;
    $avenida       = (isset($_POST['avenida'])) ? clear($_POST['avenida']) : "";
    $nrovivienda   = (isset($_POST['nrovivienda'])) ? clear($_POST['nrovivienda']) : "";
    $telefono      = (isset($_POST['telefono'])) ? clear($_POST['telefono']) : "";
    $celular       = (isset($_POST['celular'])) ? clear($_POST['celular']) : "";
    $id_estudiante = (isset($_POST['id_estudiante'])) ? clear($_POST['id_estudiante']) : "";
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
    // echo "<pre>";
    // var_dump($_POST);
    // echo "</pre>";
    // die;
    $a       = (isset($_POST['a'])) ? $_POST['a'] : '';
    $b       = (isset($_POST['b'])) ? $_POST['b'] : '';

    $b1       = (isset($_POST['b1'])) ? $_POST['b1'] : '';
    $b2       = (isset($_POST['b2'])) ? $_POST['b2'] : '';
    $b3       = (isset($_POST['b3'])) ? $_POST['b3'] : '';

    $b123   = $b1 . "@" . $b2 . "@" . $b3;

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
            '412' => $b123,
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

    $id_estudiante  = $_REQUEST['id_estudiante'];


    $sql = "SELECT *
    FROM ins_inscripcion_rude AS iir
    WHERE iir.ins_estudiante_id = $id_estudiante";
    $ainscripcion_rude = $db->query($sql)->fetch_first();
    //var_dump($_REQUEST);exit();

    $id_inscripcion_rude = $ainscripcion_rude['id_ins_inscripcion_rude'];

    $columna = array(
        '1' => 'A', '2' => 'B', '3' => 'C', '4' => 'D', '5' => 'E', '6' => 'F', '7' => 'G', '8' => 'H', '9' => 'I', '10' => 'J', '11' => 'K', '12' => 'L', '13' => 'M', '14' => 'N', '15' => 'O', '16' => 'P', '17' => 'Q', '18' => 'R', '19' => 'S', '20' => 'T', '21' => 'U', '22' => 'V', '23' => 'W', '24' => 'X', '25' => 'Y', '26' => 'Z', '27' => 'AA', '27' => 'AA', '28' => 'AB', '29' => 'AC', '30' => 'AD', '31' => 'AE', '32' => 'AF', '33' => 'AG', '34' => 'AH', '35' => 'AI', '36' => 'AJ', '37' => 'AK', '38' => 'AL', '39' => 'AM', '40' => 'AN', '41' => 'AO', '42' => 'AP', '43' => 'AQ', '44' => 'AR', '45' => 'AS', '46' => 'AT', '47' => 'AU', '48' => 'AV', '49' => 'AW', '50' => 'AX', '51' => 'AY', '52' => 'AZ', '53' => 'BA', '54' => 'BB', '55' => 'BC', '56' => 'BD', '56' => 'BE', '57' => 'BF', '58' => 'BG', '59' => 'BH', '60' => 'BI', '61' => 'BJ', '62' => 'BK', '63' => 'BL', '64' => 'BM', '65' => 'BN', '66' => 'BO', '67' => 'BP', '68' => 'BQ', '69' => 'BR', '70' => 'BS', '71' => 'BT', '72' => 'BU', '73' => 'BV', '74' => 'BW', '75' => 'BX', '76' => 'BY', '77' => 'BZ', '78' => 'CA', '79' => 'CB', '80' => 'CC', '81' => 'CD', '82' => 'CE', '83' => 'CF', '84' => 'CG', '85' => 'CH', '86' => 'CI', '87' => 'CJ', '88' => 'CK', '89' => 'CL', '90' => 'CM', '91' => 'CN', '92' => 'CO', '93' => 'CP', '94' => 'CQ', '95' => 'CR', '96' => 'CS', '97' => 'CT', '98' => 'CU', '99' => 'CV', '100' => 'CW', '101' => 'CX', '102' => 'CY', '103' => 'CZ'
    );

    //Colores RGB
    $aColores = array('1' => 'ECEA5C', '2' => '8AE245', '3' => 'F577F5', '4' => '537AF5', '5' => 'F35F7F', '6' => 'F752F5', '7' => 'AAFF00');


    //$objPHPExcel = excel_iniciar("plantilla_rude.xls");
    $objPHPExcel = excel_iniciar("plantilla_rude_uno.xls");
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
    // echo "<pre>";
    // var_dump($resRUDE);
    // echo "</pre>";
    // exit;

    $total = sizeof($resRUDE);

    $filaExcel = 9;  //indice de fila en excel
    //si hay registros, colocar datos en las celdas de la hoja actual

    $nombre_estudiante = $resEstudiante[0]['nombres'] . ' ' . $resEstudiante[0]['primer_apellido'] . ' ' . $resEstudiante[0]['segundo_apellido'];

    if ($total > 0) {

        $dep = $resEstudiante[0]['primer_apellido'];
        $col = 9;
        for ($i = 0; $i < strlen($resEstudiante[0]['primer_apellido']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '15', $dep[$i]);
            $col++;
        }

        $dep = $resEstudiante[0]['segundo_apellido'];
        $col = 9;
        for ($i = 0; $i < strlen($resEstudiante[0]['segundo_apellido']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '17', $dep[$i]);
            $col++;
        }

        $dep = $resEstudiante[0]['nombres'];
        $col = 9;
        for ($i = 0; $i < strlen($resEstudiante[0]['nombres']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '19', $dep[$i]);
            $col++;
        }

        $objPHPExcel->getActiveSheet()->setCellValue('AD15', $resRUDE[0]['nro_rude']);

        $dep = $resEstudiante[0]['genero'];
        $col = 36;
        if ($dep == 'v') {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '17', "X");
            $col++;
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '19', "X");
            $col++;
        }


        $_dis = $resEstudiante[0]['discapacidad'];

        if ($_dis == '1') {
            $objPHPExcel->getActiveSheet()->setCellValue("AX17", "X");
        } else {
            $objPHPExcel->getActiveSheet()->setCellValue("AX19", "X");
        }

        $objPHPExcel->getActiveSheet()->setCellValue('AP21', $resRUDE[0]['nro_ibc']);

        $objPHPExcel->getActiveSheet()->setCellValue('D30', $resRUDE[0]['oficialia']);
        $objPHPExcel->getActiveSheet()->setCellValue('H30', $resRUDE[0]['partida']);
        $objPHPExcel->getActiveSheet()->setCellValue('L30', $resRUDE[0]['libro']);
        $objPHPExcel->getActiveSheet()->setCellValue('P30', $resRUDE[0]['folio']);

        $col = 20;
        $dep = $resEstudiante[0]['fecha_nacimiento'];

        if ($dep != "0000-00-00") {
            $dep = date("d m Y", strtotime($dep));
            for ($i = 0; $i < strlen($resEstudiante[0]['fecha_nacimiento']); $i++) {
                $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '30', $dep[$i]);
                $col++;
            }
        }


        $col = 12;
        $dep = $resEstudiante[0]['numero_documento'];
        for ($i = 0; $i < strlen($resEstudiante[0]['numero_documento']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '34', $dep[$i]);
            $col++;
        }

        $col = 24;
        $dep = $resEstudiante[0]['complemento'];
        for ($i = 0; $i < strlen($resEstudiante[0]['complemento']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '34', $dep[$i]);
            $col++;
        }

        $col = 27;
        $dep = $resEstudiante[0]['expedido'];
        for ($i = 0; $i < strlen($resEstudiante[0]['expedido']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '34', $dep[$i]);
            $col++;
        }

        $col = 6;
        $dep = strtoupper($resRUDE[0]['nac_pais']);
        for ($i = 0; $i < strlen($resRUDE[0]['nac_pais']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '24', $dep[$i]);
            $col++;
        }

        $col = 20;
        $dep = strtoupper($resRUDE[0]['nac_departamento']);
        for ($i = 0; $i < strlen($resRUDE[0]['nac_departamento']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '24', $dep[$i]);
            $col++;
        }

        $col = 6;
        $dep = strtoupper($resRUDE[0]['nac_provincia']);
        for ($i = 0; $i < strlen($resRUDE[0]['nac_provincia']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '26', $dep[$i]);
            $col++;
        }

        $col = 20;
        $dep = strtoupper($resRUDE[0]['nac_localidad']);
        for ($i = 0; $i < strlen($resRUDE[0]['nac_localidad']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '26', $dep[$i]);
            $col++;
        }

        //Fin de estudiante


        $dep = strtoupper($resRUDE[0]['departamento']);
        /*$resDep = $db->query("SELECT d.nombre
                            FROM sys_departamentos AS d           
                            WHERE d.id_departamento = $dep")->fetch_first();*/
        //$dep = $resDep['nombre'];
        $col = 15;
        for ($i = 0; $i < strlen($dep); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '38', $dep[$i]);
            $col++;
        }

        $dep = strtoupper($resRUDE[0]['provincia']);
        /*$resDep = $db->query("SELECT d.nombre
                            FROM sys_provincias AS d           
                            WHERE d.id_provincia = $dep")->fetch_first();*/
        //$dep = $resDep['nombre'];
        $col = 15;
        for ($i = 0; $i < strlen($dep); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '40', $dep[$i]);
            $col++;
        }

        $dep = strtoupper($resRUDE[0]['seccion']);
        $col = 15;
        for ($i = 0; $i < strlen($resRUDE[0]['seccion']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '42', $dep[$i]);
            $col++;
        }

        $dep = strtoupper($resRUDE[0]['localidad']);
        $col = 15;
        for ($i = 0; $i < strlen($resRUDE[0]['localidad']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '44', $dep[$i]);
            $col++;
        }


        $dep = strtoupper($resRUDE[0]['zona']);
        $col = 15;
        for ($i = 0; $i < strlen($resRUDE[0]['zona']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '46', $dep[$i]);
            $col++;
        }

        $dep = strtoupper($resRUDE[0]['avenida']);
        $col = 15;
        for ($i = 0; $i < strlen($resRUDE[0]['avenida']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '48', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['nrovivienda'];
        $col = 15;
        for ($i = 0; $i < strlen($resRUDE[0]['nrovivienda']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '50', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['telefono'];
        $col = 28;
        for ($i = 0; $i < strlen($resRUDE[0]['telefono']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '50', $dep[$i]);
            $col++;
        }

        $dep = $resRUDE[0]['celular'];
        $col = 44;
        for ($i = 0; $i < strlen($resRUDE[0]['celular']); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '50', $dep[$i]);
            $col++;
        }

        $objPHPExcel->getActiveSheet()->setCellValue('C63', strtoupper($resRUDE[0]['411']));
        //Lenguajes que mayormente habla}
        $idiomas = explode("@", $resRUDE[0]['412']);


        // 413 pertenece a una nacion indigena originaria
        $nacion = $resRUDE[0]['413'];

        if ($nacion == "NINGUNO") {
            $objPHPExcel->getActiveSheet()->setCellValue('M59', "X");
        }

        if ($nacion == "AFROBOLIVIANO") {
            $objPHPExcel->getActiveSheet()->setCellValue('M61', "X");
        }

        if ($nacion == "ESSE EJA") {
            $objPHPExcel->getActiveSheet()->setCellValue('T63', "X");
        }

        if ($nacion == "AYMARA") {
            $objPHPExcel->getActiveSheet()->setCellValue('M65', "X");
        }

        if ($nacion == "AYOROA") {
            $objPHPExcel->getActiveSheet()->setCellValue('M67', "X");
        }

        if ($nacion == "BAURES") {
            $objPHPExcel->getActiveSheet()->setCellValue('M69', "X");
        }

        if ($nacion == "CANICHANA") {
            $objPHPExcel->getActiveSheet()->setCellValue('M71', "X");
        }

        if ($nacion == "CABINEﾃ前") {
            $objPHPExcel->getActiveSheet()->setCellValue('M73', "X");
        }

        if ($nacion == "CAYUBABA") {
            $objPHPExcel->getActiveSheet()->setCellValue('M75', "X");
        }

        if ($nacion == "CHACOBO") {
            $objPHPExcel->getActiveSheet()->setCellValue('M77', "X");
        }

        if ($nacion == "CHIMAN") {
            $objPHPExcel->getActiveSheet()->setCellValue('T59', "X");
        }

        if ($nacion == "CHIQUITANO(MONKOX)") {
            $objPHPExcel->getActiveSheet()->setCellValue('T61', "X");
        }

        if ($nacion == "ESE EJJA") {
            $objPHPExcel->getActiveSheet()->setCellValue('T63', "X");
        }

        if ($nacion == "GUARANI") {
            $objPHPExcel->getActiveSheet()->setCellValue('T65', "X");
        }

        if ($nacion == "GUARASUG WE") {
            $objPHPExcel->getActiveSheet()->setCellValue('T67', "X");
        }

        if ($nacion == "ITOMANO") {
            $objPHPExcel->getActiveSheet()->setCellValue('T71', "X");
        }

        if ($nacion == "LECO") {
            $objPHPExcel->getActiveSheet()->setCellValue('T73', "X");
        }

        if ($nacion == "KALLAWAYA") {
            $objPHPExcel->getActiveSheet()->setCellValue('T75', "X");
        }

        if ($nacion == "MACHINERI") {
            $objPHPExcel->getActiveSheet()->setCellValue('T77', "X");
        }

        if ($nacion == "MAROPA") {
            $objPHPExcel->getActiveSheet()->setCellValue('Z59', "X");
        }

        if ($nacion == "MOJOS-IGNACIANO") {
            $objPHPExcel->getActiveSheet()->setCellValue('Z61', "X");
        }

        if ($nacion == "MOJOS-TRINITARIO") {
            $objPHPExcel->getActiveSheet()->setCellValue('Z63', "X");
        }

        if ($nacion == "MORE") {
            $objPHPExcel->getActiveSheet()->setCellValue('Z65', "X");
        }

        if ($nacion == "MOSETEN") {
            $objPHPExcel->getActiveSheet()->setCellValue('Z67', "X");
        }

        if ($nacion == "MOVIMA") {
            $objPHPExcel->getActiveSheet()->setCellValue('Z69', "X");
        }

        if ($nacion == "PACAWARA") {
            $objPHPExcel->getActiveSheet()->setCellValue('Z71', "X");
        }

        if ($nacion == "PUKINA") {
            $objPHPExcel->getActiveSheet()->setCellValue('Z73', "X");
        }

        if ($nacion == "QUECHUA") {
            $objPHPExcel->getActiveSheet()->setCellValue('Z75', "X");
        }

        if ($nacion == "SIRIONO") {
            $objPHPExcel->getActiveSheet()->setCellValue('Z77', "X");
        }

        if ($nacion == "TACANA") {
            $objPHPExcel->getActiveSheet()->setCellValue('AF59', "X");
        }

        if ($nacion == "TAPIETE") {
            $objPHPExcel->getActiveSheet()->setCellValue('AF61', "X");
        }

        if ($nacion == "TOROMONA") {
            $objPHPExcel->getActiveSheet()->setCellValue('AF63', "X");
        }

        if ($nacion == "URU CHIPAYA") {
            $objPHPExcel->getActiveSheet()->setCellValue('AF65', "X");
        }

        if ($nacion == "WEENHAYEK") {
            $objPHPExcel->getActiveSheet()->setCellValue('AF67', "X");
        }

        if ($nacion == "YAMANAHUA") {
            $objPHPExcel->getActiveSheet()->setCellValue('AF69', "X");
        }

        if ($nacion == "YUKI") {
            $objPHPExcel->getActiveSheet()->setCellValue('AF71', "X");
        }

        if ($nacion == "YUCARARE") {
            $objPHPExcel->getActiveSheet()->setCellValue('AF73', "X");
        }




        $objPHPExcel->getActiveSheet()->setCellValue('D73', $idiomas[0]);
        $objPHPExcel->getActiveSheet()->setCellValue('D75', $idiomas[1]);
        $objPHPExcel->getActiveSheet()->setCellValue('D77', $idiomas[2]);

        if ($resRUDE[0]['421'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('AX55', "X");
        } else if ($resRUDE[0]['421'] == "0") {
            $objPHPExcel->getActiveSheet()->setCellValue('AX57', "X");
        }



        /***** */
        $aSalud = explode(",", $resRUDE[0]['422']);
        for ($i = 0; $i < sizeof($aSalud); $i++) {
            if ($aSalud[$i] == "4221") {
                $objPHPExcel->getActiveSheet()->setCellValue('AR63', "X");
            }
            if ($aSalud[$i] == "4222") {
                $objPHPExcel->getActiveSheet()->setCellValue('AR65', "X");
            }
            if ($aSalud[$i] == "4223") {
                $objPHPExcel->getActiveSheet()->setCellValue('AR67', "X");
            }
            if ($aSalud[$i] == "4224") {
                $objPHPExcel->getActiveSheet()->setCellValue('AX63', "X");
            }
            if ($aSalud[$i] == "4225") {
                $objPHPExcel->getActiveSheet()->setCellValue('AX65', "X");
            }
            if ($aSalud[$i] == "4226") {
                $objPHPExcel->getActiveSheet()->setCellValue('AX67', "X");
            }
        }

        if ($resRUDE[0]['423'] == '1 a 2 veces') {
            $objPHPExcel->getActiveSheet()->setCellValue('AK75', "X");
        }

        if ($resRUDE[0]['423'] == '3 a 5 veces') {
            $objPHPExcel->getActiveSheet()->setCellValue('AO75', "X");
        }

        if ($resRUDE[0]['423'] == '6 o mﾃ｡s veces') {
            $objPHPExcel->getActiveSheet()->setCellValue('AT75', "X");
        }

        if ($resRUDE[0]['423'] == 'Ninguna') {
            $objPHPExcel->getActiveSheet()->setCellValue('AX75', "X");
        }

        /***/


        if ($resRUDE[0]['424'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('AR77', "X");
        } else if ($resRUDE[0]['424'] == "0") {
            $objPHPExcel->getActiveSheet()->setCellValue('AV77', "X");
        }

        if ($resRUDE[0]['431'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('G82', "X");
        } else if ($resRUDE[0]['431'] == "0") {
            $objPHPExcel->getActiveSheet()->setCellValue('K82', "X");
        }

        if ($resRUDE[0]['432'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('G86', "X");
        } else if ($resRUDE[0]['432'] == "0") {
            $objPHPExcel->getActiveSheet()->setCellValue('K86', "X");
        }

        if ($resRUDE[0]['433'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('G90', "X");
        } else if ($resRUDE[0]['433'] == "0") {
            $objPHPExcel->getActiveSheet()->setCellValue('K90', "X");
        }

        if ($resRUDE[0]['434'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('Y82', "X");
        } else if ($resRUDE[0]['434'] == "0") {
            $objPHPExcel->getActiveSheet()->setCellValue('AC82', "X");
        }

        if ($resRUDE[0]['435'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('Y86', "X");
        } else if ($resRUDE[0]['435'] == "0") {
            $objPHPExcel->getActiveSheet()->setCellValue('AC86', "X");
        }

        /****** */
        if ($resRUDE[0]['436'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('AO84', "X");
        }

        if ($resRUDE[0]['436'] == "2") {
            $objPHPExcel->getActiveSheet()->setCellValue('AO86', "X");
        }

        if ($resRUDE[0]['436'] == "3") {
            $objPHPExcel->getActiveSheet()->setCellValue('AO88', "X");
        }

        if ($resRUDE[0]['436'] == "4") {
            $objPHPExcel->getActiveSheet()->setCellValue('AY84', "X");
        }

        if ($resRUDE[0]['436'] == "5") {
            $objPHPExcel->getActiveSheet()->setCellValue('AY86', "X");
        }

        if ($resRUDE[0]['436'] == "6") {
            $objPHPExcel->getActiveSheet()->setCellValue('AY88', "X");
        }
        /****** */

        $internet = explode(",", $resRUDE[0]['441']);

        for ($i = 0; $i < sizeof($internet); $i++) {
            if ($internet[$i] == "4411") {
                $objPHPExcel->getActiveSheet()->setCellValue('I96', "X");
            }
            if ($internet[$i] == "4412") {
                $objPHPExcel->getActiveSheet()->setCellValue('I98', "X");
            }
            if ($internet[$i] == "4413") {
                $objPHPExcel->getActiveSheet()->setCellValue('R96', "X");
            }
            if ($internet[$i] == "4414") {
                $objPHPExcel->getActiveSheet()->setCellValue('R98', "X");
            }
            if ($internet[$i] == "4415") {
                $objPHPExcel->getActiveSheet()->setCellValue('Y96', "X");
            }
        }

        /****** */

        if ($resRUDE[0]['442'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('AJ96', "X");
        }

        if ($resRUDE[0]['442'] == "2") {
            $objPHPExcel->getActiveSheet()->setCellValue('AJ98', "X");
        }

        if ($resRUDE[0]['442'] == "3") {
            $objPHPExcel->getActiveSheet()->setCellValue('AT96', "X");
        }

        if ($resRUDE[0]['442'] == "4") {
            $objPHPExcel->getActiveSheet()->setCellValue('AT98', "X");
        }

        /****** */
        if ($resRUDE[0]['451'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('H104', "X");
        }

        if ($resRUDE[0]['451'] == "2") {
            $objPHPExcel->getActiveSheet()->setCellValue('E104', "X");
        }

        if ($resRUDE[0]['451'] == "3") {
            $objPHPExcel->getActiveSheet()->setCellValue('E108', "X");
        }

        /****** */

        $meses = explode(",", $resRUDE[0]['4511']);

        for ($i = 0; $i < sizeof($meses); $i++) {
            if ($meses[$i] == "4511") {
                cellColor('I106', '000');
            }
            if ($meses[$i] == "4512") {
                cellColor('K106', '000');
            }
            if ($meses[$i] == "4513") {
                cellColor('M106', '000');
            }
            if ($meses[$i] == "4514") {
                cellColor('O106', '000');
            }
            if ($meses[$i] == "4515") {
                cellColor('I108', '000');
            }
            if ($meses[$i] == "4516") {
                cellColor('K108', '000');
            }
            if ($meses[$i] == "4517") {
                cellColor('M108', '000');
            }
            if ($meses[$i] == "4518") {
                cellColor('O108', '000');
            }
            if ($meses[$i] == "4519") {
                cellColor('I110', '000');
            }
            if ($meses[$i] == "45110") {
                cellColor('K110', '000');
            }
            if ($meses[$i] == "45111") {
                cellColor('M110', '000');
            }
            if ($meses[$i] == "45112") {
                cellColor('O110', '000');
            }
        }

        /****** */

        $trabajo = explode(",", $resRUDE[0]['452']);

        for ($i = 0; $i < sizeof($trabajo); $i++) {
            if ($trabajo[$i] == "4521") {
                $objPHPExcel->getActiveSheet()->setCellValue('V104', "X");
            }
            if ($trabajo[$i] == "4522") {
                $objPHPExcel->getActiveSheet()->setCellValue('V106', "X");
            }
            if ($trabajo[$i] == "4523") {
                $objPHPExcel->getActiveSheet()->setCellValue('V108', "X");
            }
            if ($trabajo[$i] == "4524") {
                $objPHPExcel->getActiveSheet()->setCellValue('V110', "X");
            }
            if ($trabajo[$i] == "4525") {
                $objPHPExcel->getActiveSheet()->setCellValue('V112', "X");
            }
            if ($trabajo[$i] == "4526") {
                $objPHPExcel->getActiveSheet()->setCellValue('AC104', "X");
            }
            if ($trabajo[$i] == "4527") {
                $objPHPExcel->getActiveSheet()->setCellValue('AC106', "X");
            }
            if ($trabajo[$i] == "4528") {
                $objPHPExcel->getActiveSheet()->setCellValue('AC108', "X");
            }
            if ($trabajo[$i] == "4529") {
                $objPHPExcel->getActiveSheet()->setCellValue('AC110', "X");
            }
            if ($trabajo[$i] == "45210") {
                $objPHPExcel->getActiveSheet()->setCellValue('AL104', "X");
            }
            if ($trabajo[$i] == "45211") {
                $objPHPExcel->getActiveSheet()->setCellValue('AL106', "X");
            }
            if ($trabajo[$i] == "45212") {
                $objPHPExcel->getActiveSheet()->setCellValue('AL108', "X");
            }
            if ($trabajo[$i] == "45213") {
                $objPHPExcel->getActiveSheet()->setCellValue('AL110', "X");
            }
        }

        /****** */
        $objPHPExcel->getActiveSheet()->setCellValue('AH112', strtoupper($resRUDE[0]['4521']));

        /****** */

        $h_trabajo = explode(",", $resRUDE[0]['453']);

        for ($i = 0; $i < sizeof($h_trabajo); $i++) {
            if ($h_trabajo[$i] == "4531") {
                $objPHPExcel->getActiveSheet()->setCellValue('AV104', "X");
            }
            if ($h_trabajo[$i] == "4532") {
                $objPHPExcel->getActiveSheet()->setCellValue('AV104', "X");
            }
            if ($h_trabajo[$i] == "4533") {
                $objPHPExcel->getActiveSheet()->setCellValue('AY104', "X");
            }
        }

        /****** */

        if ("Todos los dias" == $resRUDE[0]['454']) {
            cellColor('AM108', '000');
        }
        if ("Fines de semana" == $resRUDE[0]['454']) {
            cellColor('AQ108', '000');
        }
        if ("Dias festivos" == $resRUDE[0]['454']) {
            cellColor('AV108', '000');
        }
        if ("Dias Hﾃ｡biles" == $resRUDE[0]['454']) {
            cellColor('AM110', '000');
        }
        if ("Eventual/esporﾃ｡dico" == $resRUDE[0]['454']) {
            cellColor('AQ110', '000');
        }
        if ("En vacaciones" == $resRUDE[0]['454']) {
            cellColor('AV110', '000');
        }

        /****** */

        $pago = explode(",", $resRUDE[0]['455']);
        for ($i = 0; $i < sizeof($pago); $i++) {
            if ($pago[$i] == "1") {
                $objPHPExcel->getActiveSheet()->setCellValue('AS112', "X");
            }
            if ($pago[$i] == "2") {
                $objPHPExcel->getActiveSheet()->setCellValue('AU112', "X");
            }
            if ($pago[$i] == "3") {
                $objPHPExcel->getActiveSheet()->setCellValue('AS113', "X");
            }
        }

        /***** */

        $s_pago = explode(",", $resRUDE[0]['4551a']);

        for ($i = 0; $i < sizeof($s_pago); $i++) {
            if ($s_pago[$i] == "4551") {
                cellColor('AV112', '000');
            }
            if ($s_pago[$i] == "4552") {
                cellColor('AV113', '000');
            }
        }

        /****** */
        if ($resRUDE[0]['461'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('L122', "X");
        }
        if ($resRUDE[0]['461'] == "2") {
            $objPHPExcel->getActiveSheet()->setCellValue('L124', "X");
        }
        if ($resRUDE[0]['461'] == "3") {
            $objPHPExcel->getActiveSheet()->setCellValue('L126', "X");
        }
        if ($resRUDE[0]['461'] == "4") {
            $objPHPExcel->getActiveSheet()->setCellValue('L128', "X");
        }

        /****** */
        $objPHPExcel->getActiveSheet()->setCellValue('C130', strtoupper($resRUDE[0]['461a']));


        /****** */
        if ($resRUDE[0]['462'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('W126', "X");
        }
        if ($resRUDE[0]['462'] == "2") {
            $objPHPExcel->getActiveSheet()->setCellValue('W128', "X");
        }
        if ($resRUDE[0]['462'] == "3") {
            $objPHPExcel->getActiveSheet()->setCellValue('W130', "X");
        }
        if ($resRUDE[0]['462'] == "4") {
            $objPHPExcel->getActiveSheet()->setCellValue('W132', "X");
        }

        /****** */
        if ($resRUDE[0]['471'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('AR117', "X");
        }
        if ($resRUDE[0]['471'] == "2") {
            $objPHPExcel->getActiveSheet()->setCellValue('AR119', "X");
        }

        /****** */
        $abandono = explode(",", $resRUDE[0]['472']);

        for ($i = 0; $i < sizeof($abandono); $i++) {
            if ($abandono[$i] == "4721") {
                $objPHPExcel->getActiveSheet()->setCellValue('AM122', "X");
            }
            if ($abandono[$i] == "4722") {
                $objPHPExcel->getActiveSheet()->setCellValue('AM124', "X");
            }
            if ($abandono[$i] == "4723") {
                $objPHPExcel->getActiveSheet()->setCellValue('AM126', "X");
            }
            if ($abandono[$i] == "4724") {
                $objPHPExcel->getActiveSheet()->setCellValue('AM128', "X");
            }
            if ($abandono[$i] == "4725") {
                $objPHPExcel->getActiveSheet()->setCellValue('AM130', "X");
            }
            if ($abandono[$i] == "4726") {
                $objPHPExcel->getActiveSheet()->setCellValue('AM132', "X");
            }
            if ($abandono[$i] == "4727") {
                $objPHPExcel->getActiveSheet()->setCellValue('AY122', "X");
            }
            if ($abandono[$i] == "4728") {
                $objPHPExcel->getActiveSheet()->setCellValue('AY124', "X");
            }
            if ($abandono[$i] == "4729") {
                $objPHPExcel->getActiveSheet()->setCellValue('AY126', "X");
            }
            if ($abandono[$i] == "47210") {
                $objPHPExcel->getActiveSheet()->setCellValue('AY128', "X");
            }
            if ($abandono[$i] == "47211") {
                $objPHPExcel->getActiveSheet()->setCellValue('AY130', "X");
            }
        }

        /****** */

        $objPHPExcel->getActiveSheet()->setCellValue('AR132', strtoupper($resRUDE[0]['472a']));

        /****** */
        if ($resRUDE[0]['51'] == "1") {
            $objPHPExcel->getActiveSheet()->setCellValue('Y142', "X");
        }
        if ($resRUDE[0]['51'] == "2") {
            $objPHPExcel->getActiveSheet()->setCellValue('AF142', "X");
        }
        if ($resRUDE[0]['51'] == "3") {
            $objPHPExcel->getActiveSheet()->setCellValue('AM142', "X");
        }
        if ($resRUDE[0]['51'] == "4") {
            $objPHPExcel->getActiveSheet()->setCellValue('AS142', "X");
        }
        if ($resRUDE[0]['51'] == "5") {
            $objPHPExcel->getActiveSheet()->setCellValue('AY142', "X");
        }
        /***** */

        //Consultamos a los padres o tutores
        $resPadres = $db->query("SELECT *
                	FROM ins_inscripcion i
                	INNER JOIN ins_estudiante_familiar ef ON i.estudiante_id = ef.estudiante_id
                	INNER JOIN ins_familiar f ON ef.familiar_id = f.id_familiar
                	INNER JOIN sys_persona per ON f.persona_id = per.id_persona
                	WHERE i.estudiante_id = $id_estudiante AND i.gestion_id = $id_gestion AND f.estado='A'")->fetch();


        foreach ($resPadres as $key => $value) {
            //var_dump($value['parentesco']);exit();
            if (strtoupper($value['parentesco']) == "PADRE") {

                $dep = $value['numero_documento'];
                $col = 12;
                for ($i = 0; $i < strlen($value['numero_documento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '146', $dep[$i]);
                    $col++;
                }
                $col = 21;
                $dep = strtoupper($value['complemento']);
                for ($i = 0; $i < strlen($value['complemento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '146', $dep[$i]);
                    $col++;
                }

                $col = 24;
                $dep = strtoupper($value['expedido']);
                for ($i = 0; $i < strlen($value['expedido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '146', $dep[$i]);
                    $col++;
                }

                $col = 12;
                $dep = strtoupper($value['primer_apellido']);
                for ($i = 0; $i < strlen($value['primer_apellido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '148', $dep[$i]);
                    $col++;
                }
                $col = 12;
                $dep = strtoupper($value['segundo_apellido']);
                for ($i = 0; $i < strlen($value['segundo_apellido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '150', $dep[$i]);
                    $col++;
                }
                $col = 12;
                $dep = strtoupper($value['nombres']);
                for ($i = 0; $i < strlen($value['nombres']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '152', $dep[$i]);
                    $col++;
                }

                $col = 12;
                $dep = strtoupper($value['idioma_frecuente']);
                for ($i = 0; $i < strlen($value['idioma_frecuente']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '154', $dep[$i]);
                    $col++;
                }

                $col = 12;
                $dep = strtoupper($value['profesion']);
                for ($i = 0; $i < strlen($value['profesion']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '156', $dep[$i]);
                    $col++;
                }

                $col = 12;
                $dep = strtoupper($value['grado_instruccion']);
                for ($i = 0; $i < strlen($value['grado_instruccion']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '158', $dep[$i]);
                    $col++;
                }

                $col = 13;
                $dep = $value['fecha_nacimiento'];
                if ($dep != "0000-00-00") {
                    //$objPHPExcel->getActiveSheet()->setCellValue('C160', $dep);
                    $dep = date("d m Y", strtotime($dep));
                    for ($i = 0; $i < strlen($value['fecha_nacimiento']); $i++) {
                        $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '160', $dep[$i]);
                        $col++;
                    }
                }
            }
            if (strtoupper($value['parentesco']) == "MADRE") {
                $dep = $value['numero_documento'];
                $col = 37;
                for ($i = 0; $i < strlen($value['numero_documento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '146', $dep[$i]);
                    $col++;
                }
                $col = 46;
                $dep = strtoupper($value['complemento']);
                for ($i = 0; $i < strlen($value['complemento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '146', $dep[$i]);
                    $col++;
                }
                $col = 49;
                $dep = strtoupper($value['expedido']);
                for ($i = 0; $i < strlen($value['expedido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '146', $dep[$i]);
                    $col++;
                }
                $col = 37;
                $dep = strtoupper($value['primer_apellido']);
                for ($i = 0; $i < strlen($value['primer_apellido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '148', $dep[$i]);
                    $col++;
                }
                $col = 37;
                $dep = strtoupper($value['segundo_apellido']);
                for ($i = 0; $i < strlen($value['segundo_apellido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '150', $dep[$i]);
                    $col++;
                }
                $col = 37;
                $dep = strtoupper($value['nombres']);
                for ($i = 0; $i < strlen($value['nombres']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '152', $dep[$i]);
                    $col++;
                }

                $col = 37;
                $dep = strtoupper($value['idioma_frecuente']);
                for ($i = 0; $i < strlen($value['idioma_frecuente']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '154', $dep[$i]);
                    $col++;
                }

                $col = 37;
                $dep = strtoupper($value['profesion']);
                for ($i = 0; $i < strlen($value['profesion']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '156', $dep[$i]);
                    $col++;
                }

                $col = 37;
                $dep = strtoupper($value['grado_instruccion']);
                for ($i = 0; $i < strlen($value['grado_instruccion']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '158', $dep[$i]);
                    $col++;
                }

                $col = 38;
                $dep = $value['fecha_nacimiento'];
                if ($dep != "0000-00-00") {
                    $dep = date("d m Y", strtotime($dep));
                    for ($i = 0; $i < strlen($value['fecha_nacimiento']); $i++) {
                        $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '160', $dep[$i]);
                        $col++;
                    }
                }
            }
            if (strtoupper($value['parentesco']) == "TUTOR") {
                $dep = $value['numero_documento'];
                $col = 12;
                for ($i = 0; $i < strlen($value['numero_documento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '166', $dep[$i]);
                    $col++;
                }
                $col = 21;
                $dep = strtoupper($value['complemento']);
                for ($i = 0; $i < strlen($value['complemento']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '166', $dep[$i]);
                    $col++;
                }
                $col = 24;
                $dep = strtoupper($value['expedito']);
                for ($i = 0; $i < strlen($value['expedito']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '166', $dep[$i]);
                    $col++;
                }
                $col = 12;
                $dep = strtoupper($value['primer_apellido']);
                for ($i = 0; $i < strlen($value['primer_apellido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '168', $dep[$i]);
                    $col++;
                }
                $col = 12;
                $dep = strtoupper($value['segundo_apellido']);
                for ($i = 0; $i < strlen($value['segundo_apellido']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '170', $dep[$i]);
                    $col++;
                }
                $col = 12;
                $dep = strtoupper($value['nombres']);
                for ($i = 0; $i < strlen($value['nombres']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '172', $dep[$i]);
                    $col++;
                }

                $col = 12;
                $dep = strtoupper($value['idioma_frecuente']);
                for ($i = 0; $i < strlen($value['idioma_frecuente']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '174', $dep[$i]);
                    $col++;
                }

                $col = 12;
                $dep = strtoupper($value['profesion']);
                for ($i = 0; $i < strlen($value['profesion']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '176', $dep[$i]);
                    $col++;
                }

                $col = 12;
                $dep = strtoupper($value['grado_instruccion']);
                for ($i = 0; $i < strlen($value['grado_instruccion']); $i++) {
                    $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '178', $dep[$i]);
                    $col++;
                }

                $objPHPExcel->getActiveSheet()->setCellValue('L180', strtoupper($value['parentesco']));

                $col = 12;
                $dep = $value['fecha_nacimiento'];
                if ($dep != "0000-00-00") {
                    $dep = date("d m Y", strtotime($dep));
                    for ($i = 0; $i < strlen($value['fecha_nacimiento']); $i++) {
                        $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '180', $dep[$i]);
                        $col++;
                    }
                }
            }
        }

        $col = 33;

        $valor = "LA PAZ";

        for ($i = 0; $i < strlen($valor); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '166', $valor[$i]);
            $col++;
        }

        $fecha_registro = Date('d  m  Y', strtotime($fecha_actual));
        $col = 33;
        for ($i = 0; $i < strlen($fecha_registro); $i++) {
            $objPHPExcel->getActiveSheet()->setCellValue($columna[$col] . '170', $fecha_registro[$i]);
            $col++;
        }
    }

    //exit;     
    //-------------------------------------------------- finalizar
    //mostrar la primera hoja de excel
    //seleccionar una hoja
    $objPHPExcel->setActiveSheetIndex(0);   //la primera Hoja de excel se numera como 0
    //$objPHPExcel->getActiveSheet()->setCellValue('A1', '');
    excel_finalizar($objPHPExcel, "RUDE " . $nombre_estudiante . ".xls");
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
        $fecha_recepcion_c = "0000-00-00 00:00:00";
        $fecha_recepcion_o = "0000-00-00 00:00:00";
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
                'fecha_devolucion_copia' => "0000-00-00",
                'fecha_recepcion_original' => $fecha_recepcion_o,
                'fecha_devolucion_original' => "0000-00-00",
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
                'fecha_devolucion_copia' => "0000-00-00",
                'fecha_recepcion_original' => $fecha_recepcion_o,
                'fecha_devolucion_original' => "0000-00-00",
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

if ($boton == "nro_cuotas") {
    $codigo  = $_POST['codigo'];
    $respuesta = $db->query("SELECT * FROM ins_datos_estudiante WHERE id_datos_estudiante =$codigo")->fetch_first();
    echo $respuesta['cuotas_pendiente'];
}


function cellColor($cells, $color)
{
    global $objPHPExcel;

    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
            'rgb' => $color
        )
    ));
}


// Fin de reserva
