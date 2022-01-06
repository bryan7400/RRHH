<?php

// Obtiene los parametros
$id_aula_paralelo = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_aula_paralelo == 0) {
	// Obtiene los aula_paralelo
	$aula_paralelo = $db->select('z.*, a.nombre_paralelo as paralelo')->from('ins_aula_paralelo z')->join('ins_paralelo a', 'z.paralelo_id = a.id_paralelo', 'left')->order_by('z.id_aula_paralelo', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los aula_paralelo
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el aula_paralelo
	$aula_paralelo = $db->select('z.*, a.nombre_paralelo as paralelo')->from('ins_aula_paralelo z')->join('ins_paralelo a', 'z.paralelo_id = a.id_paralelo', 'left')->where('z.id_aula_paralelo', $id_aula_paralelo)->fetch_first();
	
	// Ejecuta un error 404 si no existe el aula_paralelo
	if (!$aula_paralelo || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_aula_paralelo == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'ASIGNACION DE AULA Y PARALELO', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($aula_paralelo as $nro => $aula_paralelo) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($aula_paralelo[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['aula_id']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['paralelo']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['capacidad']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['estado']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['usuario_registro']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['fecha_registro']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['usuario_modificacion']) . '</td>';
		$body .= '<td>' . escape($aula_paralelo['fecha_modificacion']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="9">No existen aula paralelo registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="3.96%">Aula</th>';
	$tabla .= '<th width="7.92%">Paralelo</th>';
	$tabla .= '<th width="8.91%">Capacidad</th>';
	$tabla .= '<th width="5.94%">Estado</th>';
	$tabla .= '<th width="15.83%">Usuario registro</th>';
	$tabla .= '<th width="13.85%">Fecha registro</th>';
	$tabla .= '<th width="19.79%">Usuario modificacion</th>';
	$tabla .= '<th width="17.81%">Fecha modificacion</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'aula_paralelo_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'AULA PARALELO # ' . $id_aula_paralelo, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_aula_id = escape($aula_paralelo['aula_id']);
	$valor_paralelo_id = escape($aula_paralelo['paralelo']);
	$valor_capacidad = escape($aula_paralelo['capacidad']);
	$valor_estado = escape($aula_paralelo['estado']);
	$valor_usuario_registro = ($aula_paralelo['usuario_registro'] != '') ? escape($aula_paralelo['usuario_registro']) : 'No asignado';
	$valor_fecha_registro = ($aula_paralelo['fecha_registro'] != '') ? escape($aula_paralelo['fecha_registro']) : 'No asignado';
	$valor_usuario_modificacion = ($aula_paralelo['usuario_modificacion'] != '') ? escape($aula_paralelo['usuario_modificacion']) : 'No asignado';
	$valor_fecha_modificacion = ($aula_paralelo['fecha_modificacion'] != '') ? escape($aula_paralelo['fecha_modificacion']) : 'No asignado';
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Aula:</th><td class="right">' . $valor_aula_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Paralelo:</th><td class="right">' . $valor_paralelo_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Capacidad:</th><td class="right">' . $valor_capacidad . '</td></tr>';
	$tabla .= '<tr><th class="left">Estado:</th><td class="right">' . $valor_estado . '</td></tr>';
	$tabla .= '<tr><th class="left">Usuario registro:</th><td class="right">' . $valor_usuario_registro . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha registro:</th><td class="right">' . $valor_fecha_registro . '</td></tr>';
	$tabla .= '<tr><th class="left">Usuario modificacion:</th><td class="right">' . $valor_usuario_modificacion . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Fecha modificacion:</th><td class="right">' . $valor_fecha_modificacion . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'aula_paralelo_' . $id_aula_paralelo . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>