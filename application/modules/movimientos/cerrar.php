<?php 

// Obtiene la fecha
$fecha = str_replace('/', '-', now($_institution['formato']));

// Obtiene los formatos
$formato_textual = get_date_textual($_institution['formato']);
$formato_numeral = get_date_numeral($_institution['formato']);

// Verifica si existe el parametro
if (sizeof($params) == 1) {
	// Verifica el tipo del parametro
	if (!is_date($params[0])) {
		// Redirecciona la pagina
		redirect('?/movimientos/cerrar/' . $fecha);
	}
} else {
	// Redirecciona la pagina
	redirect('?/movimientos/cerrar/' . $fecha);
}

// Obtiene el parametro
$fecha = date_encode($params[0]);

// Obtiene las ventas
$ventas = $db->query("SELECT * FROM inv_egresos e WHERE e.fecha_egreso = '$fecha' AND e.tipo='Venta' AND e.estado = 'V' AND e.empleado_id = '" . $_user['empleado_id'] . "' group by id_egreso")->fetch();
//Obtiene compras
$compras = $db->query("SELECT * FROM inv_ingresos i LEFT JOIN inv_proveedores p ON i.proveedor_id = p.id_proveedor WHERE i.fecha_ingreso = '$fecha' AND i.tipo='Compra' AND i.empleado_id = '" . $_user['empleado_id'] . "' group by id_ingreso")->fetch();
//cronograma de pagos
$cronogramas = $db->query("select c.periodo, cc.detalle, cc.monto, cc.fecha_pago, cc.id_cronograma_cuentas, cc.tipo_pago from cronograma c left join cronograma_cuentas cc on c.id_cronograma = cc.cronograma_id where cc.estado='1' and cc.empleado_id=" . $_user['empleado_id'] . " and cc.fecha_pago='$fecha' GROUP by c.id_cronograma")->fetch();
//COBRO DE VENTAS
$cobros = $db->query("select p.id_pago, p.movimiento_id, pd.metodo, pd.fecha_real, e.nro_factura, e.fecha_egreso,e.nombre_cliente,e.nit_ci, e.monto_total, e.tipo, ifnull(abono,0) as subtotal from inv_pagos p left join inv_pago_detalles pd on p.id_pago= pd.pago_id LEFT JOIN inv_egresos e ON e.id_egreso=p.movimiento_id where p.tipo='Egreso' and pd.estado='1' and pd.empleado_id=" . $_user['empleado_id'] . " and pd.fecha_real='$fecha' and e.fecha_egreso!=pd.fecha_real")->fetch(); 
//pagos compras
$pagos_compras = $db->query("select p.id_pago, p.movimiento_id, i.fecha_ingreso, i.fecha_ingreso, i.tipo, i.id_ingreso, pr.razon_social, i.monto_total, pd.metodo, ifnull(abono,0) as subtotal from inv_pagos p left join inv_pago_detalles pd on p.id_pago= pd.pago_id LEFT JOIN inv_ingresos i ON i.id_ingreso=p.movimiento_id LEFT JOIN inv_proveedores pr ON pr.id_proveedor= i.proveedor_id where p.tipo='Ingreso' and pd.estado='1' and pd.empleado_id=" . $_user['empleado_id'] . " and pd.fecha_real='$fecha' and i.fecha_ingreso!=pd.fecha_real")->fetch();

// Obtiene la moneda oficial
$moneda = $db->from('gen_monedas')->where('principal', 'S')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['codigo'] . ')' : '';

// Obtiene los ingresos
$ingresos = $db->select("m.*, concat(p.nombres, ' ', p.paterno, ' ', p.materno) as empleado")
				->from('caj_movimientos m')
				->join('per_empleados e', 'm.empleado_id = e.id_empleado', 'left')
				->join('sys_personas p', 'e.persona_id = p.id_persona', 'left')
				->where('m.tipo', 'i')
				->where('m.empleado_id', $_user['empleado_id'])
				->where('m.fecha_movimiento', $fecha)
				->order_by('m.fecha_movimiento desc, m.hora_movimiento desc')
				->fetch();

// Obtiene los egresos
$egresos = $db->select("m.*, concat(p.nombres, ' ', p.paterno, ' ', p.materno) as empleado")
				->from('caj_movimientos m')
				->join('per_empleados e', 'm.empleado_id = e.id_empleado', 'left')
				->join('sys_personas p', 'e.persona_id = p.id_persona', 'left')
				->where('m.tipo', 'e')
				->where('m.empleado_id', $_user['empleado_id'])
				->where('m.fecha_movimiento', $fecha)
				->order_by('m.fecha_movimiento desc, m.hora_movimiento desc')
				->fetch();

// Obtiene los gastos
$gastos = $db->select("m.*, concat(p.nombres, ' ', p.paterno, ' ', p.materno) as empleado")
				->from('caj_movimientos m')
				->join('per_empleados e', 'm.empleado_id = e.id_empleado', 'left')
				->join('sys_personas p', 'e.persona_id = p.id_persona', 'left')
				->where('m.tipo', 'g')
				->where('m.empleado_id', $_user['empleado_id'])
				->where('m.fecha_movimiento', $fecha)
				->order_by('m.fecha_movimiento desc, m.hora_movimiento desc')
				->fetch();

$ultimo_registro_caja = $db->query("SELECT * FROM `inv_caja` WHERE fecha_caja = (SELECT MAX(fecha_caja) AS fecha FROM inv_caja) AND hora_caja = (SELECT MAX(hora_caja) AS fecha FROM inv_caja) AND id_caja = (SELECT MAX(id_caja) AS fecha FROM inv_caja)")->fetch_first();

