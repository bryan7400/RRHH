<?php  
$id_contrato = (isset($_params[0])) ? $_params[0] : 0;

require_once show_template('header-design');  

$res = $db->query(" SELECT *  
                    FROM per_asignaciones
                    WHERE id_asignacion='$id_contrato'
                    ")->fetch_first();

?>


<!--cuerpo card table--> 

<?php //echo "df".$res['id_asignacion']." - ".$id_contrato; ?>
<form id="form_documento" enctype="multipart/form-data"> 
<div class="modal fade" id="modal_documento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">

                <h5 class="modal-title" id="exampleModalLabel">Área de Contrato</h5>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </a>
            </div>
            <div class="modal-body">




                
    <input type="hidden" id="id"  name="id"  value="<?= $id_contrato ?>">
    <input type="hidden" id="txt" name="txt" value="">

    <div class="row"> 
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="card">         
                <div class="card-body">
                    <div class="modal-header">
                        <div class="centered">
                            <div id="editor-contratos">
<p></p><h1><p></p><h1>CONTRATO DE TRABAJO A PLAZO FIJO</h1><p></p><p></p><p>Que suscriben por una parte la UNIDAD EDUCATIVA PRIVADA “MARANATA”, con Resolución Ministerial No. 164 de fecha 30 de mayo de 1998 domiciliado en la av. Martín Sánchez Alcaya Nº 121 Urb. Atipiris de la zona de Senkata – Puente Vela, representada por '.$firmax['nombre'].' con C.I.&nbsp;'._____________________.' en calidad de '_____________________(cargo)' de este centro educativo (en adelante el CONTRATANTE), y por la otra parte el Sr(a).&nbsp;'.strtoupper($persona['primer_apellido'].' '.$persona['segundo_apellido'].' '.$persona['nombres']).' con C.I.&nbsp;'.$persona['numero_documento'].''.strtoupper ($persona['expedido']).'&nbsp;&nbsp;(en adelante EL(LA)&nbsp; CONTRATADO(A)), de acuerdo a las siguientes cláusulas:</p>

<p><strong>PRIMERO</strong></p><p>EL(LA) CONTRATADO(A) se compromete a trabajar para la UNIDAD EDUCATIVA PRIVADA “MARANATA”, como Docente de 

con&nbsp;'.$horas_academicas.' horas académicas mensuales a partir del '.$fecha_contratacion.', hasta el '.$fecha_finalizacion.', periodo que comprende el año escolar, a cuyo vencimiento quedará sin efecto el presente contrato de trabajo a plazo fijo.</p>


<p><strong>SEGUNDO</strong></p><p>EL(LA) CONTRATADO(A), está obligado a desempeñar todas las labores propias de la docencia, las cuales se señalan a continuación con sentido meramente indicativo y no limitado:&nbsp;</p><ul><li>Integrar la comunidad magisterial.</li><li>Cumplir con el contrato de trabajo establecido en mutuo acuerdo con la Junta Directiva, el Director y el Plantel Administrativo; dentro del marco de la Ley de Centros Educativos Privados, en sus diferentes obligaciones y tareas como personal de la Institución.</li><li>Cumplir su función de Maestro frente a los Estudiantes, Coordinación Académica, Dirección y Junta Directiva del Colegio.</li><li>Cumplir con el horario de trabajo establecido por la Institución.</li><li>Asistir y participar con regularidad en las actividades espirituales del Colegio y de la Iglesia.</li><li>Mantenerse en los principios y normas de la iglesia Adventista en el desempeño de sus funciones.</li><li>Trabajar en armonía con las resoluciones de la Junta Directiva, la Dirección, la Administración, Coordinación Académica y de las comisiones establecidas para el buen desempeño de la enseñanza.</li><li>Renovarse constantemente para ofrecer técnicas y conocimientos actualizados.</li><li>Evaluar y reevaluar a los Estudiantes e informarlos oportunamente las notas de evaluación.</li><li>Realizar una reflexión y oración antes de iniciar las clases, sobre todo las primeras horas de cada día.</li><li>Participar en los cultos de docentes en forma puntual, sobre todo cuando tenga las primeras horas de clase.</li><li>Dar a conocer a través de un documento sobre las inasistencias en caso de emergencia y contratiempo.</li><li>Comunicar con anticipación a la Dirección, Coordinación Académica sobre las actividades de paseos excursiones y planes de estudio para su tratamiento oportuno.</li><li>Mantener la disciplina en todas las dependencias educativas del Colegio.</li><li>Cooperar con el plan de visitación a los hogares de los Estudiantes.</li><li>En caso de enfermedad, presentar el certificado médico oportunamente.</li><li>Mantienen un alto nivel de ética cristiana profesional para inspirar altos ideales en los Estudiantes.</li><li>Velar por el mantenimiento adecuado del aula, instalaciones y equipamiento del Centro Educativo y promover su mejora.</li><li>Se abstiene de realizar en el Colegio actividades que contravengan los objetivos fines y reglamentos de la institución.</li><li>Programan, desarrollan y evalúan las actividades curriculares.</li><li>Realizan acciones de recuperación pedagógica, en coordinación con la Dirección y Coordinación Académica.</li><li>Coordinan y mantienen la comunicación permanente con los Padres de Familia sobre asuntos relacionados con el rendimiento académico y el comportamiento de los Estudiantes en las jornadas informativas (entrevista de padres).</li><li>Promueven el desarrollo armonioso del Estudiante, buscando los mejores métodos.</li><li>Se muestran aptos para inculcar en los Estudiantes principios de verdad, integridad, pureza, honradez y obediencia.</li><li>Trabajan por precepto y ejemplo.</li><li>Trabajan con especial atención con los Estudiantes difíciles y/o deficientes, sin impacientarse con los errores de los mismos.</li><li>No ser irritables, impacientes, arbitrarios o autoritarios y se esfuerzan por tener buena relación con los colegas y autoridades.</li><li>Participan activamente en los trabajos de mejoramiento de la Unidad Educativa.</li><li>Controlan la asistencia y puntualidad de los Estudiantes.</li><li>Llevan registros de asistencia, conducta, evaluación de los Estudiantes a su cargo.</li><li>Registran su ingreso y salida diariamente en el biométrico.</li><li>Portan y emplean su PAB y PDC diario de clases y otros documentos técnico-pedagógicos, en forma permanente.</li><li>En su trabajo se visten adecuadamente como docentes cristianos: Profesores con terno y corbata, y Profesoras con falda, respetando las normas cristianas de la Iglesia Adventista del Séptimo Día.</li><li>Observan las normas y principios de la Iglesia Adventista por ejemplo y por precepto</li><li>Cualquier otro inherente a su cargo, o que le sea solicitado por el Director.</li><li>Toda orden o instructivo (verbal o escrito) debe ser acatado en el momento (sin excusas).</li></ul><p><strong>TERCERO</strong></p><p>EL(LA) CONTRATADO(A) cumplirá con la jornada de trabajo de horas académicas semanales de acuerdo al horario que establezca la Dirección de la UNIDAD EDUCATIVA PRIVADA “MARANATA”. El horario de trabajo podrá variar a las necesidades de la programación académica a las actividades programadas, (concejo de profesores)</p><p>Por tratarse de labores escolares EL(LA) CONTRATADO(A) debe programar para cumplir bajo su responsabilidad dentro del horario de trabajo, no tendrá derecho al cobro de horas extraordinarias.</p><p><strong>CUARTO</strong></p><p>Las licencias por enfermedad y por otras razones justificadas serán concedidas por el Director del establecimiento, de acuerdo a las disposiciones legales vigentes en la Legislación del Trabajo, debiendo presentar y/o dejar a un docente como suplente para no perjudicar al estudiante.</p><p><strong>


QUINTO</strong></p><p>Se deja establecida que la Institución pagara al empleado(a) por horas trabajados mensualmente deduciendo descuentos de Ley y otros como; faltas, abandonos injustificados de clases y a diferentes actividades que realice la Institución.</p><p>El salario Mensual de Trabajo Convenido entre partes de mutuo acuerdo es Bs.&nbsp;'.$sueldo_total.' ('.$monto_literal.' 00/100 Bolivianos)</p><p><strong>SEXTO</strong></p><p>Al finalizar la gestión y una vez entregado a la Dirección los boletines de evaluación anual y libretas de calificaciones de los estudiantes se cancelará al CONTRATADO(A) el Aguinaldo y la Indemnización de los diez meses Trabajados de acuerdo a las duodécimas.&nbsp;</p><p><strong>SEPTIMO</strong></p><p>EL(LA) CONTRATADO(A) (A), se somete al control disciplinario y de asistencia que establezca la UNIDAD EDUCATIVA PRIVADA “MARANATA”, a través de su Dirección.</p><p><strong>OCTAVO</strong></p><p>En caso de inasistencia por más de tres días continuos injustificados, abandono injustificado de funciones y por consiguiente quedará sin efecto del presente CONTRATO.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p><strong>NOVENO</strong></p><p>Los que suscriben el presente CONTRATO, da así su conformidad a cada una de las cláusulas estipuladas por lo que se firma en triple ejemplar al pie del presente contrato, en mención en el Departamento de La Paz Bolivia '.$fecha_today.'</p>



 <p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; _________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; _________________________</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CONTRATANTE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;CONTRATADO(A)</p><p>&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; '.$firmax['nombre'].'&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;PROF(A).&nbsp;'.strtoupper($persona['primer_apellido'].' '.$persona['segundo_apellido'].' '.$persona['nombres']).'&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;'.$firmax['cargo'].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; MAESTRO(A)&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p><br data-cke-filler="true"></p><p><br data-cke-filler="true"></p>

                            </div>
                        </div>      
                      </div>
                      <div class="modal-footer">    
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </a>
                        <a href="?/rrhh-personal/imprimir-contrato/<?php echo $id_contrato; ?>" target="_blank">    
                            <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="">
                                <span class="glyphicon glyphicon-floppy-disk"></span>
                                <span>Imprimir</span>
                            </button>
                        </a>
                        <button type="submit" class="btn btn-primary" id="btn_guardar" onclick="copy();">
                            <span class="glyphicon glyphicon-floppy-disk"></span>
                            <span>Guardar</span>
                        </button>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
</div>
</form>
<link type="text/css" href="sample/css/sample.css" rel="stylesheet" media="screen" />

<style>
    .ajs-message.ajs-custom { 
        color: #31708f;  
        background-color: #d9edf7;  
        border-color: #31708f; 
    }
</style>

<script src="<?= js ?>/ckeditor.js"></script>

<script>
    ClassicEditor
    .create( document.querySelector('#editor-contratos'), {
    } )
    .then( editor => {
        window.editor_contratos = editor_contratos;
    } )
    .catch( err => {
        console.error( err.stack );
    } );
    function copy(){
        txt=$('.ck-editor__editable').html();
        $('#txt').val(txt);
    }
</script>
