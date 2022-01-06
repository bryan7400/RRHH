<?php
// Obtiene el id_egreso
$id_estudiante = (isset($_params[0])) ? $_params[0] : 0; 
//var_dump($id_estudiante);exit(); 
//$id_inscripcion=3;

if ($id_estudiante > 0) { 
    $mensualidad = $db->query("SELECT IFNULL(SUM(ppd.monto),0) mensualidad, IFNULL(ppd.monto,0) total_mensualidad
	FROM ins_inscripcion i
	INNER JOIN pen_pensiones_estudiante ppe ON i.id_inscripcion = ppe.inscripcion_id
	INNER JOIN pen_pensiones_detalle ppd ON ppe.detalle_pension_id = ppd.id_pensiones_detalle
	INNER JOIN pen_pensiones pp ON ppd.pensiones_id = pp.id_pensiones
	WHERE pp.nombre_pension ='MENSUALIDAD'
	AND i.estudiante_id =$id_estudiante")->fetch_first();

	if($mensualidad>0.00){

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

	// Obtiene los detalle
	$tutor = $db->query("SELECT*
	FROM ins_inscripcion i
	INNER JOIN ins_estudiante_familiar ef ON i.estudiante_id = ef.estudiante_id
	INNER JOIN ins_familiar f ON ef.familiar_id = f.id_familiar
	INNER JOIN sys_persona per ON f.persona_id = per.id_persona
	WHERE i.estudiante_id = $id_estudiante AND ef.tutor='1'")->fetch_first();
	//var_dump($tutor);exit();
       
	}else{
		require_once not_found();
	    exit;
	}
}else{
require_once not_found();
	   exit;	
}

//var_dump($estudiante);exit();
// Obtiene la moneda oficial
$moneda = $db->from('inv_monedas')->where('oficial', 'S')->fetch_first(); 
$moneda = ($moneda) ? '(' . $moneda['sigla'] . ')' : '';

// Include the main TCPDF library (search for installation path).
//require_once('tcpdf_include.php');
require_once libraries . '/TCPDF-master/tcpdf.php';
require_once libraries . '/numbertoletter-class/NumberToLetterConverter.php';
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
//$pdf->SetAuthor('Checkcode');
$pdf->SetTitle('CONTRATO DE PRESTACIÓN DE SERVICIOS EDUCATIVOS');
$pdf->SetSubject('TCPDF');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH);
$pdf->Ln(1);
// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin();

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
	// Define la fecha de hoy
	$hoy = date('Y-m-d');

	// Obtiene la imagen QR en modo cadena
	//$imagen = $objeto->getBarcodePngData(4, 4, array(30, 30, 30));

	// Crea la imagen a partir de la cadena
	// $imagen = imagecreatefromstring($imagen);
	$documento=$tutor['numero_documento'];
	$nombre_estudiante = $estudiante['primer_apellido'].' '.$estudiante['segundo_apellido'].' '.$estudiante['nombres'];
	$curso = $estudiante['nombre_aula'].' '.$estudiante['nombre_paralelo'].' '.$estudiante['nombre_nivel'];
	$total_mensualidad=$mensualidad['total_mensualidad'];
	$dia=date('d');
	$mes=date('m');
	$anio=date('Y');
	
	$nombre_tutor = $tutor['primer_apellido'].' '.$tutor['segundo_apellido'].' '.$tutor['nombres'];
	$ci=$tutor['numero_documento'];

	// Obtiene los datos del monto total
	$conversor = new NumberToLetterConverter();
	$monto_textual = explode('.', $total_mensualidad);
	//$monto_textual = explode('.', $valor_total_con_descuento);
	$monto_numeral = $monto_textual[0];
	$monto_decimal = $monto_textual[1];
	$monto_literal = ucfirst(strtolower(trim($conversor->to_word($monto_numeral))));

	$monto_escrito = $monto_literal . ' ' . $monto_decimal . '/100';
// add a page im
$pdf->AddPage();

// set font
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Ln(6);
$pdf->Write(0, 'CONTRATO DE PRESTACIÓN DE SERVICIOS EDUCATIVOS', '', 0, 'C', true, 0, false, false, 0);

// create some HTML content
$html = '<span style="text-align:justify;line-height:1.5;" ><font size="8px">
Conste por el presente documento privado de “Prestación de Servicios Educativos”, que reconocidas las firmas y rubricas surtirá efectos de instrumento público, de acuerdo al tenor de las clausulas y condiciones siguientes:<br><br>
<b>PRIMERA. (ANTECEDENTES). - </b>El Ministerio de Educación emitió las Normas Generales del Subsistema de Educación Regular, y que la misma establece que la Unidad Educativa debe firmar el presente contrato con los padres y/o apoderados. Así mismo de los Arts. 454 y 519 del Código Civil establecen la libertad contractual y que lo establecido en este contrato tiene fuerza de Ley entre las partes Contratantes.<br><br>
<b>SEGUNDA. (PARTES). - </b>Son partes integrantes del presente documento:<br>
<b>a)</b>	La UNIDAD EDUCATIVA “MARANATA”, con domicilio legal en la Ciudad de El Alto, Av. Martin Alcaya N° 121, Urb. Señor de la Cruz (Puente Vela – Senkata), con Resolución Ministerial de funcionamiento N° 164/2006 con SIE N° 40730408, representada en este acto por el Sr. Lic. ARIEL BITARGO CACHI MAMANI, con C.I. N° 6730759LP en calidad de Tesorero, quien para efectos de este contrato se denomina la “UNIDAD EDUCATIVA”.<br>
<b>b)</b>	El/la Sr./a  <i><b>'.$nombre_tutor.'</b></i>  con C.I. N°  <i><b>'.$documento.'</b></i>, mayor de edad, capaz de obrar, con domicilio en la ciudad/localidad de EL ALTO, responsable legal de El/La estudiante: <i><b>'.$nombre_estudiante.'</b></i> que cursa el <i><b>'.$curso.'</b></i>, quien para efectos de este contrato será denominado el “RESPONSABLE”.<br><br>
<b>TERCERA. (OBJETO). - </b>El objeto del presente documento es establecer la relación contractual entre la “UNIDAD EDUCATIVA” y el “RESPONSABLE”, referente a la “PRESTACION DE SERVICIOS EDUCATIVOS” a favor del estudiante.<br><br>
<b>CUARTA. (OBLIGACIONES DE LAS PARTES). - </b>Los intervinientes tendrán las siguientes obligaciones:<br><br>
<b>OBLIGACIONES DE LA UNIDAD EDUCATIVA</b><br>
<b>a)</b>	Brindar Servicios Educativos al ESTUDIANTE en cumplimiento con las disposiciones establecidas por el Ministerio de Educación.<br>
<b>b)</b>	Cobrar diez (10) cuotas mensuales en moneda nacional por concepto de Prestación de Servicios Educativos.<br><br>
<b>OBLIGACIONES DEL RESPONSABLE</b><br>
<b>c)</b>	Cumplir con el pago adelantado de las cuotas de forma mensual según el calendario de la agenda escolar (hasta el cinco de cada mes impostergablemente).<br>
<b>d)</b>	Proveer materiales necesarios al estudiante para el desarrollo de sus actividades de enseñanza – aprendizaje y para el cumplimiento del reglamento como ser: uniformes, deportivos y otros que contengan la identidad institucional de la Unidad Educativa.<br><br>
<b>QUINTA. (MONTO Y FORMA DE PAGO). - </b>La UNIDAD EDUCATIVA no cobrará matrícula. En contraprestación por los servicios prestados, el “RESPONSABLE” pagará la suma de Bs. <i><b>'.$total_mensualidad.'</b></i>(<i><b>'.$monto_escrito.'</b></i> bolivianos), mismo cuyos plazos y cuotas se detallan en la cuarta parte del presente contrato, incisos (b) y (c).<br>
A la fecha vencida “EL RESPONSABLE”, si no hubiera cumplido la obligación de pagar, se constituirá en mora, siendo el presente contrato en Titulo Ejecutivo.<br><br>
<b>SEXTA. (ACLARACIÓN). - </b>En caso de incumplimiento en el pago puntual de las pensiones escolares la “UNIDAD EDUCATIVA” podrá asegurar su cumplimiento adoptando medios idóneos para asegurar este cobro, por cuanto “EL RESPONSABLE” acepta y acata dichos medios.<br><br>
<b>OCTAVA. (CONFORMIDAD). - </b>Las partes intervinientes declaran su conformidad a las cláusulas y condiciones establecidas en el presente Contrato, por lo que firman bajo consenso y conocimiento mutuo.<br><br><br><br><br><br>


<table border="0">
  <tr>
    <th style="text-align:center;">-----------------------------------------------------</th>
    <td></td>
    <th style="text-align:left;">Firma ---------------------------------------------------</th>
  </tr>
  <tr>
    <td style="text-align:center;">Lic. ARIEL BITARGO CACHI MAMANI</td>
    <td></td>
    <td>Responsable: '.$nombre_tutor.'</td>
  </tr>
  <tr>
    <td style="text-align:center;">TESORERO</td>
    <td></td>
    <td>C.I.: '.$documento.'</td>
  </tr>
  <tr>
    <td></td>
    <td></td>
    <td></td>
  </tr>
</table>
</font></span>';

// set core font
$pdf->SetFont('helvetica', '', 9);

// output the HTML content
//$pdf->writeHTML($html, true, 0, true, true);

$pdf->Ln();

// set UTF-8 Unicode font
//$pdf->SetFont('dejavusans', '', 10);

// output the HTML content
$pdf->writeHTML($html, true, 0, true, true);

// reset pointer to the last page
$pdf->lastPage();

// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('poliza_de_seguro.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
?>
