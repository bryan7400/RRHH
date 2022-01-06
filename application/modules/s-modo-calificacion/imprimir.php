<?php

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);
// Obtiene el id de la gestion actual 
$id_gestion=$_gestion['id_gestion']; 


$smodocalificacion = $db->query("SELECT * FROM cal_modo_calificacion AS cmc INNER JOIN ins_gestion as g ON g.id_gestion = cmc.gestion_id WHERE cmc.gestion_id = $id_gestion AND cmc.estado='A'")->fetch();


// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($smodocalificacion) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'MODO CALIFICACIÓN', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($smodocalificacion as $nro => $smodocalificacion) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($smodocalificacion[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . date_decode($smodocalificacion['fecha_inicio'], $_format) . '</td>';
		$body .= '<td>' . date_decode($smodocalificacion['fecha_final'], $_format) . '</td>';
		$body .= '<td>' . escape($smodocalificacion['descripcion']) . '</td>';
		$body .= '<td>' . escape($smodocalificacion['gestion']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="5">No existen modo calificacion registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5" border="0.5px">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="27.51%">Fecha inicio</th>';
	$tabla .= '<th width="25.22%">Fecha final</th>';
	$tabla .= '<th width="25.22%">Descripción</th>';
	$tabla .= '<th width="16.05%">Gestión</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'smodocalificacion_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'MODO CALIFICACION # ' . $id_modo_calificacion, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_fecha_inicio = date_decode($smodocalificacion['fecha_inicio'], $_format);
	$valor_fecha_final = date_decode($smodocalificacion['fecha_final'], $_format);
	$valor_descripcion = escape($smodocalificacion['descripcion']);
	$valor_gestion_id = escape($smodocalificacion['gestion_id']);
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Fecha inicio:</th><td class="right">' . $valor_fecha_inicio . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha final:</th><td class="right">' . $valor_fecha_final . '</td></tr>';
	$tabla .= '<tr><th class="left">Descripcion:</th><td class="right">' . $valor_descripcion . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Gestion:</th><td class="right">' . $valor_gestion_id . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'smodocalificacion_' . $id_modo_calificacion . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>