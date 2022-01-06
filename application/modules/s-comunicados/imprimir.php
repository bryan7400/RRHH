<?php

// Obtiene los parametros
$id_comunidado = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_comunidado == 0) {
	// Obtiene los comunidados
	$comunidados = $db->select('z.*')->from('ins_comunicados z')->order_by('z.id_comunicado', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los comunidados
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el comunidados
	$comunidados = $db->select('z.*')->from('ins_comunicados z')->where('z.id_comunicado', $id_comunidado)->fetch_first();
	
	// Ejecuta un error 404 si no existe el comunidados
	if (!$comunidados || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_comunidado == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'COMUNICADOS', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($comunidados as $nro => $comunidados) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($comunidados[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($comunidados['fecha_inicio']) . '</td>';
		$body .= '<td>' . escape($comunidados['fecha_final']) . '</td>';
		$body .= '<td>' . escape($comunidados['nombre_evento']) . '</td>';
		$body .= '<td>' . escape($comunidados['descripcion']) . '</td>';
		$body .= '<td>' . escape($comunidados['color']) . '</td>';
		$body .= '<td>' . escape($comunidados['usuarios']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="7">No existen comunidados registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">Id comunidado</th>';
	$tabla .= '<th width="18.80%">Fecha inicio</th>';
	$tabla .= '<th width="17.23%">Fecha final</th>';
	$tabla .= '<th width="20.37%">Nombre evento</th>';
	$tabla .= '<th width="17.23%">Descripcion</th>';
	$tabla .= '<th width="7.83%">Color</th>';
	$tabla .= '<th width="12.53%">Usuarios</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'comunidados_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'COMUNICADOS # ' . $id_comunidado, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_fecha_inicio = escape($comunidados['fecha_inicio']);
	$valor_fecha_final = escape($comunidados['fecha_final']);
	$valor_nombre_evento = escape($comunidados['nombre_evento']);
	$valor_descripcion = escape($comunidados['descripcion']);
	$valor_color = escape($comunidados['color']);
	$valor_usuarios = escape($comunidados['usuarios']);
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Fecha inicio:</th><td class="right">' . $valor_fecha_inicio . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha final:</th><td class="right">' . $valor_fecha_final . '</td></tr>';
	$tabla .= '<tr><th class="left">Nombre evento:</th><td class="right">' . $valor_nombre_evento . '</td></tr>';
	$tabla .= '<tr><th class="left">Descripcion:</th><td class="right">' . $valor_descripcion . '</td></tr>';
	$tabla .= '<tr><th class="left">Color:</th><td class="right">' . $valor_color . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Usuarios:</th><td class="right">' . $valor_usuarios . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'comunicados_' . $id_comunicado . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>