$estado = '';
if ($ultimo_registro_caja){
	if ($ultimo_registro_caja['estado'] == 'CAJA')
		$estado = 'CIERRE';
	if ($ultimo_registro_caja['estado'] == 'CIERRE')
		$estado = 'INICIO';
}else{
	$estado = 'INICIO';
}
//var_dump($ultimo_registro_caja['estado']);
?>
<!-- variables usadas -->
<?php $total_ingresos = $total_ingresos_banco = $total_venta = $total_venta_banco = $total_egresos = $total_egresos_banco = $total_compra = $total_compra_banco = $total_gasto = $total_gasto_banco = 0; ?>
<!-- cabecera -->
<?php require_once show_template('header-sidebar'); ?>
<!-- body -->
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Cierre de Caja</strong>
	</h3>
</div>
<div class="panel-body" data-servidor="<?= ip_local . name_project . '/diario.php'; ?>">
	<div class="row">
		<div class="col-xs-6">
			<div class="text-label hidden-xs">Seleccionar acción:</div>
			<div class="text-label visible-xs-block">Acciones:</div>
		</div>
		<div class="col-xs-6 text-right">
			<a href="?/movimientos/imprimir/<?= $fecha; ?>" target="_blank" class="btn btn-default"><i class="glyphicon glyphicon-print"></i><span class="hidden-xs hidden-sm"> Exportar</span></a>
			<!-- <a href="?/movimientos/caja/<?= $fecha; ?>" target="_blank" class="btn btn-default"><i class="glyphicon glyphicon-stats"></i><span class="hidden-xs hidden-sm"> Listado Caja</span></a> -->
			

				<div class="btn-group">
					<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
						<span class="glyphicon glyphicon-wrench"></span>
						<span class="hidden-xs">Acciones</span>
					</button>
					<ul class="dropdown-menu dropdown-menu-right">
						<li class="dropdown-header visible-xs-block">Seleccionar acción</li>
						<li><a href="#" data-toggle="modal" data-target="#modal_cambiar" data-backdrop="static" data-keyboard="false"><span class="glyphicon glyphicon-calendar"></span> Cambiar fecha</a></li>
					</ul>
				</div>
			</div>
		</div>
		<hr>
		<?php if ($message = get_notification()) : ?>
		<div class="alert alert-<?= $message['type']; ?>">
			<button type="button" class="close" data-dismiss="alert">&times;</button>
			<strong><?= $message['title']; ?></strong>
			<p><?= $message['content']; ?></p>
		</div>
		<?php endif ?>
		<div class="well">
			<p class="lead margin-none">
				<b>Empleado:</b>
				<span><?= escape($_user['nombres'] . ' ' . $_user['paterno'] . ' ' . $_user['materno']); ?></span>
			</p>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="col-sm-12">
					<p class="lead oscureceringresp"><b><a href="?/movimientos/ingresos_listar" class="h2 text-uppercase text-success"><i class="glyphicon glyphicon-log-in" aria-hidden="true"></i> Ingresos</a></b></p>
					<?php if ($ingresos || $cobros) : ?>
						<div class="table-responsive margin-none">
							<table id="compras" class="table table-bordered table-condensed table-striped table-hover table-xs margin-none">
								<thead class="h5">
									<tr class="success">
										<th class="text-middle" rowspan="2">Nº DOC.</th>
										<th class="text-nowrap text-middle" rowspan="2">FECHA</th>
										<th class="text-nowrap text-middle" rowspan="2">DETALLE</th>
										<th class="text-middle text-right" rowspan="2">TOTAL CONCEPTO</th>
										<th class="text-nowrap text-middle text-center" colspan="2">TOTAL PAGADO</th>
									</tr>
									<tr class="success">
										<th class="text-nowrap text-middle text-right">MONTO</th>
										<th class="text-nowrap text-middle text-right">TIPO PAGADO</th>
									</tr>
								</thead>

								<?php if ($cobros) {	?>
									<tbody>								
										<?php foreach ($cobros as $key => $cobro) { 
											if ($cobro['metodo'] == 'efectivo') 
												$total_ingresos = $total_ingresos + $cobro['subtotal']; 
											else
												$total_ingresos_banco = $total_ingresos_banco + $cobro['subtotal'];?>
											<tr>
												<td class="text-nowrap text-middle text-right"><?= $cobro['nro_factura']; ?></td>
												<td class="text-nowrap text-middle"><?= date_decode($cobro['fecha_real'], $_institution['formato']); ?></td>
												<td class="text-middle"><?= $cobro['nombre_cliente']; ?><font size="1"> <?= ($cobro['nit_ci']) ? 'NIT:'.$cobro['nit_ci']:''; ?></font></td>
												<td class="text-nowrap text-middle text-right"><?= number_format($cobro['monto_total'], 2, '.', ''); ?></td>
												<td class="text-nowrap text-middle text-right"><?= number_format($cobro['subtotal'], 2, '.', ''); ?></td>
												<td class="text-right"><?= $cobro['metodo'];  ?></td>
											</tr>
										<?php } ?>
									<?php } ?>
									<?php foreach ($ingresos as $nro => $ingreso) : ?>
										<tr>
											<td class="text-nowrap text-middle text-right"><?= $ingreso['nro_comprobante']; ?></td>	
											<td class="text-nowrap text-middle"><?= date_decode($ingreso['fecha_movimiento'], $_institution['formato']); ?></td>	
											<td class="text-nowrap text-middle"><?= escape($ingreso['concepto']) ?></td>	
											<td class="text-nowrap text-middle text-right"><?= number_format($ingreso['monto'], 2, '.', '') ?></td>	
											<td class="text-nowrap text-middle text-right"><?= number_format($ingreso['monto'], 2, '.', '') ?></td>	
											<td class="text-right">Efectivo</td>	
											<?php $total_ingresos = $total_ingresos + $ingreso['monto']; ?>
										</tr>
									<?php endforeach ?>
									<?php $total_total_ingresos = (($total_ingresos_banco + $total_ingresos )) ? number_format(($total_ingresos_banco + $total_ingresos), 2, '.', ''): number_format(0, 2, '.', ''); ?>
								</tbody>
								<tfoot class="h5">
									<tr class="">
										<th class="text-nowrap text-right" colspan="4">Importe total efectivo <?= escape($moneda); ?></th>
										<th class="text-nowrap text-right" data-subtotal=""><?= $total_ingresos = ($total_ingresos) ? number_format($total_ingresos, 2, '.', ''):  number_format(0, 2, '.', ''); ?></th>
										<th class="text-nowrap text-right" data-subtotal="">Efectivo</th>
									</tr>
									<tr class="">
										<th class="text-nowrap text-right" colspan="4">Importe total entidad financiera <?= escape($moneda); ?></th>
										<th class="text-nowrap text-right" data-subtotal=""><?= $total_ingresos_banco = ($total_ingresos_banco ) ? number_format($total_ingresos_banco, 2, '.', ''): number_format(0, 2, '.', '');  ?></th>
										<th class="text-nowrap text-right" data-subtotal="">Entidad Bancaria</th>
									</tr>
									<tr class="">
										<th class="text-nowrap text-right" colspan="4">Total de totales <?= escape($moneda); ?></th>
										<th class="text-nowrap text-right" data-subtotal=""><?= $total_total_ingresos;  ?></th>
										<th class="text-nowrap text-right" data-subtotal="">Monto Total</th>
									</tr>
								</tfoot>							
							</table>
						</div>
						<?php else : ?>
							<div class="well">No hay ingresos</div>
							<?php $total_ingreso = 0; ?>
						<?php endif ?>
					</div>

