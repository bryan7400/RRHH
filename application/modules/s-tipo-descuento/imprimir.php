<?php

// Obtiene los parametros
$id_tipo_descuento = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_tipo_descuento == 0) {
	// Obtiene los tipo_descuento
	$tipo_descuento = $db->select('z.*')->from('pen_tipo_descuento z')->order_by('z.id_tipo_descuento', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los tipo_descuento
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el tipo_descuento
	$tipo_descuento = $db->select('z.*')->from('pen_tipo_descuento z')->where('z.id_tipo_descuento', $id_tipo_descuento)->fetch_first();
	
	// Ejecuta un error 404 si no existe el tipo_descuento
	if (!$tipo_descuento || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_tipo_descuento == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'TIPO DESCUENTO', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($tipo_descuento as $nro => $tipo_descuento) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($tipo_descuento[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($tipo_descuento['tipo_descuento']) . '</td>';
		$body .= '<td>' . escape($tipo_descuento['porcentaje']) . '</td>';
		$body .= '<td>' . escape($tipo_descuento['descuento']) . '</td>';
		$body .= '<td>' . escape($tipo_descuento['gestion_id']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="5">No existen tipo descuento registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="32.90%">Tipo descuento</th>';
	$tabla .= '<th width="23.50%">Porcentaje</th>';
	$tabla .= '<th width="21.15%">Descuento</th>';
	$tabla .= '<th width="16.45%">Gestion</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'tipo_descuento_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'TIPO DESCUENTO # ' . $id_tipo_descuento, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_tipo_descuento = escape($tipo_descuento['tipo_descuento']);
	$valor_porcentaje = escape($tipo_descuento['porcentaje']);
	$valor_descuento = ($tipo_descuento['descuento'] != '') ? escape($tipo_descuento['descuento']) : 'No asignado';
	$valor_gestion_id = ($tipo_descuento['gestion_id'] != '') ? escape($tipo_descuento['gestion_id']) : 'No asignado';
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Tipo descuento:</th><td class="right">' . $valor_tipo_descuento . '</td></tr>';
	$tabla .= '<tr><th class="left">Porcentaje:</th><td class="right">' . $valor_porcentaje . '</td></tr>';
	$tabla .= '<tr><th class="left">Descuento:</th><td class="right">' . $valor_descuento . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Gestion:</th><td class="right">' . $valor_gestion_id . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'tipo_descuento_' . $id_tipo_descuento . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>