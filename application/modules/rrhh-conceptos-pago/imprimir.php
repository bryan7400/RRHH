<?php

// Obtiene los parametros
$id_concepto_pago = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_concepto_pago == 0) {
	// Obtiene los concepto_pago
	$concepto_pago = $db->select('z.*')->from('rhh_concepto_pago z')->order_by('z.id_concepto_pago', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los concepto_pago
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el concepto_pago
	$concepto_pago = $db->select('z.*')->from('rhh_concepto_pago z')->where('z.id_concepto_pago', $id_concepto_pago)->fetch_first();
	
	// Ejecuta un error 404 si no existe el concepto_pago
	if (!$concepto_pago || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_concepto_pago == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'CONCEPTO PAGO', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($concepto_pago as $nro => $concepto_pago) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($concepto_pago[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($concepto_pago['nombre_concepto_pago']) . '</td>';
		$body .= '<td>' . escape($concepto_pago['porcentaje']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="3">No existen concepto pago registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="62.67%">Nombre concepto pago</th>';
	$tabla .= '<th width="31.33%">Porcentaje</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'concepto_pago_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'CONCEPTO PAGO # ' . $id_concepto_pago, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_nombre_concepto_pago = escape($concepto_pago['nombre_concepto_pago']);
	$valor_porcentaje = escape($concepto_pago['porcentaje']);
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Nombre concepto pago:</th><td class="right">' . $valor_nombre_concepto_pago . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Porcentaje:</th><td class="right">' . $valor_porcentaje . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'concepto_pago_' . $id_concepto_pago . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>