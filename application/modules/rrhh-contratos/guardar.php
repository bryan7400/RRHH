<?php

/**
 * FunctionPHP - Framework Functional PHP
 * 
 * @package  FunctionPHP
 * @author   Wilfredo Nina <wilnicho@hotmail.com>
 */
$gestion=$_gestion['gestion'];
$documentos_actividad = "";
$archivos_permitidos = 0;
$nombre_dominio = escape($_institution['nombre_dominio']);
$permiso_subir = in_array('subir', $_views);

define("6LcKS7wdAAAAAPGQ9QJqnDZlShIFj8eBBillM9lQ", "6LcKS7wdAAAAALOIflsirUl4khgkwp9PddZlOVXk");


if (!isset($_POST["g-recaptcha-response"]) || empty($_POST["g-recaptcha-response"])) {
    exit("Debes completar el captcha");
}

# Antes de comprobar usuario y contraseña, vemos si resolvieron el captcha
$token = $_POST["g-recaptcha-response"];
$verificado = verificarToken($token, CLAVE_SECRETA);

if ($verificado) {
    /**
     * Llegados a este punto podemos confirmar que el usuario
     * no es un robot. Aquí debes hacer lo que se deba hacer, es decir,
     * comprobar las credenciales, darle acceso, etcétera, pues
     * ya ha pasado el captcha
     */
    echo "Has completado la prueba :)";
} else {
    exit("Lo siento, parece que eres un robot");

}

