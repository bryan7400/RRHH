<?php

// Obtiene los parametros
$id_materia = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_materia == 0) {
	// Obtiene los materia
	$materia = $db->select('z.*')->from('pro_materia z')->where('z.estado','A')->order_by('z.id_materia', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los materia
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el materia
	$materia = $db->select('z.*')->from('pro_materia z')->where('z.id_materia', $id_materia)->fetch_first();
	
	// Ejecuta un error 404 si no existe el materia
	if (!$materia || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_materia == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'MATERIA', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($materia as $nro => $materia) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($materia[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($materia['nombre_materia']) . '</td>';
		$body .= '<td>' . escape($materia['descripcion']) . '</td>';
		$body .= '<td>' . escape($materia['estado']) . '</td>';
		$body .= '<td>' . escape($materia['usuario_registro']) . '</td>';
		$body .= '<td>' . escape($materia['fecha_registro']) . '</td>';
		$body .= '<td>' . escape($materia['usuario_modificacion']) . '</td>';
		$body .= '<td>' . escape($materia['fecha_modificacion']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="8">No existen materia registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="13.29%">Nombre materia</th>';
	$tabla .= '<th width="10.44%">Descripcion</th>';
	$tabla .= '<th width="5.70%">Estado</th>';
	$tabla .= '<th width="15.19%">Usuario registro</th>';
	$tabla .= '<th width="13.29%">Fecha registro</th>';
	$tabla .= '<th width="18.99%">Usuario modificacion</th>';
	$tabla .= '<th width="17.09%">Fecha modificacion</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'materia_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'MATERIA # ' . $id_materia, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_nombre_materia = escape($materia['nombre_materia']);
	$valor_descripcion = escape($materia['descripcion']);
	$valor_estado = escape($materia['estado']);
	$valor_usuario_registro = ($materia['usuario_registro'] != '') ? escape($materia['usuario_registro']) : 'No asignado';
	$valor_fecha_registro = ($materia['fecha_registro'] != '') ? escape($materia['fecha_registro']) : 'No asignado';
	$valor_usuario_modificacion = ($materia['usuario_modificacion'] != '') ? escape($materia['usuario_modificacion']) : 'No asignado';
	$valor_fecha_modificacion = ($materia['fecha_modificacion'] != '') ? escape($materia['fecha_modificacion']) : 'No asignado';
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Nombre materia:</th><td class="right">' . $valor_nombre_materia . '</td></tr>';
	$tabla .= '<tr><th class="left">Descripcion:</th><td class="right">' . $valor_descripcion . '</td></tr>';
	$tabla .= '<tr><th class="left">Estado:</th><td class="right">' . $valor_estado . '</td></tr>';
	$tabla .= '<tr><th class="left">Usuario registro:</th><td class="right">' . $valor_usuario_registro . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha registro:</th><td class="right">' . $valor_fecha_registro . '</td></tr>';
	$tabla .= '<tr><th class="left">Usuario modificacion:</th><td class="right">' . $valor_usuario_modificacion . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Fecha modificacion:</th><td class="right">' . $valor_fecha_modificacion . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'materia_' . $id_materia . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>