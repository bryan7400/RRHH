<?php 
$anio = (isset($_params[0])) ? $_params[0] : 0;

if ($anio > 0) { 

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
    public function ColoredTable($header,$anio,$db) {
        // Colors, line width and bold font

		$res = $db->query(" SELECT pp.* , p.*, a.id_asignacion, pos.cuenta_bancaria, pos.genero
                      FROM rrhh_retroactivos pp, ins_gestion as g 
                      LEFT JOIN per_asignaciones as a ON id_gestion=gestion_id
                      LEFT JOIN sys_persona as p ON id_persona=persona_id
                      LEFT JOIN per_postulacion as pos ON postulante_id=id_postulacion
                      WHERE anio='".$anio."' AND pp.asignacion_id=a.id_asignacion
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
            $this->MultiCell($w[8], 4, "30", 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[9], 4, number_format($rxx['sueldo'],2,'.',''), 1, 'C', 1, 0, '', '', true, 0, false, true, 0);

            $this->MultiCell($w[10], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[11], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[12], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[13], 4, number_format($rxx['sueldo'],2,'.',''), 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[14], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[15], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[16], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[17], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[18], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
            $this->MultiCell($w[19], 4, '0.00', 1, 'C', 1, 0, '', '', true, 0, false, true, 0);

            $this->MultiCell($w[20], 4, number_format($rxx['sueldo'],2,'.',''), 1, 'C', 1, 0, '', '', true, 0, false, true, 0);
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
$pdf->ColoredTable($header,$anio,$db);

// ---------------------------------------------------------

// close and output PDF document
$pdf->Output('example_011.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+

?>
