<?php

// Obtiene los parametros
$id_familiar = (isset($_params[0])) ? $_params[0] : 0;


// Verifica si existen los parametros
if ($id_familiar == 0) {
	// Obtiene los familiar
	$familiar =  $db->query("SELECT *
                            FROM ins_familiar AS sf
                            INNER JOIN sys_persona AS sp ON sp.id_persona = sf.persona_id")->fetch();

	

} else {
	// Obtiene el familiar
	$familiar = $db->select('z.*')->from('ins_familiar z')->where('z.id_familiar', $id_familiar)->fetch_first();
	

}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_familiar == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'FAMILIAR', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($familiar as $nro => $familiar) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($familiar[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($familiar['profesion']) . '</td>';
		$body .= '<td>' . escape($familiar['direccion_oficina']) . '</td>';
		$body .= '<td>' . escape($familiar['telefono_oficina']) . '</td>';
		$body .= '<td>' . escape($familiar['persona_id']) . '</td>';
		$body .= '<td>' . escape($familiar['estado']) . '</td>';
		$body .= '<td>' . escape($familiar['usuario_registro']) . '</td>';
		$body .= '<td>' . escape($familiar['fecha_registro']) . '</td>';
		$body .= '<td>' . escape($familiar['usuario_modificacion']) . '</td>';
		$body .= '<td>' . escape($familiar['fecha_modificacion']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="10">No existen familiar registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="6.88%">Profesion</th>';
	$tabla .= '<th width="12.99%">Direccion oficina</th>';
	$tabla .= '<th width="12.23%">Telefono oficina</th>';
	$tabla .= '<th width="5.35%">Persona</th>';
	$tabla .= '<th width="4.59%">Estado</th>';
	$tabla .= '<th width="12.23%">Usuario registro</th>';
	$tabla .= '<th width="10.70%">Fecha registro</th>';
	$tabla .= '<th width="15.28%">Usuario modificacion</th>';
	$tabla .= '<th width="13.76%">Fecha modificacion</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'familiar_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'FAMILIAR # ' . $id_familiar, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_profesion = escape($familiar['profesion']);
	$valor_direccion_oficina = escape($familiar['direccion_oficina']);
	$valor_telefono_oficina = escape($familiar['telefono_oficina']);
	$valor_persona_id = escape($familiar['persona_id']);
	$valor_estado = escape($familiar['estado']);
	$valor_usuario_registro = ($familiar['usuario_registro'] != '') ? escape($familiar['usuario_registro']) : 'No asignado';
	$valor_fecha_registro = ($familiar['fecha_registro'] != '') ? escape($familiar['fecha_registro']) : 'No asignado';
	$valor_usuario_modificacion = ($familiar['usuario_modificacion'] != '') ? escape($familiar['usuario_modificacion']) : 'No asignado';
	$valor_fecha_modificacion = ($familiar['fecha_modificacion'] != '') ? escape($familiar['fecha_modificacion']) : 'No asignado';
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Profesion:</th><td class="right">' . $valor_profesion . '</td></tr>';
	$tabla .= '<tr><th class="left">Direccion oficina:</th><td class="right">' . $valor_direccion_oficina . '</td></tr>';
	$tabla .= '<tr><th class="left">Telefono oficina:</th><td class="right">' . $valor_telefono_oficina . '</td></tr>';
	$tabla .= '<tr><th class="left">Persona:</th><td class="right">' . $valor_persona_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Estado:</th><td class="right">' . $valor_estado . '</td></tr>';
	$tabla .= '<tr><th class="left">Usuario registro:</th><td class="right">' . $valor_usuario_registro . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha registro:</th><td class="right">' . $valor_fecha_registro . '</td></tr>';
	$tabla .= '<tr><th class="left">Usuario modificacion:</th><td class="right">' . $valor_usuario_modificacion . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Fecha modificacion:</th><td class="right">' . $valor_fecha_modificacion . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'familiar_' . $id_familiar . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>