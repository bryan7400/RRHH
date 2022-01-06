<?php

// Obtiene los parametros
$id_empleado = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_ver = in_array('ver', $_views);

// Verifica si existen los parametros
if ($id_empleado == 0) {
	// Obtiene los empleados
	$empleados = $db->select('z.*, a.procedencia as procedencia, b.cargo as cargo, c.sucursal')->from('per_empleados z')->join('gen_procedencias a', 'z.procedencia_id = a.id_procedencia', 'left')->join('per_cargos b', 'z.cargo_id = b.id_cargo', 'left')->join('gen_sucursales c', 'z.sucursal_id = c.id_sucursal', 'left')->order_by('z.id_empleado', 'asc')->fetch();

	// Ejecuta un error 404 si no existe los empleados
	if (!$permiso_listar) { require_once not_found(); exit; }
} else {
	// Obtiene el empleado
	$empleado = $db->select('z.*, a.procedencia as procedencia, b.cargo as cargo, c.sucursal')->from('per_empleados z')->join('gen_procedencias a', 'z.procedencia_id = a.id_procedencia', 'left')->join('per_cargos b', 'z.cargo_id = b.id_cargo', 'left')->join('gen_sucursales c', 'z.sucursal_id = c.id_sucursal', 'left')->where('z.id_empleado', $id_empleado)->fetch_first();
	
	// Ejecuta un error 404 si no existe el empleado
	if (!$empleado || !$permiso_ver) { require_once not_found(); exit; }
}

// Importa la libreria para generar el reporte
require_once libraries . '/tcpdf-class/tcpdf.php';