// Verifica la peticion post
if (is_post()) { 
	// Verifica la cadena csrf
	//if (isset($_POST[get_csrf()])) {
		// Verifica la existencia de datos
	if (isset($_POST['area_contrato']) && isset($_FILES['archivo_documento']["name"] ) ) {
			

		$nombre_archivo_documento = isset($_FILES["archivo_documento"]["name"]) ? ($_FILES["archivo_documento"]["name"]) : false;
		// Obtiene los datos
		$id_contrato = (isset($_POST['id_contrato'])) ? clear($_POST['id_contrato']) : 0;
		
		$area_contrato = clear($_POST['area_contrato']);
		$tipo_contrato = clear($_POST['tipo_contrato']);
		$modalidad_contrato = clear($_POST['modalidad_contrato']);
		$nivel_academico =(isset($_POST['nivel_academico'])) ? $_POST['nivel_academico'] : array();
		$tipo_documento = clear($_POST['tipo_documento']);
		$documento = clear($_POST['documento']);
		$fecha_inicio = (isset($_POST['fecha_inicio'])) ? ($_POST['fecha_inicio']) : '0000-00-00';
		$fecha_final =  (isset($_POST['fecha_final'])) ? ($_POST['fecha_final']) : '0000-00-00';
		
		
		
        // obtiene la gestion
		



 		$archivo_documento_nombre = clear($_POST['archivo_documento_nombre']);


		$archivo_documentoedit = $db->query("SELECT * FROM rrhh_contrato WHERE archivo_documento='$archivo_documento_nombre'")->fetch_first();


	if (($nombre_archivo_documento != '') || ($archivo_documento_nombre != '')) {

		if ($nombre_archivo_documento != ''){
			$formatos_permitidos =  array('pdf', 'jpg', 'jpeg', 'png', 'docx');
 			$archivo_documento = $_FILES['archivo_documento']["name"];
 			$extension = pathinfo($nombre_archivo_documento, PATHINFO_EXTENSION);
 			$extension = strtolower($extension);

 			if (!in_array($extension, $formatos_permitidos)) {
 				$archivo_documentos_permitidos = 1;
 			} else {
 				$output_dir = 'files/' . $nombre_dominio . '/rrhh/';
 				$imagen =  date('dmY_His') . '_' . '.' . pathinfo($nombre_archivo_documento, PATHINFO_EXTENSION);;
 				if (!move_uploaded_file($_FILES['archivo_documento']["tmp_name"], $output_dir . $imagen)) {
 					$msg = 'No pudo subir el archivo_documento';
 					var_dump($msg);
 				} else {
 					$documentos_actividad = $documentos_actividad . $imagen . "@";
 				}
 			}

 			if ($archivo_documento_nombre != ''){
 				unlink('files/' . $nombre_dominio . '/rrhh/'.$archivo_documento_nombre);
 			} else {
 				
 			}

 			$archivo_documento = clear($imagen);
 			



		} else {


				$archivo_documento = clear($_POST['archivo_documento_nombre']);
			}

}





$documento='<p></p><h1>CONTRATO DE TRABAJO A PLAZO FIJO</h1><p></p><p></p><p>Que suscriben por una parte la UNIDAD EDUCATIVA PRIVADA “MARANATA”, con Resolución Ministerial No. 164 de fecha 30 de mayo de 1998 domiciliado en la av. Martín Sánchez Alcaya Nº 121 Urb. Atipiris de la zona de Senkata – Puente Vela, representada por __________________[nombre] con C.I.&nbsp;__________________[ci] en calidad de '.$area_contrato.' de este centro educativo (en adelante el CONTRATANTE), y por la otra parte el Sr(a).&nbsp;__________________[primer_apellido] __________________[segundo_apellido] __________________[nombres] con C.I.&nbsp;__________________persona numero_documento]__________________[expedido]&nbsp;&nbsp;(en adelante EL(LA)&nbsp; CONTRATADO(A)), de acuerdo a las siguientes cláusulas:</p>';

$documento.='<p><strong>PRIMERO</strong></p><p>EL(LA) CONTRATADO(A) se compromete a trabajar para la UNIDAD EDUCATIVA PRIVADA “MARANATA”, como Docente de '.implode(',', $nivel_academico).' de __________________';

$documento.=' con&nbsp;_________________[horas_academicas] horas académicas mensuales a partir del '.$fecha_inicio.', hasta el '.$fecha_final.', periodo que comprende el año escolar, a cuyo vencimiento quedará sin efecto el presente contrato de trabajo a plazo fijo.</p>';

$documento.='<p><strong>SEGUNDO</strong></p><p>EL(LA) CONTRATADO(A), está obligado a desempeñar todas las labores propias de la docencia, las cuales se señalan a continuación con sentido meramente indicativo y no limitado:&nbsp;</p><ul><li>Integrar la comunidad magisterial.</li><li>Cumplir con el contrato de trabajo establecido en mutuo acuerdo con la Junta Directiva, el Director y el Plantel Administrativo; dentro del marco de la Ley de Centros Educativos Privados, en sus diferentes obligaciones y tareas como personal de la Institución.</li><li>Cumplir su función de Maestro frente a los Estudiantes, Coordinación Académica, Dirección y Junta Directiva del Colegio.</li><li>Cumplir con el horario de trabajo establecido por la Institución.</li><li>Asistir y participar con regularidad en las actividades espirituales del Colegio y de la Iglesia.</li><li>Mantenerse en los principios y normas de la iglesia Adventista en el desempeño de sus funciones.</li><li>Trabajar en armonía con las resoluciones de la Junta Directiva, la Dirección, la Administración, Coordinación Académica y de las comisiones establecidas para el buen desempeño de la enseñanza.</li><li>Renovarse constantemente para ofrecer técnicas y conocimientos actualizados.</li><li>Evaluar y reevaluar a los Estudiantes e informarlos oportunamente las notas de evaluación.</li><li>Realizar una reflexión y oración antes de iniciar las clases, sobre todo las primeras horas de cada día.</li><li>Participar en los cultos de docentes en forma puntual, sobre todo cuando tenga las primeras horas de clase.</li><li>Dar a conocer a través de un documento sobre las inasistencias en caso de emergencia y contratiempo.</li><li>Comunicar con anticipación a la Dirección, Coordinación Académica sobre las actividades de paseos excursiones y planes de estudio para su tratamiento oportuno.</li><li>Mantener la disciplina en todas las dependencias educativas del Colegio.</li><li>Cooperar con el plan de visitación a los hogares de los Estudiantes.</li><li>En caso de enfermedad, presentar el certificado médico oportunamente.</li><li>Mantienen un alto nivel de ética cristiana profesional para inspirar altos ideales en los Estudiantes.</li><li>Velar por el mantenimiento adecuado del aula, instalaciones y equipamiento del Centro Educativo y promover su mejora.</li><li>Se abstiene de realizar en el Colegio actividades que contravengan los objetivos fines y reglamentos de la institución.</li><li>Programan, desarrollan y evalúan las actividades curriculares.</li><li>Realizan acciones de recuperación pedagógica, en coordinación con la Dirección y Coordinación Académica.</li><li>Coordinan y mantienen la comunicación permanente con los Padres de Familia sobre asuntos relacionados con el rendimiento académico y el comportamiento de los Estudiantes en las jornadas informativas (entrevista de padres).</li><li>Promueven el desarrollo armonioso del Estudiante, buscando los mejores métodos.</li><li>Se muestran aptos para inculcar en los Estudiantes principios de verdad, integridad, pureza, honradez y obediencia.</li><li>Trabajan por precepto y ejemplo.</li><li>Trabajan con especial atención con los Estudiantes difíciles y/o deficientes, sin impacientarse con los errores de los mismos.</li><li>No ser irritables, impacientes, arbitrarios o autoritarios y se esfuerzan por tener buena relación con los colegas y autoridades.</li><li>Participan activamente en los trabajos de mejoramiento de la Unidad Educativa.</li><li>Controlan la asistencia y puntualidad de los Estudiantes.</li><li>Llevan registros de asistencia, conducta, evaluación de los Estudiantes a su cargo.</li><li>Registran su ingreso y salida diariamente en el biométrico.</li><li>Portan y emplean su PAB y PDC diario de clases y otros documentos técnico-pedagógicos, en forma permanente.</li><li>En su trabajo se visten adecuadamente como docentes cristianos: Profesores con terno y corbata, y Profesoras con falda, respetando las normas cristianas de la Iglesia Adventista del Séptimo Día.</li><li>Observan las normas y principios de la Iglesia Adventista por ejemplo y por precepto</li><li>Cualquier otro inherente a su cargo, o que le sea solicitado por el Director.</li><li>Toda orden o instructivo (verbal o escrito) debe ser acatado en el momento (sin excusas).</li></ul><p><strong>TERCERO</strong></p><p>EL(LA) CONTRATADO(A) cumplirá con la jornada de trabajo de horas académicas semanales de acuerdo al horario que establezca la Dirección de la UNIDAD EDUCATIVA PRIVADA “MARANATA”. El horario de trabajo podrá variar a las necesidades de la programación académica a las actividades programadas, (concejo de profesores)</p><p>Por tratarse de labores escolares EL(LA) CONTRATADO(A) debe programar para cumplir bajo su responsabilidad dentro del horario de trabajo, no tendrá derecho al cobro de horas extraordinarias.</p><p><strong>CUARTO</strong></p><p>Las licencias por enfermedad y por otras razones justificadas serán concedidas por el Director del establecimiento, de acuerdo a las disposiciones legales vigentes en la Legislación del Trabajo, debiendo presentar y/o dejar a un docente como suplente para no perjudicar al estudiante.</p><p><strong>';

$documento.='QUINTO</strong></p><p>Se deja establecida que la Institución pagara al empleado(a) por horas trabajados mensualmente deduciendo descuentos de Ley y otros como; faltas, abandonos injustificados de clases y a diferentes actividades que realice la Institución.</p><p>El salario Mensual de Trabajo Convenido entre partes de mutuo acuerdo es Bs.&nbsp;_______________[sueldo_total] _____________________[monto_literal] 00/100 Bolivianos)</p><p><strong>SEXTO</strong></p><p>Al finalizar la gestión y una vez entregado a la Dirección los boletines de evaluación anual y libretas de calificaciones de los estudiantes se cancelará al CONTRATADO(A) el Aguinaldo y la Indemnización de los diez meses Trabajados de acuerdo a las duodécimas.&nbsp;</p><p><strong>SEPTIMO</strong></p><p>EL(LA) CONTRATADO(A) (A), se somete al control disciplinario y de asistencia que establezca la UNIDAD EDUCATIVA PRIVADA “MARANATA”, a través de su Dirección.</p><p><strong>OCTAVO</strong></p><p>En caso de inasistencia por más de tres días continuos injustificados, abandono injustificado de funciones y por consiguiente quedará sin efecto del presente CONTRATO.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p><strong>NOVENO</strong></p><p>Los que suscriben el presente CONTRATO, da así su conformidad a cada una de las cláusulas estipuladas por lo que se firma en triple ejemplar al pie del presente contrato, en mención en el Departamento de La Paz Bolivia </p>';

$documento.=' <p><br ></p><p><br ></p><p><br ></p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; _________________________&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; _________________________</p><p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;CONTRATANTE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; &nbsp;CONTRATADO(A)</p><p>&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; _____________________[nombre]&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;PROF(A).&nbsp;_____________________[primer_apellido] _____________________[segundo_apellido] _____________________[nombres]&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p><p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;_____________________[cargo]&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; MAESTRO(A)&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p><p><br ></p><p><br ></p>';
           




			// Instancia el cliente
			$contrato = array(
				'area_contrato' => $area_contrato,
				'tipo_contrato' => $tipo_contrato,
				'modalidad_contrato' => $modalidad_contrato,
				'tipo_documento' => $tipo_documento,
				'gestion_id' => $gestion,
				'fecha_inicio' => $fecha_inicio,
				'fecha_final' => $fecha_final,
				'nivel_academico' => implode(',', $nivel_academico),
				'documento' => $documento,
				'archivo_documento' => $archivo_documento
			);
			
			// Verifica si es creacion o modificacion
			if ($id_contrato > 0) {
				// Modifica el cliente
				$db->where('id_contrato', $id_contrato)->update('rrhh_contrato', $contrato);


				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'u',
					'nivel' => 'l',
					'detalle' => 'Se modificó el cliente con identificador número ' . $id_contrato . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Modificación exitosa!', 'El registro se modificó satisfactoriamente.');
				echo 1;
				// Redirecciona la pagina
				// redirect('?/cliente/ver/' . $id_contrato);
			} else {
				// Crea el cliente
				$id_contrato = $db->insert('rrhh_contrato', $contrato);
				
				// Guarda el proceso
				$db->insert('sys_procesos', array(
					'fecha_proceso' => date('Y-m-d'),
					'hora_proceso' => date('H:i:s'),
					'proceso' => 'c',
					'nivel' => 'l',
					'detalle' => 'Se creó el cliente con identificador número ' . $id_contrato . '.',
					'direccion' => $_location,
					'usuario_id' => $_user['id_user']
				));
				
				// Crea la notificacion
				//set_notification('success', 'Creación exitosa!', 'El registro se creó satisfactoriamente.');
				echo 2;
				// Redirecciona la pagina
				// redirect('?/cliente/listar');
			}
		} else {
			// Error 400
			require_once bad_request();
			exit;
		}
	//	echo 3;
	// } else {
	// 	// Redirecciona la pagina
	// 	redirect('?/cliente/listar');
	// }
} else {
	// Error 404
	require_once not_found();
	exit;
}







