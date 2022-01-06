<?php

// Obtiene el id_egreso
$id_pago_general = (isset($_params[0])) ? $_params[0] : 0; 
$id_gestion = $_gestion['id_gestion'];
// var_dump($id_pago_general);exit();
if ($id_pago_general == 0) { 
	//var_dump('0');exit();
	// Obtiene las egresos
	// $generals = $db->select('p.*, a.almacen, a.principal, e.nombres, e.primer_apellido, e.segundo_apellido')
	// 				->from('inv_egresos p')
	// 				->join('inv_almacenes a', 'p.almacen_id = a.id_almacen', 'left')
	// 				->join('sys_persona e', 'p.empleado_id = e.id_persona', 'left')
	// 				->where('p.empleado_id', $_user['persona_id'])
	// 				->order_by('p.fecha_egreso desc, p.hora_egreso desc')
	// 				->fetch();
} else { 
	// var_dump('!=0');exit();
	// Obtiene los permisos
	// $permisos = explode(',', permits);  
	
	// Almacena los permisos en variables
	$permiso_ver = in_array('ver', $_views);
	
	// Obtiene la egreso
	$general = $db->select('p.*,e.nombres, e.primer_apellido, e.segundo_apellido')
				  ->from('pen_pensiones_estudiante_general p')
				  //->join('inv_almacenes a', 'p.almacen_id = a.id_almacen', 'left')
				  ->join('sys_persona e', 'p.empleado_id = e.id_persona', 'left')
				  ->where('id_general', $id_pago_general)
				  ->fetch_first();
	// var_dump($general);exit();
	// Verifica si existe el egreso
	// if (!$general || $general['empleado_id'] != $_user['persona_id']) {
	// 	// Error 404
	// 	require_once not_found();
	// 	exit;
	// } elseif (!$permiso_ver) {
	// 	// Error 401
	// 	require_once bad_request();
	// 	exit;
	// }
	    $consulta="SELECT
	d.monto, d.descuento, g.tipo_pago, d.general_id, CONCAT(g.fecha_general,' ',g.hora_general) fecha_factura, IFNULL(p.monto-d.monto,p.monto) saldo,
	CONCAT(per.nombres,' ',per.primer_apellido,' ', per.segundo_apellido) nombre_completo, per.numero_documento,
	CONCAT('COLEGIATURA DE ',p.nombre_pension) as nombre_detalle,
	ia.nombre_aula,
	par.nombre_paralelo
	FROM pen_pensiones_estudiante_detalle d
	INNER JOIN pen_pensiones_estudiante_general g ON d.general_id=g.id_general
	LEFT JOIN pen_pensiones_estudiante pe ON d.pensiones_estudiante_id = pe.id_pensiones_estudiante
	INNER JOIN ins_inscripcion i ON pe.inscripcion_id = i.id_inscripcion
	INNER JOIN ins_aula_paralelo ap ON i.aula_paralelo_id=ap.id_aula_paralelo
	INNER JOIN ins_aula ia ON ap.aula_id=ia.id_aula
	INNER JOIN ins_paralelo par ON ap.paralelo_id=par.id_paralelo
	INNER JOIN ins_estudiante e ON i.estudiante_id = e.id_estudiante
	INNER JOIN sys_persona per ON e.persona_id = per.id_persona
	LEFT JOIN pen_pensiones p ON pe.pension_id = p.id_pensiones
	WHERE d.general_id=$id_pago_general
	AND i.gestion_id=$id_gestion
	UNION 
	SELECT 
	IFNULL(z.cancelado,0)monto, z.descuento, z.tipo_pago, z.id_general, z.fecha_factura,IFNULL(a.costo-z.cancelado,a.costo) saldo,
	CONCAT(p.nombres,' ',p.primer_apellido,' ', p.segundo_apellido) nombre_completo, p.numero_documento,
	CONCAT('CURSO ADICIONAL DE ',ce.nombre_curso,'',IFNULL(a.modulo,'')) AS nombre_detalle,
	ia.nombre_aula,
	par.nombre_paralelo
	FROM ext_asignacion a
	INNER JOIN ext_curso_extra ce ON a.curso_extra_id=ce.id_curso_extra
	INNER JOIN ext_inscripcion i ON a.id_asignacion=i.asignacion_id
	INNER JOIN ins_estudiante e ON i.estudiante_id=e.id_estudiante
	INNER JOIN ins_inscripcion iin ON e.id_estudiante=iin.estudiante_id
	INNER JOIN ins_aula_paralelo ap ON iin.aula_paralelo_id=ap.id_aula_paralelo
	INNER JOIN ins_aula ia ON ap.aula_id=ia.id_aula
	INNER JOIN ins_paralelo par ON ap.paralelo_id=par.id_paralelo
	INNER JOIN sys_persona p ON e.persona_id=p.id_persona
	LEFT JOIN (
	    SELECT SUM(pd.acuenta) cancelado, pd.inscripcion_id, d.id_general, pd.descuento, d.tipo_pago, CONCAT(d.fecha_general,' ',d.hora_general) fecha_factura
	    FROM ext_pago_detalle pd
	    INNER JOIN pen_pensiones_estudiante_general d ON pd.general_id=d.id_general
	    GROUP BY pd.inscripcion_id, pd.curso_extra_id
	) z ON i.id_inscripcion=z.inscripcion_id
	WHERE z.id_general=$id_pago_general
	AND i.gestion_id=$id_gestion";
	// Obtiene los detalles
	$detalles = $db->query($consulta)->fetch();


				   //var_dump($detalles);exit();
}
//var_dump($detalles);exit();
// Obtiene la moneda oficial
$moneda = $db->from('inv_monedas')->where('oficial', 'S')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['sigla'] . ')' : '';

