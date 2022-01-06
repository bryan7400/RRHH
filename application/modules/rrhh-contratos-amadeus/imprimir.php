<?php

// Obtiene los parametros
$id_contrato = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_contrato == 0) {
	// Obtiene los contratos
	$contratos = $db->select('z.*')->from('rhh_contratos z')->order_by('z.id_contrato', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los contratos
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el contratos
	$contratos = $db->select('z.*')->from('rhh_contratos z')->where('z.id_contrato', $id_contrato)->fetch_first();
	
	// Ejecuta un error 404 si no existe el contratos
	if (!$contratos || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_contrato == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'CONTRATOS', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($contratos as $nro => $contratos) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($contratos[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($contratos['tipo_contrato_id']) . '</td>';
		$body .= '<td>' . escape($contratos['horario']) . '</td>';
		$body .= '<td>' . escape($contratos['cargo_id']) . '</td>';
		$body .= '<td>' . escape($contratos['sueldo_base']) . '</td>';
		$body .= '<td>' . date_decode($contratos['fecha_inicio'], $_format) . '</td>';
		$body .= '<td>' . escape($contratos['fecha_final']) . '</td>';
		$body .= '<td>' . escape($contratos['forma_pago']) . '</td>';
		$body .= '<td>' . escape($contratos['entidad_financiera_id']) . '</td>';
		$body .= '<td>' . escape($contratos['concepto_pago_id']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="10">No existen contratos registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="12.22%">Tipo_contrato</th>';
	$tabla .= '<th width="6.58%">Horario</th>';
	$tabla .= '<th width="4.70%">Cargo</th>';
	$tabla .= '<th width="10.34%">Sueldo base</th>';
	$tabla .= '<th width="11.28%">Fecha inicio</th>';
	$tabla .= '<th width="10.34%">Fecha final</th>';
	$tabla .= '<th width="9.40%">Forma pago</th>';
	$tabla .= '<th width="16.92%">Entidad_financiera</th>';
	$tabla .= '<th width="12.22%">Concepto_pago</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'contratos_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'CONTRATOS # ' . $id_contrato, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_tipo_contrato_id = escape($contratos['tipo_contrato_id']);
	$valor_horario = escape($contratos['horario']);
	$valor_cargo_id = escape($contratos['cargo_id']);
	$valor_sueldo_base = escape($contratos['sueldo_base']);
	$valor_fecha_inicio = date_decode($contratos['fecha_inicio'], $_format);
	$valor_fecha_final = escape($contratos['fecha_final']);
	$valor_forma_pago = escape($contratos['forma_pago']);
	$valor_entidad_financiera_id = escape($contratos['entidad_financiera_id']);
	$valor_concepto_pago_id = escape($contratos['concepto_pago_id']);
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Tipo_contrato:</th><td class="right">' . $valor_tipo_contrato_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Horario:</th><td class="right">' . $valor_horario . '</td></tr>';
	$tabla .= '<tr><th class="left">Cargo:</th><td class="right">' . $valor_cargo_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Sueldo base:</th><td class="right">' . $valor_sueldo_base . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha inicio:</th><td class="right">' . $valor_fecha_inicio . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha final:</th><td class="right">' . $valor_fecha_final . '</td></tr>';
	$tabla .= '<tr><th class="left">Forma pago:</th><td class="right">' . $valor_forma_pago . '</td></tr>';
	$tabla .= '<tr><th class="left">Entidad_financiera:</th><td class="right">' . $valor_entidad_financiera_id . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Concepto_pago:</th><td class="right">' . $valor_concepto_pago_id . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'contratos_' . $id_contrato . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>