<?php

$id_gestion = $_gestion['id_gestion'];

// Obtiene los parametros
$id_area_calificacion = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_area_calificacion == 0) {
	// Obtiene los sareacalificacion
	$sareacalificacion = $db->query("SELECT * FROM cal_area_calificacion as cac INNER JOIN ins_gestion as ig ON ig.id_gestion = cac.gestion_id WHERE cac.gestion_id = $id_gestion AND cac.estado = 'A' ORDER BY cac.orden ASC")->fetch();

	// Ejecuta un error 404 si no existe los sareacalificacion
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el sareacalificacion
	$sareacalificacion = $db->query("SELECT * FROM cal_area_calificacion as cac INNER JOIN ins_gestion as ig ON ig.id_gestion = cac.gestion_id WHERE cac.gestion_id = $id_gestion AND cac.estado = 'A' ORDER BY cac.orden ASC")->fetch();
	
	// Ejecuta un error 404 si no existe el sareacalificacion
	if (!$sareacalificacion || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_area_calificacion == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'ÁREA CALIFICACIÓN', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($sareacalificacion as $nro => $sareacalificacion) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($sareacalificacion[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($sareacalificacion['descripcion']) . '</td>';
		$body .= '<td>' . escape($sareacalificacion['ponderado']) . '</td>';
		$body .= '<td>' . escape($sareacalificacion['gestion']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="4">No existen area calificacion registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5" border="0.5px">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="38.30%">Descripción</th>';
	$tabla .= '<th width="31.33%">Ponderado</th>';
	$tabla .= '<th width="24.37%">Gestión</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'sareacalificacion_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'AREA CALIFICACION # ' . $id_area_calificacion, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_descripcion = escape($sareacalificacion['descripcion']);
	$valor_ponderado = escape($sareacalificacion['ponderado']);
	$valor_gestion_id = escape($sareacalificacion['gestion_id']);
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5" border="0.5px">';
	$tabla .= '<tr class="first"><th class="left">Descripcion:</th><td class="right">' . $valor_descripcion . '</td></tr>';
	$tabla .= '<tr><th class="left">Ponderado:</th><td class="right">' . $valor_ponderado . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Gestion:</th><td class="right">' . $valor_gestion_id . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'sareacalificacion_' . $id_area_calificacion . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>