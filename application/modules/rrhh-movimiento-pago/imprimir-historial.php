<?php

// Obtiene el id_egreso
$id_asignacion = (isset($_params[0])) ? $_params[0] : 0; 

//obtiene la gestion actual 
$id_gestion = $_gestion['id_gestion'];  

//obtiene la fecha actual del sistema
$fecha_actual = Date('Y-m-d');  

//obtiene la hora actual del sistema
$hora_actual = Date('H:i:s'); 

if ($id_asignacion == 0) { 
} else{
	
	// Almacena los permisos en variables
	$permiso_ver = in_array('ver', $_views);
	
	// Obtiene la egreso
    $consulta_informacion = "SELECT *
    FROM sys_persona p
    INNER JOIN sys_users su ON p.id_persona = su.persona_id
    INNER JOIN per_asignaciones a ON p.id_persona = a.persona_id
    INNER JOIN per_cargos c ON a.cargo_id = c.id_cargo
    WHERE a.estado = 'A'
    AND a.gestion_id = $id_gestion
    AND a.id_asignacion = $id_asignacion";
    $informacion = $db->query($consulta_informacion)->fetch_first();

	// Obtiene los detalles
    $consulta_historial = "SELECT *, mp.mes mes_cancelado, mp.monto monto_cancelado
    FROM sys_persona p
    INNER JOIN sys_users su ON p.id_persona = su.persona_id
    INNER JOIN per_asignaciones a ON p.id_persona = a.persona_id
    INNER JOIN rhh_movimiento_pago mp ON a.id_asignacion = mp.asignacion_id
    INNER JOIN rhh_concepto_pago cp ON mp.concepto_pago_id = cp.id_concepto_pago
    WHERE a.estado = 'A'
    AND a.gestion_id = $id_gestion
    AND a.id_asignacion = $id_asignacion
    ORDER BY cp.nombre_concepto_pago, mp.mes";
    $historial = $db->query($consulta_historial)->fetch();
}

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

if ($id_asignacion == 0) {
} else {
	// Documento individual --------------------------------------------------
	
	// Asigna la orientacion de la pagina factura
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
	$valor_fecha = escape(date_decode($fecha_actual, $_institution['formato']) . ' ' . $hora_actual);
	$valor_nombre_cliente = escape($informacion['nombres']. ' ' .$informacion['primer_apellido']. ' ' .$informacion['segundo_apellido']);
	$valor_nit_ci = escape($informacion['numero_documento']);
	$valor_nro_egreso = escape($informacion['nro']);
	$valor_monto_total = escape($informacion['monto_cancelado']);
	$valor_nro_registros = escape($informacion['mes_cancelado']);
	$valor_almacen = escape($informacion['nombre_concepto_pago']);
	$valor_cargo = escape($informacion['cargo']);
	//$valor_empleado = escape($informacion['nombres'] . ' ' . $informacion['primer_apellido'] . ' ' . $informacion['segundo_apellido']);
	$valor_empleado = '';
	// $valor_descuento_global = escape($general['descuento_bs']);
    // $valor_descuento_porcentaje = (isset($general['descuento_porcentaje'])) ? clear($general['descuento_porcentaje']) : '';
	$valor_moneda = $moneda;
	$total = 0;

	// Establece la fuente del contenido
	$pdf->SetFont(PDF_FONT_NAME_DATA, '', 8);

	// Estructura la tabla
	$body = '';$total_con_descuento=0;
	foreach ($historial as $nro => $detalle) {
		$total = $total + $detalle['monto_cancelado'];
		
		// Valida si es mensualidad para imprimir nro de cuota
		$body .= '<tr>';
		$body .= '<td align="center">' . ($detalle['nro']) . '</td>';
		$body .= '<td align="left">' . escape($detalle['nombre_concepto_pago']) . '</td>';
		$body .= '<td align="center">' . escape($detalle['mes_cancelado']).'/2020</td>';		
		$body .= '<td align="center">' . escape($detalle['fecha_pago']).'</td>';
		$body .= '<td align="right">' . number_format($detalle['monto_cancelado'], 2, '.', '') . '</td>';
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
	$valor_pie = $_institution['pie_pagina'];
	$valor_razon = $_institution['razon_social']; 
	$valor_propietario = $_institution['propietario'];


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

	$monto_escrito = $monto_literal . ' ' . $monto_decimal . '/100 '.$moneda;

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
			<table cellpadding="1" >
		<tr>
			<td width="25%" class="none" align="left" rowspan="6">
				<img src="" width="90">
			</td>
			<td width="60%" class="none" align="center"><b>$valor_empresa</b></td>
			<td width="10%" class="none" align="right"></td>
			<td width="5%" class="none" align="right"></td>
		</tr>
		<tr>
			<td class="none" align="center">Resolución Ministerial 164/98</td>
            <td class="none" align="right"></td>
			<td class="none" align="right"></td>
		</tr>
		<tr>
			<td class="none" align="center"><i>De: $valor_propietario</i></td>
            <td class="none" align="right"></td>
			<td class="none" align="right"></td>
		</tr>
		<tr>
			<td class="none" align="center"><b> $almacen CASA MATRIZ - 0 </b></td>
			<td class="none" align="right"></td>
			<td class="none" align="right"></td>
		</tr>
		<tr>
			<td class="none" align="center">$valor_direccion * Teléfono: $valor_telefono</td>
			<td class="none" align="right"></td>
			<td class="none" align="right"></td>
		</tr>
		<tr>
			<td class="none" align="center">$valor_pie</td>
            <td class="none" align="right" colspan="2"></td>
		</tr>
		<tr>
			<td class="none" align="center"></td>
			<td class="none" align="center">$valor_razon</td>
			<td class="none" align="right"></td>
		</tr>
	</table>

	<h1 align="center">HISTORIAL &nbsp;&nbsp; DE &nbsp;&nbsp; PAGOS &nbsp;&nbsp;</h1>
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
		<tr>
			<td class="none"><b>CARGO:</b></td>
			<td class="none">$valor_cargo</td>
		</tr>
	</table>
	<br><br>
    <table cellpadding="5" border="0.5px">
		<tr>
			<th width="10%"  class="none" align="center"><b>Nº</b></th>
			<th width="45%" class="none" align="center"><b>CONCEPTO DE PAGO</b></th>
			<th width="15%" class="none" align="center"><b>MES/GESTIÓN</b></th>
			<th width="15%" class="none" align="center"><b>FECHA PAGO</b></th>
			<th width="15%" class="none" align="center"><b>IMPORTE</b></th>
		</tr>
		$body
		<tr>
			<th class="all" align="left" colspan="4">SON: $monto_escrito</th>
			<th class="all" align="right">$valor_total</th>
		</tr>
	</table> 
	<br><br> 
EOD;
	// Imprime la tabla
	$pdf->writeHTML($tabla, true, false, false, false, '');
	
	// Genera el nombre del archivo
	$nombre = 'factura_' . $id_pago_general . '_' . date('Y-m-d_H-i-s') . '.pdf';
}
// ------------------------------------------------------------
// Cierra y devuelve el fichero pdf
ob_end_clean();
$pdf->Output('Recibo', 'I');

?>
