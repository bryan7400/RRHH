<?php
$id_contrato = (isset($_params[0])) ? $_params[0] : 0;

if ($id_contrato > 0) { 
	$contrato = $db->query("SELECT * FROM rrhh_contrato 
                        WHERE id_contrato='$id_contrato'")
                        ->fetch_first();
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
$pdf->SetMargins(30, 30, 30);

// Elimina las cabeceras
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// ------------------------------------------------------------

if ($id_contrato == 0) {
	require_once not_found();
	   exit;
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
	$pdf->SetFont(PDF_FONT_NAME_DATA, '', 11);

	// Estructura la tabla
	$body = '';

	// Define la fecha de hoy
	$hoy = date('Y-m-d');

	// Obtiene la imagen QR en modo cadena
	//$imagen = $objeto->getBarcodePngData(4, 4, array(30, 30, 30));

	// Crea la imagen a partir de la cadena
	// $imagen = imagecreatefromstring($imagen);
	
	$documento=$contrato['documento'];
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
$pdf->Output('Recibo', 'I');

?>
