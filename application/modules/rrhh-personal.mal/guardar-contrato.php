<?php
if (is_post()) {    
    $id_gestion=$_gestion['id_gestion'];

    $tipo = (isset($_POST['tipo'])) ? clear($_POST['tipo']) : 0;

    $id_persona = (isset($_POST['id_persona'])) ? clear($_POST['id_persona']) : '0';
    $id_postulante = (isset($_POST['id_postulante'])) ? clear($_POST['id_postulante']) : '0';
    $id_asignacion = (isset($_POST['id_asignacionx'])) ? clear($_POST['id_asignacionx']) : '0';

    $nombres = (isset($_POST['nombres'])) ? clear($_POST['nombres']) : '';
    $primer_apellido = (isset($_POST['primer_apellido'])) ? clear($_POST['primer_apellido']) : '';
    $segundo_apellido = (isset($_POST['segundo_apellido'])) ? clear($_POST['segundo_apellido']) : '';
    $numero_documento = (isset($_POST['numero_documento'])) ? clear($_POST['numero_documento']) : '';
    $complemento = (isset($_POST['complemento'])) ? clear($_POST['complemento']) : '';
    $expedido = (isset($_POST['expedido'])) ? clear($_POST['expedido']) : '';
    $fecha_nacimiento = (isset($_POST['fecha_nacimiento'])) ? clear($_POST['fecha_nacimiento']) : '';
    $genero = (isset($_POST['genero'])) ? clear($_POST['genero']) : '';
    $contacto = (isset($_POST['contacto'])) ? clear($_POST['contacto']) : '';
    $email = (isset($_POST['email'])) ? clear($_POST['email']) : '';
    $direccion = (isset($_POST['direccion'])) ? clear($_POST['direccion']) : '';
    
    $cargo = (isset($_POST['cargo'])) ? clear($_POST['cargo']) : 0;
    $fecha_inicio_en = clear($_POST['fecha_inicio']);
    $fecha_final_en = clear($_POST['fecha_final']);
    
    $tipo_contrato = (isset($_POST['tipo_contrato'])) ? clear($_POST['tipo_contrato']) : '';
    
    $sueldo_por_hora = (isset($_POST['sueldo_por_hora'])) ? clear($_POST['sueldo_por_hora']) : 0;
    $horas_academicas = (isset($_POST['horas_academicas'])) ? clear($_POST['horas_academicas']) : 0;
    $sueldo_total = (isset($_POST['sueldo_total'])) ? clear($_POST['sueldo_total']) : 0;
    
    $observacion = (isset($_POST['observacion'])) ? clear($_POST['observacion']) : '';
    
    $generar_usuario = (isset($_POST['generar_usuario'])) ? clear($_POST['generar_usuario']) : '';
    $rol_user = (isset($_POST['rol_user'])) ? clear($_POST['rol_user']) : '';
    
    $sueldo_por_hora = floatval($sueldo_por_hora);
    $horas_academicas = floatval($horas_academicas);
    $sueldo_total = floatval($sueldo_total);
    
    $niveles_academicos = (isset($_POST['nivel_academico'])) ? $_POST['nivel_academico'] : array();
    $materias = (isset($_POST['materias'])) ? $_POST['materias'] : array();
   
    $generar_usuario = (isset($_POST['generar_usuario'])) ? clear($_POST['generar_usuario']) : '';
    $rol_user = (isset($_POST['rol_user'])) ? clear($_POST['rol_user']) : '';
    $quien_firma = (isset($_POST['quien_firma'])) ? $_POST['quien_firma'] : 0;

    $materiax="";
    $nivelx="";
    
    for($i=0;$i<count($materias);$i++){
        $materiax=$materiax.$materias[$i].",";
    }

    for($i=0;$i<count($niveles_academicos);$i++){
        $nivelx=$nivelx.$niveles_academicos[$i].",";
    }

    if($tipo==2){
        $cargo=1;
    }

    if(strlen($fecha_inicio_en)<10){
        $fecha_inicio_en="0000-00-00";
    }
    if(strlen($fecha_final_en)<10){
        $fecha_final_en="0000-00-00";
    }
    
    $res = array(
        //'id_postulacion' => "",
        'nombre' => $nombres,
        'paterno' => $primer_apellido,
        'materno' => $segundo_apellido,
        'cargo_id' => $cargo,
        'localidad' => "",
        'provincia' => "",
        'departamento' => "",
        'fecha_nacimiento' => $fecha_nacimiento,
        'estado_civil' => "",
        'ci' => $numero_documento,
        'expirado' => $expedido,
        'direccion' => $direccion,
        'nro_direccion' => "",
        'zona' => "",
        'ciudad' => "",
        'telefono' => "",
        'celular' => "",
        'email' => $email,
        'afp' => "",
        'nua' => "",
        'conyuge' => "",
        'fecha_nacimiento_c' => "0000-00-00",
        'fecha_bautismo' => "0000-00-00",
        'pastor' => "",
        'iglesia' => "",
        'distrito' => "",
        'escalafon' => "",
        'fecha_escalafon' => "0000-00-00",
        'unidad' => "",
        'turno' => "",
        'asignatura' => "",
        'periodos' => "",
        'fecha_registro' => "0000-00-00",
        'estado' => "A",
        'personal' => "A",
        'genero' => $genero,
        'cuenta_bancaria' => "",
        'cns' => "",
        'nacionalidad' => "",
    );
    if($id_persona==0){
        $id_postulante = $db->insert('per_postulacion', $res);
    }else{
        //$db->where('id_persona', $id_persona)->update('sys_persona', $res);        
    }
    
    $res = array(
        'nombres' => $nombres,
        'primer_apellido' => $primer_apellido,
        'segundo_apellido' => $segundo_apellido,
        'tipo_documento' => "1",
        'numero_documento' => $numero_documento,
        'complemento' => $complemento,
        'expedido' => $expedido,
        'genero' => $genero,
        'direccion' => $direccion,
        'foto' => "",
        'nit' => "",
        'postulante_id' => $id_postulante,
        'contacto' => "",
        'celular' => $contacto,
        'email' => $email,
        'fecha_nacimiento' => $fecha_nacimiento
    );
    
    if($id_persona==0){
        $id_persona = $db->insert('sys_persona', $res);
    }else{
        $db->where('id_persona', $id_persona)->update('sys_persona', $res);        
    }
    


    /***************************************************************************************/
    /***************************************************************************************/
    /***************************************************************************************/
    /***************************************************************************************/
    /***************************************************************************************/



    //contrato documento
    if(strlen($fecha_inicio_en)<10){
        $fecha_inicio_en="0000-00-00";
    }
    if(strlen($fecha_final_en)<10){
        $fecha_final_en="0000-00-00";
    }

    $materiax="";
    $materiax_nombrex="";
    $nivelx="";
    $nivelx_nombrex="";

    for($i=0;$i<count($materias);$i++){
        $materiax=$materiax.$materias[$i].",";

        $persona = $db->query(" SELECT *
                                FROM pro_materia
                                WHERE id_materia='".$materias[$i]."'
                                ")->fetch_first();     

        if($i==0){
            if(count($materias)==1){
                $materiax_nombrex="la asignatura de:&nbsp;".$persona['nombre_materia'];
            }else{
                $materiax_nombrex="las asignaturas de:&nbsp;".$persona['nombre_materia'];
            }
        }
        else{
            if($i+1==count($materias)){
                $materiax_nombrex.=" y ".$persona['nombre_materia'];                    
            }else{
                $materiax_nombrex.=", ".$persona['nombre_materia'];                    
            }
        }
    }

    for($i=0;$i<count($niveles_academicos);$i++){
        $nivelx=$nivelx.$niveles_academicos[$i].",";

        $persona = $db->query(" SELECT *
                                FROM ins_nivel_academico
                                WHERE id_nivel_academico='".$niveles_academicos[$i]."'
                                ")->fetch_first();     

        if($i==0){
            if(count($niveles_academicos)==1){
                $nivelx_nombrex=" del Nivel: &nbsp;".$persona['nombre_nivel'];
            }else{
                $nivelx_nombrex=" de los Niveles:&nbsp;".$persona['nombre_nivel'];
            }
        }
        else{
            if($i+1==count($niveles_academicos)){
                $nivelx_nombrex.=" y ".$persona['nombre_nivel'];                    
            }else{
                $nivelx_nombrex.=", ".$persona['nombre_nivel'];                    
            }
        }
    }

    $persona = $db->query(" SELECT *  
                            FROM sys_persona
                            WHERE id_persona='$id_persona'
                            ")->fetch_first();

    $m=date("m");
    switch($m){
        case "1":   case "01":   $m="Enero";    break;
        case "2":   case "02":   $m="Febrero";    break;
        case "3":   case "03":   $m="Marzo";    break;
        case "4":   case "04":   $m="Abril";    break;
        case "5":   case "05":   $m="Mayo";    break;
        case "6":   case "06":   $m="Junio";    break;
        case "7":   case "07":   $m="Julio";    break;
        case "8":   case "08":   $m="Agosto";    break;
        case "9":   case "09":   $m="Septiembre";    break;
        case "10":   $m="Octubre";    break;
        case "11":   $m="Noviembre";    break;
        case "12":   $m="Diciembre";    break;
    }
    $fecha_today=intval(date("d"))." de $m de ".date("Y");

            
    $x=explode("-",$fecha_inicio_en);
    switch($x[1]){
        case "1":   case "01":   $m="Enero";    break;
        case "2":   case "02":   $m="Febrero";    break;
        case "3":   case "03":   $m="Marzo";    break;
        case "4":   case "04":   $m="Abril";    break;
        case "5":   case "05":   $m="Mayo";    break;
        case "6":   case "06":   $m="Junio";    break;
        case "7":   case "07":   $m="Julio";    break;
        case "8":   case "08":   $m="Agosto";    break;
        case "9":   case "09":   $m="Septiembre";    break;
        case "10":   $m="Octubre";    break;
        case "11":   $m="Noviembre";    break;
        case "12":   $m="Diciembre";    break;
    }
    $fecha_contratacion=intval($x[2])." de $m de ".$x[0];

    $x=explode("-",$fecha_final_en);
    switch($x[1]){
        case "1":   case "01":   $m="Enero";    break;
        case "2":   case "02":   $m="Febrero";    break;
        case "3":   case "03":   $m="Marzo";    break;
        case "4":   case "04":   $m="Abril";    break;
        case "5":   case "05":   $m="Mayo";    break;
        case "6":   case "06":   $m="Junio";    break;
        case "7":   case "07":   $m="Julio";    break;
        case "8":   case "08":   $m="Agosto";    break;
        case "9":   case "09":   $m="Septiembre";    break;
        case "10":   $m="Octubre";    break;
        case "11":   $m="Noviembre";    break;
        case "12":   $m="Diciembre";    break;
    }
    $fecha_finalizacion=intval($x[2])." de $m de ".$x[0];


    $firmax = $db->query("  SELECT *
                            FROM rrhh_firma_contrato
                            WHERE id_firma='".$quien_firma."'
                            ")->fetch_first();     

    

    require_once libraries . '/numbertoletter-class/NumberToLetterConverter.php';
    $conversor = new NumberToLetterConverter();
    $monto_literal = ucfirst(strtolower(trim($conversor->to_word($sueldo_total))));

    $documento="";
    
    switch($cargo){
        case '1':
            $documento='<p></p><h1>CONTRATO DE TRABAJO A PLAZO FIJO</h1><p></p><p></p><p>Que suscriben por una parte la UNIDAD EDUCATIVA PRIVADA “MARANATA”, con Resolución Ministerial No. 164 de fecha 30 de mayo de 1998 domiciliado en la av. Martín Sánchez Alcaya Nº 121 Urb. Atipiris de la zona de Senkata – Puente Vela, representada por '.$firmax['nombre'].' con C.I.&nbsp;'.$firmax['ci'].' en calidad de '.$firmax['cargo'].' de este centro educativo (en adelante el CONTRATANTE), y por la otra parte el Sr(a).&nbsp;'.strtoupper($persona['primer_apellido'].' '.$persona['segundo_apellido'].' '.$persona['nombres']).' con C.I.&nbsp;'.$persona['numero_documento'].''.strtoupper ($persona['expedido']).'&nbsp;&nbsp;(en adelante EL(LA)&nbsp; CONTRATADO(A)), de acuerdo a las siguientes cláusulas:</p>';
            
            $documento.='<p><strong>PRIMERO</strong></p><p>EL(LA) CONTRATADO(A) se compromete a trabajar para la UNIDAD EDUCATIVA PRIVADA “MARANATA”, como Docente de '.$materiax_nombrex." ".$nivelx_nombrex;

            $documento.=' con&nbsp;'.$horas_academicas.' horas académicas mensuales a partir del '.$fecha_contratacion.', hasta el '.$fecha_finalizacion.', periodo que comprende el año escolar, a cuyo vencimiento quedará sin efecto el presente contrato de trabajo a plazo fijo.</p>';

            $documento.='<p><strong>SEGUNDO</strong></p><p>EL(LA) CONTRATADO(A), está obligado a desempeñar todas las labores propias de la docencia, las cuales se señalan a continuación con sentido meramente indicativo y no limitado:&nbsp;</p><ul><li>Integrar la comunidad magisterial.</li><li>Cumplir con el contrato de trabajo establecido en mutuo acuerdo con la Junta Directiva, el Director y el Plantel Administrativo; dentro del marco de la Ley de Centros Educativos Privados, en sus diferentes obligaciones y tareas como personal de la Institución.</li><li>Cumplir su función de Maestro frente a los Estudiantes, Coordinación Académica, Dirección y Junta Directiva del Colegio.</li><li>Cumplir con el horario de trabajo establecido por la Institución.</li><li>Asistir y participar con regularidad en las actividades espirituales del Colegio y de la Iglesia.</li><li>Mantenerse en los principios y normas de la iglesia Adventista en el desempeño de sus funciones.</li><li>Trabajar en armonía con las resoluciones de la Junta Directiva, la Dirección, la Administración, Coordinación Académica y de las comisiones establecidas para el buen desempeño de la enseñanza.</li><li>Renovarse constantemente para ofrecer técnicas y conocimientos actualizados.</li><li>Evaluar y reevaluar a los Estudiantes e informarlos oportunamente las notas de evaluación.</li><li>Realizar una reflexión y oración antes de iniciar las clases, sobre todo las primeras horas de cada día.</li><li>Participar en los cultos de docentes en forma puntual, sobre todo cuando tenga las primeras horas de clase.</li><li>Dar a conocer a través de un documento sobre las inasistencias en caso de emergencia y contratiempo.</li><li>Comunicar con anticipación a la Dirección, Coordinación Académica sobre las actividades de paseos excursiones y planes de estudio para su tratamiento oportuno.</li><li>Mantener la disciplina en todas las dependencias educativas del Colegio.</li><li>Cooperar con el plan de visitación a los hogares de los Estudiantes.</li><li>En caso de enfermedad, presentar el certificado médico oportunamente.</li><li>Mantienen un alto nivel de ética cristiana profesional para inspirar altos ideales en los Estudiantes.</li><li>Velar por el mantenimiento adecuado del aula, instalaciones y equipamiento del Centro Educativo y promover su mejora.</li><li>Se abstiene de realizar en el Colegio actividades que contravengan los objetivos fines y reglamentos de la institución.</li><li>Programan, desarrollan y evalúan las actividades curriculares.</li><li>Realizan acciones de recuperación pedagógica, en coordinación con la Dirección y Coordinación Académica.</li><li>Coordinan y mantienen la comunicación permanente con los Padres de Familia sobre asuntos relacionados con el rendimiento académico y el comportamiento de los Estudiantes en las jornadas informativas (entrevista de padres).</li><li>Promueven el desarrollo armonioso del Estudiante, buscando los mejores métodos.</li><li>Se muestran aptos para inculcar en los Estudiantes principios de verdad, integridad, pureza, honradez y obediencia.</li><li>Trabajan por precepto y ejemplo.</li><li>Trabajan con especial atención con los Estudiantes difíciles y/o deficientes, sin impacientarse con los errores de los mismos.</li><li>No ser irritables, impacientes, arbitrarios o autoritarios y se esfuerzan por tener buena relación con los colegas y autoridades.</li><li>Participan activamente en los trabajos de mejoramiento de la Unidad Educativa.</li><li>Controlan la asistencia y puntualidad de los Estudiantes.</li><li>Llevan registros de asistencia, conducta, evaluación de los Estudiantes a su cargo.</li><li>Registran su ingreso y salida diariamente en el biométrico.</li><li>Portan y emplean su PAB y PDC diario de clases y otros documentos técnico-pedagógicos, en forma permanente.</li><li>En su trabajo se visten adecuadamente como docentes cristianos: Profesores con terno y corbata, y Profesoras con falda, respetando las normas cristianas de la Iglesia Adventista del Séptimo Día.</li><li>Observan las normas y principios de la Iglesia Adventista por ejemplo y por precepto</li><li>Cualquier otro inherente a su cargo, o que le sea solicitado por el Director.</li><li>Toda orden o instructivo (verbal o escrito) debe ser acatado en el momento (sin excusas).</li></ul><p><strong>TERCERO</strong></p><p>EL(LA) CONTRATADO(A) cumplirá con la jornada de trabajo de horas académicas semanales de acuerdo al horario que establezca la Dirección de la UNIDAD EDUCATIVA PRIVADA “MARANATA”. El horario de trabajo podrá variar a las necesidades de la programación académica a las actividades programadas, (concejo de profesores)</p><p>Por tratarse de labores escolares EL(LA) CONTRATADO(A) debe programar para cumplir bajo su responsabilidad dentro del horario de trabajo, no tendrá derecho al cobro de horas extraordinarias.</p><p><strong>CUARTO</strong></p><p>Las licencias por enfermedad y por otras razones justificadas serán concedidas por el Director del establecimiento, de acuerdo a las disposiciones legales vigentes en la Legislación del Trabajo, debiendo presentar y/o dejar a un docente como suplente para no perjudicar al estudiante.</p><p><strong>';

            $documento.='QUINTO</strong></p><p>Se deja establecida que la Institución pagara al empleado(a) por horas trabajados mensualmente deduciendo descuentos de Ley y otros como; faltas, abandonos injustificados de clases y a diferentes actividades que realice la Institución.</p><p>El salario Mensual de Trabajo Convenido entre partes de mutuo acuerdo es Bs.&nbsp;'.$sueldo_total.' ('.$monto_literal.' 00/100 Bolivianos)</p><p><strong>SEXTO</strong></p><p>Al finalizar la gestión y una vez entregado a la Dirección los boletines de evaluación anual y libretas de calificaciones de los estudiantes se cancelará al CONTRATADO(A) el Aguinaldo y la Indemnización de los diez meses Trabajados de acuerdo a las duodécimas.&nbsp;</p><p><strong>SEPTIMO</strong></p><p>EL(LA) CONTRATADO(A) (A), se somete al control disciplinario y de asistencia que establezca la UNIDAD EDUCATIVA PRIVADA “MARANATA”, a través de su Dirección.</p><p><strong>OCTAVO</strong></p><p>En caso de inasistencia por más de tres días continuos injustificados, abandono injustificado de funciones y por consiguiente quedará sin efecto del presente CONTRATO.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p><strong>NOVENO</strong></p><p>Los que suscriben el presente CONTRATO, da así su conformidad a cada una de las cláusulas estipuladas por lo que se firma en triple ejemplar al pie del presente contrato, en mención en el Departamento de La Paz Bolivia '.$fecha_today.'</p>';

            $documento.=' <p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; _________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; _________________________</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CONTRATANTE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;CONTRATADO(A)</p><p>&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; '.$firmax['nombre'].'&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;PROF(A).&nbsp;'.strtoupper($persona['primer_apellido'].' '.$persona['segundo_apellido'].' '.$persona['nombres']).'&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'.$firmax['cargo'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; MAESTRO(A)&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p>';
            break;
    }
    //contrato
    $res = array(
        'fecha_asignacion' => date("Y-m-d H:i:s"),
        'observacion' => $observacion,
        'horario_id' => "",
        'persona_id' => $id_persona,
        'cargo_id' => $cargo,
        
        'user_id' => $_user['id_user'],
        'nivel_academico_id'=>$nivelx,
        'materia_id' => $materiax,
        'sueldo_por_hora' => $sueldo_por_hora,                    
        'horas_academicas' => $horas_academicas,

        'usuario_registro' => $_user['id_user'],
        'fecha_registro' => date('Y-m-d'),
        'usuario_modificacion' => $_user['id_user'],
        'fecha_modificacion' => date('Y-m-d'),
        'documento'=>$documento,

        'codigo'=>'',
        'gestion_id'=>$_gestion['id_gestion'],
        'estado' => "A",
        'cuenta_bancaria' => "",
        'item_cns' => "",

        'fecha_final'=>$fecha_final_en,
        'fecha_inicio'=>$fecha_inicio_en,
        'sueldo_total' => $sueldo_total,
        'firma_id'=>$firmax['id_firma'],
        'estado_real' => "A",

        'estado_contrato'=>"VIGENTE",
        'fecha_estado_contrato'=>"0000-00-00 00:00:00"
    );
    
    if($id_asignacion==0){
        $id_gondola = $db->insert('per_asignaciones', $res);
    }else{
        $db->where('id_asignacion', $id_asignacion)->update('per_asignaciones', $res);        
    }
    






    if($generar_usuario =="SI"){    
        ///////////////////////////////////////////////////////////////////////
        $find = array('Á','É','Í','Ó','Ú','Ñ','ñ','á','é','í','ó','ú');
        $repl = array('A','E','I','O','U','N','N','A','E','I','O','U');
        $nombre_estudiante  = str_replace($find, $repl, $nombres);
        $primer_apellido    = str_replace($find, $repl, $primer_apellido);
        $segundo_apellido    = str_replace($find, $repl, $segundo_apellido);

        $nuevo_nombre_estudiante  = clear($nombre_estudiante);
        // $nuevo_primer_apellido    = clear($primer_apellido);
        // $nuevo_segundo_apellido    = clear($segundo_apellido);

        if($primer_apellido!=''){
           $username_apellido = clear($primer_apellido); 
        }else{
           $username_apellido = clear($segundo_apellido); 
        }

        $nombre         = explode(" ", $nuevo_nombre_estudiante);
        $username_nombre    = $nombre[0];

        // Instancia el usuario
        $usuario = array(
            'username'  => strtoupper($username_nombre.'.'.$username_apellido),
            'password'  => encrypt($numero_documento),
            'email'     => clear(strtolower($email)),
            'active'    => 's',
            'visible'   => 's',
            
            'rol_id'    => $rol_user,
            'avatar'        => '',
            'login_at'      => '0000-00-00 00:00:00',
            'logout_at'     => '0000-00-00 00:00:00',            
            'persona_id'    => $id_persona,

            'gestion_id'    => $id_gestion,
            'token'    => '',
            'imei'    => '',
            'estado'    => 'A'
        );
        $usser = $db->query("SELECT *
                            from sys_users
                            where persona_id='$id_persona' AND gestion_id='$id_gestion'")
                    ->fetch_first();

        if($usser){
            $db->where('id_user', $usser['id_user'])->update('sys_users', $usuario);        
        }else{
            $id_gondola = $db->insert('sys_users', $usuario);
        }
    }


    echo "1";        
} else {
    // Error 404
    require_once not_found();
    exit;
}
?>