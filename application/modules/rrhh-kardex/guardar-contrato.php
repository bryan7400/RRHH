<?php
/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
// Verifica la peticion post
if (is_post()) {
    // Verifica la cadena csrf
    //if (isset($_POST[get_csrf()])) {
    if (true) {
        // Verifica la existencia de datos
        if(isset($_POST['cargo']) ){
            // Obtiene los datos
            
            $id_contrato = (isset($_POST['id_contrato'])) ? clear($_POST['id_contrato']) : 0;
            $id_persona = (isset($_POST['id_postulante'])) ? clear($_POST['id_postulante']) : 0;
            $cargo = clear($_POST['cargo']);
            $niveles_academicos = (isset($_POST['nivel_academico'])) ? $_POST['nivel_academico'] : array();
            $sueldo_por_hora = clear($_POST['sueldoxhora']);
            $sueldo_total = clear($_POST['sueldo_total']);
            $horas = clear($_POST['horas']);
            $materias = (isset($_POST['materias'])) ? $_POST['materias'] : array();
            $quien_firma = (isset($_POST['quien_firma'])) ? $_POST['quien_firma'] : 0;

            $fecha_inicio_en = clear($_POST['fecha_inicio']);
            $fecha_final_en = clear($_POST['fecha_final']);
            
            /*
            $x=explode("-",$fecha_inicio);
            $fecha_inicio_en = $x[2]."-".$x[1]."-".$x[0];

            $x=explode("-",$fecha_final);
            $fecha_final_en = $x[2]."-".$x[1]."-".$x[0];
            */

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
        

            //switch($cargo){
            //    case '1':
                    $documento='<p></p><h1>CONTRATO DE TRABAJO A PLAZO FIJO</h1><p></p><p></p><p>Que suscriben por una parte la UNIDAD EDUCATIVA PRIVADA ???MARANATA???, con Resoluci??n Ministerial No. 164 de fecha 30 de mayo de 1998 domiciliado en la av. Mart??n S??nchez Alcaya N?? 121 Urb. Atipiris de la zona de Senkata ??? Puente Vela, representada por '.$firmax['nombre'].' con C.I.&nbsp;'.$firmax['ci'].' en calidad de '.$firmax['cargo'].' de este centro educativo (en adelante el CONTRATANTE), y por la otra parte el Sr(a).&nbsp;'.strtoupper($persona['primer_apellido'].' '.$persona['segundo_apellido'].' '.$persona['nombres']).' con C.I.&nbsp;'.$persona['numero_documento'].''.strtoupper ($persona['expedido']).'&nbsp;&nbsp;(en adelante EL(LA)&nbsp; CONTRATADO(A)), de acuerdo a las siguientes cl??usulas:</p>';
                    
                    $documento.='<p><strong>PRIMERO</strong></p><p>EL(LA) CONTRATADO(A) se compromete a trabajar para la UNIDAD EDUCATIVA PRIVADA ???MARANATA???, como Docente de '.$materiax_nombrex." ".$nivelx_nombrex;

                    $documento.=' con&nbsp;'.$horas.' horas acad??micas mensuales a partir del '.$fecha_contratacion.', hasta el '.$fecha_finalizacion.', periodo que comprende el a??o escolar, a cuyo vencimiento quedar?? sin efecto el presente contrato de trabajo a plazo fijo.</p>';

                    $documento.='<p><strong>SEGUNDO</strong></p><p>EL(LA) CONTRATADO(A), est?? obligado a desempe??ar todas las labores propias de la docencia, las cuales se se??alan a continuaci??n con sentido meramente indicativo y no limitado:&nbsp;</p><ul><li>Integrar la comunidad magisterial.</li><li>Cumplir con el contrato de trabajo establecido en mutuo acuerdo con la Junta Directiva, el Director y el Plantel Administrativo; dentro del marco de la Ley de Centros Educativos Privados, en sus diferentes obligaciones y tareas como personal de la Instituci??n.</li><li>Cumplir su funci??n de Maestro frente a los Estudiantes, Coordinaci??n Acad??mica, Direcci??n y Junta Directiva del Colegio.</li><li>Cumplir con el horario de trabajo establecido por la Instituci??n.</li><li>Asistir y participar con regularidad en las actividades espirituales del Colegio y de la Iglesia.</li><li>Mantenerse en los principios y normas de la iglesia Adventista en el desempe??o de sus funciones.</li><li>Trabajar en armon??a con las resoluciones de la Junta Directiva, la Direcci??n, la Administraci??n, Coordinaci??n Acad??mica y de las comisiones establecidas para el buen desempe??o de la ense??anza.</li><li>Renovarse constantemente para ofrecer t??cnicas y conocimientos actualizados.</li><li>Evaluar y reevaluar a los Estudiantes e informarlos oportunamente las notas de evaluaci??n.</li><li>Realizar una reflexi??n y oraci??n antes de iniciar las clases, sobre todo las primeras horas de cada d??a.</li><li>Participar en los cultos de docentes en forma puntual, sobre todo cuando tenga las primeras horas de clase.</li><li>Dar a conocer a trav??s de un documento sobre las inasistencias en caso de emergencia y contratiempo.</li><li>Comunicar con anticipaci??n a la Direcci??n, Coordinaci??n Acad??mica sobre las actividades de paseos excursiones y planes de estudio para su tratamiento oportuno.</li><li>Mantener la disciplina en todas las dependencias educativas del Colegio.</li><li>Cooperar con el plan de visitaci??n a los hogares de los Estudiantes.</li><li>En caso de enfermedad, presentar el certificado m??dico oportunamente.</li><li>Mantienen un alto nivel de ??tica cristiana profesional para inspirar altos ideales en los Estudiantes.</li><li>Velar por el mantenimiento adecuado del aula, instalaciones y equipamiento del Centro Educativo y promover su mejora.</li><li>Se abstiene de realizar en el Colegio actividades que contravengan los objetivos fines y reglamentos de la instituci??n.</li><li>Programan, desarrollan y eval??an las actividades curriculares.</li><li>Realizan acciones de recuperaci??n pedag??gica, en coordinaci??n con la Direcci??n y Coordinaci??n Acad??mica.</li><li>Coordinan y mantienen la comunicaci??n permanente con los Padres de Familia sobre asuntos relacionados con el rendimiento acad??mico y el comportamiento de los Estudiantes en las jornadas informativas (entrevista de padres).</li><li>Promueven el desarrollo armonioso del Estudiante, buscando los mejores m??todos.</li><li>Se muestran aptos para inculcar en los Estudiantes principios de verdad, integridad, pureza, honradez y obediencia.</li><li>Trabajan por precepto y ejemplo.</li><li>Trabajan con especial atenci??n con los Estudiantes dif??ciles y/o deficientes, sin impacientarse con los errores de los mismos.</li><li>No ser irritables, impacientes, arbitrarios o autoritarios y se esfuerzan por tener buena relaci??n con los colegas y autoridades.</li><li>Participan activamente en los trabajos de mejoramiento de la Unidad Educativa.</li><li>Controlan la asistencia y puntualidad de los Estudiantes.</li><li>Llevan registros de asistencia, conducta, evaluaci??n de los Estudiantes a su cargo.</li><li>Registran su ingreso y salida diariamente en el biom??trico.</li><li>Portan y emplean su PAB y PDC diario de clases y otros documentos t??cnico-pedag??gicos, en forma permanente.</li><li>En su trabajo se visten adecuadamente como docentes cristianos: Profesores con terno y corbata, y Profesoras con falda, respetando las normas cristianas de la Iglesia Adventista del S??ptimo D??a.</li><li>Observan las normas y principios de la Iglesia Adventista por ejemplo y por precepto</li><li>Cualquier otro inherente a su cargo, o que le sea solicitado por el Director.</li><li>Toda orden o instructivo (verbal o escrito) debe ser acatado en el momento (sin excusas).</li></ul><p><strong>TERCERO</strong></p><p>EL(LA) CONTRATADO(A) cumplir?? con la jornada de trabajo de horas acad??micas semanales de acuerdo al horario que establezca la Direcci??n de la UNIDAD EDUCATIVA PRIVADA ???MARANATA???. El horario de trabajo podr?? variar a las necesidades de la programaci??n acad??mica a las actividades programadas, (concejo de profesores)</p><p>Por tratarse de labores escolares EL(LA) CONTRATADO(A) debe programar para cumplir bajo su responsabilidad dentro del horario de trabajo, no tendr?? derecho al cobro de horas extraordinarias.</p><p><strong>CUARTO</strong></p><p>Las licencias por enfermedad y por otras razones justificadas ser??n concedidas por el Director del establecimiento, de acuerdo a las disposiciones legales vigentes en la Legislaci??n del Trabajo, debiendo presentar y/o dejar a un docente como suplente para no perjudicar al estudiante.</p><p><strong>';
                
                    $documento.='QUINTO</strong></p><p>Se deja establecida que la Instituci??n pagara al empleado(a) por horas trabajados mensualmente deduciendo descuentos de Ley y otros como; faltas, abandonos injustificados de clases y a diferentes actividades que realice la Instituci??n.</p><p>El salario Mensual de Trabajo Convenido entre partes de mutuo acuerdo es Bs.&nbsp;'.$sueldo_total.' ('.$monto_literal.' 00/100 Bolivianos)</p><p><strong>SEXTO</strong></p><p>Al finalizar la gesti??n y una vez entregado a la Direcci??n los boletines de evaluaci??n anual y libretas de calificaciones de los estudiantes se cancelar?? al CONTRATADO(A) el Aguinaldo y la Indemnizaci??n de los diez meses Trabajados de acuerdo a las duod??cimas.&nbsp;</p><p><strong>SEPTIMO</strong></p><p>EL(LA) CONTRATADO(A) (A), se somete al control disciplinario y de asistencia que establezca la UNIDAD EDUCATIVA PRIVADA ???MARANATA???, a trav??s de su Direcci??n.</p><p><strong>OCTAVO</strong></p><p>En caso de inasistencia por m??s de tres d??as continuos injustificados, abandono injustificado de funciones y por consiguiente quedar?? sin efecto del presente CONTRATO.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p><strong>NOVENO</strong></p><p>Los que suscriben el presente CONTRATO, da as?? su conformidad a cada una de las cl??usulas estipuladas por lo que se firma en triple ejemplar al pie del presente contrato, en menci??n en el Departamento de La Paz Bolivia '.$fecha_today.'</p>';
                
                    $documento.=' <p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; _________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; _________________________</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CONTRATANTE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;CONTRATADO(A)</p><p>&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; '.$firmax['nombre'].'&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;PROF(A).&nbsp;'.strtoupper($persona['primer_apellido'].' '.$persona['segundo_apellido'].' '.$persona['nombres']).'&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'.$firmax['cargo'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; MAESTRO(A)&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p>';
                /*    break;

                default:
                    $documento='<p></p><h1>CONTRATO DE TRABAJO A PLAZO FIJO</h1><p></p><p></p><p>Que suscriben por una parte la UNIDAD EDUCATIVA PRIVADA ???MARANATA???, con Resoluci??n Ministerial No. 164 de fecha 30 de mayo de 1998 domiciliado en la av. Mart??n S??nchez Alcaya N?? 121 Urb. Atipiris de la zona de Senkata ??? Puente Vela, representada por la Lic.&nbsp;CARLOS CARVAJAL CORONEL con C.I.&nbsp;4288101 LP en calidad de DIRECTOR de este centro educativo (en adelante el CONTRATANTE), y por la otra parte el Sr(a).&nbsp;'.strtoupper($persona['primer_apellido'].' '.$persona['segundo_apellido'].' '.$persona['nombres']).' con C.I.&nbsp;'.$persona['numero_documento'].''.strtoupper ($persona['expedido']).'&nbsp;&nbsp;(en adelante EL(LA)&nbsp; CONTRATADO(A)), de acuerdo a las siguientes cl??usulas:</p>';
                    
                    $documento.='<p><strong>PRIMERO</strong></p><p>EL(LA) CONTRATADO(A) se compromete a trabajar para la UNIDAD EDUCATIVA PRIVADA ???MARANATA???, como Docente de '.$materiax_nombrex." ".$nivelx_nombrex;

                    $documento.=' con&nbsp;'.$horas.' horas acad??micas mensuales a partir del 01 de febrero del 2020, hasta el 30 de noviembre la gesti??n 2020, periodo que comprende el a??o escolar, a cuyo vencimiento quedar?? sin efecto el presente contrato de trabajo a plazo fijo.</p>';

                    $documento.='<p><strong>SEGUNDO</strong></p><p>EL(LA) CONTRATADO(A), est?? obligado a desempe??ar todas las labores propias de la docencia, las cuales se se??alan a continuaci??n con sentido meramente indicativo y no limitado:&nbsp;</p><p><strong>TERCERO</strong></p><p>EL(LA) CONTRATADO(A) cumplir?? con la jornada de trabajo de horas acad??micas semanales de acuerdo al horario que establezca la Direcci??n de la UNIDAD EDUCATIVA PRIVADA ???MARANATA???. El horario de trabajo podr?? variar a las necesidades de la programaci??n acad??mica a las actividades programadas, (concejo de profesores)</p><p>Por tratarse de labores escolares EL(LA) CONTRATADO(A) debe programar para cumplir bajo su responsabilidad dentro del horario de trabajo, no tendr?? derecho al cobro de horas extraordinarias.</p><p><strong>CUARTO</strong></p><p>Las licencias por enfermedad y por otras razones justificadas ser??n concedidas por el Director del establecimiento, de acuerdo a las disposiciones legales vigentes en la Legislaci??n del Trabajo, debiendo presentar y/o dejar a un docente como suplente para no perjudicar al estudiante.</p><p><strong>';
                
                    $documento.='QUINTO</strong></p><p>Se deja establecida que la Instituci??n pagara al empleado(a) por horas trabajados mensualmente deduciendo descuentos de Ley y otros como; faltas, abandonos injustificados de clases y a diferentes actividades que realice la Instituci??n.</p><p>El salario Mensual de Trabajo Convenido entre partes de mutuo acuerdo es Bs.&nbsp;'.$sueldo_total.' ('.$monto_literal.' 00/100 Bolivianos)</p><p><strong>SEXTO</strong></p><p>Al finalizar la gesti??n y una vez entregado a la Direcci??n los boletines de evaluaci??n anual y libretas de calificaciones de los estudiantes se cancelar?? al CONTRATADO(A) el Aguinaldo y la Indemnizaci??n de los diez meses Trabajados de acuerdo a las duod??cimas.&nbsp;</p><p><strong>SEPTIMO</strong></p><p>EL(LA) CONTRATADO(A) (A), se somete al control disciplinario y de asistencia que establezca la UNIDAD EDUCATIVA PRIVADA ???MARANATA???, a trav??s de su Direcci??n.</p><p><strong>OCTAVO</strong></p><p>En caso de inasistencia por m??s de tres d??as continuos injustificados, abandono injustificado de funciones y por consiguiente quedar?? sin efecto del presente CONTRATO.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p><strong>NOVENO</strong></p><p>Los que suscriben el presente CONTRATO, da as?? su conformidad a cada una de las cl??usulas estipuladas por lo que se firma en triple ejemplar al pie del presente contrato, en menci??n en el Departamento de La Paz Bolivia '.$fecha_today.'</p>';
                
                    $documento.=' <p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; _________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; _________________________</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CONTRATANTE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;CONTRATADO(A)</p><p>&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; LIC. CARLOS CARVAJAL CORONEL&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;PROF(A).&nbsp;'.strtoupper($persona['primer_apellido'].' '.$persona['segundo_apellido'].' '.$persona['nombres']).'&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;DIRECTOR GENERAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; MAESTRO(A)&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p>';
                    break;
            }*/




            // Instancia el gondolas
            if($id_contrato > 0){
                $res = array(
                    'fecha_asignacion' => date("Y-m-d H:i:s"),
                    'observacion' => "",
                    'horario_id' => "",
                    'persona_id' => $id_persona,
                    'cargo_id' => $cargo,
                    'user_id' => $_user['id_user'],
                    'nivel_academico_id'=>$materiax,
                    'materia_id' => $nivelx,
                    'sueldo_total' => $sueldo_total,
                    'sueldo_por_hora' => $sueldo_por_hora,
                    'horas_academicas' => $horas,
                    'estado' => "A",
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro' => date('Y-m-d'),
                    'usuario_modificacion' => "",
                    'fecha_modificacion' => date('Y-m-d'),
                    'documento'=>$documento,
                    'fecha_inicio'=>$fecha_inicio_en,
                    'fecha_final'=>$fecha_final_en,
                    'firma_id'=>$quien_firma
                );
                $db->where('id_asignacion', $id_contrato)->update('per_asignaciones', $res);

                // Redirecciona la pagina
                echo "1";
                //redirect('?/gon-gondolas/ver/' . $id_gondola);
            }else{                
                $res = array(
                    'fecha_asignacion' => date("Y-m-d H:i:s"),
                    'observacion' => "",
                    'horario_id' => "",
                    'persona_id' => $id_persona,
                    'cargo_id' => $cargo,
                    'user_id' => $_user['id_user'],
                    'nivel_academico_id'=>$materiax,
                    'materia_id' => $nivelx,
                    'sueldo_total' => $sueldo_total,
                    'sueldo_por_hora' => $sueldo_por_hora,
                    'horas_academicas' => $horas,
                    'estado' => "A",
                    'usuario_registro' => $_user['id_user'],
                    'fecha_registro' => date('Y-m-d'),
                    'usuario_modificacion' => "",
                    'fecha_modificacion' => date('Y-m-d'),
                    'documento'=>$documento,
                    'fecha_inicio'=>$fecha_inicio_en,
                    'fecha_final'=>$fecha_final_en,
                    'firma_id'=>$quien_firma
                );
                $id_gondola = $db->insert('per_asignaciones', $res);

                echo "1";
                //redirect('?/gon-gondolas/listar');
            }
        } else {
            // Error 400
            require_once bad_request();
            exit;    
        }
    } else {
        // Redirecciona la pagina
        redirect('?/rrhh-personal/listar');
    }
} else {
    // Error 404
    require_once not_found();
    exit;
}

?>