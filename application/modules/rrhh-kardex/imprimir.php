<?php
$persona = (isset($_params[0])) ? $_params[0] : 0;

$_id_gestion = (isset($_params[1])) ? $_params[1] : 0;

$felicitacions = $db->query("SELECT * FROM per_kardex_personal  WHERE  estado='A' AND persona_id=$persona AND tipo_kardex='felicitacion' AND gestion=$_id_gestion")->fetch();//


$sancions = $db->query("SELECT * FROM per_kardex_personal  WHERE  estado='A' AND persona_id=$persona AND tipo_kardex='sancion' AND gestion=$_id_gestion")->fetch();

/*
// Obtiene los parametros
$id_tipo_estudiante = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_tipo_estudiante == 0) {
	// Obtiene los stipoestudiante
	$stipoestudiante = $db->select('z.*')->from('ins_tipo_estudiante z')->order_by('z.id_tipo_estudiante', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los stipoestudiante
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el stipoestudiante
	$stipoestudiante = $db->select('z.*')->from('ins_tipo_estudiante z')->where('z.id_tipo_estudiante', $id_tipo_estudiante)->fetch_first();
	
	// Ejecuta un error 404 si no existe el stipoestudiante
	if (!$stipoestudiante || !$permiso_ver) { require_once not_found(); exit; }
}
*/
// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros

	$pdf->SetPageOrientation('P');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 50, 'Kardex De Personal', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	

	// Define el contenido de la tabla
	$body = '';
	


						
	
	$pdf->Cell(0, 50, 'FELITACIONES', 0, true, 'C', false, '', 0, false, 'T', 'M');





	foreach ($felicitacions as $nro => $empleado) {


	


			$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($empleado[$nro + 1])) ? '' : ' last') . '">';
			$body .= '<td>' . ($nro + 1) . '</td>';
			
			$body .= '<td>' . escape($empleado['fecha_kardex']) . '</td>';
			$body .= '<td>' . escape($empleado['concepto_kardex']) . '</td>';
			$body .= '<td>' . escape($empleado['observacion_kardex']) . '</td>';
		
			
			$body .= '</tr>';
		} 
			
			// Verifica el contenido de la tabla
			$body = ($body == '') ? '<tr class="last"><td colspan="5">No existen tipo estudiante registrados en la base de datos.</td></tr>' : $body;
			
			// Define el formato de la tabla
			$tabla = $style;
			$tabla .= '<table cellpadding="5">';
			$tabla .= '<tr class="first last">';
			$tabla .= '<th width="3%"><B>#</B></th>';
			$tabla .= '<th width="30%"><B>Fecha_kardex</B></th>';
			$tabla .= '<th width="30%"><B>Concepto</B></th>';
			$tabla .= '<th width="30%"><B>Observacion </B></th>';
			$tabla .= '</tr>';
			$tabla .= $body;
			$tabla .= '</table>';


		
			$body1 = '';
	


		foreach ($sancions as $nro => $empleado) {


			
	

			$body1 .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($empleado[$nro + 1])) ? '' : ' last') . '">';
			$body1 .= '<td>' . ($nro + 1) . '</td>';
			
			$body1 .= '<td>' . escape($empleado['fecha_kardex']) . '</td>';
			$body1 .= '<td>' . escape($empleado['concepto_kardex']) . '</td>';
			$body1 .= '<td>' . escape($empleado['observacion_kardex']) . '</td>';
		
	
		
			
			$body1 .= '</tr>';
		}
			
			// Verifica el contenido de la tabla
			$body1 = ($body1 == '') ? '<tr class="last"><td colspan="5">No existen tipo estudiante registrados en la base de datos.</td></tr>' : $body1;
			
			// Define el formato de la tabla
			$tabla1 = $style;
			$tabla1 .= '<table cellpadding="5">';
			$tabla1 .= '<tr class="first last">';
			$tabla1 .= '<th width="3%"><B>#</B></th>';
			$tabla1 .= '<th width="30%"><B>Fecha_kardex</B></th>';
			$tabla1 .= '<th width="30%"><B>Concepto</B></th>';
			$tabla1 .= '<th width="30%"><B>Observacion </B></th>';
			$tabla1 .= '</tr>';
			$tabla1 .= $body1;
			$tabla1 .= '</table>';


		

	

	


	// Imprime la tabla
	




	// Construye la estructura del contenido de la tabla
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');

	$pdf->Cell(0, 50, 'SANCIONES', 0, true, 'C', false, '', 0, false, 'T', 'M');



	$pdf->writeHTML($tabla1, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'stipoestudiante_' . date('Y-m-d_H-i-s') . '.pdf';


	 
$nombre="";

$pdf->Output($nombre, 'I');

?>