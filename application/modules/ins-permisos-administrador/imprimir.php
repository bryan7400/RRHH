<?php





$contratos = $db->query("SELECT * FROM ins_permisos WHERE estado = 'A'")->fetch();
//var_dump($contratos);die;

$informacion_estudiante = $db->query("SELECT *,per.nombres as nombres ,pers.nombres as nombre_familiar ,iper.fecha_inicio as fecha_inicios ,pers.id_persona as persona_idf ,
group_concat(c.hora_ini, ' a ', c.hora_fin, ' ') AS horarios_materias
FROM ins_permisos iper
INNER JOIN ins_estudiante  e ON e.id_estudiante=iper.estudiante_id
INNER JOIN sys_persona  per ON per.id_persona=e.persona_id
INNER JOIN sys_users su ON su.persona_id = per.id_persona

left JOIN ins_familiar ifa ON ifa.id_familiar = iper.familiar_id

left JOIN sys_persona  pers ON pers.id_persona=ifa.persona_id




left JOIN ext_curso_asignacion eca ON eca.id_curso_asignacion = iper.materia_id
left JOIN ext_curso ec ON ec.id_curso = eca.curso_id
left join ins_horario_dia c on find_in_set(c.id_horario_dia, iper.horarios_id)
WHERE iper.estado = 'A'
group by
iper.id_permiso 
")->fetch(); 


$array_permisos = array();
foreach ($informacion_estudiante as $key => $value) {

  //Tu variable $perfil['experiencia']
$data = $value['materia_id'];

//Divide una cadena.
$cadena = explode(",", $data);      

//Recorrer array
$materias = ''; 

$extras = ''; 
foreach ($cadena as $valor) {    

if (strpos($valor, 'e') !== false) {
$trimmed = rtrim($valor, "e");


$materias_extra = $db->query("SELECT * FROM ext_curso WHERE id_curso='$trimmed'")->fetch_first();

$extras .= $materias_extra['nombre_curso'].', ';
}else{


$materias_ = $db->query("SELECT * FROM pro_materia WHERE id_materia='$valor'")->fetch_first();

$materias .= $materias_['nombre_materia'].', ';

}



 
}
 $value['materia_id']=$materias.$extras;
  // code...





 $permisos = array(
'id_permiso' => $value['id_permiso'],
'estudiante_id' => $value['estudiante_id'],
'horarios_materias' => $value['horarios_materias'],
'familiar_id' => $value['familiar_id'],
'contrato_id' => $value['contrato_id'],
'materia_id' => $value['materia_id'],
'categoria' => $value['categoria'],
'horarios_id' => $value['horarios_id'],
'username' => $value['username'],
'motivo' => $value['motivo'],
'archivo_documento' => $value['archivo_documento'],
'fecha_inicio' => $value['fecha_inicios'],
'fecha_final' => $value['fecha_final'],
'tipo_permiso' => $value['tipo_permiso'],
'seguimiento_permiso' => $value['seguimiento_permiso'],
'grupo_permiso' => $value['grupo_permiso'],
'estado' => $value['estado'],
'persona_idf' => $value['persona_idf'],
'persona_id' => $value['persona_id'],
'nombres' => $value['nombres'],
'primer_apellido' => $value['primer_apellido'],
'segundo_apellido' => $value['segundo_apellido'],

'numero_documento' => $value['numero_documento'],
'horario_dia' => $value['horario_dia'],
'fecha_fin' => $value['fecha_fin'],
'id_curso' => $value['id_curso'],
'nombre_curso' => $value['nombre_curso'],
'nombre_familiar' => $value['nombre_familiar']

);  

				array_push($array_permisos, $permisos);

  // code...
}
 // code...






$permisos_list = array();
    $permisos_list=$array_permisos;




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
	foreach ($permisos_list as $nro => $permiso) {
	$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($permiso[$nro + 1])) ? '' : ' last') . '">';
	$body .= '<td>' . ($nro + 1) . '</td>';
	$body .= '<td>' . escape(str_replace("."," ",$permiso['username'])) . '</td>';
	$body .= '<td>' . escape($permiso['nombres']). ' ' . escape($permiso['primer_apellido'])  . '</td>';
	$body .= '<td>' . escape($permiso['materia_id']) . '</td>';
	$body .= '<td>' . escape($permiso['horarios_materias']) . '</td>';
	$body .= '<td>' . escape($permiso['motivo']) . '</td>';
	$body .= '<td>' . escape($permiso['seguimiento_permiso']) . '</td>';
	




	$body .= '<td>' . escape($permiso['fecha_inicio']) . '</td>';
	$body .= '<td>' . escape($permiso['fecha_final']) . '</td>';

	$body .= '<td>' . escape($permiso['tipo_permiso']) . '</td>';
	$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="5">No existen tipo estudiante registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="3%"><B>#</B></th>';
	$tabla .= '<th width="10%"><B>Estudiante</B></th>';
	$tabla .= '<th width="10%"><B>Familiar</B></th>';
	$tabla .= '<th width="12%"><B>Materias </B></th>';
	$tabla .= '<th width="12%"><B>Horarios</B></th>';
	$tabla .= '<th width="8%"><B>Motivo</B></th>';
	$tabla .= '<th width="10%"><B>Seguimiento</B></th>';
	$tabla .= '<th width="11%"><B>Inicia</B></th>';
	$tabla .= '<th width="11%"><B>Finaliza</B></th>';
	$tabla .= '<th width="10%"><B>Tipo Permiso</B></th>';
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