<?php 

// Obtiene el ID del estudiante
$id_estudiante = (isset($_params[0])) ? $_params[0] : 0; 
//$id_gestion = $_gestion['id_gestion'];

if ($id_estudiante == 0) { 
} else { 
	$general = $db->query("SELECT *
	FROM ins_inscripcion i
	INNER JOIN ins_estudiante e ON i.estudiante_id = e.id_estudiante
	INNER JOIN sys_persona sp ON e.persona_id = sp.id_persona
	INNER JOIN ins_nivel_academico na ON i.nivel_academico_id = na.id_nivel_academico
	INNER JOIN ins_tipo_estudiante te ON i.tipo_estudiante_id = te.id_tipo_estudiante
	INNER JOIN ins_aula_paralelo ap ON i.aula_paralelo_id = ap.id_aula_paralelo
	INNER JOIN ins_aula a ON ap.aula_id = a.id_aula
	INNER JOIN ins_paralelo pp ON ap.paralelo_id = pp.id_paralelo
	INNER JOIN ins_turno t ON ap.turno_id = t.id_turno
	WHERE e.id_estudiante = $id_estudiante")->fetch_first();

	$id_inscripcion = $general['id_inscripcion'];

    $detalles = $db->query("SELECT
	g.gestion, g.id_gestion, 
	i.id_inscripcion, i.estudiante_id, i.tipo_estudiante_id, i.nivel_academico_id, i.aula_paralelo_id, i.gestion_id,
	ppe.id_pensiones_estudiante, ppe.descuento_bs, ppe.estado_concepto_estudiante, ppe.monto, ppe.fecha_final, ppe.mora_dia, IFNULL(ppe.nit_ci,'') nit_ci, IFNULL(ppe.nombre_cliente,'') nombre_cliente, ppe.tipo_concepto, ppe.tipo_documento, ppe.descuento_porcentaje, ppe.compromiso,
	ppd.id_pensiones_detalle, ppd.nro, ppd.estado_detalle,
	pp.id_pensiones, pp.nombre_pension, pp.orden, pp.descripcion,
	IFNULL(pc.nombre_compromiso,'') nombre_compromiso, IFNULL(pc.estado_compromiso,'') estado_compromiso, IFNULL(pc.nro_compromiso,'') nro_compromiso, IFNULL(pc.fecha_limite,'') fecha_limite, IFNULL(pc.observacion,'') observacion,
	IFNULL(p.monto_cancelado,0) monto_cancelado, p.fecha_general,
	IFNULL(pa.monto_adelanto,0) monto_adelanto, pa.fecha_adelanto
	FROM ins_inscripcion i
	INNER JOIN ins_gestion g ON i.gestion_id = g.id_gestion
	INNER JOIN pen_pensiones_estudiante ppe ON i.id_inscripcion = ppe.inscripcion_id
	INNER JOIN pen_pensiones_detalle ppd ON ppe.detalle_pension_id = ppd.id_pensiones_detalle
	INNER JOIN pen_pensiones pp ON ppd.pensiones_id = pp.id_pensiones
	INNER JOIN pen_usuario_habilitado puh ON pp.id_pensiones = puh.pensiones_id 
	LEFT JOIN pen_compromiso pc ON ppe.id_pensiones_estudiante = pc.id_compromiso
	LEFT JOIN (
		SELECT  IFNULL(SUM(ped.monto),0) monto_cancelado,pe.inscripcion_id,pe.detalle_pension_id, pe.fecha_inicio, pe.id_pensiones_estudiante id_pensiones_estudiante_, peg.fecha_general
		FROM pen_pensiones_estudiante pe
		INNER JOIN pen_pensiones_estudiante_detalle ped ON pe.id_pensiones_estudiante=ped.pensiones_estudiante_id
		INNER JOIN pen_pensiones_estudiante_general peg ON ped.general_id=peg.id_general
		WHERE pe.inscripcion_id = $id_inscripcion
		GROUP BY pe.id_pensiones_estudiante, ped.id_pensiones_estudiante_detalle, pe.detalle_pension_id 
	) p ON ppe.id_pensiones_estudiante=p.id_pensiones_estudiante_
	LEFT JOIN (
	SELECT  IFNULL(SUM(ped.monto),0) monto_adelanto,pe.inscripcion_id,pe.detalle_pension_id, pe.fecha_inicio, pe.id_pensiones_estudiante id_pensiones_estudiante_, peg.fecha_adelanto
	FROM pen_pensiones_estudiante pe
	INNER JOIN pen_adelantos_estudiante_detalle ped ON pe.id_pensiones_estudiante=ped.pensiones_estudiante_id
	INNER JOIN pen_adelantos_estudiante_general peg ON ped.adelanto_id=peg.id_adelanto
	WHERE pe.inscripcion_id = $id_inscripcion
	GROUP BY pe.id_pensiones_estudiante, ped.id_adelanto_estudiante_detalle, pe.detalle_pension_id
	) pa ON ppe.id_pensiones_estudiante=pa.id_pensiones_estudiante_  
	WHERE i.estudiante_id = $id_estudiante
	AND p.monto_cancelado IS NULL
	OR i.estudiante_id    = $id_estudiante
	GROUP BY ppe.id_pensiones_estudiante
	ORDER BY pp.orden, ppd.fecha_final ASC")->fetch();
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

if ($id_estudiante == 0) {
} else {

	// Documento individual --------------------------------------------------
	
	// Asigna la orientacion de la pagina
	$pdf->SetPageOrientation('L');
	
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
	$codigo_estudiante 	= escape($general['codigo_estudiante']);
	$nombres 			= escape($general['nombres']);
	$primer_apellido 	= escape($general['primer_apellido']);
	$segundo_apellido 	= escape($general['segundo_apellido']);
	$numero_documento 	= escape($general['numero_documento']);
	$expedido 			= escape($general['expedido']);
	$nombre_nivel 		= escape($general['nombre_nivel']);
	$nombre_aula 		= escape($general['nombre_aula']);
	$nombre_paralelo 	= escape($general['nombre_paralelo']);
	$nombre_turno 		= escape($general['nombre_turno']);
	$nombre_tipo_estudiante = escape($general['nombre_tipo_estudiante']);


	$fecha_hora = date('d/m/Y H:i:s');

	$valor_moneda = $moneda;
	$total = 0;

	// Establece la fuente del contenido
	$pdf->SetFont(PDF_FONT_NAME_DATA, '', 8);

	// Estructura la tabla
	$body = '';
	foreach ($detalles as $nro => $detalle) {
		$saldo =0;$total =0;
		$estado=''; $color='';
		$total = $total + $detalle['monto'];
		$saldo = $total-($detalle['monto_adelanto']+$detalle['monto_cancelado']);
		if($saldo == 0){
		   $color='green';
           $estado='CANCELADO';
		}else{
		   $color='red';
           $estado='PENDIENTE';
		}
		$body .= '<tr>';
		$body .= '<td class="left-center"  align="center">' . ($nro + 1) . '</td>';
		$body .= '<td class="left-right" align="left">' . escape($detalle['nombre_pension']).'</td>';
		$body .= '<td class="left-center" align="center" color="red">' . escape($detalle['fecha_final']) . '</td>';
		$body .= '<td class="left-center" align="center">' . escape($detalle['fecha_general']) . '</td>';
		$body .= '<td class="left-right" align="right">' . number_format($detalle['monto'], 2, '.', '') . '</td>';
		$body .= '<td class="left-right" align="right">' . number_format($detalle['mora_dia'], 2, '.', '') . '</td>';
		$body .= '<td class="left-right" align="right">' . number_format($total, 2, '.', '') . '</td>';
		$body .= '<td class="left-right" align="right">' . number_format($detalle['monto_adelanto'], 2, '.', '') . '</td>';
		$body .= '<td class="left-right" align="right">' . number_format($detalle['monto_cancelado'], 2, '.', '') . '</td>';
		$body .= '<td class="left-right" align="right">' . number_format($saldo, 2, '.', '') . '</td>';
		$body .= '<td class="text-center" align="center">' . escape($detalle['compromiso']) . '</td>';
		$body .= '<td class="left-right"  align="center" color="'.$color.'">' . escape($estado) . '</td>';
		$body .= '</tr>';

	}

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
	</table>
	<h2 align="center">HISTORIAL DE PAGOS</h2>
	<br><br><br><br>
	<table cellpadding="1">
		<tr>
			<td width="25%" class="none"><b>CÓDIGO ESTUDIANTE:</b></td>
			<td width="25%" class="none">$codigo_estudiante </td> 
			<td width="25%" class="none"><b>FECHA Y HORA:</b></td>
			<td width="25%" class="none">$fecha_hora</td> 
		</tr>
		<tr>
			<td class="none"><b>NOMBRE ESTUDIANTE:</b></td>
			<td class="none">$nombres $primer_apellido $segundo_apellido</td>
			<td class="none"><b>C.I.:</b></td>
			<td class="none">$numero_documento $expedido</td>
		</tr>
		<tr>
			<td width="25%" class="none"><b>CURSO:</b></td>
			<td width="25%" class="none">$nombre_aula $nombre_paralelo $nombre_nivel  TURNO/$nombre_turno </td> 
			<td width="25%" class="none"><b>TIPO ESTUDIANTE:</b></td>
			<td width="25%" class="none">$nombre_tipo_estudiante</td> 
		</tr>
	</table>
	<br><br>
	<table cellpadding="5" border="0.1px">
		<tr background-color="#9c9c9c">
			<th background-color="#9c9c9c" width="3%" class="all text-center">#</th>
			<th background-color="#9c9c9c" width="14%" class="all text-center">CONCEPTO DE PAGO</th>
			<th background-color="#9c9c9c" width="9%" class="all text-center">FECHA LÍMITE</th>
			<th background-color="#9c9c9c" width="10%" class="all text-center">FECHA COBRO</th>
			<th background-color="#9c9c9c" width="7%" class="all text-center">MONTO</th>
			<th background-color="#9c9c9c" width="7%" class="all text-center">MORA DÍA</th>
			<th background-color="#9c9c9c" width="7%" class="all text-center">TOTAL</th>
			<th background-color="#9c9c9c" width="8%" class="all text-center">ADELANTO</th>
			<th background-color="#9c9c9c" width="7%" class="all text-center">ACUENTA</th>
			<th background-color="#9c9c9c" width="7%" class="all text-center">SALDO</th>
			<th background-color="#9c9c9c" width="10%" class="all text-center">COMPROMISO</th>
			<th background-color="#9c9c9c" width="10%" class="all text-center">ESTADO</th>
		</tr>
		$body
		<tr>
			<th class="all" align="right" colspan="4"><b>TOTALES:</b></th>
			<th class="all" align="right">$valor_total</th>
			<th class="all" align="right">$valor_total</th>
			<th class="all" align="right">$valor_total</th>
			<th class="all" align="right">$valor_total</th>
			<th class="all" align="right">$valor_total</th>
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
$pdf->Output($nombre, 'I');

?>
