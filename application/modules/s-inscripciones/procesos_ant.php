<?php
    //var_dump($_FILES);die;
    $boton = $_POST['boton'];

    $id_gestion = $_gestion['id_gestion'];

   /*use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    // Importa la libreria para generar el reporte
    require_once libraries . '/PHPMailer-6.0.7/src/Exception.php';
    require_once libraries . '/PHPMailer-6.0.7/src/PHPMailer.php';
    require_once libraries . '/PHPMailer-6.0.7/src/SMTP.php';*/
    ///////////// require_once libraries . '/PHPMailer/PHPMailerAutoload.php';

    if($boton == "listar_familiares"){
         //Obtiene los estudiantes
        //var_dump($_POST);die;
        if(isset($_POST['id_estudiante'])){
            $id_estudiante = $_POST['id_estudiante'];
        }else{
            $id_estudiante = "";
        }
        //$familiar = $db->select('z.*')->from('vista_estudiante_familiar z')->where('id_estudiante', $id_estudiante)->order_by('z.id_estudiante_familiar', 'asc')->fetch();
        $familiar = $db->query("SELECT * FROM vista_estudiante_familiar WHERE id_estudiante = '$id_estudiante'")->fetch();
        echo json_encode($familiar);
    }

    if($boton == "listar_tipo_documento"){
        $respuesta = $db->query("SELECT * FROM catalogo_detalle WHERE catalogo_id = 1")->fetch();
        echo json_encode($respuesta);
    }

    if($boton == "datos_estudiante"){
        
        $id_estudiante = $_POST['id_estudiante']; 

        $array = array();

        $datos_personales = $db->query("SELECT e.id_estudiante,
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
                                        WHERE e.id_estudiante = $id_estudiante")->fetch_first();
        $array['datos_personales'] = $datos_personales;

        $vacunas = $db->query("SELECT *
                               FROM ins_vacunas WHERE estudiante_id = $id_estudiante")->fetch_first();
        $array['vacunas'] = $vacunas;
        
        echo json_encode($array);
    }

    if($boton == "vacunas"){
        $id_estudiante = $_POST['id_vacunas'];
        if(isset($_POST['bcg'])){
            $bcg = 'SI';
        }else{
            $bcg = 'NO';
        }

        if(isset($_POST['a1'])){
            $a1 = "SI";
        }else{
            $a1 = "NO";
        }

        if(isset($_POST['a2'])){
            $a2 = "SI";
        }else{
            $a2 = "NO";
        }

        if(isset($_POST['a3'])){
            $a3 = "SI";
        }else{
            $a3 = "NO";
        }

        if(isset($_POST['p1'])){
            $p1 = "SI";
        }else{
            $p1 = "NO";
        }

        if(isset($_POST['p2'])){
            $p2 = "SI";
        }else{
            $p2 = "NO";
        }

        if(isset($_POST['p3'])){
            $p3 = "SI";
        }else{
            $p3 = "NO";
        }

        if(isset($_POST['am1'])){
            $am1 = "SI";
        }else{
            $am1 = "NO";
        }

        if(isset($_POST['srp1'])){
            $srp1 = "SI";
        }else{
            $srp1 = "NO";
        }

        if(isset($_POST['o1'])){
            $o1 = "SI";
        }else{
            $o1 = "NO";
        }

        if(isset($_POST['observaciones_vacunas'])){
            $observaciones_vacunas = $_POST['observaciones_vacunas'];
        }else{
            $observaciones_vacunas = "";
        }

        //var_dump($_POST);die;
        // Guarda el proceso de vacunas
        $db->insert('ins_vacunas', array(
            'estudiante_id' => $id_estudiante,
            'tuberculosis' => $bcg,
            'antipolio_1'=>$a1,
            'antipolio_2'=>$a2,
            'antipolio_3'=>$a3,
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
        $cadena = array('id_estudiante'=> $id_estudiante,
                    'estado'=> 1);
        echo json_encode($cadena);
    }

    if($boton == "agregar_familiar"){
        $nombres = $_POST['f_nombres'];
        $primer_apellido = $_POST['f_primer_apellido'];
        $segundo_apellido = $_POST['f_segundo_apellido'];
        $tipo_documento = $_POST['f_tipo_documento'];
        $numero_documento = $_POST['f_numero_documento'];
        $complemento = $_POST['f_complemento'];
        $nit = $_POST['f_nit'];
        $genero = $_POST['f_genero'];
        $fecha_nacimiento = $_POST['f_fecha_nacimiento'];
        $telefono = $_POST['f_telefono'];
        $profesion = $_POST['f_profesion'];
        $direccion_oficina = $_POST['f_direccion_oficina'];
        $id_estudiante = $_POST['id_familiares'];
        
        //var_dump($_POST);die;
        /*$id_persona = $db->query( "INSERT INTO sys_persona (nombres, primer_apellido, segundo_apellido, tipo_documento, numero_documento, complemento, genero, fecha_nacimiento)
                    VALUES ('$nombres', '$primer_apellido', '$segundo_apellido', '$tipo_documento', '$numero_documento', '$complemento', '$genero', '$fecha_nacimiento')")->execute();*/
        //$respuesta = $conexion->query($consulta);
        $persona = array(
            'nombres' => $nombres,
            'primer_apellido' => $primer_apellido,
            'segundo_apellido' => $segundo_apellido,
            'tipo_documento' => $tipo_documento, 
            'numero_documento' => $numero_documento,
            'complemento' => $complemento,
            'genero' => $genero,
            'fecha_nacimiento' => $fecha_nacimiento,
            'nit' => $nit
        );
        $id_persona = $db->insert('sys_persona', $persona);
        //var_dump($id_persona);die;
        if($id_persona){
            //echo 1;
            $codigo_mayor = $db->query("SELECT MAX(id_familiar) as id_familiar FROM ins_familiar")->fetch_first();
            $id_anterior = $codigo_mayor['id_familiar'];//id_comunicado mayor
            if(is_null($id_anterior)){
                $nuevo_codigo = "F-1";            
            }else{
                //recupera los datos del ultimo registro
                $familiar_mayor = $db->query("SELECT id_familiar, codigo_familia FROM ins_familiar WHERE id_familiar = $id_anterior ")->fetch_first();
                $codigo_anterior = $familiar_mayor['codigo_familia']; //codigo anterior
                $separado = explode('-', $codigo_anterior);
                $nuevo_codigo = "F-" . ($separado[1] + 1);
            }

            //var_dump($nuevo_codigo);die;
            $familiar = array(
                'profesion' => $profesion,
                'direccion_oficina' => $direccion_oficina,
                'telefono_oficina' => $telefono,
                'persona_id' => $id_persona,
                'codigo_familia' => $nuevo_codigo,
                'estado' => 'A',
                'usuario_registro' => $_user['id_user'],
                'fecha_registro' => Date('Y-m-d H:i:s')
            );

            //var_dump($familiar);die;
            $id_familiar = $db->insert('ins_familiar', $familiar);
            
            if($id_familiar){
                $estudiante_familiar = array(
                    'familiar_id' => $id_familiar,
                    'estudiante_id' => $id_estudiante,
                    'tutor' => 0
                );
                $id_estudiante_familiar = $db->insert('ins_estudiante_familiar', $estudiante_familiar);

                // Instancia el usuario
                $usuario = array(
                    'username' => $nombres.'.'.$primer_apellido,
                    'password' => encrypt($numero_documento),
                    'email' => '',
                    'active' => 's',
                    'visible' => 's',
                    'rol_id' => 4,
                    'persona_id' => $id_persona,
                    'gestion_id' => $id_gestion
                );

                // Crea el usuario
                $id_usuario = $db->insert('sys_users', $usuario);
                
                if($id_estudiante_familiar){
                    echo 1;
                }else{
                    echo 2;
                }
                //echo 1;
            }else{
                echo 3;
            }
        }else{
            echo 4;
        }
    }

    //obtiene el listado de cursos
    if($boton == "listar_cursos"){
        //obtiene el nivel
        $nivel = $_POST['nivel'];
        //obtiene los cursos segun el nivel
        $cursos = $db->query("SELECT * FROM ins_aula WHERE nivel_academico_id = $nivel ORDER BY nombre_aula ")->fetch();
        echo json_encode($cursos);
    }

    //obtiene el listado de paralelos
    if($boton == "listar_paralelos"){
        $id_curso = $_POST['id_curso'];
        $paralelo = $db->query("SELECT * FROM vista_aula_paralelo WHERE id_aula = $id_curso")->fetch();
        echo json_encode($paralelo);
    }

    //obtiene el listado de vacantes segun curso/paralelo
    if($boton == "listar_vacantes"){
        $id_aula_paralelo = $_POST['id_aula_paralelo'];
        $consulta = $db->query("SELECT COUNT(aula_paralelo_id) as contador FROM ins_inscripcion WHERE aula_paralelo_id = $id_aula_paralelo")->fetch_first();
        $consulta_aula = $db->query("SELECT * FROM ins_aula_paralelo WHERE id_aula_paralelo = $id_aula_paralelo")->fetch_first();
        //obtiene el total de vacantes del curso paralelo        
        $vacantes = $consulta_aula['capacidad'] - $consulta['contador'];
        echo json_encode($vacantes);
    }
    
    if($boton == "seleccionar_tutor"){
        //var_dump($_POST);die;
        $id_estudiante_familiar = $_POST['id_estudiante_familiar'];
        $id_estudiante = $_POST['id_estudiante'];
        $id_tutor = $_POST['id_tutor'];

        $tutor = array('tutor'=> 1); //instancia tutor

        //selecciona al tutor
        $db->where('id_estudiante_familiar', $id_estudiante_familiar)->update('ins_estudiante_familiar', $tutor);
        //$consulta = "UPDATE ins_estudiante_familiar SET tutor = 1 WHERE id_estudiante_familiar = $id_estudiante_familiar";
        
        $familiar = array('tutor'=> 0);
        //$consulta ="UPDATE ins_estudiante_familiar SET tutor = 0 WHERE id_estudiante_familiar <> $id_estudiante_familiar AND estudiante_id = $id_estudiante";
        $db->query("UPDATE ins_estudiante_familiar SET tutor = 0 WHERE id_estudiante_familiar <> $id_estudiante_familiar AND estudiante_id = $id_estudiante")->execute();
        
        echo 1;
    }

    if($boton == "identificar_familiar"){
        //var_dump($_POST);die;
        $id_estudiante = $_POST['id_estudiante'];
        $id_familiar = $_POST['id_familiar'];

        if($id_estudiante AND $id_familiar){
            $estudiante_familiar = array(
                'familiar_id' => $id_familiar,
                'estudiante_id' => $id_estudiante,
                'tutor' => 0
            );
    
            $id_estudiante_familiar = $db->insert('ins_estudiante_familiar', $estudiante_familiar);
            
            if($id_estudiante_familiar){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            echo 3;
        }
    }

    if($boton == "guardar_inscripcion"){
        //var_dump($_POST);die;
        $id_estudiante = $_POST['id_inscripciones'];
        $id_tipo_estudiante = $_POST['tipo_estudiante'];
        $id_nivel_academico = $_POST['nivel_academico'];
        $id_aula = $_POST['select_curso'];
        $id_aula_paralelo = $_POST['select_paralelo'];
        /*$aula_paralelo = $db->query("SELECT * FROM ins_aula_paralelo WHERE id_aula_paralelo = $id_aula_paralelo")->fetch_first();
        $id_aula_paralelo = $aula_paralelo['id_aula_paralelo'];*/

        //var_dump($_POST);die;
        $busqueda = $db->query("SELECT COUNT(codigo_inscripcion) as codigo_inscripcion FROM ins_inscripcion WHERE estudiante_id = $id_estudiante AND gestion_id = $id_gestion")->fetch_first();
        $contador = $busqueda['codigo_inscripcion'];
        if($contador > 0){
            echo 3;
        }else{
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
                'usuario_registro' => $_user['id_user'],
                'fecha_registro' => Date('Y-m-d H:i:s')
            );

            $id_inscripcion = $db->insert('ins_inscripcion', $inscripcion);
            if($id_inscripcion){
                echo 1;
            }else{
                echo 2;
            }
        }
        //var_dump($busqueda);

    }

    if($boton == "eliminar_familiar"){
        //var_dump($_POST);die;
        $id_estudiante_familiar = $_POST['id_estudiante_familiar'];
        if($id_estudiante_familiar){
            $db->delete()->from('ins_estudiante_familiar')->where('id_estudiante_familiar', $id_estudiante_familiar)->limit(1)->execute();
            if ($db->affected_rows) {
                echo 1;
			} else {
				echo 2;
			}
        }else{
            echo 3;
        }
        
    }

  //   if($boton == "correo_prueba"){
  //       $mail=new PHPMailer();
		// $mail->isSMTP();
		// $mail->CharSet="UTF-8";
		// $mail->Host="mail.checkcode.bo";
		// $mail->SMTPAuth=true;
		// $mail->Username="checkcode";
		// $mail->Password="Qa3vL3NG-@njur";
		// $mail->SMTPSecure='tls';
		// $mail->Port=587;

		// //configuracion de correo a enviar
		// $mail->setFrom('checkcode.bo');
		// $mail->addAddress('jorge.patzi.jp@gmail.com');
		// $mail->IsHTML(true);

		// $body = "<b>Estimado«a» Recepcionista</b><br>";
		// $body .= "Victor";
		// $body .= "<p>Presente.-</p><br>";
		// $body .= "<p>En nombre del equipo de Kirikú Gestores de Voluntariado Social, agradecemos su trabajo como Recepcionista.</p>";
		
		// $body .= "<p><center><h3>AHORA ERES PARTE DE ESTA TRANSFORMACIÓN.</h3></center></p>";
		// $body .= "<p>Adjunto va el documento Comprobante por la recepción de la donación.</p>";
		// $body .= "<p>A partir de ahora y por este mismo medio haremos llegar todas las Donaciones que fue recepcionado por su persona.</p>";
		// $body .= "<p>Nos despedimos afectuosamente.</p><br><br>";
		// $body .= "<p>Imprima el documento PDF, coloque su rubrica y entregue una copia al encargado de almacen de KIRIKÚ.</p><br><br>";
		// $body .= "<p style='margin-bottom: 0;'><h1> <font color='#865110'>KIRIKÚ</font></h1></p>";
		// $body .= "<p style='margin-top: 0; margin-bottom: 0;'>Gestores de voluntariado Social</p>";
		// $body .= "<p style='margin-top: 0; margin-bottom: 0;'>Calle Carlos Blanco N° 1922 «B» Villa Copacabana, La Paz, Bolivia</p>";
		// $body .= "<p style='margin-top: 0; margin-bottom: 0;'>Celular: +591 73714906 - 70131214 Teléfono: +591 2239450</p>";
		// $body .= "<p style='margin-top: 0;'><a href='http://www.voluntariadokiriku.com'> www.voluntariadokiriku.com</a></p>";

		// $mail->Subject='Conprobante de Entrega';
		// $mail->Body=$body;
  //       //$mail->addAttachment($path);
  //       //envio del email
  //       if(!$mail->Send()) {
  //       //echo "Error al enviar: " . $mail->ErrorInfo;
  //           echo "1";
  //       } else {
  //       //echo "Mensaje enviado!";
  //           echo "2";
  //       }
  //   }
?>