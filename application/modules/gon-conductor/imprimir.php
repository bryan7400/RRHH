<?php

$gondolas =  $db->select('z.*, a.*')
				->from('gon_conductor z')
				->join('sys_persona a','z.persona_id = a.id_persona')
                ->order_by('z.id_conductor', 'asc')
				->fetch();

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros

	$pdf->SetPageOrientation('P');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 12, 'LISTADO DE CONDUCTOR', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(12);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', 9);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($gondolas as $nro => $gondola) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($gondola[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($gondola['nombres'].' '.$gondola['primer_apellido'].' '.$gondola['segundo_apellido']) . '</td>';
		$body .= '<td>' . escape($gondola['categoria']) . '</td>';
		$body .= '<td>' . escape($gondola['lentes']) . '</td>';
		$body .= '<td>' . escape($gondola['audifonos']) . '</td>';
		$body .= '<td>' . escape($gondola['grupo_sanguineo']) . '</td>';
		$body .= '<td>' . escape($gondola['fecha_emision']) . '</td>';
		$body .= '<td>' . escape($gondola['fecha_vencimiento']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="5">No existen tipo estudiante registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="5%"><B>#</B></th>';
	$tabla .= '<th width="32%"><B>Nombre</B></th>';
	$tabla .= '<th width="10%"><B>Categoria</B></th>';
	$tabla .= '<th width="10%"><B>Lentes</B></th>';
	$tabla .= '<th width="10%"><B>Audifonos</B></th>';
	$tabla .= '<th width="11%"><B>Grupo Sanguineo</B></th>';
	$tabla .= '<th width="11%"><B>Fecha Emision</B></th>';
	$tabla .= '<th width="11%"><B>Fecha Vencimiento</B></th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'stipoestudiante_' . date('Y-m-d_H-i-s') . '.pdf';

/*} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'TIPO ESTUDIANTE # ' . $id_tipo_estudiante, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_nombre_tipo_estudiante = escape($stipoestudiante['nombre_tipo_estudiante']);
	$valor_descripcion = escape($stipoestudiante['descripcion']);
	$valor_fecha_registro = escape($stipoestudiante['fecha_registro']);
	$valor_gestion_id = escape($stipoestudiante['gestion_id']);
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Nombre tipo estudiante:</th><td class="right">' . $valor_nombre_tipo_estudiante . '</td></tr>';
	$tabla .= '<tr><th class="left">Descripcion:</th><td class="right">' . $valor_descripcion . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha registro:</th><td class="right">' . $valor_fecha_registro . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Gestion:</th><td class="right">' . $valor_gestion_id . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'stipoestudiante_' . $id_tipo_estudiante . '_' . date('Y-m-d_H-i-s') . '.pdf';
}*/

// Cierra y devuelve el fichero pdf
$nombre="";
$pdf->Output($nombre, 'I');

?>