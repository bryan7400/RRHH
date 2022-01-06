<?php
// Verifica la peticion post
if (is_post()) {
    $accion = $_POST['accion'];
    $id_gestion = $_gestion['id_gestion'];
    $fecha_actual = Date('Y-m-d'); //'2020-03-03';//Date('Y-m-d');
    //var_dump($accion);exit();
    $nombre_dominio = escape($_institution['nombre_dominio']);
    //kardex

    if ($accion == "listar_modos") {

        $modo_calificacion = $db->query("SELECT * FROM cal_modo_calificacion
        WHERE gestion_id=$id_gestion AND estado='A'")->fetch();
        echo json_encode($modo_calificacion);
    }

    if ($accion == "listar_estud_kardex") {
        $asignacion_docente_id = $_POST['asignacion_docente_id']; //INNER JOIN pro_asignacion_docente apam ON apam.aula_paralelo_id=ap.id_aula_paralelo
        $id_bimestre = $_POST['id_bimestre'];
        $tipo_extra = $_POST['tipo_extra'];
        if ($tipo_extra != 'N') {

            $estudiantes_cursos = $db->query("
				SELECT
				 (SELECT   COUNT(ci.archivo_id) FROM arc_citaciones ci  
				INNER JOIN `arc_archivo`  ar ON ar.`id_archivo`=ci.`archivo_id`  
				WHERE ar.`inscripcion_id`=cins.id_curso_inscripcion AND  ci.`asignacion_docente_id`=ca.id_curso_asignacion  and ci.modo_calificacion_id=$id_bimestre)AS citaciones,

				 (SELECT   COUNT(sa.archivo_id) FROM arc_sanciones sa  
				INNER JOIN `arc_archivo`  ar ON ar.`id_archivo`=sa.`archivo_id`  
				WHERE ar.`inscripcion_id`=cins.id_curso_inscripcion AND sa.`asignacion_docente_id`=ca.id_curso_asignacion  and sa.modo_calificacion_id=$id_bimestre)AS sanciones,

				 (SELECT   COUNT(fe.archivo_id) FROM arc_felicitaciones fe  
				INNER JOIN `arc_archivo`  ar ON ar.`id_archivo`=fe.`archivo_id`  
				WHERE ar.`inscripcion_id`=cins.id_curso_inscripcion AND   fe.`asignacion_docente_id`=ca.id_curso_asignacion  and fe.modo_calificacion_id=$id_bimestre)AS felicitaciones,
				
				cins.id_curso_inscripcion as id_inscripcion, cins.estudiante_id, 
				p.*,ca.* FROM ext_curso_inscripcion cins
				INNER JOIN ext_curso_asignacion ca ON ca.id_curso_asignacion=cins.curso_asignacion_id 

				INNER JOIN ins_estudiante e ON cins.estudiante_id=e.id_estudiante
				INNER JOIN sys_persona p ON e.persona_id=p.id_persona  

				WHERE ca.id_curso_asignacion=$asignacion_docente_id AND cins.gestion_id=$id_gestion
				  AND cins.estado='A' 


				   ORDER BY   p.primer_apellido ASC,  p.segundo_apellido ASC")->fetch();
        } else {


            $estudiantes_cursos = $db->query("
		SELECT  (SELECT  COUNT(ci.id_citacion) FROM arc_citaciones ci  
		INNER JOIN `arc_archivo`  ar ON ar.`id_archivo`=ci.`archivo_id` 
		WHERE ar.`inscripcion_id`=z.`id_inscripcion` AND ci.`asignacion_docente_id`=apam.`id_asignacion_docente`  and ci.modo_calificacion_id=$id_bimestre)AS citaciones,

		(SELECT  COUNT(fe.inscripcion_id) FROM arc_felicitaciones fe  
		INNER JOIN `arc_archivo`  ar ON ar.`id_archivo`=fe.`archivo_id` 
		WHERE ar.`inscripcion_id`=z.`id_inscripcion` AND fe.`asignacion_docente_id`=apam.`id_asignacion_docente` and fe.modo_calificacion_id=$id_bimestre)AS felicitaciones,

		(SELECT  COUNT(sa.inscripcion_id) FROM arc_sanciones sa  
		INNER JOIN `arc_archivo`  ar ON ar.`id_archivo`=sa.`archivo_id` 
		WHERE ar.`inscripcion_id`=z.`id_inscripcion` AND sa.`asignacion_docente_id`=apam.`id_asignacion_docente`  and sa.modo_calificacion_id=$id_bimestre)AS sanciones,
		z.id_inscripcion, z.estudiante_id,p.*
        
        FROM ins_inscripcion z 
            INNER JOIN ins_gestion g ON z.gestion_id=g.id_gestion  
            INNER JOIN ins_estudiante e ON z.estudiante_id=e.id_estudiante
            INNER JOIN sys_persona p ON e.persona_id=p.id_persona  
            INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=z.aula_paralelo_id  
	         INNER JOIN pro_asignacion_docente apam ON apam.aula_paralelo_id=ap.id_aula_paralelo
             
  
            
        WHERE z.gestion_id=$id_gestion AND ( z.estado_inscripcion = 'INSCRITO' OR z.estado_inscripcion = 'INCORPORADO' OR z.estado_inscripcion = 'REPITENTE' )
	       AND  apam.`id_asignacion_docente`=$asignacion_docente_id 
            AND z.estado='A' 
            AND g.estado='A'
            AND ap.estado='A'
          
           ORDER BY   p.primer_apellido ASC,  p.segundo_apellido ASC")->fetch();
        }       
        echo json_encode($estudiantes_cursos);
    }
   

    if ($accion == "historialcardex") {

        $id_inscripcion = $_POST['id_inscripcion'];
        $modo_id = $_POST['id_bimestre'];
        $con = "SELECT fe.fecha_felicitacion AS fecha ,fe.id_felicitaciones AS id_evento,fe.motivo,ar.inscripcion_id,'fe' AS tipo,(fe.descripcion) AS descripcion
            FROM arc_felicitaciones fe
            INNER JOIN arc_archivo ar ON ar.id_archivo= fe.archivo_id
            WHERE ar.inscripcion_id = $id_inscripcion  AND fe.modo_calificacion_id=$modo_id
            UNION ALL

             SELECT sa.fecha_sancion AS fecha,id_sancion AS id_evento,motivo,ar.inscripcion_id,'sa'AS tipo,
            CONCAT('Disas suspencion:',sa.dias_suspencion,' - para el:',sa.fecha_traer_tutor)  AS descripcion

            FROM arc_sanciones sa
            INNER JOIN arc_archivo ar ON ar.id_archivo= sa.archivo_id
            WHERE ar.inscripcion_id = $id_inscripcion  AND sa.modo_calificacion_id=$modo_id
            UNION ALL
            SELECT ci.fecha_envio AS fecha,id_citacion AS id_evento,motivo,ar.inscripcion_id,'ci'AS tipo,CONCAT ('El tutor debe apersonarse para la fecha: ' ,ci.fecha_asistencia)AS descripcion
            FROM arc_citaciones ci
            INNER JOIN arc_archivo ar ON ar.id_archivo= ci.archivo_id
            WHERE ar.inscripcion_id = $id_inscripcion  AND ci.modo_calificacion_id=$modo_id
            ORDER BY fecha DESC";
        $area_calificacionw = $db->query($con)->fetch();
      
        echo json_encode($area_calificacionw);
    }

    if ($accion == "datosmodalFelicitacion") {
        $id_comunicado = isset($_POST['id_comunicado']) ? $_POST['id_comunicado'] : 0;
        $res = $db->query("SELECT * FROM `arc_felicitaciones` WHERE `id_felicitaciones`=$id_comunicado")->fetch();
        echo json_encode($res);
    }

    if ($accion == "datosmodalCitacion") {
        $id_comunicado = isset($_POST['id_comunicado']) ? $_POST['id_comunicado'] : 0;
        $res = $db->query("SELECT * FROM `arc_citaciones` WHERE `id_citacion`=$id_comunicado")->fetch();
        echo json_encode($res);
    }

    if ($accion == "datosmodalSancion") {
        $id_comunicado = isset($_POST['id_comunicado']) ? $_POST['id_comunicado'] : 0;
        $res = $db->query("SELECT * FROM `arc_sanciones` WHERE `id_sancion`=$id_comunicado")->fetch();
        echo json_encode($res);
    }

    if ($accion == "btn_citacion") {

        $id_citacion = $_POST['id_citacion'];
        $motivo = $_POST['motivo_ci'];
        $fecha_envio = $fecha_actual;
        $fecha_asistencia = $_POST['fecha_ci'];
        $profesor_materia_id = $_POST['id_profesor_materia'];
        $id_estudiante = $_POST['id_estudiante'];
        $modo_calificacion_id = $_POST['modo_calificacion_id'];
        if (isset($id_citacion) && $id_citacion != '') {
            //editar::::::::::::::::::::::::::::::::
            $datcitacion = array(
                'motivo' => $motivo,
                'fecha_asistencia' => $fecha_asistencia,
                'usuario_modificacion' =>  $_user['id_user'],
                'fecha_modificacion' => date('Y-m-d H:i:s')
            );
            //$db->insert('arc_sanciones', $felicitacion);
            $db->where('id_citacion', $id_citacion)->update('arc_citaciones', $datcitacion);
            echo 3; //edit corrcetmnetr
        } else {
            //crear:::::::::::::::::
            $sql_inscripcion = $db->query("SELECT id_inscripcion, estudiante_id
										FROM ins_inscripcion 
										WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion AND ( estado_inscripcion = 'INSCRITO' OR estado_inscripcion = 'INCORPORADO' OR estado_inscripcion = 'REPITENTE' )")->fetch_first();
            $id_inscripcion = $sql_inscripcion['id_inscripcion'];

            //var_dump($_POST);die;
            /*$sql_archivo = $db->query("SELECT *
								   FROM arc_archivo 
								   WHERE inscripcion_id = $id_inscripcion")->fetch_first();
		$valor = $sql_archivo['id_archivo'];*/
            $sql_archivo = $db->query("SELECT *
								   FROM arc_archivo 
								   WHERE inscripcion_id = $id_inscripcion")->fetch_first();
            $valor = $sql_archivo['id_archivo'];

            if (isset($valor)) {
                //Si existe el estudiante solo recuperamos el id para poder añadir su sancion

                $citacion = array(
                    'asignacion_docente_id' => $profesor_materia_id,
                    'motivo' => $motivo,
                    'fecha_envio' => date('Y-m-d'),
                    'fecha_asistencia' => $fecha_asistencia,
                    'archivo_id' => $valor,
                    'estado' => 'A',
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro' => date('Y-m-d H:i:s'),
                    'usuario_modificacion' => '0',
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                    'modo_calificacion_id' => $modo_calificacion_id
                );
                $db->insert('arc_citaciones', $citacion);

                if ($db->affected_rows) {
                    echo 1;
                } else {
                    echo 2;
                }
            } else {
                //Creamos el archivo para el estudiante
                $archivo = array(
                    'inscripcion_id' => $id_inscripcion,
                    'estado' => 1
                );
                //$sqlArchivo = "INSERT INTO arc_archivo (inscripcion_id, estado) VALUES ('{$id_estudiante_ins}', '1');";			

                $id_archivo = $db->insert('arc_archivo', $archivo);
                //  var_dump($id_archivo);exit();

                $citacion = array(
                    'asignacion_docente_id' => $profesor_materia_id,
                    'motivo' => $motivo,
                    'fecha_envio' => date('Y-m-d'),
                    'fecha_asistencia' => $fecha_asistencia, //$fecha_traer_tutor,
                    'archivo_id' => $id_archivo,
                    'estado' => 'A',
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro' => date('Y-m-d H:i:s'),
                    'usuario_modificacion' => '0',
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                    'modo_calificacion_id' => $modo_calificacion_id
                );
                $db->insert('arc_citaciones', $citacion);
                if ($db->affected_rows) {
                    echo 1;
                } else {
                    echo 2;
                }
            }
        } //fin crear
    }

    if ($accion == "btn_felicitacion") {

        $motivo = $_POST['motivo'];

        $id_felicitacion = $_POST['id_felicitacion'];
        $descripcion = $_POST['descripcion'];
        //$fecha_felicitacion = $_POST['fecha_felicitacion'];
        $profesor_materia_id = $_POST['id_profesor_materia'];
        $id_estudiante = $_POST['id_estudiante'];
        $modo_calificacion_id = $_POST['modo_calificacion_id'];
        //$estado_curso = $_POST['tipo_extra'];

        if (isset($id_felicitacion) && $id_felicitacion != '') {
            //editar::::::::::::::::::::::::::::::::
            $datFelicitacion = array(
                'motivo' => $motivo,
                'descripcion' => $descripcion,
                'usuario_modificacion' =>  $_user['id_user'],
                'fecha_modificacion' => date('Y-m-d H:i:s')
            );
            //$db->insert('arc_sanciones', $felicitacion);
            $db->where('id_felicitaciones', $id_felicitacion)->update('arc_felicitaciones', $datFelicitacion);
            echo 3; //edit corrcetmnetr
        } else {
            //crear:::::::::::::::::
            //obtener id inscripcion, depende de si se inscribio en extracurricular o en curso normal
            //if($estado_curso!='E'){
            //NORmAL
            $sql_inscripcion = $db->query("SELECT id_inscripcion, estudiante_id
										FROM ins_inscripcion 
										WHERE estudiante_id = $id_estudiante and gestion_id=$id_gestion and estado='A' AND ( estado_inscripcion = 'INSCRITO' OR estado_inscripcion = 'INCORPORADO' OR estado_inscripcion = 'REPITENTE' )")->fetch_first(); // AND gestion_id = $id_gestion")->fetch_first();
           
            if ($sql_inscripcion) {

                $id_inscripcion = $sql_inscripcion['id_inscripcion'];
                //Buscamos el id_archivo del estudiante
                $sql_archivo = $db->query("SELECT *
								   FROM arc_archivo 
								   WHERE inscripcion_id = $id_inscripcion")->fetch_first();
                $valor = $sql_archivo['id_archivo'];
                //var_dump($valor);die;

                //Preguntamos si existe el archivo 1 si existiese y 0 no existe
                if (isset($valor)) {
                    //Si existe el estudiante solo recuperamos el id para poder añadir su sancion

                    $felicitacion = array(
                        'asignacion_docente_id' => $profesor_materia_id,
                        'motivo' => $motivo,
                        'descripcion' => $descripcion,
                        'fecha_felicitacion' => date('Y-m-d'),
                        'archivo_id' => $valor,
                        'estado' => 'A',
                        'usuario_registro' => $_user['id_user'],
                        'fecha_registro' => date('Y-m-d H:i:s'),
                        'usuario_modificacion' => '0',
                        'fecha_modificacion' => date('Y-m-d H:i:s'),
                        'modo_calificacion_id' => $modo_calificacion_id
                    );
                    $db->insert('arc_felicitaciones', $felicitacion);

                    if ($db->affected_rows) {
                        echo 1;
                    } else {
                        echo 2;
                    }
                } else {
                    //Creamos el archivo para el estudiante
                    $archivo = array(
                        'inscripcion_id' => $id_inscripcion,
                        'estado' => 1,
                        'estado_curso' => $estado_curso
                    );
                    //$sqlArchivo = "INSERT INTO arc_archivo (inscripcion_id, estado) VALUES ('{$id_estudiante_ins}', '1');";			

                    $id_archivo = $db->insert('arc_archivo', $archivo);

                    $felicitacion = array(
                        'asignacion_docente_id' => $profesor_materia_id,
                        'motivo' => $motivo,
                        'descripcion' => $descripcion,
                        'fecha_felicitacion' => date('Y-m-d'),
                        'archivo_id' => $id_archivo,
                        'estado' => 'A',
                        'usuario_registro' => $_user['id_user'],
                        'fecha_registro' => date('Y-m-d H:i:s'),
                        'usuario_modificacion' => '0',
                        'fecha_modificacion' => date('Y-m-d H:i:s'),
                        'modo_calificacion_id' => $modo_calificacion_id
                    );
                    $db->insert('arc_felicitaciones', $felicitacion);

                    if ($db->affected_rows) {
                        echo 1;
                    } else {
                        echo 2;
                    }
                }
            } else {
                echo 11;
            }
        } //fin crear::::::       
    }

    if ($accion == "btn_sancion") {
        $id_sancion = $_POST['id_sancion'];
        $motivo = $_POST['motivo'];
        $fecha_sancion = $fecha_actual;
        $dias_suspencion = $_POST['dias'];
        $traer_tutor = $_POST['traertutor'];
        $fecha_traer_tutor = $_POST['fecha_asistir'];
        $modo_calificacion_id = $_POST['modo_calificacion_id'];
        $asistio_tutor = "0";
        $fecha_asistio_tutor = $fecha_actual;
        if (isset($id_sancion) && $id_sancion != '') {
            //editar::::::::::::::::::::::::::::::::
            //editar::::::::::::::::::::::::::::::::
            $sancion = array(         //'asignacion_docente_id'=> $profesor_materia_id,
                'motivo' => $motivo,
                //'fecha_sancion'=> date('Y-m-d'),
                'dias_suspencion' => $dias_suspencion,
                'traer_tutor' => $traer_tutor,
                'fecha_traer_tutor' => $fecha_traer_tutor,               
                'usuario_modificacion' =>  $_user['id_user'],
                'fecha_modificacion' => date('Y-m-d H:i:s')
            );
            //$db->insert('arc_sanciones', $felicitacion);
            $db->where('id_sancion', $id_sancion)->update('arc_sanciones', $sancion);
            echo 3; //edit corrcetmnetr
        } else {
            //:::::::::::::::NEW:::::::::::::::::::::::::
            $profesor_materia_id = $_POST['id_profesor_materia'];
            $id_estudiante = $_POST['id_estudiante'];

            $sql_inscripcion = $db->query("SELECT id_inscripcion, estudiante_id
                                            FROM ins_inscripcion 
                                            WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion AND ( estado_inscripcion = 'INSCRITO' OR estado_inscripcion = 'INCORPORADO' OR estado_inscripcion = 'REPITENTE' )")->fetch_first();
            $id_inscripcion = $sql_inscripcion['id_inscripcion'];

            //var_dump($_POST);die;
            $sql_archivo = $db->query("SELECT *
                                       FROM arc_archivo 
                                       WHERE inscripcion_id = $id_inscripcion")->fetch_first();
            $valor = $sql_archivo['id_archivo'];

            if (isset($valor)) {
                //Si existe el estudiante solo recuperamos el id para poder añadir su sancion

                $sancion = array(
                    'asignacion_docente_id' => $profesor_materia_id,
                    'motivo' => $motivo,
                    'fecha_sancion' => date('Y-m-d'),
                    'dias_suspencion' => $dias_suspencion,
                    'traer_tutor' => $traer_tutor,
                    'fecha_traer_tutor' => $fecha_traer_tutor,
                    'asistio_tutor' => $asistio_tutor,
                    'fecha_asistio_tutor' => $fecha_asistio_tutor,
                    'archivo_id' => $valor,
                    'estado' => 'A',
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro' => date('Y-m-d H:i:s'),
                    'usuario_modificacion' => '0',
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                    'modo_calificacion_id' => $modo_calificacion_id
                );
                $db->insert('arc_sanciones', $sancion);

                if ($db->affected_rows) {
                    echo 1;
                } else {
                    echo 2;
                }
            } else {
                //Creamos el archivo para el estudiante
                $archivo = array(
                    'inscripcion_id' => $id_inscripcion,
                    'estado' => 1
                );
                //$sqlArchivo = "INSERT INTO arc_archivo (inscripcion_id, estado) VALUES ('{$id_estudiante_ins}', '1');";			

                $id_archivo = $db->insert('arc_archivo', $archivo);

                $sancion = array(
                    'asignacion_docente_id' => $profesor_materia_id,
                    'motivo' => $motivo,
                    'fecha_sancion' => date('Y-m-d'),
                    'dias_suspecion' => $dias_suspencion,
                    'traer_tutor' => $traer_tutor,
                    'fecha_traer_tutor' => $fecha_traer_tutor,
                    'asistio_tutor' => $asistio_tutor,
                    'fecha_asistio_tutor' => $fecha_asistio_tutor,
                    'archivo_id' => $id_archivo,
                    'estado' => 'A',
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro' => date('Y-m-d H:i:s'),
                    'usuario_modificacion' => '0',
                    'fecha_modificacion' => date('Y-m-d H:i:s'),
                    'modo_calificacion_id' => $modo_calificacion_id
                );
                $db->insert('arc_sanciones', $felicitacion);

                if ($db->affected_rows) {
                    echo 1;
                } else {
                    echo 2;
                }
            }
        }     
    }

    if ($accion == "listar_areas") {
       
        $modo = isset($_POST['modo']) ? $_POST['modo'] : 0;       

        $area_calificacion = $db->query("SELECT * FROM cal_area_calificacion are, cal_modo_calificacion_area_calificaion ma 
        WHERE 
        are.`id_area_calificacion`= ma.`area_calificacion_id` AND
        gestion_id =$id_gestion
        AND ma.`modo_calificacion_id`=$modo")->fetch();
       
        echo json_encode($area_calificacion);
    }

    if ($accion == "listar_estud") {
        //$id_aula_asignacion = $_POST['id_aula_asignacion'];
        $id_asignacion_docente = $_POST['id_aula_asignacion']; //id_asignacion_docente
        $id_bimestre = $_POST['id_bimestre'];
        $tipo_extra = $_POST['tipo_extra'];
        //var_dump($tipo_extra);exit();
        $estudiantes_cursos = $db->query("SELECT (SELECT obs.id_estudiante_modo_observacion FROM cal_estudiante_modo_observacion obs WHERE obs.estudiante_id=e.`id_estudiante`  
        AND obs.estado_curso='$tipo_extra'  AND obs.modo_calificacion_id=$id_bimestre  LIMIT 1)AS id_estudiante_modo_observacion,
        (SELECT obs.valoracion_cualitativa FROM cal_estudiante_modo_observacion obs WHERE obs.estudiante_id=e.`id_estudiante`  AND obs.estado_curso='$tipo_extra' 
        AND obs.modo_calificacion_id=$id_bimestre  LIMIT 1)AS valoracion_cualitativa, z.estudiante_id, p.*, us.id_user
        
         FROM ins_inscripcion z 
            INNER JOIN ins_gestion g ON z.gestion_id=g.id_gestion  
            INNER JOIN ins_estudiante e ON z.estudiante_id=e.id_estudiante
            INNER JOIN sys_persona p ON e.persona_id=p.id_persona  
            INNER  JOIN sys_users us ON us.persona_id=p.id_persona
            INNER JOIN ins_aula_paralelo ap ON ap.id_aula_paralelo=z.aula_paralelo_id  
            INNER JOIN pro_asignacion_docente apam ON apam.aula_paralelo_id=ap.id_aula_paralelo
	    
         
        WHERE z.gestion_id=$id_gestion AND ( z.estado_inscripcion = 'INSCRITO' OR z.estado_inscripcion = 'INCORPORADO' OR z.estado_inscripcion = 'REPITENTE' )
			AND  apam.`id_asignacion_docente`=$id_asignacion_docente
            AND z.estado='A' 
            AND g.estado='A'
            AND ap.estado='A'
       

           ORDER BY   p.primer_apellido ASC,  p.segundo_apellido ASC")->fetch();

        echo json_encode($estudiantes_cursos);
    }


    if ($accion == "listar_estud_extra") {
        //$id_aula_asignacion = $_POST['id_aula_asignacion'];
        $id_asignacion_docente = $_POST['id_aula_asignacion']; //id_asignacion_docente
        $id_bimestre = $_POST['id_bimestre'];
        $tipo_extra = $_POST['tipo_extra'];

        $estudiantes_cursos = $db->query("SELECT   
            (SELECT obs.id_estudiante_modo_observacion FROM cal_estudiante_modo_observacion obs 
            WHERE obs.estudiante_id=e.`id_estudiante`   AND obs.modo_calificacion_id=$id_bimestre AND obs.estado_curso='$tipo_extra'  LIMIT 1)AS id_estudiante_modo_observacion,

            (SELECT obs.valoracion_cualitativa FROM cal_estudiante_modo_observacion obs 
            WHERE obs.estudiante_id=e.`id_estudiante`  AND obs.modo_calificacion_id=$id_bimestre AND obs.estado_curso='$tipo_extra'   LIMIT 1)AS valoracion_cualitativa,

            z.estudiante_id, p.* 	
            FROM ext_curso_inscripcion AS z 

            INNER JOIN ins_estudiante e ON z.estudiante_id=e.id_estudiante
            INNER JOIN sys_persona p ON e.persona_id=p.id_persona    

            WHERE z.curso_asignacion_id=$id_asignacion_docente  AND z.gestion_id=  $id_gestion
            ORDER BY   p.primer_apellido ASC,  p.segundo_apellido ASC")->fetch();




        echo json_encode($estudiantes_cursos);
    }


    if ($accion == "agregar_persona") {

        $id_persona = isset($_POST['id_persona']) ? $_POST['id_persona'] : 0;
        // var_dump($id_persona);exit();
        $personas = $db->query("SELECT pe.* FROM sys_persona pe WHERE pe.id_persona=" . $id_persona)->fetch();

        echo json_encode($personas);
    }

    if ($accion == "listar_actividades") {
      
        $id_modo_calificacion = $_POST['id_modo_calificacion'];
        $id_asignacion_docente = $_POST['asignacion_docente_id']; //id_asignacion_docente
        $tipo_extrar = $_POST['tipo_extra']; //id_asignacion_docente


        //consulta para listar las actividades de ese dia por profesor
        $actividades = $db->query("SELECT are.id_area_calificacion,are.descripcion, asca.*  
          FROM   tem_asesor_curso_actividad asca  
          INNER JOIN cal_area_calificacion are ON are.id_area_calificacion =asca.area_calificacion_id
          WHERE asca.asignacion_docente_id=$id_asignacion_docente
          AND asca.estado_cartilla = 'SI' 
          AND asca.tipo_actividad <> 'REUNION'
	      AND asca.modo_calificacion_id=$id_modo_calificacion  
          AND asca.estado_actividad='A'
          AND asca.estado_curso='$tipo_extrar'")->fetch();
          
        echo json_encode($actividades);
    }

    if ($accion == "listar_notas_est") {

        $id_modo_calificacion = $_POST['id_modo_calificacion'];
        $asignacion_docente_id = $_POST['asignacion_docente_id'];
        //var_dump($asignacion_docente_id);exit();
        //consulta para listar las actividades de ese dia por profesor
        $actividades = $db->query("SELECT   asca.id_asesor_curso_actividad, asca.asignacion_docente_id,
            asca.nombre_actividad,asca.modo_calificacion_id,asca.area_calificacion_id,
			are.descripcion, 
			est.id_estudiante_curso_actividad,est.estudiante_id,ifnull(est.nota,'0')as nota,ifnull(est.nota_cualitativa,'0')as nota_cualitativa
            from tem_asesor_curso_actividad asca 
            INNER JOIN tem_estudiante_curso_actividad est ON est.asesor_curso_actividad_id=asca.id_asesor_curso_actividad 
            INNER JOIN cal_area_calificacion are ON are.id_area_calificacion=asca.area_calificacion_id 
            WHERE asca.asignacion_docente_id=$asignacion_docente_id
            AND asca.modo_calificacion_id=$id_modo_calificacion")->fetch();

       

        echo json_encode($actividades);
    }

    if ($accion == "registrar_nota") {
        //recepciona las variables de la hoja calificar-actividad
        $estudiante_id = isset($_POST['estudiante_id']) ? $_POST['estudiante_id'] : 0;
        $actividad_mat_id = isset($_POST['actividad_mat_id']) ? $_POST['actividad_mat_id'] : 0; //id_asesor_curso_actividad
        $nota = isset($_POST['nota']) ? $_POST['nota'] : 0;
		
        if (is_numeric($nota) && $nota >= 0 && $nota <= 100) {


            //BUSCAR NOTA CON EST Y ACTIVIDAD
            $actividad = $db->query(" SELECT ifnull((act.estado_bloqueo),'NO')as blokeado,eca.* FROM tem_estudiante_curso_actividad eca
                 INNER JOIN tem_asesor_curso_actividad act ON act.id_asesor_curso_actividad=eca.asesor_curso_actividad_id
                 WHERE estudiante_id=$estudiante_id
                 AND  asesor_curso_actividad_id=$actividad_mat_id")->fetch_first();
     
            if ($actividad) {
                //SI SE ENCUENTRA ACTUALIZAR

                if ($actividad['blokeado'] == 'NO') {

                    $datos = array(
                        'nota' => $nota
                    );
                    $db->where('id_estudiante_curso_actividad', $actividad['id_estudiante_curso_actividad'])->update('tem_estudiante_curso_actividad', $datos);
                    echo 'update';
                } else {
                    echo 'bloqueado';
                }
                // var_dump($db);
            } else {
                //SI NO SE ENCUANTRA CREAR
                $db->insert('tem_estudiante_curso_actividad', array(
                    'estudiante_id' => $estudiante_id,
                    'asesor_curso_actividad_id' => $actividad_mat_id,
                    'nota' => $nota,
                    'fecha_registro' => date('Y-m-d'),
                    'hora_registro' => Date('H:i:s'),
                    'usuario_registro' => $_user['id_user'],
                    'estado_presentacion' => 'CARTILLA'
                ));
                echo 'crear';
                //}else{
                //    echo 'bloqueado'; 
                //}
            }
        } //verificar si es numero de nota

        //echo json_encode($actividades);


    }

    if ($accion == "registrar_nota_cualitativa") {
        //recepciona las variables de la hoja calificar-actividad
        $estudiante_id = isset($_POST['estudiante_id']) ? $_POST['estudiante_id'] : 0;
        $id_asesor_curso_actividad = isset($_POST['actividad_mat_id']) ? $_POST['actividad_mat_id'] : 0; //actividad_id id_asesor_curso_actividad
        $nota = isset($_POST['nota']) ? $_POST['nota'] : 0;


        //BUSCAR NOTA CON EST Y ACTIVIDAD
        $horario = $db->from('tem_estudiante_curso_actividad')->where('estudiante_id', $estudiante_id)->where('asesor_curso_actividad_id', $id_asesor_curso_actividad)->fetch_first();

        //$horario = $db->from('cal_estudiante_actividad_nota')->where('estudiante_id',$estudiante_id)->where('actividad_materia_modo_area_id',$actividad_mat_id)->fetch_first();

        if ($horario) {
            //SI NO SE ENCUANTRA CREAR
            $datos = array(
                //'estudiante_id' => $estudiante_id,
                //'actividad_materia_modo_area_id' => $actividad_mat_id, 
                'nota_cualitativa' => $nota
                //'usuario_modificacion'=> $_user['id_user'],
                //'fecha_modificacion'=> date('Y-m-d')
            );

            $db->where('id_estudiante_curso_actividad', $horario['id_estudiante_curso_actividad'])->update('tem_estudiante_curso_actividad', $datos);

            // $db->where('id_estudiante_actividad_nota', $horario['id_estudiante_actividad_nota'])->update('cal_estudiante_actividad_nota', $datos);
            // var_dump($horario['id_estudiante_curso_actividad']);exit();
            echo 'update';
        } else {
            //SI SE ENCUENTRA ACTAULIZAR

            $db->insert('tem_estudiante_curso_actividad', array(
                'estudiante_id' => $estudiante_id,
                'asesor_curso_actividad_id' => $id_asesor_curso_actividad,
                'nota_cualitativa' => $nota,
                'estado_calificado' => 'SI',
                'cartilla' => 'SI'
            ));

            echo 'crear';
        }
        //  

        //echo json_encode($actividades);


    }

    if ($accion == "guardar_tarea") {

        $id_asesor_curso_actividad = isset($_POST['id_actividad_materia_modo_area']) ? $_POST['id_actividad_materia_modo_area'] : false; //cada actividad id_asesor_curso_actividad

        $nombre_actividad  = $_POST['nombre_actividad'];
        $estado_curso      = $_POST['tipo_extra_m'];


        $descripcion_actividad = $_POST['descripcion_actividad'];
        $fecha_presentacion    = $_POST['fecha_presentacion'];
        $asignacion_docente_id = $_POST['asignacion_docente_id'];
        //$id_modo_area = $_POST['id_modo_area'];

        $id_bimestre     = $_POST['id_bimestre']; //modo_calificacion_id
        $id_area_calificacion = $_POST['id_area_calificacion'];
        $tipo_extra      = $_POST['tipo_extra'];
        $estado_cartilla = "SI";

        if ($id_asesor_curso_actividad) {
            //tiene id editar
            $datos = array(
                //'estado_curso' => $estado_curso,
                'nombre_actividad' => $nombre_actividad,
                'descripcion_actividad' => $descripcion_actividad,
                'fecha_presentacion_actividad' => $fecha_presentacion,  //ant fecha_presentacion
                'usuario_modificacion' => $_user['id_user'],
                'fecha_modificacion' => date('Y-m-d'),
                'estado_cartilla' => $estado_cartilla,
                'presentar_actividad' => 'NO'
            );
            $db->where('id_asesor_curso_actividad', $id_asesor_curso_actividad)->update('tem_asesor_curso_actividad', $datos);
            //$db->where('id_actividad_materia_modo_area', $id_actividad_materia_modo_area)->update('cal_actividad_materia_modo_area', $datos);

            if ($db) {
                echo 1; //editado correctamente

            } else {
                echo 6; //no se pudo editar
            }
        } else {

            $fecha = new DateTime($fecha_presentacion);
            $fechaRes = $fecha->format('Y-m-d');

            //$vermodosArea = $db->query("SELECT * FROM `cal_modo_calificacion_area_calificaion` WHERE `estado`='A' AND `id_modo_calificacion_area_calificacion`=$id_modo_area")->fetch_first();
            //if($vermodosArea){
            //BUSCAR NOTA CON EST Y ACTIVIDAD
            $horars = $db->query("SELECT * FROM  cal_modo_calificacion WHERE  fecha_inicio<='$fechaRes' AND fecha_final>='$fechaRes' AND id_modo_calificacion=$id_bimestre")->fetch_first();


            //var_dump("SELECT * FROM  cal_modo_calificacion WHERE  fecha_inicio<='$fechaRes' AND fecha_final>='$fechaRes' AND id_modo_calificacion=$id_bimestre");exit();

            if ($horars) {
                //fecahs correctas
                $datos = array(
                    'asignacion_asesor_id' => 0, ///nadie usa este campo bd
                    'asignacion_docente_id' => $asignacion_docente_id,
                    'modo_calificacion_id' => $id_bimestre,
                    'area_calificacion_id' => $id_area_calificacion,
                    'estado_curso' => 'N',
                    'materia_id' => '0', ///nadie usa este campo bd
                    'estado_curso' => $tipo_extra, ///nadie usa este campo bd
                    'estado_curso' => $estado_curso,
                    //anteriores
                    'estado_bloqueo' => 'NO', //bloqueado
                    'nombre_actividad' => $nombre_actividad,
                    'descripcion_actividad' => $descripcion_actividad,
                    'fecha_presentacion_actividad' => $fecha_presentacion,                     
                    'estado_actividad' => 'A',                 
                    'estado_cartilla' => $estado_cartilla,
                    'presentar_actividad' => 'NO',
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro' => date('Y-m-d'),
                    'usuario_modificacion' => $_user['id_user'],
                    'fecha_modificacion' => date('Y-m-d'),
                    'imagen' => '',                   
                );
                //var_dump($datos);exit();

                // Crea el horario
                $insertado = $db->insert('tem_asesor_curso_actividad', $datos);
                //$insertado = $db->insert('cal_actividad_materia_modo_area', $datos);
                if ($insertado) {
                    echo 2; //creado ok 
                } else {
                    echo 5; //no se puedo crear
                }
            } else {
                //fechas incorrectas
                echo 10; //revisa las fechas correspondintes a la fecha del bimentre
            }            

        }
    }

    if ($accion == "eliminar_dato") {
        $id_componente = $_POST['id_componente'];
        // verificamos su exite el id en tabla
        $horario = $db->from('tem_asesor_curso_actividad')->where('id_asesor_curso_actividad', $id_componente)->fetch_first();
        //$horario = $db->from('cal_actividad_materia_modo_area')->where('id_actividad_materia_modo_area',$id_componente)->fetch_first(); 

        //en caso de si eliminarar en caso de no dara mensaje de error
        if (validar($horario)) {
            //NOELIMINAR $db->delete()->from('per_horarios')->where('id_horario', $id_horario)->limit(1)->execute(); 
            $esta = $db->query("UPDATE tem_asesor_curso_actividad SET estado_actividad = 'I', usuario_modificacion = '" . $_user['id_user'] . "', fecha_modificacion = '" . date('Y-m-d') . "' WHERE id_asesor_curso_actividad = '" . $id_componente . "'")->execute();
            //$esta=$db->query("UPDATE cal_actividad_materia_modo_area SET estado = 'I', usuario_modificacion = '".$_user['id_user']."', fecha_modificacion = '".date('Y-m-d')."' WHERE id_actividad_materia_modo_area = '".$id_componente."'")->execute();

            // var_dump('aqui php'.$id_componente);exit();

            if ($esta) { //$db->affected_rows) {
                //registrarProceso('Se eliminó el horario con identificador número ' . $id_horario . '.',$db);
                registrarProceso('Se eliminó la actividad con identificador número ', $id_componente, $db, $_location, $_user['id_user']);
                echo 1; //'Eliminado Correctamente.';
            } else {
                echo 2; //'No se pudo eliminar';
            }
        }
    }

    if ($accion == "valoracionCualitativaModo") {

        //var_dump('aqui php');exit();
        $id_estudiante_modo_observacion = $_POST['id_estudiante_modo_observacion'];


        $valoracionCualitativa = $_POST['valoracionCualitativa'];
        $id_estudianteC = $_POST['id_estudianteC'];
        // $id_aula_asignacionC = $_POST['id_aula_asignacionC']; 
        $id_aula_asignacion = $_POST['id_aula_asignacion'];
        $id_bimestre = $_POST['id_bimestre'];
        $estado_curso = $_POST['estado_curso']; //N normal E extracurr

        if ($id_estudiante_modo_observacion != '') {
            //var_dump($id_estudiante_modo_observacion);exit();
            //tiene id editar
            $datos = array(
                'asignacion_docente_id' => $id_aula_asignacion,
                'modo_calificacion_id' => $id_bimestre,
                'estudiante_id' => $id_estudianteC,
                'valoracion_cualitativa' => $valoracionCualitativa,
                'usuario_modificacion' => $_user['id_user'],
                'fecha_modificacion' => date('Y-m-d') //,
                //'aula_paralelo_asignacion_materia_id'=> $id_aula_asig_materia
            );

            $db->where('id_estudiante_modo_observacion', $id_estudiante_modo_observacion)->update('cal_estudiante_modo_observacion', $datos);
            if ($db) {
                echo 2; //editar
            } else {
                echo 12; //no editar
            }
        } else {
            //var_dump('truuu php');exit();.
            //$fecha = new DateTime($fecha_presentacion);
            //$fechaRes =$fecha->format('Y-m-d');
            //BUSCAR NOTA CON EST Y ACTIVIDAD
            //$horars = $db->query("SELECT * FROM  cal_estudiante_modo_observacion WHERE   `id_estudiante_modo_observacion`=$id_estudiante_modo_observacion")->fetch_first();
            // var_dump($horars);exit();
            $datos = array(
                'id_estudiante_modo_observacion' => 0,
                'asignacion_docente_id' => $id_aula_asignacion,
                'modo_calificacion_id' => $id_bimestre,
                'estudiante_id' => $id_estudianteC,
                'valoracion_cualitativa' => $valoracionCualitativa,
                'estado' => 'A',
                'estado_curso' => $estado_curso,
                //'modo_calificacion_area_calificacion_id' => $id_modo_area, 
                'usuario_registro' => $_user['id_user'],
                'fecha_registro' => date('Y-m-d'),
                'usuario_modificacion' => $_user['id_user'],
                'fecha_modificacion' => date('Y-m-d') //,
                //'aula_paralelo_asignacion_materia_id'=> $id_aula_asig_materia
            );
            //var_dump($datos);exit();

            //if(!$horars){  
            // Crea el observaion
            $insertado = $db->insert('cal_estudiante_modo_observacion', $datos);
            if ($insertado) {
                echo 1; //creado ok

            } else {
                echo 5; //no se puedo crear
            }
        }
    }

    if ($accion == "ver_valoracion_cualitativa") {

        $id_estudianteC = $_POST['id_estudianteC'];
        $id_aula_asignacion = $_POST['id_aula_asignacion'];
        $id_bimestre = $_POST['id_bimestre'];

        $obserCualitat = $db->query(" SELECT obs.* FROM cal_estudiante_modo_observacion obs  INNER JOIN ins_estudiante e ON obs.`estudiante_id`=e.id_estudiante
            WHERE obs.estudiante_id=$id_estudianteC AND
                obs.`modo_calificacion_id`=$id_bimestre AND
                obs.`asignacion_docente_id`=$id_aula_asignacion  
         ")->fetch();
        echo json_encode($obserCualitat);
    }

    if ($accion == "asistencia_estudiantes") {

        //$estudiante_id = $_POST['estudiante_id'];
        $asignacion_docente_id = isset($_POST['aula_paralelo_asignacion_materia_id']) ? $_POST['aula_paralelo_asignacion_materia_id'] : 0;
        $modo_calificacion_id =  isset($_POST['modo_calificacion_id']) ? $_POST['modo_calificacion_id'] : 0;
        $tipo_extra = $_POST['tipo_extra'];
        // var_dump($aula_paralelo_asignacion_materia_id);exit();
        $estudiantes_cursos = $db->query("SELECT * FROM int_asistencia_estudiante_materia
        WHERE 
        asignacion_docente_id=$asignacion_docente_id AND
        modo_calificacion_id=$modo_calificacion_id AND
        gestion_id=$id_gestion AND estado_curso='$tipo_extra'")->fetch(); //estudiante_id=$estudiante_id AND

        echo json_encode($estudiantes_cursos);
    }

    if ($accion == "guardar_asistencia") {
		
		$estdatos = $_POST['estdatos']; ////A@L@
       // var_dump($estdatos);exit();
		
        $asignacion_mat_id = $_POST['asignacion_mat_id'];
        $modo_calificacion_id = $_POST['modo_calificacion_id'];
        $tipo_extra = $_POST['tipo_extra']; ////A@L@
        //$estudiante_id = $_POST['estudiante_id']; //2@34@
        //$asistencia = $_POST['asistencia']; ////A@L@
        //$check_asistencia = explode('@', $estudiante_id); //
        //$asistencia = explode('@', $asistencia); //
        //array_pop($check_asistencia);
        //array_pop($asistencia);

        //$aA = array();
        //$ic = 0;
        //foreach ($check_asistencia as $c) {
        //    $aA[$c] = $asistencia[$ic];
        //    $ic++;
        //}



        //$fecha_actual = Date('Y-m-d'); //'2020-03-03';//Date('Y-m-d');
        //$hora_actula = Date('H:i:s');
        //$cont = count($check_asistencia);
        $cont = count($estdatos);
        //$contador = 0;
        $contador_insert = 0;
        $cad_array = "";

        //var_dump($hora_actula);die;
        //Variable para recorrer la asistencia mendiante el checkbos
       /* $ic = 0;
        //ver si existe
        $sql_fecha = "SELECT * FROM int_asistencia_estudiante_materia WHERE  asignacion_docente_id = '$asignacion_mat_id'  AND modo_calificacion_id=$modo_calificacion_id and estado_curso='$tipo_extra'"; // and estado_curso='E'
        $res_fecha = $db->query($sql_fecha)->fetch_first();

        if ($res_fecha) {
			//EDITAR UPDATE ::::::::::::::::::::::::::::::
            $respestado = true;
            for ($i = 0; $i < $cont; $i++) {
                //$respestado=true; 
                $sql_consultar = "SELECT * FROM int_asistencia_estudiante_materia WHERE  estudiante_id = '$check_asistencia[$i]' AND asignacion_docente_id = '$asignacion_mat_id'  AND modo_calificacion_id='$modo_calificacion_id' and estado_curso='$tipo_extra'";

                $proceso = $db->query($sql_consultar)->fetch_first();
                //echo($proceso.' @@ '); 
                if ($proceso) {
                    //2020-03-01 15:34:26@p,2020-03-01 15:40:41@p,
                    //:::::::::::::::::::::::::verificar si existe la fecha actual
                    $json_asistencia = explode(",", $proceso['json_asistencia']); //
                    foreach ($json_asistencia as $row) { //2020-03-01 15:34:26@p
                        $fechahora = explode(" ", $row);
                        $fechabd = $fechahora[0];
                        //echo($fechabd.'_'.$fecha_actual.'   ... '); 

                        $fechabd = strtotime($fechabd);
                        $fecha_new = strtotime($fecha_actual);

                        if ($fechabd == $fecha_new) {
                            //echo('---MISMA FECHA... '); 
                            $respestado = false;
                        }
                        //else{
                        // $respestado='noexiste';
                        //} 
                        ///if($respestado=='existe')
                        // }  
                    }
                    //si no es la misma fecha
                    if ($respestado) {
                        //::::::::::::::::::::::::::
                        $datos = array(
                            'json_asistencia' => $proceso['json_asistencia'] . $fecha_actual . " " . $hora_actula . "@" . $aA[$check_asistencia[$i]] . ","
                            //'aula_paralelo_asignacion_materia_id'=> $id_aula_asig_materia
                        );

                        $db->where('id_asistencia_estudiante_materia', $proceso['id_asistencia_estudiante_materia'])->update('int_asistencia_estudiante_materia', $datos);

                        if ($db) {
                            $respestado = 'a'; //actualisado asistencias hoy
                        } else {
                            $respestado = 'b'; //error asistencias hoy 
                        }
                        //:::::::::::::::::::::::::: 
                        // echo 'trueeee';
                    } else {
                        $respestado = 'c'; //'fecha_repetida';
                        //echo 'falseee';
                    }
                } else {
                    //insertar
                    $cad_array = $fecha_actual . " " . $hora_actula . "@" . $aA[$check_asistencia[$i]] . ",";
                    $sql_AEM = "INSERT INTO int_asistencia_estudiante_materia (estudiante_id, modo_calificacion_id, json_asistencia, asignacion_docente_id, gestion_id,estado_curso) VALUES ('$check_asistencia[$i]', '$modo_calificacion_id','$cad_array', '$asignacion_mat_id', $id_gestion,'$tipo_extra')";
                    $proceso = $db->query($sql_AEM)->execute();
                    //    $contador_insert = $contador_insert + 1;
                    // echo 'inser';
                }
            } //for
            echo $respestado; //error asistencia actual
            // exit(); 
            //$respuesta = array(//
            //  'estado' => 'a',//existe ya registro en el mismo curso	 
            //);
            //var_dump($res_fecha);die;
        } else {*/
            //NUEVO :::::::::::::::::::::::::::::::::::::
            //for ($i = 0; $i < $cont; $i++) {
			foreach ($estdatos as $i => $row) {
                $sql_consultar = "SELECT * FROM int_asistencia_estudiante_materia WHERE  estudiante_id = '".$row['estudiante_id']."' AND asignacion_docente_id = '$asignacion_mat_id' AND modo_calificacion_id=$modo_calificacion_id  and estado_curso='$tipo_extra' and gestion_id=$id_gestion ";
                $resultado = $db->query($sql_consultar)->fetch_first();

                //$cad_array = $fecha_actual . " " . $hora_actula . "@" . $aA[$check_asistencia[$i]] . ",";
				$json_datos_new=json_encode(array($row['fecha_asist']=>$row['estados']));
				//var_dump($arr_datos_new);exit();
				$json_datos_ant=[];
                if ($resultado) { 
					//editar
					if($resultado['json_asistencia']!=''){
						$json_datos_ant=json_decode($resultado['json_asistencia'],true);
					}
					$json_datos_ant[$row['fecha_asist']]=$row['estados'];
					
					//$datos = array(
                    //    'json_asistencia' => $json_datos_ant
                    //);
					//$proceso = $db->where('id_asistencia_estudiante_materia', $resultado['id_asistencia_estudiante_materia'])->update('int_asistencia_estudiante_materia', $datos);
               		 
					$strdatos=json_encode($json_datos_ant);
					$sql_AEM = "UPDATE `int_asistencia_estudiante_materia` SET `json_asistencia`='$strdatos' WHERE  `id_asistencia_estudiante_materia`=".$resultado['id_asistencia_estudiante_materia'];
					$proceso = $db->query($sql_AEM)->execute();
 					//$contador_insert = $contador_insert + 1;
					
                }else{
                    //$cad_array = $fecha_actual . "," . $;
                    $sql_AEM = "INSERT INTO int_asistencia_estudiante_materia (estudiante_id, modo_calificacion_id, json_asistencia, asignacion_docente_id, gestion_id,estado_curso) VALUES ('".$row['estudiante_id']."', '$modo_calificacion_id','$json_datos_new', '$asignacion_mat_id', $id_gestion,'$tipo_extra')";
                    $proceso = $db->query($sql_AEM)->execute();
                    //$contador_insert = $contador_insert + 1;
              
                }
             
            }
		
		 if (isset($proceso)) {
			 echo 's';
		 }else{
			 echo 'x';
		 }
            //verifica que todos los registros se hayan guardado
            //if ($cont == $contador_insert) {
                //echo 's';
                //$respuesta = array(
                //  'estado' => 's',
                //   'nota' => 'Exito al guardar' 
                //);
           // } else {
            //    echo 'x';
                // $respuesta = array(
                //  'estado' => 'x',				 
                // );
           //}
        //}
    }

    if ($accion == "actualizar_asistencia") {
		
		//$estdatos = $_POST['estdatos']; ////A@L@
       // var_dump($estdatos);exit();
		
        $asignacion_mat_id = $_POST['asignacion_mat_id'];
        $modo_calificacion_id = $_POST['modo_calificacion_id'];
        $tipo_extra = $_POST['tipo_extra']; ////N E
        
        $estudiante_id = $_POST['estudiante_id']; 
        $fecha = $_POST['fecha']; 
        $estado_asi = $_POST['estado']; 
 
        //$cont = count($check_asistencia);
        //$cont = count($estdatos);
        //$contador = 0;
        //$contador_insert = 0;
        //$cad_array = "";
 
            //NUEVO :::::::::::::::::::::::::::::::::::::
        
		//	foreach ($estdatos as $i => $row) {
                $sql_consultar = "SELECT * FROM int_asistencia_estudiante_materia WHERE  estudiante_id = '".$estudiante_id."' AND asignacion_docente_id = '$asignacion_mat_id' AND modo_calificacion_id=$modo_calificacion_id  and estado_curso='$tipo_extra' and gestion_id=$id_gestion ";
                $resultado = $db->query($sql_consultar)->fetch_first();

                //$json_datos_new=json_encode(array($row['fecha_asist']=>$row['estados']));
				 
				//$json_datos_ant=[];
                if ($resultado) { 
					//editar
					if($resultado['json_asistencia']!=''){
						$json_datos_ant=json_decode($resultado['json_asistencia'],true);
					}
					$json_datos_ant[$fecha]=$estado_asi;
					
				 
               		 
					$strdatos=json_encode($json_datos_ant);
					$sql_AEM = "UPDATE `int_asistencia_estudiante_materia` SET `json_asistencia`='$strdatos' WHERE  `id_asistencia_estudiante_materia`=".$resultado['id_asistencia_estudiante_materia'];
					$proceso = $db->query($sql_AEM)->execute();
 					//$contador_insert = $contador_insert + 1;
					
                }else{ 
                    $sql_AEM = "INSERT INTO int_asistencia_estudiante_materia (estudiante_id, modo_calificacion_id, json_asistencia, asignacion_docente_id, gestion_id,estado_curso) VALUES ('".$estudiante_id."', '$modo_calificacion_id','$json_datos_new', '$asignacion_mat_id', $id_gestion,'$tipo_extra')";
                    $proceso = $db->query($sql_AEM)->execute();
                    
                }
             
          //  }
		
		 if (isset($proceso)) {
			 echo 's';
		 }else{
			 echo 'x';
		 }
   
    }

    if ($accion == "newComunicado") {
        // Verifica la existencia de datos
        if (isset($_POST['titulo']) && isset($_POST['fecha_ini'])) {

            // echo "<pre>";
            // var_dump($_POST);
            // echo "</pre>";exit();
            
            // Obtiene ;los datos
            $id_comunicado = (isset($_POST['id_comunicado'])) ? clear($_POST['id_comunicado']) : 0;
            $modo_id = (isset($_POST['modo_id'])) ? clear($_POST['modo_id']) : 0;
            $aula_asig_mat_id = (isset($_POST['aula_asig_mat_id'])) ? clear($_POST['aula_asig_mat_id']) : 0;
            $grupo = (isset($_POST['tipo'])) ? clear($_POST['tipo']) : 0;
            // var_dump($tipo);exit();
            $nombre_evento = isset($_POST['titulo']) ? $_POST['titulo'] : '';
            $descripcion_evento = isset($_POST['descripcion']) ? $_POST['descripcion'] : '';
            $fecha_inicio = isset($_POST['fecha_ini']) ?   ($_POST['fecha_ini']) : '0000-00-00 00:00:00';
            $fecha_final = isset($_POST['fecha_fin']) ?   ($_POST['fecha_fin']) : '0000-00-00 00:00:00';
            $prioridad = isset($_POST['prioridad']) ?   ($_POST['prioridad']) : '1';
            $color = isset($_POST['color']) ? $_POST['color'] : '#ffffff';

            $estado_curso = isset($_POST['tipo_extra']) ? $_POST['tipo_extra'] : 'N';
            //var_dump($estado_curso);exit();

            $nombre_archivo = isset($_FILES['file']['name']) ? ($_FILES['file']['name']) : false;
            if ($nombre_archivo && $nombre_archivo != '') {
                $tipo_archivo = $_FILES['file']['type'];
                $tamano_archivo = $_FILES['file']['size'];
                //ya 
                if ($tamano_archivo > 10000000) {
                    // if (!((strpos($tipo_archivo, "gif") || strpos($tipo_archivo, "jpg") || strpos($tipo_archivo, "png") || strpos($tipo_archivo, "jpeg") || strpos($tipo_archivo, "docx") || strpos($tipo_archivo, "xlsx") || strpos($tipo_archivo, "pptx")|| strpos($tipo_archivo, "pdf")|| strpos($tipo_archivo, "ppt")|| strpos($tipo_archivo, "pptx")|| strpos($tipo_archivo, "xls")|| strpos($tipo_archivo, "doc")|| strpos($tipo_archivo, "plain")) && ($tamano_archivo < 10000000))) {
                    //10megas?
                    echo 5; //el tipo de archivo no es permitido intente con un word o pdf
                    ///eroor
                    exit();
                } else {

                    if ($nombre_archivo != '') {
                        //se borra el archivo del servidor para poner el nuevo
                        if ($id_comunicado > 0) {
                            $bucarfile = $db->from('ins_comunicados')->where('id_comunicado', $id_comunicado)->fetch_first();
                            $file = $bucarfile["file"];
                            $delete_dir = "files/" . $nombre_dominio . "/comunicados/" . $file;
                            //var_dump($delete_dir);exit();
                            try {
                                if (is_file($delete_dir)) {
                                    unlink($delete_dir);
                                }
                            } catch (Exception $e) {
                                echo 'el archivo cambio de ubicacion.';
                            }
                        }

                        $output_dir = "files/" . $nombre_dominio . "/comunicados/";
                        $imagen = $grupo . "_" . date('dmY_His') . '.' . pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                        if (!move_uploaded_file($_FILES['file']["tmp_name"], $output_dir . $imagen)) {
                            $msg = 'No pudo subir el archivo';
                        }
                    }
                }
            } else {
                $imagen = NULL;
            }


            //$select_roles = $_POST['select_roles'];
            $id_personastr = isset($_POST['id_personas_array']) ? implode(',', $_POST['id_personas_array']) : NULL;


            //:::::::::::::::::::::::::::::::: INICIO DE INSERSION O UPDATE  :::::::::::::::::::::::::::::
            $estado = false;
            $estresp = 0;
            // Verifica si es creacion o modificacion
            if ($id_comunicado > 0) {
                //:::::::::: UPDATE ::::::::::::::
                if (!$nombre_archivo || $nombre_archivo == '') {
                    //array sin imagen
                    $regImag = array();
                } else {
                    //array con imagen
                    $regImag = array(
                        'file' => $imagen
                    );
                }
                $comunicados = array(
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_final' => $fecha_final,
                    'nombre_evento' => $nombre_evento,
                    'descripcion' => $descripcion_evento,
                    'color' => $color,
                    'prioridad' => $prioridad,
                    'persona_id' => ',' . $id_personastr . ',',
                    'vista_personas_id' => ',',
                    //'aula_paralelo_asignacion_materia_id' => $aula_asig_mat_id,
                    //'modo_calificacion_id' => $modo_id,
                    //'grupo' => $grupo 
                    'usuario_modificacion' => $_user['id_user'],
                    'fecha_modificacion' => Date('Y-m-d H:i:s')
                );
                $instacia_union = array_merge_recursive($comunicados, $regImag); //une las dos instancias


                // Modifica el comunidados
                $db->where('id_comunicado', $id_comunicado)->update('ins_comunicados', $instacia_union);
                // Guarda el proceso
                $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'u',
                    'nivel' => 'l',
                    'detalle' => 'Se modificó el comunidados con identificador número ' . $id_comunicado . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
                ));
                //var_dump($id_personastr);exit();
                //echo ('2');//.'*'.$id_personastr;
                $estado = true;
                $estresp = 1;
            } else {

                //:::::::::::CREAR::::::::::::::
                //if($nombre_archivo){
                $comunicados = array(
                    'fecha_inicio' => $fecha_inicio,
                    'fecha_final' => $fecha_final,
                    'nombre_evento' => $nombre_evento,
                    'descripcion' => $descripcion_evento,
                    'color' => $color,
                    'file' => $imagen,
                    'prioridad' => $prioridad,
                    'asignacion_docente_id' => $aula_asig_mat_id, //id_aula_asig_materia
                    'estado_curso' => $estado_curso, //new
                    'modo_calificacion_id' => $modo_id,
                    'grupo' => $grupo
                );
                //}else{

                //}
                //busca el ultimo registro para el codigo de comunicado 

                //::::::::::::::::::::::::::::::::  CREA CODIGO :::::::::::::::::::::::::::::
                $codigo_mayor = $db->query("SELECT MAX(id_comunicado) as id_comunicado FROM ins_comunicados")->fetch_first();
                $id_anterior = $codigo_mayor['id_comunicado']; //id_comunicado mayor
                if (is_null($id_anterior)) {
                    $nuevo_codigo = "C-1";
                } else {
                    //recupera los datos del ultimo registro
                    $comunicado_mayor = $db->query("SELECT id_comunicado, codigo, nombre_evento FROM ins_comunicados WHERE id_comunicado = $id_anterior ")->fetch_first();
                    $codigo_anterior = $comunicado_mayor['codigo']; //codigo anterior
                    $separado = explode('-', $codigo_anterior);
                    $nuevo_codigo = "C-" . ($separado[1] + 1);
                }

                //if($id_personas){
                // $id_personastr = implode(',', $id_personas);
                // }
                $nuevo_registro = array(
                    'codigo' => $nuevo_codigo,
                    'usuarios' => ',',
                    'estados' => '',
                    'persona_id' => ',' . $id_personastr . ',',
                    'vista_personas_id' => ',',
                    'estado' => 'A',
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro' => Date('Y-m-d H:i:s'),
                    'usuario_modificacion' => $_user['id_user'],
                    'fecha_modificacion' => Date('Y-m-d H:i:s')
                );

                $instacia_union = array_merge_recursive($comunicados, $nuevo_registro); //une las dos instancias
                //var_dump($instacia_union);exit();
                // Crea el comunidados
                $id_comunidado = $db->insert('ins_comunicados', $instacia_union);

                // Guarda el proceso
                $id_comunicado = $db->insert('sys_procesos', array(
                    'fecha_proceso' => date('Y-m-d'),
                    'hora_proceso' => date('H:i:s'),
                    'proceso' => 'c',
                    'nivel' => 'l',
                    'detalle' => 'Se creó el comunidados con identificador número ' . $id_comunidado . '.',
                    'direccion' => $_location,
                    'usuario_id' => $_user['id_user']
                ));
                $estado = true;
                $estresp = 1;
                //echo  ('1');//.'*'.$id_personastr;

            }
        } else {
            echo 'deve enviar un titulo y fechaini obligagoriamente';
        }


        if ($estado) {
            /* $id_comunicado = array(
                    'file'=>$imagen );
               }
             $comunicados = array_merge_recursive($comunicados, $regImag); //une las dos instancias*/
            $instacia_union['id_comunicado'] = $id_comunicado;
            $res = array(
                'estresp' => '1',
                'datos' => $instacia_union
            );
            echo json_encode($res);
        }
    }

    if ($accion == "listarComunicados") {
        //aula
        $id_aula_asig_materia = isset($_POST['id_aula_asig_materia']) ? $_POST['id_aula_asig_materia'] : 0;
        $id_modo = isset($_POST['id_modo']) ? $_POST['id_modo'] : 0;

        $estado_curso = isset($_POST['estado_curso']) ? $_POST['estado_curso'] : 'N';
        //var_dump('aqui php'.$estado_curso);exit();

        $comunic = $db->query("SELECT pe.* FROM ins_comunicados pe WHERE pe.`asignacion_docente_id`=$id_aula_asig_materia AND pe.`modo_calificacion_id`=$id_modo and pe.estado_curso='$estado_curso' and pe.estado='A'")->fetch(); // and pe.aula_paralelo_asignacion_materia_id=".$id_aula_asig_materia

        echo json_encode($comunic);
    }

    if ($accion == "eliminar_comunic") {

        $id_componente = $_POST['id_componente'];
        // verificamos su exite el id en tabla
        $horario = $db->from('ins_comunicados')->where('id_comunicado', $id_componente)->fetch_first();
        //en caso de si eliminarar en caso de no dara mensaje de error
        if (validar($horario)) {
            //eliminar archivo de carpeta
            //$result=$db->query("SELECT file FROM ins_comunicados  WHERE id_comunicado=$id_componente")->fetch_first();

            //while($row =mysql_fetch_array($result)){ 
            $file = $horario["file"];
            //   } 
            $delete_dir = "files/" . $nombre_dominio . "/comunicados/" . $file;
            if (is_file($delete_dir)) {
                unlink($delete_dir);
            }


            $esta = $db->query("UPDATE ins_comunicados SET estado = 'I', usuario_modificacion = '" . $_user['id_user'] . "', fecha_modificacion = '" . date('Y-m-d') . "' WHERE id_comunicado = '" . $id_componente . "'")->execute();

            if ($esta) { //$db->affected_rows) {
                //registrarProceso('Se eliminó el horario con identificador número ' . $id_horario . '.',$db);
                registrarProceso('Se eliminó la actividad con identificador número ', $id_componente, $db, $_location, $_user['id_user']);
                echo 1; //'Eliminado Correctamente.';
            } else {
                echo 2; //'No se pudo eliminar';
            }
        }
    }

    if ($accion == "guardar_tarea_completa") {

        //
        if (isset($_POST['nombre_actividad']) && isset($_POST['fecha_presentacion'])) {

            $archivos_permitidos = 0;

            // Obtiene los datos
            $tipo_extra_m = isset($_POST['tipo_extra_m']) ? $_POST['tipo_extra_m'] : 0;
            $id_asesor_curso_actividad = (isset($_POST['id_asesor_curso_actividad_a'])) ? clear($_POST['id_asesor_curso_actividad_a']) : 0;
            $id_area_calificacion        = (isset($_POST['id_area_calificacion'])) ? clear($_POST['id_area_calificacion']) : 0;
            $asignacion_docente_id     = (isset($_POST['asignacion_docente_id'])) ? clear($_POST['asignacion_docente_id']) : 0;
            //$id_modo_calificacion      = (isset($_POST['id_modo_calificacion'])) ? clear($_POST['id_modo_calificacion']) : 0;
            $id_modo_calificacion_e    = (isset($_POST['id_modo_calificacion_e'])) ? clear($_POST['id_modo_calificacion_e']) : 0;
            $nombre_actividad          = $_POST['nombre_actividad']; //datetime_decode($_POST['fecha_inicio']);
            $descripcion_actividad     = $_POST['descripcion_actividad']; //datetime_decode($_POST['fecha_final']);
            $fecha_presentacion        = (isset($_POST['fecha_presentacion'])) ? (($_POST['fecha_presentacion'] != "") ? ($_POST['fecha_presentacion']) : "0000-00-00") : "0000-00-00";
            $tipo_actividad             = (isset($_POST['tipo_actividad'])) ? clear($_POST['tipo_actividad']) : "";
            $utilizar_generador        = (isset($_POST['utilizar_generador'])) ? clear($_POST['utilizar_generador']) : "NO";

            $estado_cartilla = "SI";

            //TIPO DE ACTIVIDAD TIPO REUNION
            if ($tipo_actividad == "REUNION" || $tipo_actividad == "EXAMEN") {
                $url_doc = (isset($_POST['url_reunion'])) ? clear($_POST['url_reunion']) : "sin url";
                $url_doc = $url_doc . "@";
            } else {
                $url_doc = "";
                //Armamos el url para guardarlo
                for ($i = 1; $i <= 10; $i++) {

                    $url = clear($_POST['url_doc' . $i]);
                    if ($url != "") {
                        $url_doc = $url_doc . $url . "@";
                    }
                }
            }


            $fecha_examen              = (isset($_POST['fecha_examen'])) ? (($_POST['fecha_examen'] != "") ? ($_POST['fecha_examen']) : "0000-00-00") : "0000-00-00";
            $hora_inicio               = (isset($_POST['hora_inicio'])) ? (($_POST['hora_inicio'] != "") ? ($_POST['hora_inicio']) : "00:00:00") : "00:00:00";
            $hora_fin                  = (isset($_POST['hora_final'])) ? (($_POST['hora_final'] != "") ? ($_POST['hora_final']) : "00:00:00") : "00:00:00";
            $presentar_actividad       = (isset($_POST['presentar_actividad'])) ? clear($_POST['presentar_actividad']) : "NO";

            // actvidad programable
            $actividad_programable     = (isset($_POST['actividad_programable'])) ? clear($_POST['actividad_programable']) : "NO";
            $fecha_programable         = (isset($_POST['fecha_programable'])) ? (($_POST['fecha_programable'] != "") ? ($_POST['fecha_programable']) : "0000-00-00") : "0000-00-00";
            $hora_programable          = (isset($_POST['hora_programable'])) ? (($_POST['hora_programable'] != "") ? ($_POST['hora_programable']) : "00:00:00") : "00:00:00";


            if ($tipo_actividad == "EXAMEN") {
                $presentar_actividad = "SI";
                if ($utilizar_generador == "SI") {
                    $url_doc = "";
                }
            }



            $area_calificacion = $db->query("SELECT *
                                    FROM cal_area_calificacion AS cac
                                    WHERE cac.estado = 'A' AND cac.id_area_calificacion = $id_area_calificacion")->fetch_first();

            if ($area_calificacion['obtencion_nota'] == 'D') {
                $presentar_actividad = "SI";
            }

            if ($tipo_actividad == "REUNION") {
                $presentar_actividad = "NO";
                $estado_cartilla = "NO";
            }

            //_________ manejo de archivo ________

            $documentos_actividad = "";
            $documentos_nuevos = "";
            if ($_FILES["file_evento_p"]["name"][0]) {
                for ($i = 0; $i < count($_FILES["file_evento_p"]["name"]); $i++) {

                    $nombre_archivo = isset($_FILES["file_evento_p"]["name"][$i]) ? ($_FILES["file_evento_p"]["name"][$i]) : false;

                    $nombre_archivo = eliminar_acentos($nombre_archivo);
                    $nombre_archivo = eliminar_caracteres($nombre_archivo);
                    $nombre_actividad = eliminar_acentos($nombre_actividad);
                    //var_dump($nombre_archivo);   exit();                                                      
                    if ($nombre_archivo) {
                        if ($nombre_archivo != '') {

                            $output_dir = "files/" . $nombre_dominio . "/documentos_docentes/";
                            //$output_dir = "files/documentos_docentes/";
                            //$imagen =  $nombre_archivo . '_' . str_replace(' ', '', $nombre_actividad) . "_" . date('dmY_His') . '.' . pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                            //$imagen =  str_replace(' ', '', $nombre_archivo) . "_" . date('dmY_His') . '.' . pathinfo($nombre_archivo, PATHINFO_EXTENSION);

                            //Verificamos las Extenciones
                            $formatos_permitidos =  array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf', 'epub', 'txt', 'avi', 'mpeg', 'mp4', 'm4a', '3gp', 'flv', 'ogg', 'mp3', 'wav', 'wma', 'acc', 'bmp', 'gif', 'jpg', 'jpeg', 'png', 'psd', 'ai', 'svg', 'rar', 'zip', '7z', 'gz', 'tar', 'exe');
                            $archivo = $_FILES['file_evento_p']['name'];
                            $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                            $extension = strtolower($extension);

                            if (!in_array($extension, $formatos_permitidos)) {
                                $archivos_permitidos = 1;
                            } else {
                                $imagen =  $asignacion_docente_id . "_" . date('dmY_His') . '.' . pathinfo($nombre_archivo, PATHINFO_EXTENSION);
                                if (!move_uploaded_file($_FILES['file_evento_p']["tmp_name"][$i], $output_dir . $imagen)) {
                                    $msg = 'No pudo subir el archivo';
                                    var_dump($msg);
                                } else {
                                    $documentos_actividad = $documentos_actividad . $imagen . "@";
                                }
                            }
                        }
                    } else {
                        $documentos_actividad = (isset($_POST['file_evento_i'])) ? clear($_POST['file_evento_i']) : "";
                    }
                }
                //Recorremos todos los documentos


                //Eliminamos la ultima arroba
                $documentos_actividad = substr($documentos_actividad, 0, -1);
                $documentos_nuevos = $documentos_actividad;

                $doc_actividad = (isset($_POST['file_evento_i'])) ? clear($_POST['file_evento_i']) : "";
                if ($doc_actividad != "") {
                    $doc = str_replace(",", "@", $doc_actividad);
                    $documentos_actividad = $doc . "@" . $documentos_actividad;
                }
            } else {
                //Se sigue manteniendo los mismos archivos
                $documentos_actividad = (isset($_POST['file_evento_i'])) ? clear($_POST['file_evento_i']) : "";
                $documentos_actividad = str_replace(",", "@", $documentos_actividad);
                $documentos_actividad = $documentos_actividad;
            }
            //_____________________________________

            if ($archivos_permitidos == 0) {
                //var_dump($comunicados);die;
                // Verifica si es creacion o modificacion
                // mayor a 0 creacion
                if ($id_asesor_curso_actividad > 0) {

                    $asesor_curso_actividad = array(
                        'asignacion_asesor_id' => "0",
                        //'estado_curso' => $tipo_extra_m,///copiarrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrrr
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
                        'usuario_registro' => $_user['id_user'],
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
                        'usuario_id' => $_user['id_user']
                    ));
                    //Realizamos una consulta para poder 			
                    $resp['resp'] = "2"; // 2 es editar
                    $resp['archivos'] = $documentos_nuevos;
                    echo json_encode($resp);
                } else {

                    //Verificamos en donde crear la actividad ya sea en actividades normales o extracurriculares
                    $id = explode("-", $asignacion_docente_id);
                    if (count($id) == 2) {
                        $asesor_curso_actividad = array(
                            'asignacion_asesor_id' => "0",
                            'estado_curso' => $tipo_extra_m,
                            'asignacion_docente_id' => $id[1],
                            'modo_calificacion_id' => $id_modo_calificacion_e, //NORMAL SIN_e,
                            'area_calificacion_id' => $id_area_calificacion,
                            'materia_id' => "0",
                            'archivo' => $documentos_actividad,
                            'url_actividad' => $url_doc,
                            'nombre_actividad' => $nombre_actividad,
                            'descripcion_actividad' => $descripcion_actividad,
                            'fecha_presentacion_actividad' => $fecha_presentacion,
                            'modo_presentacion' => "0",
                            'fecha_registro' => Date('Y-m-d H:i:s'),
                            'usuario_registro' => $_user['id_user'],
                            'fecha_modificacion' => "0000-00-00",
                            'usuario_modificacion' => "0",
                            'estado_actividad' => 'A',
                            //'estado_curso' => 'E',
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
                            'estado_cartilla' => $estado_cartilla
                        );
                    } else {
                        $asesor_curso_actividad = array(
                            'asignacion_asesor_id' => "0",
                            'estado_curso' => $tipo_extra_m,
                            'asignacion_docente_id' => $asignacion_docente_id,
                            'modo_calificacion_id' => $id_modo_calificacion_e, //NORMAL SIN_e,
                            'area_calificacion_id' => $id_area_calificacion,
                            'materia_id' => "0",
                            'archivo' => $documentos_actividad,
                            'url_actividad' => $url_doc,
                            'nombre_actividad' => $nombre_actividad,
                            'descripcion_actividad' => $descripcion_actividad,
                            'fecha_presentacion_actividad' => $fecha_presentacion,
                            'modo_presentacion' => "0",
                            'fecha_registro' => Date('Y-m-d H:i:s'),
                            'usuario_registro' => $_user['id_user'],
                            'fecha_modificacion' => "0000-00-00",
                            'usuario_modificacion' => "0",
                            'estado_actividad' => 'A',
                            //'estado_curso' => 'N',
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
                        'detalle' => 'Se creó el documento subido con identificador número ' . $id_temp_asesor_curso_actividad . '.',
                        'direccion' => $_location,
                        'usuario_id' => $_user['id_user']
                    ));

                    //Verificamos si la actividad es de tipo examen para guardar en la tabla temp_curso_actividad_evaluacion
                    if ($tipo_actividad == "EXAMEN" && $utilizar_generador == "SI") {
                        $curso_actividad_evaluacion = array(
                            'asesor_curso_actividad_id' => $id_temp_asesor_curso_actividad,
                            'estado_curso' => $tipo_extra_m,
                            'codigo_evaluacion' => "0000",
                            'link_evaluacion' => "https://",
                            'nota_evaluacion' => 0.0,
                            'fecha_evaluacion' => $fecha_presentacion,
                            'hora_evaluacion' => "00:00:00",
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
                            'usuario_id' => $_user['id_user']
                        ));
                    }

                    //Realizamos una consulta para poder 
                    $doc_actividades = $db->query("SELECT taca.*
                                        FROM  tem_asesor_curso_actividad AS taca 
                                        WHERE taca.id_asesor_curso_actividad = '$id_temp_asesor_curso_actividad' AND estado_actividad = 'A'")->fetch_first();
                    $resp['resp'] = "1";
                    $resp['archivos'] = $doc_actividades['archivo'];
                    echo json_encode($resp);
                }
            } else {
                $resp['resp'] = "3";
                $resp['archivos'] = "";
                echo json_encode($resp);
            }
        } else {
            // Error 400
            require_once bad_request();
            exit;
        }
    }
	
} else {
    // Error 404
    require_once not_found();
    exit;
}


function validar($horarios)
{
    if (!$horarios) {
        // Error 400 
        require_once bad_request();
        exit;
    }
    return true;
}

function registrarProceso($detalle, $id_horario, $db, $_location, $user)
{ //,$pros,$niv){

    $db->insert('sys_procesos', array(
        'fecha_proceso' => date('Y-m-d'),
        'hora_proceso'  => date('H:i:s'),
        'proceso'       => 'u', //$pros
        'nivel'         => 'l', //$niv
        'detalle'       => $detalle . $id_horario . '.',
        'direccion'     => $_location,
        'usuario_id'    => $user
    ));
}

function eliminar_acentos($cadena)
{

    //Reemplazamos la A y a
    $cadena = str_replace(
        array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
        array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
        $cadena
    );

    //Reemplazamos la E y e
    $cadena = str_replace(
        array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
        array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
        $cadena
    );

    //Reemplazamos la I y i
    $cadena = str_replace(
        array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
        array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
        $cadena
    );

    //Reemplazamos la O y o
    $cadena = str_replace(
        array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
        array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
        $cadena
    );

    //Reemplazamos la U y u
    $cadena = str_replace(
        array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
        array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
        $cadena
    );

    //Reemplazamos la N, n, C y c
    $cadena = str_replace(
        array('Ñ', 'ñ', 'Ç', 'ç'),
        array('N', 'n', 'C', 'c'),
        $cadena
    );

    return $cadena;
}


function eliminar_caracteres($cadena)
{
    $cadena = str_replace("'", "", $cadena);
    $cadena = str_replace('"', '', $cadena);
    $cadena = str_replace('%', '', $cadena);
    $cadena = str_replace('+', '', $cadena);
    $cadena = str_replace('}', '', $cadena);
    $cadena = str_replace('{', '', $cadena);
    $cadena = str_replace('�', '', $cadena);
    $cadena = str_replace('!', '', $cadena);
    $cadena = str_replace('(', '', $cadena);
    $cadena = str_replace(')', '', $cadena);
    $cadena = str_replace('?', '', $cadena);
    $cadena = str_replace('�', '', $cadena);
    $cadena = str_replace('*', '', $cadena);
    $cadena = str_replace('�', '', $cadena);
    $cadena = str_replace(':', '', $cadena);
    $cadena = str_replace(';', '', $cadena);
    $cadena = str_replace(',', '', $cadena);
    return $cadena;
}

    // registrarProceso('Se eliminó el horario con identificador número ' . $id_horario . '.');//,'u','l');//u y l proceso y nivel con uso?
