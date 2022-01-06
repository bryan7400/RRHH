<?php 
$anio = (isset($_params[0])) ? $_params[0] : 0;
$mes = (isset($_params[1])) ? $_params[1] : 0;

if ($anio > 0 && $mes>0) { 

}else{
	require_once not_found();
	exit;	
}

// Include the main TCPDF library (search for installation path).
require_once libraries . '/tcpdf/tcpdf.php';
require_once libraries . '/tcpdf/tcpdf_barcodes_2d.php';
require_once libraries . '/numbertoletter-class/NumberToLetterConverter.php';

// extend TCPF with custom functions
class MYPDF extends TCPDF {

    // Load table data from file
    public function LoadData($file) {
        // Read file lines
        /*$lines = file($file);
        $data = array();
        foreach($lines as $line) {
            $data[] = explode(';', chop($line));
        }
        return $data;*/
    }

    // Colored table
    public function ColoredTable($header,$anio,$mes,$db) {
        // Colors, line width and bold font

		$res = $db->query(" SELECT pp.* , p.*, c.cargo, a.sueldo_total, a.id_asignacion, pos.cuenta_bancaria, pos.genero
                      FROM rrhh_planilla_pago pp, ins_gestion as g 
                      LEFT JOIN per_asignaciones as a ON id_gestion=gestion_id
                      LEFT JOIN sys_persona as p ON id_persona=persona_id
                      LEFT JOIN per_cargos as c ON id_cargo=cargo_id
                      LEFT JOIN per_postulacion as pos ON postulante_id=id_postulacion
                      WHERE anio='".$anio."' AND mes='".$mes."' AND pp.asignacion_id=a.id_asignacion
                      ")->fetch();

        $this->SetFillColor(210, 210, 210);
        $this->SetTextColor(0);
        $this->SetDrawColor(0, 0, 0);
        $this->SetLineWidth(0.01);
        $this->SetFont(PDF_FONT_NAME_MAIN, 'B',5);

        // Header        
        $w = array(5, 15, 23, 13, 12, 7, 13, 10, 10, 10, 12, 13, 13, 13, 13, 13, 13, 13, 13, 15, 13, 15);
        $num_headers = count($header);
        for($i = 0; $i < $num_headers; ++$i) {
            $this->MultiCell($w[$i], 5.5, $header[$i], 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetTextColor(0);
        //$this->SetFont('');
        // Data

        $nro=0;
		foreach ($res as $rxx) { 
	    	$nro++;    
			if($nro%2==0){
				$this->SetFillColor(235, 235, 235);        
			}
			else{
				$this->SetFillColor(255, 255, 255);        	
			}

			$vx=explode("-",$rxx['fecha_nacimiento']);
			$fechanacimiento=$vx[2]."/".$vx[1]."/".$vx[0];    

            $this->MultiCell($w[0], 4, $nro, 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[1], 4, strtoupper($rxx['numero_documento'].' '.$rxx['expedido']), 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[2], 4, strtoupper($rxx['nombres'].' '.$rxx['primer_apellido'].' '.$rxx['segundo_apellido']), 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[3], 4, "Boliviano", 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[4], 4, $fechanacimiento, 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[5], 4, strtoupper($rxx['genero']), 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[6], 4, $rxx['cargo'], 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[7], 4, '', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[8], 4, $rxx['dias_laborales'], 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[9], 4, number_format($rxx['sueldo_total'],2,'.',''), 1, 'C', 1, 0, '', '', true, 0, false, true, 0);

            $this->MultiCell($w[10], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[11], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[12], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[13], 4, number_format($rxx['sueldo_total'],2,'.',''), 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[14], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[15], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[16], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[17], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[18], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[19], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);

            $this->MultiCell($w[20], 4, number_format($rxx['sueldo_total'],2,'.',''), 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[21], 4, '', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->Ln();
        
		/*
			$body .= '<table cellpadding="' . $padding . '">';			
			$body .= '<tr>';
			$body .= '<th width="2%" align="CENTER" border="0.5px"><br>'.$nro.'<br></th>';
			
			$body .= '<th width="8%" align="CENTER" border="0.5px"><br>'.$rxx['cuenta_bancaria'].'<br></th>';
			$body .= '<th width="8%" align="center" border="0.5px"><br>'.$fechanacimiento.'<br></th>';
			$body .= '<th width="8%" align="CENTER" border="0.5px"><br>'.strtoupper($rxx['cargo']).'<br></th>';	
			$body .= '<th width="8%" align="CENTER" border="0.5px"><br> '.strtoupper($rxx['cns']).'<br></th>';	
			
			$body .= '<th width="6%" align="CENTER" border="0.5px"><br>'.number_format($rxx['sueldo'],2,'.','').'<br></th>';	
			$body .= '<th width="6%" align="CENTER" border="0.5px"><br>0.00<br></th>';	
			
			$body .= '<th width="6%" align="CENTER" border="0.5px"><br>0.00<br></th>';	
			$body .= '<th width="6%" align="CENTER" border="0.5px"><br>0.00<br></th>';	
			$body .= '<th width="6%" align="CENTER" border="0.5px"><br>0.00<br></th>';	
			$body .= '<th width="6%" align="CENTER" border="0.5px"><br>0.00<br></th>';	
			$body .= '<th width="6%" align="CENTER" border="0.5px"><br>'.number_format($rxx['sueldo'],2,'.','').'<br></th>';	
			$body .= '</tr>';
		*/
	    } 
      
        /*$fill = 0;
        foreach($data as $row) {
            $this->Cell($w[0], 6, $row[0], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row[1], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, number_format($row[2]), 'LR', 0, 'R', $fill);
            $this->Cell($w[3], 6, number_format($row[3]), 'LR', 0, 'R', $fill);
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');

        */
    }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetPageOrientation('L');
	
// set document information
/*
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 011');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 011', PDF_HEADER_STRING);

// set header and footer fonts

/*
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
*/

// set some language-dependent strings (optional)
/*
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
*/
// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 9);

// add a page
$pdf->AddPage();

// column titles
$header = array('Nº', 'Documento de Identidad', 'Apellidos y Nombres', 'Pais de Nacionalidad','Fecha de Nacimiento','Sexo (V/M)', 'Ocupacion que desempeña','Fecha de Ingreso','Dias Pagados (mes)', '(1) Haber Basico', '(2) Bono de Antiguedad', '(3) Bono de Produccion', '(4) Otros Bonos', '(5) TOTAL GANADO Suma (1 a 4)', '(6) Aporte AFPs','(7) Atrasos', '(8) Faltas','(9) Adelantos','(10) Otros Descuentos','(11) TOTAL DESCUENTOS Suma (6 a 10)','(12) LIQUIDO PAGABLE (5-11)','(13) Firma');

	

// data loading
//$data = $pdf->LoadData('data/table_data_demo.txt');

// print colored table
$pdf->ColoredTable($header,$anio,$mes,$db);

// ---------------------------------------------------------

// close and output PDF document
$pdf->Output('example_011.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

?>










<?php
/*
$anio = (isset($_params[0])) ? $_params[0] : 0;
$mes = (isset($_params[1])) ? $_params[1] : 0;

if ($anio > 0 && $mes>0) { 
	$res = $db->query(" SELECT pp.* , p.*, c.cargo, a.sueldo, a.id_asignacion, pos.cuenta_bancaria
                      FROM rrhh_planilla_pago pp, ins_gestion as g 
                      LEFT JOIN per_asignaciones as a ON id_gestion=gestion_id
                      LEFT JOIN sys_persona as p ON id_persona=persona_id
                      LEFT JOIN per_cargos as c ON id_cargo=cargo_id
                      LEFT JOIN per_postulacion as pos ON postulante_id=id_postulacion
                      WHERE anio='".$anio."' AND mes='".$mes."' 
                      ")->fetch();
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
//$pdf = new MYPDF('P', 'pt', 'LETTER', true, 'UTF-8', false);
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

if ($anio==0 || $mes==0) { 
	require_once not_found();
	   exit;
} else {
	// Documento individual --------------------------------------------------
	
	// Asigna la orientacion de la pagina factura
	$pdf->SetPageOrientation('L');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 13);
	
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
    
	// Titulo del documento	
	$pdf->Cell(0, 10, 'ORGANIZACIÓN CRISTIANA DE INSTITUCIONES EDUCATIVAS LAICAS', 0, true, 'C', false, '', 0, false, 'T', 'M');
	$pdf->Cell(0, 10, 'UNIDAD EDUCATIVA PRIVADA "MARANATA"', 0, true, 'C', false, '', 0, false, 'T', 'M');
	$pdf->Cell(0, 10, 'R. M. 164/2006', 0, true, 'C', false, '', 0, false, 'T', 'M');
	$pdf->Cell(0, 10, 'PLANILLA DE PAGOS '.$mees.' '.$res['anio'], 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	//$pdf->Ln(5);
	
	// Establece la fuente del contenido
	$pdf->SetFont(PDF_FONT_NAME_DATA, '', 7);



	            
	$vx=explode("-",$res['fecha_nacimiento']);
	$fechanacimiento=$vx[2]."/".$vx[1]."/".$vx[0];    

	$body = '
	<style>
		th{
			border:1px solid #000;
			color:#f00;
		}
	</style>
	';

	$body .= '<table cellpadding="' . $padding . '">';			
	$body .= '<tr>';
	$body .= '<th width="2%" align="CENTER" border="0.5px"><b><br> # <br></B></th>';
	
	$body .= '<th width="16%" align="CENTER" border="0.5px"><b><br> Apellidos y Nombres <br></B></th>';
	$body .= '<th width="8%" align="center" border="0.5px"><br><br> Carnet <br></th>';
	$body .= '<th width="8%" align="CENTER" border="0.5px"><b><br> Nro. Cta. <br></B></th>';
	$body .= '<th width="8%" align="center" border="0.5px"><br><br> Nacimiento <br></th>';
	$body .= '<th width="8%" align="CENTER" border="0.5px"><b><br> Cargo <br></B></th>';	
	$body .= '<th width="8%" align="CENTER" border="0.5px"><b><br> Item C.N.S. <br></B></th>';	
	
	$body .= '<th width="6%" align="CENTER" border="0.5px"><b><br> Haber Basico <br></B></th>';	
	$body .= '<th width="6%" align="CENTER" border="0.5px"><b><br> Bono Antiguedad <br></B></th>';	
	
	$body .= '<th width="6%" align="CENTER" border="0.5px"><b><br> Aportes AFP <br></B></th>';	
	$body .= '<th width="6%" align="CENTER" border="0.5px"><b><br> Atrasos <br></B></th>';	
	$body .= '<th width="6%" align="CENTER" border="0.5px"><b><br> Faltas <br></B></th>';	
	$body .= '<th width="6%" align="CENTER" border="0.5px"><b><br> Adelantos <br></B></th>';	
	$body .= '<th width="6%" align="CENTER" border="0.5px"><b><br> Liquido Pagable <br></B></th>';	
	$body .= '</tr>';

	$nro=0;

	foreach ($res as $rxx) { 
    	$nro++;    
		
		$vx=explode("-",$rxx['fecha_nacimiento']);
		$fechanacimiento=$vx[2]."/".$vx[1]."/".$vx[0];    

		$body .= '<table cellpadding="' . $padding . '">';			
		$body .= '<tr>';
		$body .= '<th width="2%" align="CENTER" border="0.5px"><br>'.$nro.'<br></th>';
		
		$body .= '<th width="16%" align="left" border="0.5px"><br>'.strtoupper($rxx['nombres'].' '.$rxx['primer_apellido'].' '.$rxx['segundo_apellido']).'<br></th>';
		$body .= '<th width="8%" align="center" border="0.5px"><br>'.strtoupper($rxx['numero_documento'].' '.$res['expedido']).'<br></th>';
		$body .= '<th width="8%" align="CENTER" border="0.5px"><br>'.$rxx['cuenta_bancaria'].'<br></th>';
		$body .= '<th width="8%" align="center" border="0.5px"><br>'.$fechanacimiento.'<br></th>';
		$body .= '<th width="8%" align="CENTER" border="0.5px"><br>'.strtoupper($rxx['cargo']).'<br></th>';	
		$body .= '<th width="8%" align="CENTER" border="0.5px"><br> '.strtoupper($rxx['cns']).'<br></th>';	
		
		$body .= '<th width="6%" align="CENTER" border="0.5px"><br>'.number_format($rxx['sueldo'],2,'.','').'<br></th>';	
		$body .= '<th width="6%" align="CENTER" border="0.5px"><br>0.00<br></th>';	
		
		$body .= '<th width="6%" align="CENTER" border="0.5px"><br>0.00<br></th>';	
		$body .= '<th width="6%" align="CENTER" border="0.5px"><br>0.00<br></th>';	
		$body .= '<th width="6%" align="CENTER" border="0.5px"><br>0.00<br></th>';	
		$body .= '<th width="6%" align="CENTER" border="0.5px"><br>0.00<br></th>';	
		$body .= '<th width="6%" align="CENTER" border="0.5px"><br>'.number_format($rxx['sueldo'],2,'.','').'<br></th>';	
		$body .= '</tr>';

    } 
	$body .= '</table>';
    

/*
	$body = '<table cellpadding="' . $padding . '">';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br><br>APELLIDOS Y NOMBRES: <br></th>';
	$body .= '<th width="25%" align="LEFT"><br><br><b> '.strtoupper($res['nombres'].' '.$res['primer_apellido'].' '.$res['segundo_apellido']).'</b><br></th>';
	$body .= '<th width="25%" align="RIGHT"><br><br>CONTRATO: <br></th>';
	$body .= '<th width="22%" align="LEFT"><br><br><b> '.$res['id_asignacion'].'</b><br></th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br>CORRESPONDIENTE AL MES DE: <br></th>';
	$body .= '<th width="25%" align="LEFT"><br><b> '.$mees.' '.$res['anio'].'</b><br></th>';
	$body .= '<th width="25%" align="RIGHT"><br>FECHA NACIMIENTO: <br></th>';
	$body .= '<th width="25%" align="LEFT"><br><b> '.$fechanacimiento.' </b><br></th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br>C.I.: <br></th>';
	$body .= '<th width="25%" align="LEFT"><br><b> '.strtoupper($res['numero_documento'].' '.$res['expedido']).'</b><br></th>';
	$body .= '<th width="25%" align="RIGHT"><br>ENTIDAD GESTORA: <br></th>';
	$body .= '<th width="25%" align="LEFT"><br><b> C.N.S.</b><br></th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br>CARGO: <br></th>';
	$body .= '<th width="25%" align="LEFT"><br><b> '.strtoupper($res['cargo']).'</b><br></th>';
	$body .= '<th width="25%" align="center"></th>';
	$body .= '<th width="25%" align="center"></th>';
	$body .= '</tr>';


	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br><br>INGRESOS: </th>';
	$body .= '<th width="25%" align="LEFT"><br><br> HABER BASICO<br></th>';
	$body .= '<th width="25%" align="RIGHT"><br><br>'.number_format($res['sueldo'],2,'.','').'</th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br><br></th>';
	$body .= '<th width="25%" align="LEFT"> BONO ANTIGUEDAD</th>';
	$body .= '<th width="25%" align="RIGHT">0.00</th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br><br></th>';
	$body .= '<th width="25%" align="LEFT"> BONOS</th>';
	$body .= '<th width="25%" align="RIGHT">0.00</th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br><br></th>';
	$body .= '<th width="25%" align="LEFT"><b> TOTAL INGRESO</B></th>';
	$body .= '<th width="25%" align="RIGHT">'.number_format($res['sueldo'],2,'.','').'</th>';
	$body .= '</tr>';
	
	$body .= '</table>';



	$descuento1=$res['sueldo']*0.10;
	$descuento2=$res['sueldo']*0.0171;
	$descuento3=$res['sueldo']*0.005;
	$descuento4=$res['sueldo']*0.005;
	
	$descuento5=$descuento1+$descuento2+$descuento3+$descuento4;

	$final=$res['sueldo']-$descuento5;






	$body .= '<table cellpadding="' . $padding . '">';		
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br><br>DESCUENTOS: </th>';
	$body .= '<th width="18%" align="LEFT"><br><br> AFP. RV. <br></th>';
	$body .= '<th width="7%" align="RIGHT"><br><br>10%</th>';
	$body .= '<th width="25%" align="RIGHT"><br><br>'.number_format($descuento1,2,'.','').'</th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br><br></th>';
	$body .= '<th width="18%" align="LEFT">  AFP. RC. </th>';
	$body .= '<th width="7%" align="RIGHT">1.71%</th>';
	$body .= '<th width="25%" align="RIGHT">'.number_format($descuento2,2,'.','').'</th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br><br></th>';
	$body .= '<th width="18%" align="LEFT">  AFP. CM. </th>';
	$body .= '<th width="7%" align="RIGHT">0.5%</th>';
	$body .= '<th width="25%" align="RIGHT">'.number_format($descuento3,2,'.','').'</th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br><br></th>';
	$body .= '<th width="18%" align="LEFT">  AFP. SOL. ASE.</th>';
	$body .= '<th width="7%" align="RIGHT">0.5%</th>';
	$body .= '<th width="25%" align="RIGHT">'.number_format($descuento4,2,'.','').'</th>';
	$body .= '</tr>';
	
	$body .= '<tr>';
	$body .= '<th width="25%" align="RIGHT"><br><br><br><br></th>';
	$body .= '<th width="25%" colspan="2" align="LEFT"><b> TOTAL DESCUENTO</B></th>';
	$body .= '<th width="25%" align="RIGHT">'.number_format($descuento5,2,'.','').'</th>';
	$body .= '<th width="25%" align="RIGHT"></th>';
	$body .= '</tr>';
	$body .= '</table>';



	



	$pdf->writeHTML($body, true, false, false, false, '');
	
	$pdf->SetMargins(30, 0, 30);
	$pdf->SetHeaderMargin(0);
	$pdf->SetFooterMargin(0);
	$pdf->SetAutoPageBreak(true, 0);


}



	





	$documento=$res['documento'];
	$documento=str_replace('<h2>', '<h1 align="center">', $documento);
	$documento=str_replace('</h2>', '</h1>', $documento);

	$documento=str_replace('<h1>', '<h1 align="center">', $documento);







	// Formateamos la tabla
	$tabla = <<<EOD
	<style>
	</style>

	$documento
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
$pdf->Output('Boleta_Pago', 'I');
*/
?>
