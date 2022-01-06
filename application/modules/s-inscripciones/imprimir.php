<?php

// Obtiene los parametros
$id_pensiones = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_pensiones == 0) {
	// Obtiene los spensiones
	$spensiones = $db->select('z.*')->from('pen_pensiones z')->order_by('z.id_pensiones', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los spensiones
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el spensiones
	$spensiones = $db->select('z.*')->from('pen_pensiones z')->where('z.id_pensiones', $id_pensiones)->fetch_first();
	
	// Ejecuta un error 404 si no existe el spensiones
	if (!$spensiones || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_pensiones == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'PENSIONES', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($spensiones as $nro => $spensiones) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($spensiones[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($spensiones['nombre_pension']) . '</td>';
		$body .= '<td>' . escape($spensiones['descripcion']) . '</td>';
		$body .= '<td>' . escape($spensiones['monto']) . '</td>';
		$body .= '<td>' . escape($spensiones['mora_dia']) . '</td>';
		$body .= '<td>' . date_decode($spensiones['fecha_inicio'], $_format) . '</td>';
		$body .= '<td>' . date_decode($spensiones['fecha_final'], $_format) . '</td>';
		$body .= '<td>' . escape($spensiones['tipo_estudiante_id']) . '</td>';
		$body .= '<td>' . escape($spensiones['nivel_academico_id']) . '</td>';
		$body .= '<td>' . escape($spensiones['gestion_id']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="10">No existen pensiones registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="13.43%">Nombre pension</th>';
	$tabla .= '<th width="10.55%">Descripcion</th>';
	$tabla .= '<th width="4.80%">Monto</th>';
	$tabla .= '<th width="7.67%">Mora dia</th>';
	$tabla .= '<th width="11.51%">Fecha inicio</th>';
	$tabla .= '<th width="10.55%">Fecha final</th>';
	$tabla .= '<th width="14.39%">Tipo_estudiante</th>';
	$tabla .= '<th width="14.39%">Nivel_academico</th>';
	$tabla .= '<th width="6.71%">Gestion</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'spensiones_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'PENSIONES # ' . $id_pensiones, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_nombre_pension = escape($spensiones['nombre_pension']);
	$valor_descripcion = escape($spensiones['descripcion']);
	$valor_monto = escape($spensiones['monto']);
	$valor_mora_dia = escape($spensiones['mora_dia']);
	$valor_fecha_inicio = date_decode($spensiones['fecha_inicio'], $_format);
	$valor_fecha_final = date_decode($spensiones['fecha_final'], $_format);
	$valor_tipo_estudiante_id = escape($spensiones['tipo_estudiante_id']);
	$valor_nivel_academico_id = escape($spensiones['nivel_academico_id']);
	$valor_gestion_id = escape($spensiones['gestion_id']);
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Nombre pension:</th><td class="right">' . $valor_nombre_pension . '</td></tr>';
	$tabla .= '<tr><th class="left">Descripcion:</th><td class="right">' . $valor_descripcion . '</td></tr>';
	$tabla .= '<tr><th class="left">Monto:</th><td class="right">' . $valor_monto . '</td></tr>';
	$tabla .= '<tr><th class="left">Mora dia:</th><td class="right">' . $valor_mora_dia . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha inicio:</th><td class="right">' . $valor_fecha_inicio . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha final:</th><td class="right">' . $valor_fecha_final . '</td></tr>';
	$tabla .= '<tr><th class="left">Tipo_estudiante:</th><td class="right">' . $valor_tipo_estudiante_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Nivel_academico:</th><td class="right">' . $valor_nivel_academico_id . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Gestion:</th><td class="right">' . $valor_gestion_id . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'spensiones_' . $id_pensiones . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>