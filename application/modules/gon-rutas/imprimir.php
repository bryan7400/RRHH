<?php

// Obtiene los parametros
$id_ruta = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_ruta == 0) {
	// Obtiene los rutas
	$rutas = $db->select('z.*')->from('gon_rutas z')->order_by('z.id_ruta', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los rutas
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el rutas
	$rutas = $db->select('z.*')->from('gon_rutas z')->where('z.id_ruta', $id_ruta)->fetch_first();
	
	// Ejecuta un error 404 si no existe el rutas
	if (!$rutas || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_ruta == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'RUTAS', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($rutas as $nro => $rutas) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($rutas[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($rutas['nombre']) . '</td>';
		$body .= '<td>' . escape($rutas['descripcion']) . '</td>';
		$body .= '<td>' . escape($rutas['punto_id']) . '</td>';
		$body .= '<td>' . escape($rutas['estado']) . '</td>';
		$body .= '<td>' . escape($rutas['usario_registro']) . '</td>';
		$body .= '<td>' . escape($rutas['fecha_registro']) . '</td>';
		$body .= '<td>' . escape($rutas['usario_modificacion']) . '</td>';
		$body .= '<td>' . escape($rutas['fecha_modificacion']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="9">No existen rutas registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="6.00%">Nombre</th>';
	$tabla .= '<th width="11.00%">Descripcion</th>';
	$tabla .= '<th width="5.00%">Punto</th>';
	$tabla .= '<th width="6.00%">Estado</th>';
	$tabla .= '<th width="15.00%">Usario registro</th>';
	$tabla .= '<th width="14.00%">Fecha registro</th>';
	$tabla .= '<th width="19.00%">Usario modificacion</th>';
	$tabla .= '<th width="18.00%">Fecha modificacion</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'rutas_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'RUTAS # ' . $id_ruta, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_nombre = escape($rutas['nombre']);
	$valor_descripcion = escape($rutas['descripcion']);
	$valor_punto_id = escape($rutas['punto_id']);
	$valor_estado = escape($rutas['estado']);
	$valor_usario_registro = escape($rutas['usario_registro']);
	$valor_fecha_registro = escape($rutas['fecha_registro']);
	$valor_usario_modificacion = escape($rutas['usario_modificacion']);
	$valor_fecha_modificacion = escape($rutas['fecha_modificacion']);
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Nombre:</th><td class="right">' . $valor_nombre . '</td></tr>';
	$tabla .= '<tr><th class="left">Descripcion:</th><td class="right">' . $valor_descripcion . '</td></tr>';
	$tabla .= '<tr><th class="left">Punto:</th><td class="right">' . $valor_punto_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Estado:</th><td class="right">' . $valor_estado . '</td></tr>';
	$tabla .= '<tr><th class="left">Usario registro:</th><td class="right">' . $valor_usario_registro . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha registro:</th><td class="right">' . $valor_fecha_registro . '</td></tr>';
	$tabla .= '<tr><th class="left">Usario modificacion:</th><td class="right">' . $valor_usario_modificacion . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Fecha modificacion:</th><td class="right">' . $valor_fecha_modificacion . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'rutas_' . $id_ruta . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>