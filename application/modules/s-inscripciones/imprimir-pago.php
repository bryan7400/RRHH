<?php
//var_dump('sdfgdg');exit();

// Obtiene el id_egreso
$id_estudiante = (isset($_params[0])) ? $_params[0] : 0; 
$id_inscripcion=3;

if ($id_estudiante > 0) { 

	// Obtiene los detalles
	$estudiante = $db->query("SELECT*
	FROM ins_inscripcion i
	LEFT JOIN ins_estudiante e ON i.estudiante_id = e.id_estudiante
	LEFT JOIN sys_persona per ON e.persona_id = per.id_persona
	WHERE i.estudiante_id = $id_estudiante ")->fetch_first();

	// Obtiene los detalles
	$pagos = $db->query("SELECT 
        i.id_inscripcion, i.estudiante_id, i.gestion_id,
        p.id_pensiones, p.nombre_pension, pe.monto, pe.mora_dia,pe.fecha_inicio, pe.fecha_final, p.gestion_id, p.nivel_academico_id, p.tipo_estudiante_id, pd.nro,
        IFNULL(0,0) cancelado,IFNULL(0,0) suma_acuenta, pe.id_pensiones_estudiante
        FROM ins_inscripcion i
        LEFT JOIN pen_pensiones_estudiante pe ON i.id_inscripcion=pe.inscripcion_id
        LEFT JOIN pen_pensiones_detalle pd ON pe.detalle_pension_id = pd.id_pensiones_detalle
        LEFT JOIN pen_pensiones p ON pd.pensiones_id = p.id_pensiones
        WHERE i.estudiante_id=$id_estudiante")->fetch();
	//var_dump($pagos);exit();
}

// Obtiene la moneda oficial
$moneda = $db->from('inv_monedas')->where('oficial', 'S')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['sigla'] . ')' : '';

// Importa la libreria para el generado del pdf
require_once libraries . '/tcpdf/tcpdf.php';
require_once libraries . '/tcpdf/tcpdf_barcodes_2d.php';
require_once libraries . '/numbertoletter-class/NumberToLetterConverter.php';

// Define variables globales
// define('direccion', escape($_institution['pie_pagina']));
// define('imagen', escape($_institution['imagen_encabezado']));
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

if ($id_inscripcion == 0) {
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
	//$gestion=date('Y');
	$gestion=2020;

	// Obtiene la imagen QR en modo cadena
	//$imagen = $objeto->getBarcodePngData(4, 4, array(30, 30, 30));

	// Crea la imagen a partir de la cadena
	// $imagen = imagecreatefromstring($imagen);
    $nombre = $estudiante['primer_apellido'].' '.$estudiante['segundo_apellido'].' '.$estudiante['nombres'];
    $codigo = $estudiante['codigo_estudiante'];
    $documento = $estudiante['numero_documento'];


    // Estructura la tabla
	$body = '';
	foreach ($pagos as $nro => $detalle) {
		$body .= '<tr>';
		//$body .= '<td class="left-right">' . ($nro + 1) . '</td>';
		$body .= '<td class="left-right" align="left">' . escape($detalle['nombre_pension']).'  CUOTA ' . escape($detalle['nro']) . '</td>';
		$body .= '<td class="left-right" align="center">' . escape($detalle['fecha_inicio']) . '</td>';
		$body .= '<td class="left-right" align="center">' . escape($detalle['fecha_final']) . '</td>';
		$body .= '<td class="left-right" align="rigth">' . number_format($detalle['monto'], 2, '.', '') . '</td>';
		$body .= '<td class="left-right" align="rigth">' . escape($detalle['mora_dia']) . '</td>';
		$body .= '</tr>';
	}

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

	<h3 align="center">TICKET  DE PAGOS GESTIÓN ACADÉMICA - $gestion</h3>
	<br><br><br><br><br>
	<table cellpadding="5">
		<tr>
			<td class="all text-center">Código Estudiante:</td>
			<td class="all text-center">$codigo</td>
			<td class="all text-center">Apellidos y Nombre:</td>
			<td class="all text-center">$nombre</td>
			<td class="all text-center">Nº Documento:</td>
			<td class="all text-center">$documento</td>
		</tr>
	</table>
	<br><br>
	<table cellpadding="5" border="0.5">
		<tr>
			<td class="all text-center">Concepto de Pago</td>
			<td class="all text-center" align="center">Fecha Inicio</td>
			<td class="all text-center" align="center">Fecha Límite</td>
			<td class="all text-center" align="rigth">Monto</td>
			<td class="all text-center" align="rigth">Mora por Día</td>
		</tr>
		$body
	</table>
	<br><br><br><br><br><br>
	<table cellpadding="5">
		<tr>
			<td width="30%" class="all text-center">
			……………………………………………………………<br>
			LIC. ARIEL BITARGO CACHI MAMANI<br>
			TESORERO</td>
			<td width="30%" class="all text-center"></td>
			<td width="30%" class="all text-center">
			FIRMA: ……………………………………………………<br>
			NOMBRE: …………………………………………………<br>
			RESPONSABLE</td>
		</tr>
		<tr><td width="100%" align="center">Vo. Bo. Director</td></tr>
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
$pdf->Output('Recibo', 'I');

?>
