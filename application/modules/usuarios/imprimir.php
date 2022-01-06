<?php

// Obtiene los parametros
$id_gestion = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_gestion == 0) {
	// Obtiene los gestiones
	$gestiones = $db->select('z.*')->from('ins_gestion z')->where('z.estado', 'A')->order_by('z.id_gestion', 'asc')->fetch();
	//$db->query("SELECT * FROM `ins_gestion` WHERE estado='A'")->execute();
	// Ejecuta un error 404 si no existe los gestiones
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el gestion
	$gestion = $db->select('z.*')->from('ins_gestion z')->where('z.id_gestion', $id_gestion)->where('z.estado', 'A')->fetch_first();
	

	// Ejecuta un error 404 si no existe el gestion
	if (!$gestion || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_gestion == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'GESTIONES', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($gestiones as $nro => $gestion) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($gestiones[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($gestion['gestion']) . '</td>';
		$body .= '<td>' . date_decode($gestion['inicio_gestion'], $_format) . '</td>';
		$body .= '<td>' . date_decode($gestion['final_gestion'], $_format) . '</td>';
		$body .= '<td>' . date_decode($gestion['inicio_vacaciones'], $_format) . '</td>';
		$body .= '<td>' . date_decode($gestion['final_vacaciones'], $_format) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="6">No existen gestiones registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="9.82%">Gestion</th>';
	$tabla .= '<th width="19.64%">Inicio gestion</th>';
	$tabla .= '<th width="18.24%">Final gestion</th>';
	$tabla .= '<th width="23.85%">Inicio vacaciones</th>';
	$tabla .= '<th width="22.45%">Final vacaciones</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'gestiones_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'GESTION # ' . $id_gestion, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_gestion = escape($gestion['gestion']);
	$valor_inicio_gestion = date_decode($gestion['inicio_gestion'], $_format);
	$valor_final_gestion = date_decode($gestion['final_gestion'], $_format);
	$valor_inicio_vacaciones = date_decode($gestion['inicio_vacaciones'], $_format);
	$valor_final_vacaciones = date_decode($gestion['final_vacaciones'], $_format);
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Gestion:</th><td class="right">' . $valor_gestion . '</td></tr>';
	$tabla .= '<tr><th class="left">Inicio gestion:</th><td class="right">' . $valor_inicio_gestion . '</td></tr>';
	$tabla .= '<tr><th class="left">Final gestion:</th><td class="right">' . $valor_final_gestion . '</td></tr>';
	$tabla .= '<tr><th class="left">Inicio vacaciones:</th><td class="right">' . $valor_inicio_vacaciones . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Final vacaciones:</th><td class="right">' . $valor_final_vacaciones . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'gestion_' . $id_gestion . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>