// Verifica si existen los parametros
if ($id_empleado == 0) {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');

	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'EMPLEADOS', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define el contenido de la tabla
	$body = '';
	
	// Construye la estructura del contenido de la tabla
	foreach ($empleados as $nro => $empleado) {
		$body .= '<tr class="' . (($nro % 2 == 0) ? 'even' : 'odd') . ((isset($empleados[$nro + 1])) ? '' : ' last') . '">';
		$body .= '<td>' . ($nro + 1) . '</td>';
		$body .= '<td>' . escape($empleado['nombres']) . '</td>';
		$body .= '<td>' . escape($empleado['paterno']) . '</td>';
		$body .= '<td>' . escape($empleado['materno']) . '</td>';
		$body .= '<td>' . escape($empleado['genero']) . '</td>';
		$body .= '<td>' . date_decode($empleado['fecha_nacimiento'], $_format) . '</td>';
		$body .= '<td>' . escape($empleado['ci']) . '</td>';
		$body .= '<td>' . escape($empleado['procedencia']) . '</td>';
		$body .= '<td>' . escape($empleado['direccion']) . '</td>';
		$body .= '<td>' . escape(str_replace(',', ' / ', $empleado['telefono'])) . '</td>';
		$body .= '<td>' . date_decode($empleado['fecha_contratacion'], $_format) . '</td>';
		$body .= '<td>' . (($empleado['fecha_finalizacion'] != '0000-00-00') ? date_decode($empleado['fecha_finalizacion'], $_format) : '') . '</td>';
		$body .= '<td>' . escape($empleado['cargo']) . '</td>';
		$body .= '<td>' . escape($empleado['sucursal']) . '</td>';
		$body .= '<td>' . escape($empleado['observacion']) . '</td>';
		$body .= '</tr>';
	}
	
	// Verifica el contenido de la tabla
	$body = ($body == '') ? '<tr class="last"><td colspan="15">No existen empleados registrados en la base de datos.</td></tr>' : $body;
	
	// Define el formato de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first last">';
	$tabla .= '<th width="6%">#</th>';
	$tabla .= '<th width="3.58%">Nombres</th>';
	$tabla .= '<th width="8.17%">Apellido paterno</th>';
	$tabla .= '<th width="8.17%">Apellido materno</th>';
	$tabla .= '<th width="3.58%">Género</th>';
	$tabla .= '<th width="9.71%">Fecha de nacimiento</th>';
	$tabla .= '<th width="10.22%">Cédula de identidad</th>';
	$tabla .= '<th width="5.62%">Procedencia</th>';
	$tabla .= '<th width="5.11%">Dirección</th>';
	$tabla .= '<th width="4.60%">Teléfono</th>';
	$tabla .= '<th width="11.24%">Fecha de contratación</th>';
	$tabla .= '<th width="11.24%">Fecha de finalización</th>';
	$tabla .= '<th width="2.55%">Cargo</th>';
	$tabla .= '<th width="4.09%">Sucursal</th>';
	$tabla .= '<th width="6.13%">Observación</th>';
	$tabla .= '</tr>';
	$tabla .= $body;
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'empleados_' . date('Y-m-d_H-i-s') . '.pdf';
} else {
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont($font_name_main, 'BU', $font_size_main);
	
	// Define el titulo del documento
	$pdf->Cell(0, 15, 'EMPLEADO # ' . $id_empleado, 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	$pdf->Ln(15);
	
	// Establece la fuente del contenido
	$pdf->SetFont($font_name_data, '', $font_size_data);
	
	// Define las variables
	$valor_nombres = escape($empleado['nombres']);
	$valor_paterno = ($empleado['paterno'] != '') ? escape($empleado['paterno']) : 'No asignado';
	$valor_materno = escape($empleado['materno']);
	$valor_genero = escape($empleado['genero']);
	$valor_fecha_nacimiento = date_decode($empleado['fecha_nacimiento'], $_format);
	$valor_ci = escape($empleado['ci']);
	$valor_procedencia_id = escape($empleado['procedencia']);
	$valor_direccion = escape($empleado['direccion']);
	$valor_telefono = escape(str_replace(',', ' / ', $empleado['telefono']));
	$valor_fecha_contratacion = date_decode($empleado['fecha_contratacion'], $_format);
	$valor_fecha_finalizacion = date_decode($empleado['fecha_finalizacion'], $_format);
	$valor_cargo_id = escape($empleado['cargo']);
	$valor_sucursal_id = escape($empleado['sucursal']);
	$valor_observacion = ($empleado['observacion'] != '') ? escape($empleado['observacion']) : 'No asignado';
	
	// Construye la estructura de la tabla
	$tabla = $style;
	$tabla .= '<table cellpadding="5">';
	$tabla .= '<tr class="first"><th class="left">Nombres:</th><td class="right">' . $valor_nombres . '</td></tr>';
	$tabla .= '<tr><th class="left">Apellido paterno:</th><td class="right">' . $valor_paterno . '</td></tr>';
	$tabla .= '<tr><th class="left">Apellido materno:</th><td class="right">' . $valor_materno . '</td></tr>';
	$tabla .= '<tr><th class="left">Género:</th><td class="right">' . $valor_genero . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha de nacimiento:</th><td class="right">' . $valor_fecha_nacimiento . '</td></tr>';
	$tabla .= '<tr><th class="left">Cédula de identidad:</th><td class="right">' . $valor_ci . '</td></tr>';
	$tabla .= '<tr><th class="left">Procedencia:</th><td class="right">' . $valor_procedencia_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Dirección:</th><td class="right">' . $valor_direccion . '</td></tr>';
	$tabla .= '<tr><th class="left">Teléfono:</th><td class="right">' . $valor_telefono . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha de contratación:</th><td class="right">' . $valor_fecha_contratacion . '</td></tr>';
	$tabla .= '<tr><th class="left">Fecha de finalización:</th><td class="right">' . $valor_fecha_finalizacion . '</td></tr>';
	$tabla .= '<tr><th class="left">Cargo:</th><td class="right">' . $valor_cargo_id . '</td></tr>';
	$tabla .= '<tr><th class="left">Sucursal:</th><td class="right">' . $valor_sucursal_id . '</td></tr>';
	$tabla .= '<tr class="last"><th class="left">Observación:</th><td class="right">' . $valor_observacion . '</td></tr>';
	$tabla .= '</table>';
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'empleado_' . $id_empleado . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// Cierra y devuelve el fichero pdf
$pdf->Output($nombre, 'I');

?>