<div class="col-sm-12"><br>
<p class="lead oscureceringresp"><b><a href="?/reportes/diario" class="h2 text-uppercase text-success"><i class="glyphicon glyphicon-log-in" aria-hidden="true"></i> Ventas</a></b></p>
<?php if ($ventas) { ?>
<div class="table-responsive margin-none">
	<table id="compras" class="table table-bordered table-condensed table-striped table-hover table-xs margin-none">
		<thead class="h5">
			<tr class="success">
				<th class="text-middle" rowspan="2">Nº DOC.</th>
				<th class="text-nowrap text-middle" rowspan="2">FECHA</th>
				<th class="text-nowrap text-middle" rowspan="2">DETALLE</th>
				<th class="text-middle text-right" rowspan="2">TOTAL CONCEPTO</th>
				<th class="text-nowrap text-middle text-center" colspan="2">TOTAL PAGADO</th>
			</tr>
			<tr class="success">
				<th class="text-nowrap text-middle text-right">MONTO</th>
				<th class="text-nowrap text-middle text-right">TIPO PAGADO</th>
			</tr>
		</thead>				
		<tbody>								
			<?php foreach ($ventas as $key => $venta) { ?>
				<tr>
					<td class="text-nowrap text-middle text-right"><?= $venta['nro_factura']; ?></td>
					<td class="text-nowrap text-middle"><?= date_decode($venta['fecha_egreso'], $_institution['formato']); ?></td>
					<td class="text-middle"><?= $venta['nombre_cliente']; ?><font size="1"> <?= ($venta['nit_ci']) ? 'NIT:'.$venta['nit_ci']. ' - '. escape($venta['tipo']):''; ?></font></td>
					<?php if ($venta['plan_de_pagos'] == 'si'): ?>
						<td class="text-nowrap text-middle text-right"><?= number_format($venta['monto_total'], 2, '.', ''); ?></td>
						<?php $pagos = $db->query("select id_pago, movimiento_id, metodo, ifnull(SUM(abono),0) as subtotal from inv_pagos p left join inv_pago_detalles pd on p.id_pago= pd.pago_id where p.movimiento_id = '". $venta['id_egreso'] . "' AND pd.estado='1' AND p.tipo='Egreso' GROUP by movimiento_id")->fetch_first(); ?>
						<?php if ($pagos): ?>
							<?php 
							if ($pagos['metodo'] == 'Efectivo') 
								$total_venta = $total_venta + $pagos['subtotal']; 
							else 
								$total_venta_banco = $total_venta_banco + $pagos['subtotal']; ?>
							<td class="text-nowrap text-middle text-right"><?= number_format($pagos['subtotal'], 2, '.', ''); ?></td>
							<td class="text-nowrap text-middle text-right"><?= $pagos['metodo']; ?></td>		
							<?php else: ?>											
								<td class="text-nowrap text-middle text-right"><?= number_format(0, 2, '.', ''); ?></td>		
								<td class="text-nowrap text-middle text-right"><i> SIN PAGO </i></td>																
							<?php endif ?>
							<?php else: ?>
								<?php if ($venta['tipo_de_pago'] == 'Efectivo'): ?>
									<?php $total_venta = $total_venta + $venta['monto_total']; ?>
									<td class="text-nowrap text-middle text-right"><?= number_format($venta['monto_total'], 2, '.', ''); ?></td>																
									<td class="text-nowrap text-middle text-right"><?= number_format($venta['monto_total'], 2, '.', ''); ?></td>																
									<td class="text-nowrap text-middle text-right"><?= $venta['tipo_de_pago']; ?></td>		
									<?php else: ?>
										<?php $total_venta_banco = $total_venta_banco + $venta['monto_total']; ?>
										<td class="text-nowrap text-middle text-right"><?= number_format($venta['monto_total'], 2, '.', ''); ?></td>
										<td class="text-nowrap text-middle text-right"><?= number_format($venta['monto_total'], 2, '.', ''); ?></td>														
										<td class="text-nowrap text-middle text-right"><?= $venta['tipo_de_pago']; ?></td>																		
									<?php endif ?>
								<?php endif ?>	
							</tr>
						<?php } ?>
						<?php $total_total_venta = (($total_venta_banco + $total_venta )) ? number_format(($total_venta_banco + $total_venta), 2, '.', ''): number_format(0, 2, '.', ''); ?>
					</tbody>
					<tfoot class="h5">
						<tr class="">
							<th class="text-nowrap text-right" colspan="4">Importe total efectivo <?= escape($moneda); ?></th>
							<th class="text-nowrap text-right" data-subtotal=""><?= $total_venta = ($total_venta) ? number_format($total_venta, 2, '.', ''):  number_format(0, 2, '.', ''); ?></th>
							<th class="text-nowrap text-right" data-subtotal="">Efectivo</th>
						</tr>
						<tr class="">
							<th class="text-nowrap text-right" colspan="4">Importe total entidad financiera <?= escape($moneda); ?></th>
							<th class="text-nowrap text-right" data-subtotal=""><?= $total_venta_banco = ($total_venta_banco ) ? number_format($total_venta_banco, 2, '.', ''): number_format(0, 2, '.', '');  ?></th>
							<th class="text-nowrap text-right" data-subtotal="">Entidad Bancaria</th>
						</tr>
						<tr class="">
							<th class="text-nowrap text-right" colspan="4">Total de totales <?= escape($moneda); ?></th>
							<th class="text-nowrap text-right" data-subtotal=""><?= $total_total_venta;  ?></th>
							<th class="text-nowrap text-right" data-subtotal="">Monto Total</th>
						</tr>
					</tfoot>							
				</table>
			</div>
		<?php } else { ?>
			<div class="well">No hay ventas</div>
			<?php $total_venta = 0; ?>
		<?php } ?>
	</div>
