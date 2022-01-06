<?php

//var_dump('sdfgdg');exit(); 



// Obtiene el id_egreso

$id_estudiante = (isset($_params[0])) ? $_params[0] : 0; 

//var_dump($id_estudiante);exit(); 

//$id_inscripcion=3;



if ($id_estudiante > 0) { 



	// Obtiene los detalles

	$estudiante = $db->query("SELECT*

	FROM ins_inscripcion i

	INNER JOIN ins_estudiante e ON i.estudiante_id = e.id_estudiante

	INNER JOIN sys_persona per ON e.persona_id = per.id_persona

	inner join ins_aula_paralelo ap on i.aula_paralelo_id=ap.id_aula_paralelo

	inner join ins_aula a on ap.aula_id=a.id_aula

	inner join ins_paralelo p on ap.paralelo_id=p.id_paralelo

	inner join ins_nivel_academico na on i.nivel_academico_id=na.id_nivel_academico

	WHERE i.estudiante_id = $id_estudiante ")->fetch_first();

    //var_dump($estudiante);exit(); 



	// Obtiene los detalles

	$tutor = $db->query("SELECT*

	FROM ins_inscripcion i

	INNER JOIN ins_estudiante_familiar ef ON i.estudiante_id = ef.estudiante_id

	INNER JOIN ins_familiar f ON ef.familiar_id = f.id_familiar

	INNER JOIN sys_persona per ON f.persona_id = per.id_persona

	WHERE i.estudiante_id = $id_estudiante AND ef.tutor=1 ")->fetch_first();

	//var_dump($tutor);exit();

}



//var_dump($estudiante);exit();

// Obtiene la moneda oficial

$moneda = $db->from('inv_monedas')->where('oficial', 'S')->fetch_first(); 

$moneda = ($moneda) ? '(' . $moneda['sigla'] . ')' : '';



// Importa la libreria para el generado del pdf

require_once libraries . '/tcpdf/tcpdf.php';

require_once libraries . '/tcpdf/tcpdf_barcodes_2d.php';

//require_once libraries . '/numbertoletter-class/NumberToLetterConverter.php';



// Define variables globales

// define('direccion', escape($_institution['pie_pagina']));

//define('imagen', escape($_institution['imagen_encabezado']));

define('atencion', 'Lun. a Vie. de 08:30 a 18:30 y Sáb. de 08:30 a 13:00');

// define('pie', escape($_institution['pie_pagina']));

define('telefono', escape(str_replace(',', ', ', $_institution['telefono'])));

//define('telefono', date(escape($_institution['formato'])) . ' ' . date('H:i:s'));



// Extiende la clase TCPDF para crear Header y Footer

class MYPDF extends TCPDF {

}



// Instancia el documento PDF

$pdf = new MYPDF('P', 'pt', 'LETTER', true, 'UTF-8', false);



// Asigna la informacion al documento

$pdf->SetCreator(name_autor);

$pdf->SetAuthor(name_autor);

$pdf->SetTitle($_institution['nombre']);

$pdf->SetSubject($_institution['propietario']);

$pdf->SetKeywords($_institution['sigla']);



// Asignamos margenes

$pdf->SetMargins(30, 30, 30);



// Elimina las cabeceras

$pdf->setPrintHeader(false);

$pdf->setPrintFooter(false);



// ------------------------------------------------------------



if ($id_estudiante == 0) {

} else {

	// Documento individual --------------------------------------------------

	

	// Asigna la orientacion de la pagina factura

	$pdf->SetPageOrientation('P');

	

	// Adiciona la pagina

	$pdf->AddPage();

	

	// Establece la fuente del titulo

	$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 16);

	

	// Titulo del documento

	//$pdf->Cell(0, 10, 'FACTURA', 0, true, 'C', false, '', 0, false, 'T', 'M');

	

	// Salto de linea

	//$pdf->Ln(5);

	

	// Establece la fuente del contenido

	$pdf->SetFont(PDF_FONT_NAME_DATA, '', 9);



	// Establece la fuente del contenido

	$pdf->SetFont(PDF_FONT_NAME_DATA, '', 8);



	// Estructura la tabla

	$body = '';



	// Define la fecha de hoy

	$hoy = date('Y-m-d');
	
	$aHoy = explode("-",$hoy);
	//var_dump($aHoy);exit;
	//Arreglo con los nombres de los meses
    $meses = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
   
	$dia = $aHoy[2];
	$mes = $meses[($aHoy[1]*1)-1];
	$anio = $aHoy[0];

	//echo $dia;exit;

	// Obtiene la imagen QR en modo cadena

	//$imagen = $objeto->getBarcodePngData(4, 4, array(30, 30, 30));



	// Crea la imagen a partir de la cadena

	// $imagen = imagecreatefromstring($imagen);

	$documento=$tutor['numero_documento'];

	$nombre_tutor = $tutor['primer_apellido'] .' '. $tutor['segundo_apellido'].' '. $tutor['nombres']; 

	$nombre_estudiante = $estudiante['primer_apellido'].' '.$estudiante['segundo_apellido'].' '.$estudiante['nombres'];

	$curso = $estudiante['nombre_aula'].' '.$estudiante['nombre_paralelo'].' '.$estudiante['nombre_nivel'];


	// Image example with resizing
	// $pdf->SetXY(10, 10);
	// $pdf->Image('files/logos/bannermaranata.png', '', '', 500, 100, '', '', 'T', false, 200, '', false, false, 1, false, false, false);

	// Formateamos la tabla

	$tabla = <<<EOD

	<style>

	th {

		background-color: #eee;

		font-weight: bold;

	}

	.left-right {

		border-left: 1px solid #444;

		border-right: 1px solid #444;

	}

	.none {

		border: 1px solid #fff;

		height: 15px;

	}

	.all {

		border: 1px solid #444;

	}

	td p{

		font-size:50px;

		align:justify;

	}

	</style>

	<img src="files/logos/bannermaranata.png" alt="" height="52" width="542">

	<h3 align="center">REGLAMENTO INTERNO<br><br></h3>

	<table cellpadding="1">

		<tr>

			<td>Apreciado/a Madre/Padre de familia (tutor y apoderado) y sr. estudiante:<br></td>

        </tr>

        <tr>

			<td>La misión del Reglamento Interno del Colegio Particular Cristiano “Maranata”, consiste en formar al estudiante con disciplina: sus talentos, carácter, sentido de responsabilidad, compromiso y servicio; a través de la práctica de principios, normas, valores y creencias fundamentadas en la visión educativa cristiana que orienta las Sagradas Escrituras. Propiciando un desarrollo integral de las potencialidades mentales, espirituales, físicas y sociales del estudiante.<br></td>

        </tr>

        <tr>

			<td>Por lo tanto: Al elegir esta Unidad Educativa, se debe considerar muy cuidadosamente y regirse a los siguientes aspectos que hará provechoso su esfuerzo y grata su permanencia.<br></td>

        </tr>

		<tr>

			<td><b>I.	DERECHOS DE LOS ESTUDIANTES.-  </b>Sin perjuicio de lo establecido en otras normas, los y las estudiantes gozan de los siguientes derechos:<br><br>

			&nbsp;&nbsp;&nbsp;&nbsp;1.	Recibir formación integral para su desarrollo intelectual, físico socio-emocional y espiritual, en un ambiente adecuado y que le brinde seguridad física y psicológica.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;2.	Ser tratado con dignidad, respeto y sin discriminación ya sea por estudiantes, profesores u otros.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;3.	Recibir asignación de calificaciones justas y acordes a su rendimiento académico.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;4.	Ser evaluado para orientar su proceso de aprendizaje.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;5.	Utilizar los ambientes y servicios que le ofrece la U. E. en los horarios establecidos con previa autorización en ocasiones especiales.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;6.	Ser elegido como representante de su curso o para participar en comisiones internas. <br><br>


			<b>II.	DEBERES.- </b>Los estudiantes tienen los siguientes deberes:<br><br>

			<b>A)	Disciplina:</b><br>
			&nbsp;&nbsp;&nbsp;&nbsp;7.	Respetar y cumplir estrictamente las normas, reglamentos y principios de la Unidad Educativa.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;8.	Demostrar una conducta disciplinada en el medio social en que se desenvuelve para la conservación del prestigio de la Institución durante y después del horario académico.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;9.	Mantener un comportamiento adecuado que fortalezca un espíritu patriótico y de responsabilidad democrática, asistiendo a actos cívicos, desfiles y otros actos programados.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;10.	Ser muy respetuoso a la Institución, sus símbolos y sus autoridades; a los docentes, compañeros, visitantes  y a toda persona mayor y menor dentro y fuera del Establecimiento.<br>
			
			<b>B)	Horario y Asistencia:</b><br>
			&nbsp;&nbsp;&nbsp;&nbsp;11.	Asistir regularmente y de manera puntual a clases en los horarios establecidos por la Institución: Horario normal y Horario de invierno.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;12.	Solicitar permisos de inasistencia justificables a la Dirección de manera oportuna y escrita a través de la agenda escolar y/u otro medio idóneo con la firma del padre de familia o apoderado y que sean de manera personal.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;13.	Permanecer en el aula hasta la conclusión del periodo de clases.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;14.	Tolerancia de 5 minutos después del timbre de ingreso a clases.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;15.	Realizar la Rotación de aula en 3 minutos como máximo.<br>			

			<b>C)	Uniforme y presentación personal:</b><br>

			&nbsp;&nbsp;&nbsp;&nbsp;16.	Usar cotidianamente el uniforme oficial y reglamentario del Establecimiento para la asistencia a clases, actividades oficiales y actividades cívicas, el mismo es: 
				Para señoritas; Saco Negro (Lunes), chompa negra, falda ploma por debajo de la rodilla, pantis negras, blusa plomo claro, corbata azul marino con logotipo oficial, calzados negros, peinado moño con red blanca y en caso de tener el cabello muy corto usar wincha blanca.
				Para varones; Saco Negro (Lunes), chompa negra, pantalón plomo claro, camisa plomo claro, calzados negros, medias negras, corbata azul marino con logotipo oficial, corte estudiantil (primaria) y corte cadete (secundaria).
				En caso de no tener el corte y peinado correspondiente, a la segunda llamada de atención se convocará al/la padre/madre para proceder a la corrección del mismo.
				El uniforme de educación física; Deportivo del colegio e implementos reglamentarios según horario de clases y/o actividades.<br>
				17.	No debe existir ningún tipo de tinte en los cabellos. Además, abstenerse de peinados y cortes de cabellos extravagantes fuera de lo establecido, así como de cosméticos, tintes, pinturas y maquillajes innecesarios.<br>
				18.	Vestirse conforme los principios de decoro, sencillez y modestia, evitando toda vestimenta ajustada, extravagante o de tipo sensual. <br>
				19.	Mantener hábitos de higiene y limpieza para el cuidado personal, que denota estimación y respeto propio.<br>
			
			<b>D)	Responsabilidad:</b><br>

			&nbsp;&nbsp;&nbsp;&nbsp;20.	Portar diariamente sus agendas escolares haciendo un uso adecuado del mismo.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;21.	El estudiante deberá portar todos los materiales correspondientes que utilizará durante el día (no se entregarán materiales al estudiante en horarios de clase)<br>
			&nbsp;&nbsp;&nbsp;&nbsp;22.	Cumplir los compromisos económicos adquiridos con el Establecimiento.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;23.	Cooperar en la conservación y la limpieza de la Institución y su campus, evitando daños, destrozos, pintados, y rayado de ambientes, muebles, instalaciones eléctricas, equipos y ambientes sanitarios.<br> 
			&nbsp;&nbsp;&nbsp;&nbsp;24.	Respetar y cuidar las pertenencias ajenas sean de la Institución, docentes y compañeros. <br>
			&nbsp;&nbsp;&nbsp;&nbsp;25.	Exhibir con honradez pertenencias en caso de pérdidas de bienes ajenos a su propiedad, entregando al preceptor cuando no haya dueño/a.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;26.	Pagar daños materiales al Establecimiento causados por sí o por terceros bajo su responsabilidad, y objetos de propiedad de sus compañeros, docentes y funcionarios de la Institución.<br><br>


			<b>III.	FALTAS Y SANCIONES. - </b>Se consideran faltas disciplinarias las siguientes:<br><br>

			<b>A) FALTAS LEVES. -</b> Las faltas leves ameritarán la correspondiente amonestación verbal del Docente o Preceptor con registro en el Kárdex y compromiso del estudiante. Estas faltas son:<br>

			&nbsp;&nbsp;&nbsp;&nbsp;27.	Usar ropas transparentes, escotes pronunciados, minifaldas y pantalones ceñido al cuerpo en actividades curriculares y co-curriculares del Establecimiento.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;28.	Demostrar manifestaciones físicas exageradas (abrazos, besos, tomadas de la mano y otros) al sexo opuesto dentro del Establecimiento y sus inmediaciones.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;29.	Utilizar libros, novelas, revistas y otras publicaciones que atentan contra los valores y la moral conforme a su edad.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;30.	Portar celulares, mp3, mp4, iPod, Tablet, auriculares, equipos audiovisuales, equipos electrónicos y otros de igual naturaleza en predios de la Institución, siendo el/la único/a responsable en caso de extravío o deterioro de los mismos. Los objetos decomisados serán devueltos a final de mes, y en caso de reincidencia se les devolverá a sus padres o tutores al finalizar la gestión.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;31.	Ingresar a la Institución (Aulas, patios, y oficinas) con perforaciones corporales (Piercing), aretes, manillas y tatuajes expuestos.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;32.	Tardar más de 3 minutos como máximo en los cambios de periodo (rotación de aula).<br>
			&nbsp;&nbsp;&nbsp;&nbsp;33.	Comprar alimentos o materiales sin el permiso correspondiente en horarios de clase y en cambios de periodo<br>
			&nbsp;&nbsp;&nbsp;&nbsp;34.	Asignar o llamar por sobrenombre o apodos a docentes, autoridades, personal de apoyo y compañeros.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;35.	Retrasarse o no asistir a las actividades escolares o co-curriculares sin justificación escrita por el padre o apoderado.<br>
			

			<b>B) FALTAS MODERADAS. -</b>  Las faltas moderadas ameritaran la correspondiente amonestación escrita con presencia de los padres de familia y compromiso escrito del padre de familia y estudiante. Son faltas moderadas las siguientes:<br>

			&nbsp;&nbsp;&nbsp;&nbsp;36.	La reincidencia de DOS FALTAS LEVES o más, constituyen una falta moderada.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;37.	Realizar juegos de azar, apuestas y otros que vayan contra los valores y principios cristianos.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;38.	Organizar y participar de encuentros deportivos, viajes de estudio y actividades recreativas sin autorización de la Dirección de la Institución y padres de familia.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;39.	Comerciar dentro del Establecimiento cualquier tipo de producto u objetos.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;40.	Plagiar o apropiarse de trabajos prácticos, exámenes de compañeros, sellos y firmas de docentes y de la UE.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;41.	Escribir palabras, diseños o señales de cualquier naturaleza en las paredes, pisos, muebles, material escolar o en cualquier parte del edificio escolar o en materiales de los compañeros, responsabilizándose de los daños causados.<br>


			<b>C) FALTAS GRAVES. -</b>  El o los estudiantes que comentan faltas graves recibirán la correspondiente aplicación de trabajo social, suspensión y otra medida resuelta por el consejo de docente registrado en actas y compromiso escrito del padre de familia y estudiante. Las faltas muy graves son:<br>

			&nbsp;&nbsp;&nbsp;&nbsp;42.	La reincidencia de DOS O MÁS FALTAS MODERADAS constituye una falta grave.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;43.	Manifestar actos que afecten a la moral y buenas costumbres fuera del Establecimiento portando el uniforme escolar.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;44.	Portar armas de fuego, cortantes o punzocortantes.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;45.	Pertenecer o involucrar a otros compañeros en pandillas, grupos delictivos o grupos que ingieran bebidas alcohólicas y sustancias controladas.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;46.	Provocar riñas, peleas conflictos entre compañeros y docentes dentro y fuera de la Institución.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;47.	Robar o hurtar los materiales escolares y otras pertenencias de estudiantes y de la Institución.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;48.	Portar películas, revistas o cualquier medio de material pornográfico.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;49.	Asistir al Establecimiento en estado de embriaguez o bajo efecto del cigarrillo o sustancias controladas nocivas a la salud o portar cada una de ellas.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;50.	Realizar y participar de fiestas, festejos o celebraciones entre estudiantes que vayan en desmedro de los principios y valores.<br>

			<b>D) FALTAS MUY GRAVES. - </b>El o los estudiantes que comentan faltas muy graves serán pasibles a expulsión definitiva de la Institución.<br>
			<b></b><br> &nbsp;&nbsp;&nbsp;&nbsp;51.	La reincidencia en UNA FALTA GRAVE CONSTITUYE FALTA MUY GRAVE, asimismo de conformidad al art. 21, de la resolución ministerial 162/2001 de fecha 04 de abril de 2001, solo en casos comprobados de robo, hurto, agresión física, sexual, oferta venta y/o consumo de bebidas alcohólicas u otras sustancias controladas y portación de armas, el estudiante será expulsado definitivamente de la unidad educativa dando parte al ministerio público.<br>
			<b></b><br> &nbsp;&nbsp;&nbsp;&nbsp;52.	La expulsión será determinada por el Director de la Unidad Educativa, el Consejo de Docentes, Comisión Disciplinaria e informada por escrito a la Dirección Distrital de Educación y asimismo remitidos a Defensoría de la Niñez y Adolescencia y la Policía. <br>
			
			<br>	
			<b>IV.  DE LOS PADRES DE FAMILIA</b><br>
			<b>A)   DERECHOS</b><br>
			&nbsp;&nbsp;&nbsp;&nbsp;53. Ser atendidos de manera personalizada con respeto y consideración por la Administración, Personal Docente y de Servicio del colegio.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;54. Conocer los principios, propósitos y objetivos del Proyecto Educativo de la Unidad Educativa.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;55. Conocer los reglamentos referidos a Padres de Familia y/o Apoderados y el reglamento Estudiantil y coadyuvar en el cumplimiento de las mismas.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;56. Participar y coadyuvar en las actividades de la Unidad Educativa, respetando la organización de la Institución y a los delegados de padres de familia por curso en primaria.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;57. A verificar con el profesor el aprovechamiento de su hijo(a) en entrevistas, realizando observaciones respecto del proceso educativo de su hijo(a) si los hay, conversando previamente con el profesor(a) antes de acudir a la Dirección.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;58. Solicitar los documentos e instrumentos de evaluación de su hijo(a) y en caso de irregularidad, apelar a los Directivos de la Unidad Educativa.<br>


			<b>B)   DEBERES</b><br>
			&nbsp;&nbsp;&nbsp;&nbsp;59. Conocer, participar y colaborar en las actividades curriculares, extracurriculares e implementación de los Proyectos de la Unidad Educativa.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;60. Educar a sus hijos (as) en el hogar, de acuerdo a los principio morales, éticos y propósitos de la Unidad educativa.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;61. Alimentar adecuadamente a los hijos en edad escolar en favor de la buena educación y adquisición de hábitos saludables.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;62. Velar responsablemente por la presentación personal, estudios, asistencia, puntualidad y comportamiento de sus hijos(as).<br>
			&nbsp;&nbsp;&nbsp;&nbsp;63. Proporcionar los útiles escolares y materiales requeridos para el estudiante.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;64. No perjudicar ni interrumpir el desarrollo pedagógico de los docentes en horario de clases.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;65. Justificar la inasistencia a convocatorias con fines de apoyo educativo de manera personal o con nota escrita.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;66. Revisar periódicamente las tareas trabajos y materiales de sus hijos(as) acompañando y apoyando el proceso de enseñanza-aprendizaje de su hijo(a) con esmero y responsabilidad.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;67. Respetar y guardar la consideración debida a los Directores, Personal Docente, Administrativo y de Servicio de la Unidad Educativa.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;68. Mantener contacto con los profesores mediante el uso de la Agenda y kardex estudiantil.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;69. Revisar diariamente la Agenda y solicitar la revisión de la misma al asesor de curso y firmar las citaciones, informes y comunicaciones.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;70. Asistir puntualmente a las reuniones convocadas por el Director y Profesores de la Unidad Educativa e informarse sobre la conducta y el aprovechamiento en las entrevistas.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;71. Autorizar por escrito y con su firma, la participación de su hijo(a) en las actividades a ser cumplidas fuera de la Unidad Educativa.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;72. Responsabilizarse económicamente de todo daño o deterioro que su hijo (a) cause al mobiliario, instalaciones o dependencias y propiedad ajena.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;73. Dar cumplimiento al horario de ingreso establecido para todos los niveles y turnos y las variantes en horario de invierno, solicitando permisos por teléfono sólo en casos de emergencia.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;74. Acreditar y Garantizar la asistencia regular y puntual de su hijo(a) a clases en los horarios establecidos, las actividades curriculares y extracurriculares (10 min. antes), dotándole de material requerido en las diversas asignaturas y el uniforme establecido.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;75. No enviar encargos, mensajes, material escolar, comestibles,  etc., a los estudiantes durante el desarrollo de las clases.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;76. Cualquier reclamo o sugerencia debe ser presentado en forma verbal o escrita respetando el conducto regular vigente en la Unidad Educativa.<br>

			<b>C)   FALTAS ESPECÍFICAS</b><br>
			&nbsp;&nbsp;&nbsp;&nbsp;77. Ingresar a las aulas sin el permiso respectivo.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;78. Intervenir en la Dirección, organización y administración de los asuntos técnico – pedagógicos y administrativos por ser éstos propios de la Dirección.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;79. No dar cumplimiento a los compromisos adquiridos en el momento de la inscripción.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;80. Realizar comentarios que atenten contra la honra y moral de cualquier miembro de la Unidad Educativa.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;81. Inasistencia a las reuniones y entrevistas convocadas por las autoridades del Colegio.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;82. Malversación de fondos del curso al que pertenece su hijo(a) u otra actividad relacionada con el Establecimiento.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;83. Ofrecer retribuciones económicas a los profesores para lograr mejora en las calificaciones de su hijo(a).<br>
			&nbsp;&nbsp;&nbsp;&nbsp;84. Agresiones verbales fundadas en motivos racistas y/o discriminatorios.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;85. Denuncias injustificadas a Administrativos, Personal docente y de Servicio.<br>
			&nbsp;&nbsp;&nbsp;&nbsp;86. Maltrato físico, psicológico y sexual, por motivos racistas y discriminatorios, acciones denigrantes que no constituyan delito.<br>
	
			</td>
		</tr>

		<tr><h4 align="center"><br><br>COMPROMISO DEL ESTUDIANTE<br><br></h4></tr>
		

		<tr>
			Yo. <b>$nombre_estudiante</b>, del	

			curso <b> $curso </b>, ME COMPROMETO a cumplir con las normas y el reglamento Interno, observar fielmente los puntos descritos en este documento. Asimismo, en caso de no cumplir o de reiterar las faltas, acepto las sanciones que la Institución estime. conveniente o en caso de reincidir a estas, voluntariamente preceder al retiro de la Institución.<br>
			<br>
			En total constancia, firmo este compromiso, con la autorización de mi tutor y/o padre de familia.<br><br>	
		</tr>

		

		<tr><h4 align="center"><br><br>COMPROMISO DEL PADRE DE FAMILIA</h4></tr>

		Yo,<b>$nombre_tutor</b>, con C.I <b>$documento</b>, en condición de padre de familia, estoy de acuerdo con las normas y el reglamento del establecimiento, certifico la validez del compromiso de mi hijo y asumo la responsabilidad de su cumplimiento y el mío. <br>

		<tr>

			<td class="none"></td>

			<td class="none">El Alto, $dia de $mes de $anio </td>

		</tr>

	</table>	

	<table cellpadding="1">

			<tr>
			<td width="100%" align="right">El Alto, $dia de $mes de $anio <br><br><br><br><br><br> </td>
			</tr>

					<tr>
					<td width="50%" align="center">……………………………………………………………</td>
					<td width="50%" align="center">……………………………………………………………</td>
					</tr>

					<tr>
					<td width="50%" align="center">ESTUDIANTE</td>
					<td width="50%" align="center">PADRE DE FAMILIA o TUTOR</td>
					</tr>			

	</table> 

EOD;

	

	// Imprime la tabla

	$pdf->writeHTML($tabla, true, false, false, false, '');

	// Imprime la tabla

	//$pdf->writeHTML($body, true, false, false, false, '');

	

	// Genera el nombre del archivo

	// $nombre = 'factura_' . $id_pago_general . '_' . date('Y-m-d_H-i-s') . '.pdf';

}



// ------------------------------------------------------------



// Cierra y devuelve el fichero pdf

ob_end_clean();

$pdf->Output('Reglamento', 'I');
