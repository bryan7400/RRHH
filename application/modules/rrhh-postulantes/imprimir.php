<?php

$fecha_inicio_filtro = (isset($_params[0])) ? $_params[0] : 0;

$fecha_final_filtro = (isset($_params[1])) ? $_params[1] : 0;

if ($fecha_inicio_filtro=="0") {
    $postulacions = $db->select('z.*, c.*')
                ->from('per_postulacion z')
                ->join('per_cargos c', 'c.id_cargo=z.cargo_id', 'left')
                ->where('z.estado', 'A')
                ->order_by('z.id_postulacion', 'asc')
                ->fetch();

    //var_dump($contratos);die;
    

    
}else{
    $fecha_inicio  = $fecha_inicio_filtro;
    $fecha_final  = $fecha_final_filtro;
//var_dump($_POST);die;


        $postulacions = $db->query("SELECT * FROM per_postulacion z 
        LEFT JOIN per_cargos c ON c.id_cargo=z.cargo_id
        WHERE  date(z.fecha_registro) BETWEEN '$fecha_inicio'AND '$fecha_final'
        ORDER BY id_postulacion ASC")->fetch();



        
}   


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
	$pdf->Cell(0, 15, 'LISTADO DE postulacionS', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($postulacions as $nro => $postulacion) {
	$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($postulacion[$nro + 1])) ? '' : ' last') . '">';
	$body .= '<td>' . ($nro + 1) . '</td>';
	$body .= '<td>' . escape($postulacion['nombre']) . ' ' . escape($postulacion['paterno']) . ' ' . escape($postulacion['materno']) . '</td>';
	$body .= '<td>' . escape($postulacion['ci']) . '</td>';
	$body .= '<td>' . escape($postulacion['genero']) . '</td>';
	$body .= '<td>' . escape($postulacion['fecha_nacimiento']) . '</td>';
	if ($postulacion['personal'] =='A') {
                        $body .= '<td><span style="color:#009975"> Personal </span></td>';
                    } else {
                    	$body .= '<td><span style="color:#009975"> Postulacion </span></td>';
                       
                    }


	$body .= '<td>' . escape($postulacion['celular']) . '</td>';
	$body .= '<td>' . escape($postulacion['cargo']) . '</td>';
	$body .= '<td>' . escape($postulacion['fecha_registro']) . '</td>';

	
	$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="5">No existen tipo estudiante registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="3%"><B>#</B></th>';
	$tabla .= '<th width="20%"><B>Nombre</B></th>';
	$tabla .= '<th width="12%"><B>CI</B></th>';
	$tabla .= '<th width="12%"><B>Genero </B></th>';
	$tabla .= '<th width="12%"><B>Fecha Nacimiento</B></th>';
	$tabla .= '<th width="12%"><B>Estado</B></th>';
	$tabla .= '<th width="8%"><B>Telefono</B></th>';
	$tabla .= '<th width="12%"><B>Cargo</B></th>';
	$tabla .= '<th width="10%"><B>Fecha Postulacion</B></th>';
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