<?php

// Obtiene los parametros
$id_dosificacion = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_dosificacion == 0) {
	// Obtiene las dosificaciones
	$dosificaciones = $db->select('d.*, s.nro_sucursal, s.sucursal')->from('fac_dosificaciones d')->join('gen_sucursales s', 'd.sucursal_id = s.id_sucursal', 'left')->order_by('d.fecha_dosificacion desc, d.hora_dosificacion desc')->fetch();

	// Ejecuta un error 404 si no existe las dosificaciones
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene la dosificacion
	$dosificacion = $db->select('d.*, s.nro_sucursal, s.sucursal')->from('fac_dosificaciones d')->join('gen_sucursales s', 'd.sucursal_id = s.id_sucursal', 'left')->where('d.id_dosificacion', $id_dosificacion)->fetch_first();
	
	// Ejecuta un error 404 si no existe la dosificacion
	if (!$dosificacion || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_dosificacion == 0) {
	// Adiciona la pagina
	$pdf->AddPage('L', 'LEGAL');
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'DOSIFICACIONES', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($dosificaciones as $nro => $dosificacion) {
		$vigencia = (now() > $dosificacion['fecha_limite']) ? 0 : intval(date_diff(date_create(now()), date_create($dosificacion['fecha_limite']))->format('%a')) + 1;
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($dosificaciones[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . date_decode($dosificacion['fecha_dosificacion'], $_format) . '<br>' . escape($dosificacion['hora_dosificacion']) . '</td>';
		$body .= '<td>Sucursal # ' . escape($dosificacion['nro_sucursal']) . '<br>' . escape($dosificacion['sucursal']) . '</td>';
		$body .= '<td>' . escape($dosificacion['nro_tramite']) . '</td>';
		$body .= '<td>' . escape($dosificacion['nro_autorizacion']) . '</td>';
		$body .= '<td>' . date_decode($dosificacion['fecha_limite'], $_format) . '</td>';
		$body .= '<td>' . escape($dosificacion['leyenda_factura']) . '</td>';
		$body .= '<td>' . (($vigencia == 0) ? 'Sin vigencia' : 'En uso') . '</td>';
		$body .= '<td align="right">' . $vigencia . '</td>';
		$body .= '<td align="right">' . escape($dosificacion['facturas_emitidas']) . '</td>';
		$body .= '<td>' . (($dosificacion['activo'] == 's') ? 'Si' : 'No') . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="11">No existen dosificaciones registradas en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="4%">#</th>';
	$tabla .= '<th width="8%">Fecha de dosificación</th>';
	$tabla .= '<th width="14%">Sucursal</th>';
	$tabla .= '<th width="10%">Número de trámite</th>';
	$tabla .= '<th width="12%">Número de autorización</th>';
	$tabla .= '<th width="8%">Fecha límite de emisión</th>';
	$tabla .= '<th width="19%">Leyenda de la factura</th>';
	$tabla .= '<th width="8%">Estado</th>';
	$tabla .= '<th width="6%">Días restantes</th>';
	$tabla .= '<th width="6%">Facturas emitidas</th>';
	$tabla .= '<th width="5%">Activo</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'dosificaciones_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'DOSIFICACIÓN # ' . $id_dosificacion, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);

	// Obtiene la vigencia
	$vigencia = (now() > $dosificacion['fecha_limite']) ? 0 : intval(date_diff(date_create(now()), date_create($dosificacion['fecha_limite']))->format('%a')) + 1;
	
	// Define las variables
	$valor_sucursal_id = escape($dosificacion['nro_sucursal']) . ' &mdash; ' . escape($dosificacion['sucursal']);
	$valor_fecha_dosificacion = date_decode($dosificacion['fecha_dosificacion'], $_format) . ' ' . escape($dosificacion['hora_dosificacion']);
	$valor_nro_tramite = escape($dosificacion['nro_tramite']);
	$valor_nro_autorizacion = escape($dosificacion['nro_autorizacion']);
	$valor_llave_dosificacion = base64_decode($dosificacion['llave_dosificacion']);
	$valor_fecha_limite = date_decode($dosificacion['fecha_limite'], $_format);
	$valor_leyenda_factura = escape($dosificacion['leyenda_factura']);
	$valor_leyenda_factura = escape($dosificacion['leyenda_factura']);
	$valor_observacion = ($dosificacion['observacion'] != '') ? escape($dosificacion['observacion']) : 'No asignado';
	$valor_estado = ($vigencia == 0) ? 'Sin vigencia' : 'En uso';
	$valor_dias_restantes = $vigencia;
	$valor_facturas_emitidas = escape($dosificacion['facturas_emitidas']);
	$valor_activo = ($dosificacion['activo'] == 's') ? 'Si' : 'No';
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Sucursal:</th><td class="right">Sucursal # ' . $valor_sucursal_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha y hora de dosificación:</th><td class="right">' . $valor_fecha_dosificacion . '</td></tr>';
	$tabla .= '<tr><th class="left">Número de trámite:</th><td class="right">' . $valor_nro_tramite . '</td></tr>';
	$tabla .= '<tr><th class="left">Número de autorización:</th><td class="right">' . $valor_nro_autorizacion . '</td></tr>';
	$tabla .= '<tr><th class="left">Llave de dosificación:</th><td class="right"><pre>' . $valor_llave_dosificacion . '</pre></td></tr>';
	$tabla .= '<tr><th class="left">Fecha límite de emisión:</th><td class="right">' . $valor_fecha_limite . '</td></tr>';
	$tabla .= '<tr><th class="left">Leyenda de la factura:</th><td class="right">' . $valor_leyenda_factura . '</td></tr>';
	$tabla .= '<tr><th class="left">Observación:</th><td class="right">' . $valor_observacion . '</td></tr>';
	$tabla .= '<tr><th class="left">Estado:</th><td class="right">' . $valor_estado . '</td></tr>';
	$tabla .= '<tr><th class="left">Días restantes:</th><td class="right">' . $valor_dias_restantes . '</td></tr>';
	$tabla .= '<tr><th class="left">Facturas emitidas:</th><td class="right">' . $valor_facturas_emitidas . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Activo:</th><td class="right">' . $valor_activo . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'dosificacion_' . $id_dosificacion . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>