// Importa la libreria para el generado del pdf
require_once libraries . '/tcpdf/tcpdf.php';
require_once libraries . '/tcpdf/tcpdf_barcodes_2d.php';
require_once libraries . '/numbertoletter-class/NumberToLetterConverter.php';

// Define variables globales
// define('direccion', escape($_institution['pie_pagina']));
// define('imagen', escape($_institution['imagen_encabezado']));
define('atencion', 'Lun. a Vie. de 08:30 a 18:30 y Sáb. de 08:30 a 13:00');
// define('pie', escape($_institution['pie_pagina']));
define('telefono', escape(str_replace(',', ', ', $_institution['telefono'])));
//define('telefono', date(escape($_institution['formato'])) . ' ' . date('H:i:s'));

// Extiende la clase TCPDF para crear Header y Footer
class MYPDF extends TCPDF {
}

// Instancia el documento PDF
$pdf = new MYPDF('P', 'pt', 'LETTER', true, 'UTF-8', false);

// Asigna la informacion al documento
$pdf->SetCreator(name_autor);
$pdf->SetAuthor(name_autor);
$pdf->SetTitle($_institution['nombre']);
$pdf->SetSubject($_institution['propietario']);
$pdf->SetKeywords($_institution['sigla']);

// Asignamos margenes
$pdf->SetMargins(30, 30, 30);

// Elimina las cabeceras
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

// ------------------------------------------------------------