</div>

<div class="col-sm-6">
	<div class="col-sm-12">
		<p class="lead oscureceregresp"><b><a href="?/movimientos/egresos_listar" class="h2 text-uppercase text-danger"><i class="glyphicon glyphicon-log-out" aria-hidden="true"></i> Egresos</a></b></p>					
		<?php if ($egresos || $pagos_compras || $cronogramas) : ?>
			<div class="table-responsive margin-none">
				<table id="compras" class="table table-bordered table-condensed table-striped table-hover table-xs margin-none">
					<thead class="h5">
						<tr class="danger">
							<th class="text-middle" rowspan="2">Nº DOC.</th>
							<th class="text-nowrap text-middle" rowspan="2">FECHA</th>
							<th class="text-nowrap text-middle" rowspan="2">DETALLE</th>
							<th class="text-middle text-right" rowspan="2">TOTAL CONCEPTO</th>
							<th class="text-nowrap text-middle text-center" colspan="2">TOTAL PAGADO</th>
						</tr>
						<tr class="danger">
							<th class="text-nowrap text-middle text-right">MONTO</th>
							<th class="text-nowrap text-middle text-right">TIPO PAGADO</th>
						</tr>
					</thead>				
					<tbody>	
						<?php 
						if ($cronogramas) {	?>
							<?php foreach ($cronogramas as $key => $cronograma) { 
								if ($cronograma['metodo'] == 'Efectivo') 
									$total_egresos = $total_egresos + $cronograma['monto'];
								else
									$total_egresos_banco = $total_egresos_banco + $cronograma['monto'];	?>
								<tr>
									<td class="text-nowrap text-middle text-right"><?= $cronograma['id_cronograma_cuentas']; ?></td>
									<td class="text-nowrap text-middle"><?= date_decode($cronograma['fecha_real'], $_institution['formato']); ?></td>
									<td class="text-middle"><?= $cronograma['detalle']; ?></td>
									<td class="text-nowrap text-middle text-right"><?= number_format($cronograma['monto'], 2, '.', ''); ?></td>
									<td class="text-nowrap text-middle text-right"><?= number_format($cronograma['monto'], 2, '.', ''); ?></td>
									<td class="text-nowrap text-middle text-right"><?= $cronograma['metodo']; ?></td>
								</tr>
							<?php } ?>
						<?php } ?>

						<?php if ($pagos_compras) {	?>
							<?php foreach ($pagos_compras as $nro => $pagos_compra) { 
								if ($pagos_compra['metodo'] == 'Efectivo') 
									$total_egresos = $total_egresos + $pagos_compra['subtotal'];
								else
									$total_egresos_banco = $total_egresos_banco + $pagos_compra['subtotal']; ?>
								<tr>
									<td class="text-nowrap text-middle text-right"><?= $pagos_compra['id_ingreso']; ?></td>
									<td class="text-nowrap text-middle"><?= date_decode($pagos_compra['fecha_ingreso'], $_institution['formato']); ?></td>
									<td class="text-middle"><?= escape($pagos_compra['razon_social']); ?> <font size="1"><?= escape($pagos_compra['tipo']) ?></font></td>
									<td class="text-nowrap text-middle text-right"><?= number_format($pagos_compra['monto_total'], 2, '.', ''); ?></td>
									<td class="text-nowrap text-middle text-right"><?= number_format($pagos_compra['subtotal'], 2, '.', ''); ?></td>
									<td class="text-nowrap text-middle text-right"><?= $pagos_compra['metodo']; ?></td>
								</tr>
							<?php } ?>
						<?php } ?>

						<?php foreach ($egresos as $nro => $egreso) : ?>
							<?php $total_egresos = $total_egresos + $egreso['monto']; ?>
							<tr>
								<td class="text-nowrap text-middle text-right"><?= $egreso['nro_comprobante']; ?></td>
								<td class="text-nowrap text-middle"><?= date_decode($egreso['fecha_movimiento'], $_institution['formato']); ?></td>
								<td class="text-middle"><?= escape($egreso['concepto']); ?></td>
								<td class="text-nowrap text-middle text-right"><?= number_format($egreso['monto'], 2, '.', ''); ?></td>
								<td class="text-nowrap text-middle text-right"><?= number_format($egreso['monto'], 2, '.', ''); ?></td>
								<td class="text-nowrap text-middle text-right">Efectivo</td>
							</tr>
						<?php endforeach ?>
						<?php $total_total_egresos = (($total_egresos_banco + $total_egresos )) ? number_format(($total_egresos_banco + $total_egresos), 2, '.', ''): number_format(0, 2, '.', ''); ?>
					</tbody>
					<tfoot class="h5">
						<tr class="">
							<th class="text-nowrap text-right" colspan="4">Importe total efectivo <?= escape($moneda); ?></th>
							<th class="text-nowrap text-right" data-subtotal=""><?= $total_egresos = ($total_egresos) ? number_format($total_egresos, 2, '.', ''):  number_format(0, 2, '.', ''); ?></th>
							<th class="text-nowrap text-right" data-subtotal="">Efectivo</th>
						</tr>
						<tr class="">
							<th class="text-nowrap text-right" colspan="4">Importe total entidad financiera <?= escape($moneda); ?></th>
							<th class="text-nowrap text-right" data-subtotal=""><?= $total_egresos_banco = ($total_egresos_banco ) ? number_format($total_egresos_banco, 2, '.', ''): number_format(0, 2, '.', '');  ?></th>
							<th class="text-nowrap text-right" data-subtotal="">Entidad Bancaria</th>
						</tr>
						<tr class="">
							<th class="text-nowrap text-right" colspan="4">Total de totales <?= escape($moneda); ?></th>
							<th class="text-nowrap text-right" data-subtotal=""><?= $total_total_egresos;  ?></th>
							<th class="text-nowrap text-right" data-subtotal="">Monto Total</th>
						</tr>
					</tfoot>							
				</table>
			</div>
			<?php else : ?>
				<div class="well">No hay egresos</div>
				<?php $total_egreso = 0; ?>
			<?php endif ?>
		</div>

		<div class="col-sm-12"><br>
			<p class="lead oscureceregresp"><b><a href="#" class="h2 text-uppercase text-danger"><i class="glyphicon glyphicon-log-out" aria-hidden="true"></i> Compras</a></b></p>
			<?php if ($compras) { ?>
				<div class="table-responsive margin-none">
					<table id="compras" class="table table-bordered table-condensed table-striped table-hover table-xs margin-none">
						<thead class="h5">
							<tr class="danger ">
								<th class="text-middle" rowspan="2">Nº DOC.</th>
								<th class="text-nowrap text-middle" rowspan="2">FECHA</th>
								<th class="text-nowrap text-middle" rowspan="2">DETALLE</th>
								<th class="text-middle text-right" rowspan="2">TOTAL CONCEPTO</th>
								<th class="text-nowrap text-middle text-center" colspan="2">TOTAL PAGADO</th>
							</tr>
							<tr class="danger">
								<th class="text-nowrap text-middle text-right">MONTO</th>
								<th class="text-nowrap text-middle text-right">TIPO PAGADO</th>
							</tr>
						</thead>				
						<tbody>	
							<?php foreach ($compras as $key => $compra) { ?>
								<tr>
									<td class="text-nowrap text-middle text-right"><?= $compra['id_ingreso']; ?></td>
									<td class="text-nowrap text-middle"><?= date_decode($compra['fecha_ingreso'], $_institution['formato']); ?></td>
									<td class="text-middle"><?= escape($compra['razon_social']); ?><font size="1"> <?= (escape($compra['tipo'])) ? escape($compra['tipo']):''; ?></font></td>
									<?php if ($compra['plan_de_pagos'] == 'si'): ?>
										<td class="text-nowrap text-middle text-right"><?= number_format($compra['monto_total'], 2, '.', ''); ?></td>
										<?php $pagos = $db->query("select id_pago, movimiento_id, pd.metodo, ifnull(SUM(abono),0) as subtotal from inv_pagos p left join inv_pago_detalles pd on p.id_pago= pd.pago_id where p.movimiento_id = '". $compra['id_ingreso'] . "' AND p.tipo='Ingreso' AND pd.estado='1' GROUP by movimiento_id")->fetch_first(); ?>
										<?php if ($pagos): ?>
											<?php 
											if ($pagos['metodo'] == 'Efectivo') 
												$total_compra = $total_compra + $pagos['subtotal']; 							
											else
												$total_compra_banco = $total_compra_banco + $pagos['monto_total']; ?>
											<td class="text-nowrap text-middle text-right"><?= number_format($pagos['subtotal'], 2, '.', ''); ?></td>
											<td class="text-nowrap text-middle text-right"><?= $pagos['metodo']; ?></td>		
											<?php else: ?>											
												<td class="text-nowrap text-middle text-right"><?= number_format(0, 2, '.', ''); ?></td>		
												<td class="text-nowrap text-middle text-right"><i> SIN PAGO </i></td>																
											<?php endif ?>
											<?php else: ?>
												<?php if ($compra['metodo'] == 'Efectivo'): ?>
													<?php $total_compra = $total_compra + $compra['monto_total']; ?>
													<td class="text-nowrap text-middle text-right"><?= number_format($compra['monto_total'], 2, '.', ''); ?></td>													
													<td class="text-nowrap text-middle text-right"><?= number_format($compra['monto_total'], 2, '.', ''); ?></td>													
													<td class="text-nowrap text-middle text-right"><?= $compra['metodo']; ?></td>		
													<?php else: ?>
														<?php $total_venta_banco = $total_compra_banco + $compra['monto_total']; ?>
														<td class="text-nowrap text-middle text-right"><?= number_format($compra['monto_total'], 2, '.', ''); ?></td>
														<td class="text-nowrap text-middle text-right"><?= number_format($compra['monto_total'], 2, '.', ''); ?></td>												
														<td class="text-nowrap text-middle text-right"><?= $compra['metodo']; ?></td>																		
													<?php endif ?>
												<?php endif ?>	
											</tr>	
										<?php } ?>														
										<?php $total_total_compra = (($total_compra_banco + $total_compra )) ? number_format(($total_compra_banco + $total_compra), 2, '.', ''): number_format(0, 2, '.', ''); ?>	
									</tbody>
									<tfoot class="h5">
										<tr class="">
											<th class="text-nowrap text-right" colspan="4">Importe total efectivo <?= escape($moneda); ?></th>
											<th class="text-nowrap text-right" data-subtotal=""><?= $total_compra = ($total_compra) ? number_format($total_compra, 2, '.', ''):  number_format(0, 2, '.', ''); ?></th>
											<th class="text-nowrap text-right" data-subtotal="">Efectivo</th>
										</tr>
										<tr class="">
											<th class="text-nowrap text-right" colspan="4">Importe total entidad financiera <?= escape($moneda); ?></th>
											<th class="text-nowrap text-right" data-subtotal=""><?= $total_compra_banco = ($total_compra_banco ) ? number_format($total_compra_banco, 2, '.', ''): number_format(0, 2, '.', '');  ?></th>
											<th class="text-nowrap text-right" data-subtotal="">Entidad Bancaria</th>
										</tr>
										<tr class="">
											<th class="text-nowrap text-right" colspan="4">Total de totales <?= escape($moneda); ?></th>
											<th class="text-nowrap text-right" data-subtotal=""><?= $total_total_compra;  ?></th>
											<th class="text-nowrap text-right" data-subtotal="">Monto Total</th>
										</tr>
									</tfoot>							
								</table>
							</div>
						<?php } else { ?>
							<div class="well">No hay compras</div>
							<?php $total_compra = 0; ?>
						<?php } ?>
					</div>
					<div class="col-sm-12">
						<p class="lead oscureceregresp" ><b><a href="?/movimientos/gastos_listar" class="h2 text-uppercase text-danger"><i class="glyphicon glyphicon-log-out" aria-hidden="true"></i> Gastos</a></b></p>
						<?php if ($gastos) : ?>

							<div class="table-responsive margin-none">
								<table id="compras" class="table table-bordered table-condensed table-striped table-hover table-xs margin-none">
									<thead class="h5">
										<tr class="danger">
											<th class="text-middle" rowspan="2">Nº DOC.</th>
											<th class="text-nowrap text-middle" rowspan="2">FECHA</th>
											<th class="text-nowrap text-middle" rowspan="2">DETALLE</th>
											<th class="text-middle text-right" rowspan="2">TOTAL CONCEPTO</th>
											<th class="text-nowrap text-middle text-center" colspan="2">TOTAL PAGADO</th>
										</tr>
										<tr class="danger">
											<th class="text-nowrap text-middle text-right">MONTO</th>
											<th class="text-nowrap text-middle text-right">TIPO PAGADO</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($gastos as $nro => $gasto) : ?>
											<?php $total_gasto = $total_gasto + $gasto['monto']; ?>
											<tr>
												<td class="text-nowrap text-middle text-right"><?= $gasto['nro_comprobante']; ?></td>	
												<td class="text-nowrap text-middle"><?= date_decode($gasto['fecha_movimiento'], $_institution['formato']); ?></td>	
												<td class="text-nowrap text-middle"><?= escape($gasto['concepto']) ?></td>	
												<td class="text-nowrap text-middle text-right"><?= number_format($gasto['monto'], 2, '.', '') ?></td>	
												<td class="text-nowrap text-middle text-right"><?= number_format($gasto['monto'], 2, '.', '') ?></td>	
												<td class="text-right">Efectivo</td>	
											</tr>
										<?php endforeach ?>
										<?php $total_total_gasto = (($total_gasto_banco + $total_gasto )) ? number_format(($total_gasto_banco + $total_gasto), 2, '.', ''): number_format(0, 2, '.', ''); ?>
									</tbody>
									<tfoot class="h5">
										<tr class="">
											<th class="text-nowrap text-right" colspan="4">Importe total efectivo <?= escape($moneda); ?></th>
											<th class="text-nowrap text-right" data-subtotal=""><?= $total_gasto = ($total_gasto) ? number_format($total_gasto, 2, '.', ''):  number_format(0, 2, '.', ''); ?></th>
											<th class="text-nowrap text-right" data-subtotal="">Efectivo</th>
										</tr>
										<tr class="">
											<th class="text-nowrap text-right" colspan="4">Importe total entidad financiera <?= escape($moneda); ?></th>
											<th class="text-nowrap text-right" data-subtotal=""><?= $total_gasto_banco = ($total_gasto_banco ) ? number_format($total_gasto_banco, 2, '.', ''): number_format(0, 2, '.', '');  ?></th>
											<th class="text-nowrap text-right" data-subtotal="">Entidad Bancaria</th>
										</tr>
										<tr class="">
											<th class="text-nowrap text-right" colspan="4">Total de totales <?= escape($moneda); ?></th>
											<th class="text-nowrap text-right" data-subtotal=""><?= $total_total_gasto;  ?></th>
											<th class="text-nowrap text-right" data-subtotal="">Monto Total</th>
										</tr>
									</tfoot>							
								</table>
							</div>
							<?php else : ?>
								<div class="well">No hay gastos</div>
								<?php $total_gasto = 0; ?>
							<?php endif ?>
						</div>
					</div>
				</div>
				<br>
				<div class="well">
					<p class="lead margin-none">
						<b>Total Efectivo:</b>
						<u id="total"><?= number_format(($total_ingresos + $total_venta) - ($total_egresos + $total_compra + $total_gasto) , 2, '.', ''); ?></u>
						<span><?= escape($moneda); ?></span>
					</p>
					<p class="margin-none">
						<em>El total corresponde a la siguiente fórmula:</em>
						<samp><b>( Ingresos + Ventas ) - ( Egresos + Compras + Gastos ) </b></samp>
					</p>
				</div>
			</div>

			<!-- Modal cambiar inicio -->
			<div id="modal_cambiar" class="modal fade" tabindex="-1">
				<div class="modal-dialog">
					<form method="post" action="?/movimientos/cerrar" id="form_cambiar" class="modal-content loader-wrapper" autocomplete="off">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Cambiar fecha</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="fecha_cambiar" class="control-label">Fecha:</label>
								<input type="text" value="<?= date_decode($fecha, $_institution['formato']); ?>" name="fecha" id="fecha_cambiar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">
								<span class="glyphicon glyphicon-share-alt"></span>
								<span>Cambiar</span>
							</button>
							<button type="reset" class="btn btn-default">
								<span class="glyphicon glyphicon-refresh"></span>
								<span>Restablecer</span>
							</button>
						</div>
						<div id="loader_cambiar" class="loader-wrapper-backdrop hidden">
							<span class="loader"></span>
						</div>
					</form>
				</div>
			</div>



			<div id="abrir_caja" class="abrir_caja modal fade">
				<div class="modal-dialog">
					<form method="post" action="?/movimientos/abrir_caja" id="form_abrir_caja" class="modal-content loader-wrapper" autocomplete="off">
						<div class="modal-header">
							<h4 class="modal-title">Abrir Caja</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="unidad_id_asignar" class="control-label">Fecha:</label>
								<input type="text" value="<?= date_decode($fecha, $_institution['formato']); ?>" name="fecha_abrir_caja" id="fecha_abrir_caja" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">
								<span class="glyphicon glyphicon-ok"></span>
								<span>Guardar</span>
							</button>
							<button type="reset" class="btn btn-default" data-cancelar-asignar="true">
								<span class="glyphicon glyphicon-remove"></span>
								<span>Cancelar</span>
							</button>
						</div>
						<div id="loader_abrir" class="loader-wrapper-backdrop occult">
							<span class="loader"></span>
						</div>
					</form>
				</div>
			</div>


			<!-- INICIAR CAJA -->
			<div id="iniciar_caja" class="iniciar_caja modal fade">
				<div class="modal-dialog">
					<form method="post" action="?/movimientos/abrir_caja" id="form_iniciar_caja" class="modal-content loader-wrapper" autocomplete="off">
						<div class="modal-header">
							<h4 class="modal-title">Iniciar Caja</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="unidad_id_asignar" class="control-label">Fecha:</label>
								<input type="text" value="<?= date_decode($fecha, $_institution['formato']); ?>" name="fecha_inicio_caja" id="fecha_inicio_caja" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">
								<span class="glyphicon glyphicon-ok"></span>
								<span>Guardar</span>
							</button>
							<button type="reset" class="btn btn-default" data-cancelar-asignar="true">
								<span class="glyphicon glyphicon-remove"></span>
								<span>Cancelar</span>
							</button>
						</div>
						<div id="loader_iniciar" class="loader-wrapper-backdrop occult">
							<span class="loader"></span>
						</div>
					</form>
				</div>
			</div>
			<!-- CERRAR CAJA -->
			<div id="cerrar_caja" class="cerrar_caja modal fade">
				<div class="modal-dialog">
					<form method="post" action="?/movimientos/cerrar_caja" id="form_cerrar_caja" class="modal-content loader-wrapper" autocomplete="off">
						<div class="modal-header">
							<h4 class="modal-title">Cerrar Caja</h4>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="unidad_id_asignar" class="control-label">Fecha:</label>
								<input type="text" value="<?= date_decode($fecha, $_institution['formato']); ?>" name="fecha_cierre_caja" id="fecha_cierre_caja" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" class="btn btn-primary">
								<span class="glyphicon glyphicon-ok"></span>
								<span>Guardar</span>
							</button>
							<button type="reset" class="btn btn-default" data-cancelar-asignar="true">
								<span class="glyphicon glyphicon-remove"></span>
								<span>Cancelar</span>
							</button>
						</div>
						<div id="loader_cerrar" class="loader-wrapper-backdrop occult">
							<span class="loader"></span>
						</div>
					</form>
				</div>
			</div>



			<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
			<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
			<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>
			<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
			<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
			<script src="<?= js; ?>/jquery.base64.js"></script>
			<script src="<?= js; ?>/pdfmake.min.js"></script>
			<script src="<?= js; ?>/vfs_fonts.js"></script>
			<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>
			<script src="<?= js; ?>/bootstrap-notify.min.js"></script>
			<script>
				$(function () {
					var $modal_cambiar = $('#modal_cambiar'), $form_cambiar = $('#form_cambiar'), $loader_cambiar = $('#loader_cambiar'), $fecha_cambiar = $('#fecha_cambiar'), $abrir_caja = $('#abrir_caja'), $form_abrir_caja = $('#form_abrir_caja'), $fecha_abrir_caja = $('#fecha_abrir_caja'), $iniciar_caja = $('#iniciar_caja'), $form_iniciar_caja = $('#form_iniciar_caja'), $fecha_inicio_caja = $('#fecha_inicio_caja');
					var $loader_iniciar = $('loader_iniciar'), $cerrar_caja = $('#cerrar_caja'), $form_cerrar_caja = $('#form_cerrar_caja'), $fecha_cierre_caja = $('#fecha_cierre_caja'), $loader_abrir = $('#loader_abrir');

					

					$.validate({
						form: '#form_cambiar',
						modules: 'date',
						onSuccess: function () {
							$loader_cambiar.removeClass('hidden');
							var direccion_cambiar = $.trim($form_cambiar.attr('action')), fecha_cambiar = $.trim($fecha_cambiar.val());
							fecha_cambiar = fecha_cambiar.replace(new RegExp('/', 'g'), '-');
							window.location = direccion_cambiar + '/' + fecha_cambiar;
						}
					});

					<?php if(!$ultimo_registro_caja){ ?>
						$.validate({
							form: '#form_iniciar_caja',
							modules: 'date',
							onSuccess: function () {
								$loader_iniciar.removeClass('hidden');														
								var estado = "<?= $estado; ?>";															
								var direccion_cambiar = $.trim($form_iniciar_caja.attr('action')), fecha_cambiar = $.trim($fecha_inicio_caja.val());
								fecha_cambiar = fecha_cambiar.replace(new RegExp('/', 'g'), '-');
								$.ajax({
									type: 'post',
									dataType: 'json',
									url: direccion_cambiar,
									data: {
										estado: estado,
										fecha: fecha_cambiar,																
									}
								}).done(function (producto) {
									window.location.reload();
									$iniciar_caja.modal('hide');
								}).fail(function () {
									$.notify({
										message: 'Ocurrió un problema al realizar el incio de caja.'
									}, {
										type: 'danger'
									});
								}).always(function () {
									$loader_iniciar.fadeOut(100, function () {
										$iniciar_caja.modal('hide');
									});
								});
							}
						});
					<?php } ?>

					<?php if($estado == 'INICIO'){ ?>
						$.validate({
							form: '#form_abrir_caja',
							modules: 'date',
							onSuccess: function () {
								$loader_iniciar.removeClass('hidden');														
								var estado = "<?= $estado;  ?>";
								var direccion_cambiar = $.trim($form_abrir_caja.attr('action')), fecha_cambiar = $.trim($fecha_abrir_caja.val());
								fecha_cambiar = fecha_cambiar.replace(new RegExp('/', 'g'), '-');
								$.ajax({
									type: 'post',
									dataType: 'json',
									url: direccion_cambiar,
									data: {
										estado: estado,
										fecha: fecha_cambiar,																
									}
								}).done(function (producto) {
									window.location.reload();
									$abrir_caja.modal('hide');
								}).fail(function () {
									$.notify({
										message: 'Ocurrió un problema al realizar el incio de caja.'
									}, {
										type: 'danger'
									});
								}).always(function () {
									$loader_abrir.fadeOut(100, function () {
										$abrir_caja.modal('hide');
									});
								});
							}
						});
					<?php } ?>



					<?php if($estado == 'CIERRE'){ ?>
						$.validate({
							form: '#form_cerrar_caja',
							modules: 'date',
							onSuccess: function () {
								$loader_iniciar.removeClass('hidden');														
								var estado = "<?= $estado;  ?>";
								var direccion_cambiar = $.trim($form_cerrar_caja.attr('action')), fecha_cambiar = $.trim($fecha_cierre_caja.val());
								fecha_cambiar = fecha_cambiar.replace(new RegExp('/', 'g'), '-');

								$.ajax({
									type: 'post',
									dataType: 'json',
									url: direccion_cambiar,
									data: {
										estado: estado,
										fecha: fecha_cambiar,																
									}
								}).done(function (producto) {
									$iniciar_caja.modal('hide');
									window.location.reload();
									$.notify({
										message: 'Se inicio correctamente la caja.'
									}, {
										type: 'success'
									});
								}).fail(function () {
									$.notify({
										message: 'Ocurrió un problema al realizar el incio de caja.'
									}, {
										type: 'danger'
									});
								}).always(function () {
									$loader_iniciar.fadeOut(100, function () {
										$iniciar_caja.modal('hide');
									});
								});
							}
						});
					<?php } ?>

					$fecha_inicio_caja.datetimepicker({
						format: '<?= strtoupper($formato_textual); ?>'
					});

					$fecha_cambiar.datetimepicker({
						format: '<?= strtoupper($formato_textual); ?>'
					});

					var date = new Date(); 
					var today = new Date(date.getFullYear(), date.getMonth(), date.getDate()); 

					$fecha_abrir_caja.datetimepicker({
						format: '<?= strtoupper($formato_textual); ?>',
						minDate: today
					});
					
					$fecha_cierre_caja.datetimepicker({
						format: '<?= strtoupper($formato_textual); ?>',
						minDate: today 
					});

					$form_cambiar.on('submit', function (e) {
						e.preventDefault();
					});

					$modal_cambiar.on('hidden.bs.modal', function () {
						$form_cambiar.trigger('reset');
					}).on('show.bs.modal', function (e) {
						if ($('.modal:visible').size() != 0) { e.preventDefault(); }
					});

					$abrir_caja.on('hidden.bs.modal', function () {
						$form_abrir_caja.trigger('reset');
					}).on('show.bs.modal', function (e) {
						if ($('.modal:visible').size() != 0) { e.preventDefault(); }
					});

					$abrir_caja.find('[data-cancelar-asignar]').on('click', function () {
						$abrir_caja.modal('hide');
					});

					$form_abrir_caja.on('submit', function (e) {
						e.preventDefault();
					});

					$iniciar_caja.find('[data-cancelar-asignar]').on('click', function () {
						$iniciar_caja.modal('hide');
					});

					$form_iniciar_caja.on('submit', function (e) {
						e.preventDefault();
					});

					$cerrar_caja.find('[data-cancelar-asignar]').on('click', function () {
						$cerrar_caja.modal('hide');
					});

					$form_cerrar_caja.on('submit', function (e) {
						e.preventDefault();
					});
					

					$('#table').DataFilter({
						name: 'asistencias',
						reports: 'excel|word|pdf|html',
						values: {
							stateSave: true
						}
					});

					$(".alert").slideUp(2000);


					$('#modal_aperturar').modal({
			    		backdrop: 'static',
			    		keyboard: false    	
					});
					$('#spinner_aperturar').hide();		
					$(window).ready(function(){
						$("[data-titulo-caja]").text("Cierre de caja");
		   				$("[data-monto-caja]").text("Monto de cierre");
					});
				});

function imprimir_diario() {
	var fecha = '<?= $fecha; ?>';
	$.open('?/movimientos/imprimir/' + fecha, true);
	window.location.reload();
}
</script>
<?php require_once show_template('footer-sidebar'); ?>