function verificarToken($token, $claveSecreta)
{
    # La API en donde verificamos el token
    $url = "https://www.google.com/recaptcha/api/siteverify";
    # Los datos que enviamos a Google
    $datos = [
        "secret" => $claveSecreta,
        "response" => $token,
    ];
    // Crear opciones de la petición HTTP
    $opciones = array(
        "http" => array(
            "header" => "Content-type: application/x-www-form-urlencoded\r\n",
            "method" => "POST",
            "content" => http_build_query($datos), # Agregar el contenido definido antes
        ),
    );
    # Preparar petición
    $contexto = stream_context_create($opciones);
    # Hacerla
    $resultado = file_get_contents($url, false, $contexto);
    # Si hay problemas con la petición (por ejemplo, que no hay internet o algo así)
    # entonces se regresa false. Este NO es un problema con el captcha, sino con la conexión
    # al servidor de Google
    if ($resultado === false) {
        # Error haciendo petición
        return false;
    }

    # En caso de que no haya regresado false, decodificamos con JSON
    # https://parzibyte.me/blog/2018/12/26/codificar-decodificar-json-php/

    $resultado = json_decode($resultado);
    # La variable que nos interesa para saber si el usuario pasó o no la prueba
    # está en success
    $pruebaPasada = $resultado->success;
    # Regresamos ese valor, y listo (sí, ya sé que se podría regresar $resultado->success)
    return $pruebaPasada;
}

?>