if ($id_pago_general == 0) {
} else {
	// Documento individual --------------------------------------------------
	
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('P');
	
	// Adiciona la pagina
	$pdf->AddPage();
	
	// Establece la fuente del titulo
	$pdf->SetFont(PDF_FONT_NAME_MAIN, 'B', 16);
	
	// Titulo del documento
	//$pdf->Cell(0, 10, 'FACTURA', 0, true, 'C', false, '', 0, false, 'T', 'M');
	
	// Salto de linea
	//$pdf->Ln(5);
	
	// Establece la fuente del contenido
	$pdf->SetFont(PDF_FONT_NAME_DATA, '', 9);
	
	// Define las variables
	$valor_fecha = escape(date_decode($general['fecha_general'], $_institution['formato']) . ' ' . $general['hora_general']);
	$valor_nombre_cliente = escape($general['nombre_cliente']);
	$valor_nit_ci = escape($general['nit_ci']);
	$valor_nro_egreso = escape($general['nro_factura']);
	$valor_monto_total = escape($general['monto_total']);
	$valor_nro_registros = escape($general['nro_factura']);
	$valor_almacen = escape($general['almacen']);
	$valor_empleado = escape($general['nombres'] . ' ' . $general['primer_apellido'] . ' ' . $general['segundo_apellido']);
	// $valor_descuento_global = escape($general['descuento_bs']);
    // $valor_descuento_porcentaje = (isset($general['descuento_porcentaje'])) ? clear($general['descuento_porcentaje']) : '';
	$valor_moneda = $moneda;
	$total = 0;

	// Establece la fuente del contenido
	$pdf->SetFont(PDF_FONT_NAME_DATA, '', 8);

	// Estructura la tabla
	$body = '';$total_con_descuento=0;
	foreach ($detalles as $nro => $detalle) {
		$total = $total + $detalle['monto'];
		$body .= '<tr>';
		$body .= '<td class="left-right">' . ($nro + 1) . '</td>';
		$body .= '<td class="left-right">' . escape($detalle['nombre_completo']).'</td>';
		$body .= '<td class="left-right">' . escape($detalle['nombre_detalle']) . '</td>';
		$body .= '<td class="left-right" align="right">' . number_format($detalle['monto'], 2, '.', '') . '</td>';
		$body .= '</tr>';
	}
	
	$valor_total = number_format($total, 2, '.', '');
	$valor_total_con_descuento=0;
	// $total_con_descuento=$valor_total-$valor_descuento_global;
	// $valor_total_con_descuento = number_format($total_con_descuento, 2, '.', '');
	$body = ($body == '') ? '<tr><td colspan="6" align="center" class="all">Este egreso no tiene detalle, es muy importante que todos las egresos cuenten con un detalle de venta.</td></tr>' : $body;

	// Define la fecha de hoy
	$hoy = date('Y-m-d');

	// Obtiene la dosificacion del periodo actual
	$dosificacion = $db->from('inv_dosificaciones')->where('fecha_registro <=', $hoy)->where('fecha_limite >=', $hoy)->where('activo', 'S')->fetch_first();

	//$valor_logo = (imagen != '') ? institucion . '/' . imagen : imgs . '/empty.jpg';
	$valor_empresa = $_institution['nombre'];
	$valor_direccion = $_institution['direccion'];
	$valor_telefono = $_institution['telefono'];
	//$valor_pie = $_institution['pie_pagina'];
	$valor_razon = $_institution['razon_social']; 


	$valor_nit_empresa = $_institution['nit'];
	$valor_autorizacion = $general['nro_autorizacion'];
	$valor_codigo = $general['codigo_control'];
	$valor_numero = $general['nro_factura'];
	$valor_limite = date_decode($general['fecha_limite'], $_institution['formato']);

	$valor_leyenda = $dosificacion['leyenda'];

	$valor_solo_fecha = date_decode($general['fecha_egreso'], 'd/m/Y');

	// Gereramos el codigo de seguridad QR
	$factura_qr = $valor_nit_empresa . '|' . $valor_numero . '|' . $valor_autorizacion . '|' . $valor_solo_fecha . '|' . $valor_total . '|' . $valor_total_con_descuento . '|' . $valor_codigo . '|' . $valor_nit_ci . '|0.00|0.00|0.00|0.00';
	
	// Instancia el objeto QR
	$objeto = new TCPDF2DBarcode($factura_qr, 'QRCODE,L');

	// Obtiene la imagen QR en modo cadena
	$imagen = $objeto->getBarcodePngData(4, 4, array(30, 30, 30));

	// Crea la imagen a partir de la cadena
	$imagen = imagecreatefromstring($imagen);

	//imagejpeg($imagen, storage . '/qr.jpg', 100);

	//$qr_imagen = storage . '/qr.jpg';

	// Obtiene los datos del monto total
	$conversor = new NumberToLetterConverter();
	$monto_textual = explode('.', $valor_total);
	//$monto_textual = explode('.', $valor_total_con_descuento);
	$monto_numeral = $monto_textual[0];
	$monto_decimal = $monto_textual[1];
	$monto_literal = ucfirst(strtolower(trim($conversor->to_word($monto_numeral))));

	$monto_escrito = $monto_literal . ' ' . $monto_decimal . '/100';

	// Formateamos la tabla
	$tabla = <<<EOD
	<style>
	th {
		background-color: #eee;
		font-weight: bold;
	}
	.left-right {
		border-left: 1px solid #444;
		border-right: 1px solid #444;
	}
	.none {
		border: 1px solid #fff;
		height: 15px;
	}
	.all {
		border: 1px solid #444;
	}
	</style>
	<table cellpadding="1">
		<tr>
			<td width="24%" class="none" align="left" rowspan="4">
				<img src="" width="118">
			</td>
			<td width="26%" class="none" align="left">$valor_empresa</td>
			<td width="35%" class="none" align="right"><b>NIT:</b></td>
			<td width="15%" class="none" align="right">$valor_nit_empresa</td>
		</tr>
		<tr>
			<td class="none" align="left">$valor_direccion</td>
			<td class="none" align="right"><b>NRO. FACTURA:</b></td>
			<td class="none" align="right">$valor_numero</td>
		</tr>
		<tr>
			<td class="none" align="left">Teléfono: $valor_telefono</td>
			<td class="none" align="right"><b>NRO. AUTORIZACIÓN:</b></td>
			<td class="none" align="right">$valor_autorizacion</td>
		</tr>
		<tr>
			<td class="none" align="left"></td>
			<td class="none" align="right" colspan="2">$valor_razon</td>
		</tr>
	</table>
	<h1 align="center">FACTURA</h1>
	<table cellpadding="1">
		<tr>
			<td width="20%" class="none"><b>FECHA Y HORA:</b></td>
			<td width="80%" class="none">$valor_fecha</td> 
		</tr>
		<tr>
			<td class="none"><b>SEÑOR(ES):</b></td>
			<td class="none">$valor_nombre_cliente</td>
		</tr>
		<tr>
			<td class="none"><b>NIT / CI:</b></td>
			<td class="none">$valor_nit_ci</td>
		</tr>
	</table>
	<br><br>
	<table cellpadding="5">
		<tr>
			<th width="5%" class="all text-center">#</th>
			<th width="40%" class="all text-center">NOMBRE ESTUDIANTE</th>
			<th width="40%" class="all text-center">DETALLE</th>
			<th width="15%" class="all text-center">IMPORTE</th>
		</tr>
		$body
		<tr>
			<th class="all" align="left" colspan="3">SON: $monto_escrito</th>
			<th class="all" align="right">$valor_total</th>
		</tr>
	</table> 
	<br><br> 
	<table cellpadding="1">
		<tr>
			<td width="20%" class="none"><b>Código de control:</b></td>
			<td width="30%" class="none">$valor_codigo</td>
			<td width="50%" class="none" rowspan="2" align="right">
				<img src="$qr_imagen" width="80">
			</td>
		</tr>
		<tr>
			<td class="none"><b>Fecha límite de emisión:</b></td>
			<td class="none">$valor_limite</td>
		</tr>
	</table>
	<h4 align="center">"ESTA FACTURA CONTRIBUYE AL DESARROLLO DEL PAÍS. EL USO ILÍCITO DE ÉSTA SERÁ SANCIONADO DE ACUERDO A LEY"</h4>
	<div align="center"><b>Ley Nº 453:</b> "$valor_leyenda".</div>
EOD;
	
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'factura_' . $id_pago_general . '_' . date('Y-m-d_H-i-s') . '.pdf';
}

// ------------------------------------------------------------

// Cierra y devuelve el fichero pdf
ob_end_clean();
$pdf->Output($nombre, 'I');

?>
