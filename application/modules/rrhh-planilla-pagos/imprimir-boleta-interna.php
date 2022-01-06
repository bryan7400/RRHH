<?php
$id_planilla = (isset($_params[0])) ? $_params[0] : 0;

if ($id_planilla > 0) { 
	$res = $db->query(" SELECT pp.* , p.*, c.cargo, a.sueldo_total, a.id_asignacion, pos.cuenta_bancaria
                      FROM rrhh_planilla_pago pp, ins_gestion as g 
                      LEFT JOIN per_asignaciones as a ON id_gestion=gestion_id
                      LEFT JOIN sys_persona as p ON id_persona=persona_id
                      LEFT JOIN per_cargos as c ON id_cargo=cargo_id
                      LEFT JOIN per_postulacion as pos ON postulante_id=id_postulacion
                      WHERE id_planilla='".$id_planilla."' AND pp.asignacion_id=a.id_asignacion
                      ")->fetch_first();
}else{
	require_once not_found();
	exit;	
}

// Importa la libreria para el generado del pdf
require_once libraries . '/tcpdf/tcpdf.php';
require_once libraries . '/tcpdf/tcpdf_barcodes_2d.php';
require_once libraries . '/numbertoletter-class/NumberToLetterConverter.php';

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
$pdf->SetMargins(30, 50, 30);

// Elimina las cabeceras
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// ------------------------------------------------------------

if ($id_planilla == 0) {
	require_once not_found();
	   exit;
} else {
	// Documento individual --------------------------------------------------
	
	// Asigna la orientacion de la pagina factura
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 12);
	
	$body .= '<table color="#000000">';			
	$body .= '<tr>';
	$body .= '<th width="10%" align="CENTER"></th>';
	$body .= '<th width="90%" align="CENTER"><b>ORGANIZACIÓN CRISTIANA DE INSTITUCIONES EDUCATIVAS LAICAS</B></th>';
	$body .= '</tr>';
	$body .= '</table>';
	
	$body .= '<table color="#000000">';			
	$body .= '<tr>';
	$body .= '<th width="10%" align="CENTER"></th>';
	$body .= '<th width="90%" align="CENTER"><b>UNIDAD EDUCATIVA PRIVADA "MARANATA"</B></th>';
	$body .= '</tr>';
	$body .= '</table>';
	
	$body .= '<table color="#000000">';			
	$body .= '<tr>';
	$body .= '<th width="10%" align="CENTER"></th>';
	$body .= '<th width="90%" align="CENTER"><b>R. M. 164/2006</B></th>';
	$body .= '</tr>';
	$body .= '</table>';
	
	$body .= '<table color="#000000">';			
	$body .= '<tr>';
	$body .= '<th width="10%" align="CENTER"></th>';
	$body .= '<th width="90%" align="CENTER"><b>BOLETA DE PAGO</B></th>';
	$body .= '</tr>';
	$body .= '</table>';
	$pdf->writeHTML($body, true, false, false, false, '');



	
	// Establece la fuente del contenido
	$pdf->SetFont(PDF_FONT_NAME_DATA, '', 7);

	$mees='';
    switch($res['mes']){
        case "1":   $mees="ENERO";     break;
        case "2":   $mees="FEBRERO";   break;
        case "3":   $mees="MARZO";     break;
        case "4":   $mees="ABRIL";     break;
        case "5":   $mees="MAYO";      break;
        case "6":   $mees="JUNIO";     break;
        case "7":   $mees="JULIO";     break;
        case "8":   $mees="AGOSTO";         break;
        case "9":   $mees="SEPTIEMBRE";     break;
        case "10":   $mees="OCTUBRE";       break;
        case "11":   $mees="NOVIEMBRE";     break;
        case "12":   $mees="DICIEMBRE";     break;
    } 
                
	$vx=explode("-",$res['fecha_nacimiento']);
	$fechanacimiento=$vx[2]."/".$vx[1]."/".$vx[0];    

	$body = '<br><br><br>';
	$body .= '<table cellpadding="3" color="#000000">';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT">APELLIDOS Y NOMBRES: </th>';
	$body .= '<th width="25%" align="LEFT"><b> '.strtoupper($res['nombres'].' '.$res['primer_apellido'].' '.$res['segundo_apellido']).'</b></th>';
	$body .= '<th width="25%" align="RIGHT">CONTRATO: </th>';
	$body .= '<th width="22%" align="LEFT"><b> '.$res['id_asignacion'].'</b></th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT">CORRESPONDIENTE AL MES DE: </th>';
	$body .= '<th width="25%" align="LEFT"><b> '.$mees.' '.$res['anio'].'</b></th>';
	$body .= '<th width="25%" align="RIGHT">FECHA NACIMIENTO: </th>';
	$body .= '<th width="25%" align="LEFT"><b> '.$fechanacimiento.' </b></th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT">C.I.: </th>';
	$body .= '<th width="25%" align="LEFT"><b> '.strtoupper($res['numero_documento'].' '.$res['expedido']).'</b></th>';
	$body .= '<th width="25%" align="RIGHT">ENTIDAD GESTORA: </th>';
	$body .= '<th width="25%" align="LEFT"><b> C.N.S.</b></th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br>CARGO: <br></th>';
	$body .= '<th width="25%" align="LEFT"><br><b> '.strtoupper($res['cargo']).'</b><br></th>';
	$body .= '<th width="25%" align="center"></th>';
	$body .= '<th width="25%" align="center"></th>';
	$body .= '</tr>';
	$body .= '</table>';





	$body .= '<table cellpadding="3" color="#000000">';
	$body .= '<tr>';
	$body .= '<th width="15%" align="RIGHT"></th>';
	$body .= '<th width="20%" border="0.5px" align="RIGHT" rowspan="3"> INGRESOS: </th>';
	$body .= '<th width="25%" border="0.5px" align="LEFT"> HABER BASICO</th>';
	$body .= '<th width="25%" border="0.5px" align="RIGHT"> '.number_format($res['sueldo_total'],2,'.','').' </th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="15%" align="RIGHT"></th>';
	$body .= '<th width="25%" border="0.5px" align="LEFT">BONO ANTIGUEDAD</th>';
	$body .= '<th width="25%" border="0.5px" align="RIGHT">0.00</th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="15%" align="RIGHT"></th>';
	$body .= '<th width="25%" border="0.5px" align="LEFT">BONOS</th>';
	$body .= '<th width="25%" border="0.5px" align="RIGHT">0.00</th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="15%" align="RIGHT"></th>';
	$body .= '<th width="45%" border="0.5px" align="CENTER"><b>TOTAL INGRESO</B></th>';
	$body .= '<th width="25%" border="0.5px" align="RIGHT">'.number_format($res['sueldo_total'],2,'.','').'</th>';
	$body .= '</tr>';
	
	$body .= '</table>';



	$descuento1=$res['sueldo_total']*0.00;	
	$descuento5=$descuento1;
	$final=$res['sueldo_total']-$descuento5;





	$body .= '<table cellpadding="3" color="#000000">';		
	
	$body .= '<tr>';
	$body .= '<th width="15%" align="RIGHT"></th>';
	$body .= '<th width="20%" border="0.5px" align="RIGHT">DESCUENTOS: </th>';
	$body .= '<th width="25%" border="0.5px" align="LEFT">DIAS NO TRABAJADOS </th>';
	$body .= '<th width="25%" border="0.5px" align="RIGHT">'.number_format($descuento1,2,'.','').'</th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="15%" align="RIGHT"></th>';
	$body .= '<th width="45%" border="0.5px" align="CENTER"><b> TOTAL DESCUENTO</B></th>';
	$body .= '<th width="25%" border="0.5px" align="RIGHT">'.number_format($descuento5,2,'.','').'</th>';
	$body .= '</tr>';
	$body .= '</table>';

	$body .= '<br><br><br><br><br>';




	$body .= '<table cellpadding="' . $padding . '" color="#000000">';		
	
	$body .= '<tr>';
	$body .= '<th width="16%" align="CENTER" border="0.5px"><b><br> MODALIDAD<br>DE PAGO<br></B></th>';
	$body .= '<th width="16%" align="CENTER" border="0.5px"><b><br> ABONO A CTA. BANCO SOL<br></B></th>';
	$body .= '<th width="17%" align="center" border="0.5px"><br><br>'.$res['cuenta_bancaria'].'<br></th>';
	$body .= '<th width="16%" align="CENTER" border="0.5px"><b><br> LIQUIDO<br>PAGABLE<br></B></th>';
	$body .= '<th width="17%" align="center" border="0.5px"><br><br>'.number_format($final,2,'.','').'<br></th>';
	$body .= '<th width="16%" align="CENTER" border="0.5px"><b><br> BOLIVIANOS<br></B></th>';	
	$body .= '</tr>';

	$body .= '</table>';
	



	$pdf->writeHTML($body, true, false, false, false, '');
}

// Cierra y devuelve el fichero pdf
ob_end_clean();
$pdf->Output('Boleta_Pago', 'I');

?>
