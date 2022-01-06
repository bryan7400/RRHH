<?php
$fecha_inicio_filtro = (isset($_params[0])) ? $_params[0] : 0;

$fecha_final_filtro = (isset($_params[1])) ? $_params[1] : 0;

if ($fecha_inicio_filtro=="0") {
$empleados = $db->query("SELECT asi.*, ca.cargo, e.*, p.*
                        FROM sys_persona e 
                        INNER JOIN per_asignaciones asi ON asi.persona_id = e.id_persona                                 
                        INNER JOIN per_postulacion p ON p.id_postulacion = e.postulante_id                                 
                        LEFT JOIN per_cargos ca ON asi.cargo_id = ca.id_cargo

                        LEFT JOIN ins_gestion g ON g.id_gestion='".$_gestion['id_gestion']."' 
                        
                        WHERE g.gestion >= YEAR(asi.fecha_inicio)
                                AND (g.gestion <= YEAR(asi.fecha_final)
                                OR asi.fecha_final = '0000-00-00')
                                AND asi.estado='A'                        
                        GROUP BY persona_id
                        ")->fetch();



}else{


   $fecha_inicio  = $fecha_inicio_filtro;
    $fecha_final  = $fecha_final_filtro;
//var_dump($_POST);die;
$empleados = $db->query("SELECT asi.*, ca.cargo, e.*, p.*
    FROM sys_persona e 
    INNER JOIN per_asignaciones asi ON asi.persona_id = e.id_persona                                 
    INNER JOIN per_postulacion p ON p.id_postulacion = e.postulante_id                                 
    LEFT JOIN per_cargos ca ON asi.cargo_id = ca.id_cargo

    LEFT JOIN ins_gestion g ON g.id_gestion='".$_gestion['id_gestion']."' 
    
    WHERE g.gestion >= YEAR(asi.fecha_inicio)
            AND (g.gestion <= YEAR(asi.fecha_final)
            OR asi.fecha_final = '0000-00-00')
            AND asi.estado='A'
            AND  date(asi.fecha_inicio) BETWEEN '$fecha_inicio'AND '$fecha_final'                        
    GROUP BY persona_id
    ")->fetch();




        



        
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
	$pdf->Cell(0, 50, 'LISTADO DE POSTULACIONES', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	

	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($empleados as $nro => $empleado) {
	$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($empleado[$nro + 1])) ? '' : ' last') . '">';
	$body .= '<td>' . ($nro + 1) . '</td>';
	$body .= '<td>' . escape($empleado['nombre']) . ' ' . escape($empleado['primer_apellido']) . ' ' . escape($empleado['segundo_apellido']) . '</td>';
	$body .= '<td>' . escape($empleado['numero_documento']) . '</td>';
	$body .= '<td>' . escape($empleado['complemento']) . '</td>';
	$body .= '<td>' . escape($empleado['expedido']) . '</td>';
	$body .= '<td>' . escape($empleado['genero']) . '</td>';
	$body .= '<td>' . escape($empleado['celular']) . '</td>';
	$body .= '<td>' . escape($empleado['email']) . '</td>';


	$currentDate = date('Y-m-d');
$currentDate = date('Y-m-d', strtotime($currentDate));
   
$startDate = date('Y-m-d', strtotime($empleado["fecha_inicio"]));
$endDate = date('Y-m-d', strtotime($empleado["fecha_final"]));
$year = date('Y', strtotime($empleado["fecha_inicio"]));



	if (($currentDate >= $startDate) && ($currentDate <= $endDate)) {
                        $body .= '<td><span style="color:#009975"> VIGENTE </span></td>';
                    } else {
                    	$body .= '<td><span style="color:#009975"> FINALIZADO </span></td>';
                       
                    }


	$body .= '<td>' . escape($empleado['fecha_inicio']) . '</td>';
	$body .= '<td>' . escape($empleado['fecha_final']) . '</td>';

	
	$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="5">No existen tipo estudiante registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="3%"><B>#</B></th>';
	$tabla .= '<th width="10%"><B>Nombre</B></th>';
	$tabla .= '<th width="10%"><B>Documento</B></th>';
	$tabla .= '<th width="10%"><B>Complemento </B></th>';
	$tabla .= '<th width="10%"><B>Expedido</B></th>';
	$tabla .= '<th width="7%"><B>genero</B></th>';
	$tabla .= '<th width="10%"><B>Telefono</B></th>';
	$tabla .= '<th width="10%"><B>email</B></th>';
	$tabla .= '<th width="10%"><B>Estado</B></th>';
	$tabla .= '<th width="10%"><B>inicio</B></th>';
	$tabla .= '<th width="10%"><B>final</B></th>';
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