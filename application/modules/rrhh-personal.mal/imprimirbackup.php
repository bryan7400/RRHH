<?php

// Obtiene los parametros
$id_horario = (isset($params[0])) ? $params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_horario == 0) {
	// Obtiene los horarios
	$horarios = $db->from('asi_dias_feriados')->where('estado','A')->order_by('id_dias_feriados', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los horarios
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el horario
	$horario = $db->from('asi_dias_feriados')->where('id_dias_feriados', $id_horario)->fetch_first();
	
	// Ejecuta un error 404 si no existe el horario
	if (!$horario || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_horario == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'FERIADOS', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($horarios as $nro => $horario) {
		//$dias = str_replace(',', ' / ', preg_replace(array('/2/', '/3/', '/4/', '/5/', '/6/', '/7/', '/1/'), array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'), $horario['dias']));
        
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($horarios[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
	 
		$body .= '<td>' . escape($horario['fecha_inicio']) . '</td>';
		$body .= '<td>' . escape($horario['fecha_final']) . '</td>';
		$body .= '<td>' . escape($horario['descripcion']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="5">No existen horarios registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="44%">FECHA INICIO</th>';
	$tabla .= '<th width="20%">FECHA FINAL</th>';
	$tabla .= '<th width="20%">DESCRIPCION</th>'; 
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'horarios_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	/*$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'CARGOS 2 # ' . $id_horario, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	//$valor_dias = escape(str_replace(',', ' / ', preg_replace(array('/2/', '/3/', '/4/', '/5/', '/6/', '/7/', '/1/'), array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'), $horario['dias'])));
	//$valor_entrada = escape($horario['entrada']);
	//$valor_salida = escape($horario['salida']);
	//$valor_descripcion = ($horario['descripcion'] != '') ? //escape($horario['descripcion']) : 'No asignado';
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Cargo:</th><td class="right">' . $valor_dias . '</td></tr>';
	$tabla .= '<tr><th class="left">Obligacion:</th><td class="right">' . $valor_entrada . '</td></tr>';
	$tabla .= '<tr><th class="left">Descripcion:</th><td class="right">' . $valor_salida . '</td></tr>';
	 
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'horario_' . $id_horario . '_' . date('Y-m-d_H-i-s') . '.pdf';*/
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>