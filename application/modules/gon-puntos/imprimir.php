<?php

// Obtiene los parametros
$id_punto = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_punto == 0) {
	// Obtiene los puntos
	$puntos = $db->select('z.*')->from('gon_puntos z')->order_by('z.id_punto', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los puntos
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el puntos
	$puntos = $db->select('z.*')->from('gon_puntos z')->where('z.id_punto', $id_punto)->fetch_first();
	
	// Ejecuta un error 404 si no existe el puntos
	if (!$puntos || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_punto == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'PUNTOS', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($puntos as $nro => $puntos) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($puntos[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($puntos['descripcion']) . '</td>';
		$body .= '<td>' . escape($puntos['latitud']) . '</td>';
		$body .= '<td>' . escape($puntos['longitud']) . '</td>';
		$body .= '<td>' . escape($puntos['imagen_lugar']) . '</td>';
		$body .= '<td>' . escape($puntos['nombre_lugar']) . '</td>';
		$body .= '<td>' . escape($puntos['estado']) . '</td>';
		$body .= '<td>' . escape($puntos['ruta_id']) . '</td>';
		$body .= '<td>' . escape($puntos['usuario_registro']) . '</td>';
		$body .= '<td>' . escape($puntos['fecha_registro']) . '</td>';
		$body .= '<td>' . escape($puntos['usuario_modificacion']) . '</td>';
		$body .= '<td>' . escape($puntos['fecha_modificacion']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="12">No existen puntos registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="8.08%">Descripcion</th>';
	$tabla .= '<th width="5.14%">Latitud</th>';
	$tabla .= '<th width="5.88%">Longitud</th>';
	$tabla .= '<th width="8.81%">Imagen lugar</th>';
	$tabla .= '<th width="8.81%">Nombre lugar</th>';
	$tabla .= '<th width="4.41%">Estado</th>';
	$tabla .= '<th width="2.94%">Ruta</th>';
	$tabla .= '<th width="11.75%">Usuario registro</th>';
	$tabla .= '<th width="10.28%">Fecha registro</th>';
	$tabla .= '<th width="14.69%">Usuario modificacion</th>';
	$tabla .= '<th width="13.22%">Fecha modificacion</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'puntos_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'PUNTOS # ' . $id_punto, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_descripcion = escape($puntos['descripcion']);
	$valor_latitud = escape($puntos['latitud']);
	$valor_longitud = escape($puntos['longitud']);
	$valor_imagen_lugar = escape($puntos['imagen_lugar']);
	$valor_nombre_lugar = escape($puntos['nombre_lugar']);
	$valor_estado = escape($puntos['estado']);
	$valor_ruta_id = escape($puntos['ruta_id']);
	$valor_usuario_registro = escape($puntos['usuario_registro']);
	$valor_fecha_registro = escape($puntos['fecha_registro']);
	$valor_usuario_modificacion = escape($puntos['usuario_modificacion']);
	$valor_fecha_modificacion = escape($puntos['fecha_modificacion']);
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Descripcion:</th><td class="right">' . $valor_descripcion . '</td></tr>';
	$tabla .= '<tr><th class="left">Latitud:</th><td class="right">' . $valor_latitud . '</td></tr>';
	$tabla .= '<tr><th class="left">Longitud:</th><td class="right">' . $valor_longitud . '</td></tr>';
	$tabla .= '<tr><th class="left">Imagen lugar:</th><td class="right">' . $valor_imagen_lugar . '</td></tr>';
	$tabla .= '<tr><th class="left">Nombre lugar:</th><td class="right">' . $valor_nombre_lugar . '</td></tr>';
	$tabla .= '<tr><th class="left">Estado:</th><td class="right">' . $valor_estado . '</td></tr>';
	$tabla .= '<tr><th class="left">Ruta:</th><td class="right">' . $valor_ruta_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Usuario registro:</th><td class="right">' . $valor_usuario_registro . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha registro:</th><td class="right">' . $valor_fecha_registro . '</td></tr>';
	$tabla .= '<tr><th class="left">Usuario modificacion:</th><td class="right">' . $valor_usuario_modificacion . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Fecha modificacion:</th><td class="right">' . $valor_fecha_modificacion . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'puntos_' . $id_punto . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>