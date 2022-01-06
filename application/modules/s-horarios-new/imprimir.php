<?php

// Obtiene los parametros
$id_aula_paralelo = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);
$id_gestion = $_gestion['id_gestion'];
// Verifica si existen los parametros
/*if ($id_aula_paralelo == 0) {
	// Obtiene los aula_paralelo
	$aula_paralelo = $db->query('SELECT  z.hora_inicio,
        z.hora_fin,
        ins_turno.nombre_turno,
        c.nombre_aula,
        e.nombre_paralelo,
        d.nombre_nivel,
        h.nombres,
        h.primer_apellido,
        h.segundo_apellido,
        i.nombre_materia,
        z.profesor_materia_id,
        z.id_horario_profesor_materia,
        z.curso_paralelo_id
  FROM ins_horario_profesor_materia z,
  
	ins_aula_paralelo b,
	ins_aula c,
	ins_nivel_academico d,
	ins_paralelo e,
	
	pro_profesor_materia f,
    pro_profesor g,
    sys_persona h,
    pro_materia i,
    ins_turno
WHERE z.curso_paralelo_id=b.id_aula_paralelo AND 
	ins_turno.id_turno=b.turno_id AND
	b.aula_id=c.id_aula AND 
	c.nivel_academico_id= d.id_nivel_academico AND
    b.paralelo_id= e.id_paralelo AND 
        
    z.profesor_materia_id=f.id_profesor_materia AND
    f.profesor_id= g.id_profesor AND
    g.persona_id=h.id_persona AND
    f.materia_id=i.id_materia and z.estado="A"')->fetch();
 
	if (!$permiso_listar) { require_once not_found(); exit; }*/
//} else {
	// Obtiene el aula_paralelo
 $aula_paralelo = $db->query("SELECT * 
FROM ins_horario_dia ho,ins_turno WHERE ho.estado='A'
AND ho.turno_id=ins_turno.id_turno AND ins_turno.`gestion_id`=$id_gestion AND ins_turno.`estado`='A' AND ho.`estado`='A' 
ORDER BY ho.turno_id ASC,ho.hora_ini ASC")->fetch();
	
	// Ejecuta un error 404 si no existe el aula_paralelo
	//if (!$aula_paralelo || !$permiso_ver) { //require_once not_found(); exit; }
//}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
//if ($id_aula_paralelo == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('h');//l H

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'HORARIOS', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', 7);
	
	// Define el contenido de la tabla
	$body = '';
	// Construye la estructura del contenido de la tabla
   $nro=1;
	foreach ($aula_paralelo as $rowHorario) {
		$body .= '<tr>';
		$body .= '<td>' . ($nro) . '</td>';
		$body .= '<td>' . ($rowHorario['hora_ini']) . '</td>';
		$body .= '<td>' . ($rowHorario['hora_fin']) . '</td>';
        $body .= '<td>' . ($rowHorario['complemento']) . '</td>';
		$body .= '<td>' . ($rowHorario['nombre_turno']) .'</td>';
		$body .= '</tr>';
        $nro++;
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="9">No existen aula paralelo registrados en la base de datos.</td></tr>' : $body; 
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5" border="1" style="text-align:center">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="10%">#</th>';
	$tabla .= '<th width="20%"><h3>HORA INICIO</h3></th>';
	$tabla .= '<th width="20%"><h3>HORA FIN</h3></th>';
	$tabla .= '<th width="30%"><h3>COMPLEMENTO</h3></th>';
	$tabla .= '<th width="20%"><h3>TURNO</h3></th>'; 	
  
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'HORARIOS_' . date('Y-m-d_H-i-s') . '.pdf';
/*} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'AULA PARALELO # ' . $id_aula_paralelo, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
 
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'aula_paralelo_' . $id_aula_paralelo . '_' . date('Y-m-d_H-i-s') . '.pdf';
}*/

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>