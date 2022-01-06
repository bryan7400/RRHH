<?php

// Obtiene los parametros
$id_aula = (isset($_params[0])) ? $_params[0] : 0;
$id_gestion=$_gestion['id_gestion']; 
// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);


    
$sql_cursos = "SELECT ia.id_aula, ia.nombre_aula, ia.descripcion, ina.id_nivel_academico,ina.nombre_nivel, ia.orden
                FROM ins_aula AS ia
                INNER JOIN ins_nivel_academico AS ina ON ina.id_nivel_academico = ia.nivel_academico_id
                WHERE ia.gestion_id = $id_gestion AND ia.estado = 'A' AND ina.estado = 'A'
                ORDER	BY ina.orden_nivel ASC, ia.orden ASC, ia.id_aula ASC";
$aulas = $db->query($sql_cursos)->fetch();


// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');//h l

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	$pdf->SetFont('roboto', 'B', 12);
	// Define el titulo del documento
	$pdf->Ln(20);
	$pdf->Cell(0, 10, "GRADOS ACADEMICÓS", 0, true, 'C', 0, '', 1);
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
    //	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	
	$pdf->SetFont('roboto', '', 10);
	// Define el contenido de la tabla
	$body = '';
	
	//var_dump($aulas);exit();
	
	// Construye la estructura del contenido de la tabla
	foreach ($aulas as $nro => $aula) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($aula[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($aula['nombre_aula']) . '</td>';
		$body .= '<td>' . escape($aula['descripcion']) . '</td>';
		$body .= '<td>' . escape($aula['nombre_nivel']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="9">No existen aula registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5"  border="1">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="10%">#</th>';
	$tabla .= '<th width="30%">NOMBRE AULA</th>';
	$tabla .= '<th width="40%">DESCRIPCION</th>';
	$tabla .= '<th width="20%">NIVEL ACADEMICO</th>';
    $tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	
	//var_dump($tabla);exit();
	
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'aula_' . date('Y-m-d_H-i-s') . '.pdf';

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>