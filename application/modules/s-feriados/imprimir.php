<?php

// Obtiene los parametros
$id_feriado = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_feriado == 0) {
	// Obtiene los sferiados
	$sferiados = $db->select('z.*')->from('per_feriados z')->order_by('z.id_feriado', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los sferiados
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el sferiados
	$sferiados = $db->select('z.*')->from('per_feriados z')->where('z.id_feriado', $id_feriado)->fetch_first();
	
	// Ejecuta un error 404 si no existe el sferiados
	if (!$sferiados || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_feriado == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'SFERIADOS', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($sferiados as $nro => $sferiados) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($sferiados[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . date_decode($sferiados['fecha_feriado'], $_format) . '</td>';
		$body .= '<td>' . escape($sferiados['descripcion']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="3">No existen sferiados registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="50.92%">Fecha feriado</th>';
	$tabla .= '<th width="43.08%">Descripcion</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'sferiados_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'SFERIADOS # ' . $id_feriado, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_fecha_feriado = date_decode($sferiados['fecha_feriado'], $_format);
	$valor_descripcion = escape($sferiados['descripcion']);
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Fecha feriado:</th><td class="right">' . $valor_fecha_feriado . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Descripcion:</th><td class="right">' . $valor_descripcion . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'sferiados_' . $id_feriado . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>