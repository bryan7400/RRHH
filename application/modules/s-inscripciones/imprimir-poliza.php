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
La Unidad Educativa ???Maranata???, representada mediante el Tesorero Lic. Ariel Bitargo Cachi Mamani, expide la presente P??liza de acuerdo con las Condiciones Generales y Particulares estipuladas en este documento, bas??ndose en la libre y espont??nea voluntad de suscribir el presente documento entre ambas partes.<br>
<b>2. DEFINICIONES </b><br>
Para los efectos de la presente p??liza, cuando se utilice cualquiera de las siguientes palabras en el texto, ??stas tendr??n el significado que aparece a continuaci??n: 
<br>
<b>Accidente:</b> Evento imprevisto, involuntario, repentino y fortuito, causado por medios externos y de modo violento que afecte el organismo del ASEGURADO, ocasion??ndole una o m??s lesiones que se manifiestan por contusiones o heridas visibles, y tambi??n los casos de lesiones internas o inmersi??n reveladas por los ex??menes m??dicos correspondientes, y que hayan ocurrido dentro de la vigencia de la P??liza y del ??mbito de tiempo y espacio indicados en dicha p??liza.
<br>
<b>Asegurados:</b> Son los estudiantes de la Unidad Educativa que se hayan legalmente inscritos como alumnos regulares para el per??odo de cobertura de la P??liza y hayan sido reportados como tales por el contratante. 
Unidad Educativa: Es el centro docente, sea que se denomine colegio, escuela, o cualquier otro t??rmino que signifique lo mismo. 
<br>
<b>P??liza:</b> Es el documento emitido por La Unidad Educativa en el que consta el contrato del seguro. En ??l, se establecen los t??rminos y condiciones de las coberturas contratadas. 
<br>
<b>Prima:</b> Es el valor econ??mico determinado por La Unidad Educativa, como contraprestaci??n por la cobertura de seguro contratada.
<br>
<b>Vandalismo:</b> Actos realizados por cualquier individuo o grupo de individuos, con el objeto de causar da??os a la propiedad.
<br>
<b>Conmoci??n Civil:</b> Alteraci??n del orden p??blico.
<br>
<b>Da??o Malicioso:</b> Actos realizados voluntariamente con el objeto de causar da??os en beneficio propio o de terceros.
<br>
<b>Mot??n:</b> Movimiento tumultuoso de car??cter popular contra la autoridad constituida o como protesta ante alguna de sus disposiciones. Alteraci??n local del orden p??blico que reviste poca gravedad y no mayor a 5 d??as. Enti??ndase como movimiento tumultuoso, a la confusi??n agitada o ruidosa o alboroto producido por una multitud. Y alteraci??n del orden p??blico, como la perturbaci??n de la paz, tranquilidad y seguridad p??blica.
<br>
<b>3. CANCELACI??N DE LA POLIZA</b>
<br>
Este contrato de seguro deber?? ser cancelado antes del inicio de las actividades escolares de la gesti??n 2020.
<br>
<b>4. INICIO DE VIGENCIA DEL SEGURO</b>
<br>
La cobertura otorgada por esta P??liza respecto de cada ASEGURADO, entrar?? en vigencia a partir del primer d??a de clases del ???Calendario Escolar Anual??? y su finalizaci??n de la p??liza a la conclusi??n del mismo, esta instructiva de inicio y cierre de gesti??n es emitido por el ??rgano competente que es el Ministerio de Educaci??n.
<br>
<b>5. ALCANCES DE LA COBERTURA</b> 
<br>
El seguro establecido en el presente, cubre a los estudiantes de la Unidad Educativa ???Maranata???, en las siguientes condiciones particulares:
<br> 
a)	Mientras est??n en la Unidad Educativa, durante las horas regulares de clases, incluyendo protecci??n durante actividades deportivas, en las canchas, patios, realizando ejercicios de educaci??n f??sica, trabajos de taller y excursiones. 
<br>
b)	Mientras participen en actividades patrocinadas y supervisadas por la Unidad Educativa (eventos sociales) dentro y fuera de los terrenos de la misma o despu??s de las horas regulares de clases (bajo supervisi??n del personal docente). 
<br>
<b>6. EXCLUSIONES </b>
<br>
Son excluidos de la presente p??liza los siguientes casos:
<br>
<b>a) </b>	Cualquier enfermedad corporal o mental que no sea motivada por accidente amparados por la p??liza. Ataques card??acos o epil??pticos, s??ncopes, demencia, u otras enfermedades, as?? como hernias o infecciones bacteriol??gicas que no sean originadas por un accidente cubierto.
<br>
<b>b) </b>	Accidentes que no se pueda comprobar que ocurrieron dentro del tiempo y espacio a que se refiere la cl??usula ???Alcances de la Cobertura???.
<br>
<b>c) </b>	Actos de guerra, declarada o no; servicios en fuerza armadas; terrorismo, tal como se define en esta p??liza; rebeli??n o cualquier acto resultante de ??sta. 
<br>
<b>d) </b>	Alborotos populares, huelgas, des??rdenes p??blicos, motines, si el asegurado estuviere participando en los mismos. 
<br>
<b>e) </b>	Desaf??o o ri??as, salvo en caso de leg??tima defensa. 
<br>
<b>f) </b>	Mientras el asegurado est?? bajo las influencias de estupefacientes, drogas o bebidas alcoh??licas. 
<br>
<b>g) </b>	Efectos de la energ??a at??mica o nuclear.
<br>
<b>h) </b>	Por fen??menos catastr??ficos de la naturaleza (sismos, hurac??n, inundaciones, etc.).
<br>
<b>i) </b>	Pr??ctica de competencias deportivas consideradas peligrosas, como el boxeo, lucha, karate, alpinismo, motociclismo, etc. 
<br>
<b>j) </b>	Da??os causados a s?? mismo por el asegurado, suicidio o tentativa de suicidio y lesiones causadas intencionalmente.
<br>
<b>k) </b>	Lentes o prescripciones de ??stos.
<br>
<b>l) </b>	Cirug??a dental, servicio o reparaci??n dental.
<br>
<b>m) </b>	Servicio o tratamiento prestado como parte de los deberes de un m??dico, enfermera o cualquier otra persona.<br>
<br>
<b>7. GASTOS MEDICOS INCURRIDOS POR ACCIDENTE </b>
<br>
Siempre que la causa fuere un accidente cubierto bajo esta p??liza, La Unidad Educativa pagar?? los honorarios m??dicos, as?? como los gastos farmac??uticos, hospitalarios y quir??rgicos que sean indispensables y necesarios, hasta el monto estipulado en el presente documento.
Se pagar??n facturas por gastos m??dicos incurridos durante un per??odo m??ximo de 60 d??as siguientes a la fecha de un accidente cubierto, y en ning??n caso la suma total a pagar por tratamiento posteriores exceder?? a la suma asegurada en las Condiciones Particulares de esta P??liza. 
El contratante se obliga a referir a los asegurados amparados por esta p??liza a los m??dicos o centros hospitalarios o cl??nicas u hospitales que la Unidad Educativa designe mediante comunicaci??n al contratante, salvo casos de extrema urgencia comprobada. Si el contratante o el asegurado prefirieren otro m??dico u hospital, la Unidad Educativa pagar?? de acuerdo a los honorarios razonables pactados sin exceder el monto acordado por concepto de accidente, de lo contrario el contratante y/o asegurado asumir??n cualquier diferencia o exceso en la contrataci??n de esos servicios. 
<br>
<b>8. BENEFICIOS POR MUERTE ACCIDENTAL </b>
<br>
Si dentro de 60 d??as despu??s de un accidente cubierto bajo esta p??liza las lesiones recibidas por el asegurado ocasionaren su muerte, la Unidad Educativa pagar?? la suma asegurada por este beneficio indicada en las Condiciones Particulares. 
<br>
<b>9. BENEFICIOS POR DESMEMBRAMIENTO </b> 
<br>
Si como consecuencia de un accidente cubierto, las lesiones recibidas por el asegurado ocasionaren desmembramientos, que signifiquen una incapacidad permanente, se pagar?? el porcentaje indicada en las Condiciones Particulares de la P??liza. La referencia a p??rdida por Desmembramiento es en caso de separaci??n de alg??n miembro o extremidad de la persona afectada (dedos, manos, pies, brazos y ojos). 
Este beneficio se pagar?? ??nicamente si el asegurado sobreviviere 60 d??as de ocurrido un accidente cubierto, si no sobreviviere, se pagar?? ??nicamente el beneficio de muerte accidental. 
<br>
<b>10. PROCEDIMIENTO A SEGUIR</b>
<br>
El procedimiento para la reclamaci??n del beneficio en cualquiera de los casos expuestos en la presente P??liza, se deber?? obtener en la secretaria de la Unidad Educativa.
<br>
<b>11. PAGO DE INDEMNIZACI??N (ES): </b>
<br>
En caso de muerte accidental, la Unidad Educativa pagar?? la suma asegurada del beneficiario, a los familiares de primer grado (padre o madre) y en su defecto o falta de tal designaci??n, a los herederos legales del Asegurado. La Unidad Educativa no pagar?? indemnizaci??n alguna por muerte accidental, si la muerte del Asegurado sobreviene 61 d??as o m??s (m??s de 2 meses) de haber ocurrido el accidente. 
<br>
<b>12. P??RDIDA DE DERECHO A INDEMNIZACI??N</b> 
<br>
El ASEGURADO o BENEFICIARIO(S) pierde(n) su derecho a la indemnizaci??n o prestaciones del seguro, cuando:
<br>
<b>a)</b>	Provoque dolosamente el siniestro, su extensi??n o propagaci??n.
<br>
<b>b)</b>	Oculte o altere, maliciosamente, en la verificaci??n del siniestro los hechos y circunstancias del aviso del siniestro o de los informes o evidencias de verificaci??n del mismo;
<br>
<b>c)</b>	Recurra a pruebas falsas con el ??nimo de obtener un beneficio il??cito; que f??cilmente origina sanciones penales.
<br><br>
<b style="text-align:center;"><u>SLIP DE COBERTURA</u></b></center>
<br><br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>MATERIA DE SEGURO:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SEGURO ESCOLAR
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>DETALLES DEL SEGURO:</b>&nbsp;&nbsp;&nbsp;&nbsp;ACCIDENTES PERSONALES Y/O FALLECIMIENTO 
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>COBERTURAS:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;MUERTE ACCIDENTAL			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bs. 21.000.-   (VEINTI??N MIL 00/100 bolivianos).
<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;INVALIDEZ TOTAL Y/O PARCIAL PERMANENTE	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bs. 15.000.-   (QUINCE MIL 00/100 bolivianos).
<br>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ACCIDENTE (GASTOS M??DICOS)		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bs. 10.000.-   (DIEZ MIL 00/100 bolivianos).
<br>

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>PRIMA ANUAL ??? GESTI??N ACADEMICA ESCOLAR:</b>			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Bs. 50.-   (Cincuenta 00/100 bolivianos).
<br><br>
		
En fe de lo cual se expuso, la Unidad Educativa expide y firma esta P??liza para constancia de ambas partes intervinientes
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
    <td>N?? de C.I.</td>
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
