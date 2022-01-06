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

// Include the main TCPDF library (search for installation path).
//require_once('tcpdf_include.php');
require_once libraries . '/TCPDF-master/tcpdf.php';
// create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('POLIZA DE SEGURO');
$pdf->SetSubject('TCPDF Tutorial');
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

// add a page im
$pdf->AddPage();

// set font
$pdf->SetFont('helvetica', 'B', 9);
$pdf->Ln(6);
$pdf->Write(0, 'POLIZA DE SEGURO DE ACCIDENTES PERSONALES ESCOLARES CONDICIONES GENERALES', '', 0, 'C', true, 0, false, false, 0);

// create some HTML content
$html = '<span style="text-align:justify;line-height:1.5;" ><font size="8px">
<b>1. BASES DEL CONTRATO </b><br>
La Unidad Educativa “Maranata”, representada mediante el Tesorero Lic. Ariel Bitargo Cachi Mamani, expide la presente Póliza de acuerdo con las Condiciones Generales y Particulares estipuladas en este documento, basándose en la libre y espontánea voluntad de suscribir el presente documento entre ambas partes.<br>
<b>2. DEFINICIONES </b><br>
Para los efectos de la presente póliza, cuando se utilice cualquiera de las siguientes palabras en el texto, éstas tendrán el significado que aparece a continuación: 
<br>
<b>Accidente:</b> Evento imprevisto, involuntario, repentino y fortuito, causado por medios externos y de modo violento que afecte el organismo del ASEGURADO, ocasionándole una o más lesiones que se manifiestan por contusiones o heridas visibles, y también los casos de lesiones internas o inmersión reveladas por los exámenes médicos correspondientes, y que hayan ocurrido dentro de la vigencia de la Póliza y del ámbito de tiempo y espacio indicados en dicha póliza.
<br>
<b>Asegurados:</b> Son los estudiantes de la Unidad Educativa que se hayan legalmente inscritos como alumnos regulares para el período de cobertura de la Póliza y hayan sido reportados como tales por el contratante. 
Unidad Educativa: Es el centro docente, sea que se denomine colegio, escuela, o cualquier otro término que signifique lo mismo. 
<br>
<b>Póliza:</b> Es el documento emitido por La Unidad Educativa en el que consta el contrato del seguro. En él, se establecen los términos y condiciones de las coberturas contratadas. 
<br>
<b>Prima:</b> Es el valor económico determinado por La Unidad Educativa, como contraprestación por la cobertura de seguro contratada.
<br>
<b>Vandalismo:</b> Actos realizados por cualquier individuo o grupo de individuos, con el objeto de causar daños a la propiedad.
<br>
<b>Conmoción Civil:</b> Alteración del orden público.
<br>
<b>Daño Malicioso:</b> Actos realizados voluntariamente con el objeto de causar daños en beneficio propio o de terceros.
<br>
<b>Motín:</b> Movimiento tumultuoso de carácter popular contra la autoridad constituida o como protesta ante alguna de sus disposiciones. Alteración local del orden público que reviste poca gravedad y no mayor a 5 días. Entiéndase como movimiento tumultuoso, a la confusión agitada o ruidosa o alboroto producido por una multitud. Y alteración del orden público, como la perturbación de la paz, tranquilidad y seguridad pública.
<br>
<b>3. CANCELACIÓN DE LA POLIZA</b>
<br>
Este contrato de seguro deberá ser cancelado antes del inicio de las actividades escolares de la gestión 2020.
<br>
<b>4. INICIO DE VIGENCIA DEL SEGURO</b>
<br>
La cobertura otorgada por esta Póliza respecto de cada ASEGURADO, entrará en vigencia a partir del primer día de clases del “Calendario Escolar Anual” y su finalización de la póliza a la conclusión del mismo, esta instructiva de inicio y cierre de gestión es emitido por el órgano competente que es el Ministerio de Educación.
<br>
<b>5. ALCANCES DE LA COBERTURA</b> 
<br>
El seguro establecido en el presente, cubre a los estudiantes de la Unidad Educativa “Maranata”, en las siguientes condiciones particulares:
<br> 
a)	Mientras estén en la Unidad Educativa, durante las horas regulares de clases, incluyendo protección durante actividades deportivas, en las canchas, patios, realizando ejercicios de educación física, trabajos de taller y excursiones. 
<br>
b)	Mientras participen en actividades patrocinadas y supervisadas por la Unidad Educativa (eventos sociales) dentro y fuera de los terrenos de la misma o después de las horas regulares de clases (bajo supervisión del personal docente). 
<br>
<b>6. EXCLUSIONES </b>
<br>
Son excluidos de la presente póliza los siguientes casos:
<br>
<b>a) </b>	Cualquier enfermedad corporal o mental que no sea motivada por accidente amparados por la póliza. Ataques cardíacos o epilépticos, síncopes, demencia, u otras enfermedades, así como hernias o infecciones bacteriológicas que no sean originadas por un accidente cubierto.
<br>
<b>b) </b>	Accidentes que no se pueda comprobar que ocurrieron dentro del tiempo y espacio a que se refiere la cláusula “Alcances de la Cobertura”.
<br>
<b>c) </b>	Actos de guerra, declarada o no; servicios en fuerza armadas; terrorismo, tal como se define en esta póliza; rebelión o cualquier acto resultante de ésta. 
<br>
<b>d) </b>	Alborotos populares, huelgas, desórdenes públicos, motines, si el asegurado estuviere participando en los mismos. 
<br>
<b>e) </b>	Desafío o riñas, salvo en caso de legítima defensa. 
<br>
<b>f) </b>	Mientras el asegurado esté bajo las influencias de estupefacientes, drogas o bebidas alcohólicas. 
<br>
<b>g) </b>	Efectos de la energía atómica o nuclear.
<br>
<b>h) </b>	Por fenómenos catastróficos de la naturaleza (sismos, huracán, inundaciones, etc.).
<br>
<b>i) </b>	Práctica de competencias deportivas consideradas peligrosas, como el boxeo, lucha, karate, alpinismo, motociclismo, etc. 
<br>
<b>j) </b>	Daños causados a sí mismo por el asegurado, suicidio o tentativa de suicidio y lesiones causadas intencionalmente.
<br>
<b>k) </b>	Lentes o prescripciones de éstos.
<br>
<b>l) </b>	Cirugía dental, servicio o reparación dental.
<br>
<b>m) </b>	Servicio o tratamiento prestado como parte de los deberes de un médico, enfermera o cualquier otra persona.<br>
<br>
<b>7. GASTOS MEDICOS INCURRIDOS POR ACCIDENTE </b>
<br>
Siempre que la causa fuere un accidente cubierto bajo esta póliza, La Unidad Educativa pagará los honorarios médicos, así como los gastos farmacéuticos, hospitalarios y quirúrgicos que sean indispensables y necesarios, hasta el monto estipulado en el presente documento.
Se pagarán facturas por gastos médicos incurridos durante un período máximo de 60 días siguientes a la fecha de un accidente cubierto, y en ningún caso la suma total a pagar por tratamiento posteriores excederá a la suma asegurada en las Condiciones Particulares de esta Póliza. 
El contratante se obliga a referir a los asegurados amparados por esta póliza a los médicos o centros hospitalarios o clínicas u hospitales que la Unidad Educativa designe mediante comunicación al contratante, salvo casos de extrema urgencia comprobada. Si el contratante o el asegurado prefirieren otro médico u hospital, la Unidad Educativa pagará de acuerdo a los honorarios razonables pactados sin exceder el monto acordado por concepto de accidente, de lo contrario el contratante y/o asegurado asumirán cualquier diferencia o exceso en la contratación de esos servicios. 
<br>
<b>8. BENEFICIOS POR MUERTE ACCIDENTAL </b>
<br>
Si dentro de 60 días después de un accidente cubierto bajo esta póliza las lesiones recibidas por el asegurado ocasionaren su muerte, la Unidad Educativa pagará la suma asegurada por este beneficio indicada en las Condiciones Particulares. 
<br>
<b>9. BENEFICIOS POR DESMEMBRAMIENTO </b> 
<br>
Si como consecuencia de un accidente cubierto, las lesiones recibidas por el asegurado ocasionaren desmembramientos, que signifiquen una incapacidad permanente, se pagará el porcentaje indicada en las Condiciones Particulares de la Póliza. La referencia a pérdida por Desmembramiento es en caso de separación de algún miembro o extremidad de la persona afectada (dedos, manos, pies, brazos y ojos). 
Este beneficio se pagará únicamente si el asegurado sobreviviere 60 días de ocurrido un accidente cubierto, si no sobreviviere, se pagará únicamente el beneficio de muerte accidental. 
<br>
<b>10. PROCEDIMIENTO A SEGUIR</b>
<br>
El procedimiento para la reclamación del beneficio en cualquiera de los casos expuestos en la presente Póliza, se deberá obtener en la secretaria de la Unidad Educativa.
<br>
<b>11. PAGO DE INDEMNIZACIÓN (ES): </b>
<br>
En caso de muerte accidental, la Unidad Educativa pagará la suma asegurada del beneficiario, a los familiares de primer grado (padre o madre) y en su defecto o falta de tal designación, a los herederos legales del Asegurado. La Unidad Educativa no pagará indemnización alguna por muerte accidental, si la muerte del Asegurado sobreviene 61 días o más (más de 2 meses) de haber ocurrido el accidente. 
<br>
<b>12. PÉRDIDA DE DERECHO A INDEMNIZACIÓN</b> 
<br>
El ASEGURADO o BENEFICIARIO(S) pierde(n) su derecho a la indemnización o prestaciones del seguro, cuando:
<br>
<b>a)</b>	Provoque dolosamente el siniestro, su extensión o propagación.
<br>
<b>b)</b>	Oculte o altere, maliciosamente, en la verificación del siniestro los hechos y circunstancias del aviso del siniestro o de los informes o evidencias de verificación del mismo;
<br>
<b>c)</b>	Recurra a pruebas falsas con el ánimo de obtener un beneficio ilícito; que fácilmente origina sanciones penales.
<br><br>
<b style="text-align:center;"><u>SLIP DE COBERTURA</u></b></center>
<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>MATERIA DE SEGURO:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SEGURO ESCOLAR
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>DETALLES DEL SEGURO:</b>&nbsp;&nbsp;&nbsp;&nbsp;ACCIDENTES PERSONALES Y/O FALLECIMIENTO 
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>COBERTURAS:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MUERTE ACCIDENTAL			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bs. 21.000.-   (VEINTIÚN MIL 00/100 bolivianos).
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INVALIDEZ TOTAL Y/O PARCIAL PERMANENTE	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bs. 15.000.-   (QUINCE MIL 00/100 bolivianos).
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACCIDENTE (GASTOS MÉDICOS)		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bs. 10.000.-   (DIEZ MIL 00/100 bolivianos).
<br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>PRIMA ANUAL – GESTIÓN ACADEMICA ESCOLAR:</b>			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bs. 50.-   (Cincuenta 00/100 bolivianos).
<br><br>
		
En fe de lo cual se expuso, la Unidad Educativa expide y firma esta Póliza para constancia de ambas partes intervinientes
<br><br><br><br><br><br><br><br>


<table border="0">
  <tr>
    <th>-----------------------------------------------</th>
    <td></td>
    <th>-----------------------------------------------</th>
  </tr>
  <tr>
    <td>CONTRATANTE / BENEFICIARIO</td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>Nombre: </td>
    <td></td>
    <td></td>
  </tr>
  <tr>
    <td>N° de C.I.</td>
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
