<?php 

// Obtiene las fechas inicial y final
// $fecha_inicial = str_replace('/', '-', now($_format));
// $fecha_final = str_replace('/', '-', now($_format));

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format); 

// Verifica si existen los parametros
// if (sizeof($_params) == 2) {
// 	// Verifica el tipo de los parametros
// 	if (!is_date($_params[0]) || !is_date($_params[1])) {
// 		// Redirecciona la pagina
// 		redirect('?/movimientos/egresos-listar/' . $fecha_inicial . '/' . $fecha_final);
// 	}
// } else {
// 	// Redirecciona la pagina
// 	redirect('?/movimientos/egresos-listar/' . $fecha_inicial . '/' . $fecha_final);
// } 
  
// Obtiene los parametros
// $fecha_inicial = date_encode($_params[0]);
// $fecha_final = date_encode($_params[1]);

// Obtiene el rango de fechas
$gestion = date('Y');
$gestion_base = date('Y-m-d');
//$gestion_base = ($gestion - 16) . date('-m-d');
$gestion_limite = ($gestion + 16) . date('-m-d');

// Obtiene fecha inicial
$fecha = (isset($_params[0])) ? $_params[0] : $gestion_base;
$fecha = (is_date($fecha)) ? $fecha : $gestion_base;
$fecha = date_encode($fecha);

// Obtiene la moneda oficial
$moneda = $db->from('gen_monedas')->where('principal', 's')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['codigo'] . ')' : '';

// Obtiene todos los pagos realizados
$cobros = $db->query("SELECT pg.*, sp.id_persona, CONCAT(sp.nombres,' ',sp.primer_apellido,' ', sp.segundo_apellido) nombre_empleado
FROM pen_pensiones_estudiante_general pg
INNER JOIN sys_users su ON pg.usuario_registro = su.id_user
INNER JOIN sys_persona sp ON su.persona_id = sp.id_persona
WHERE pg.fecha_general = '$fecha'
AND pg.estado_factura = 'ACTIVO'
ORDER BY pg.fecha_general ASC")->fetch();
//var_dump($cobros);exit();

//COBRO DE VENTAS
$cobros_extra = $db->query("SELECT pg.*, sp.id_persona, CONCAT(sp.nombres,' ',sp.primer_apellido,' ', sp.segundo_apellido) nombre_empleado
FROM pen_pensiones_estudiante_general pg
INNER JOIN sys_users su ON pg.usuario_registro = su.id_user
INNER JOIN sys_persona sp ON su.persona_id = sp.id_persona
WHERE pg.fecha_general = '$fecha'
AND pg.estado_factura = 'EE'
ORDER BY pg.fecha_general ASC")->fetch();

// Obtiene los ingresos
$ingresos = $db->select("m.*, concat(p.nombres, ' ', p.primer_apellido, ' ', p.segundo_apellido) as empleado")
				->from('caj_movimientos m')
				->join('per_asignaciones e', 'm.asignacion_id = e.id_asignacion', 'left')
				->join('sys_persona p', 'e.persona_id = p.id_persona', 'left')
				->where('m.tipo', 'i')
				->where('p.id_persona', $_user['persona_id'])
				->where('m.fecha_movimiento', $fecha)
				->order_by('m.fecha_movimiento desc, m.hora_movimiento desc')
				->fetch();

// Obtiene los egresos
$egresos = $db->select("m.*, concat(p.nombres, ' ', p.primer_apellido, ' ', p.segundo_apellido) as empleado")
				->from('caj_movimientos m')
				->join('per_asignaciones e', 'm.asignacion_id = e.id_asignacion', 'left')
				->join('sys_persona p', 'e.persona_id = p.id_persona', 'left')
				->where('m.tipo', 'e')
				->where('p.id_persona', $_user['persona_id'])
				->where('m.fecha_movimiento', $fecha)
				->order_by('m.fecha_movimiento desc, m.hora_movimiento desc')
				->fetch();

// Obtiene los gastos
$gastos = $db->select("m.*, concat(p.nombres, ' ', p.primer_apellido, ' ', p.segundo_apellido) as empleado")
				->from('caj_movimientos m')
				->join('per_asignaciones e', 'm.asignacion_id = e.id_asignacion', 'left')
				->join('sys_persona p', 'e.persona_id = p.id_persona', 'left')
				->where('m.tipo', 'g')
				->where('p.id_persona', $_user['persona_id'])
				->where('m.fecha_movimiento', $fecha)
				->order_by('m.fecha_movimiento desc, m.hora_movimiento desc')
				->fetch();

$ultimo_registro_caja = $db->query("SELECT * 
									FROM inv_caja
									WHERE fecha_caja = (
										SELECT MAX(fecha_caja) AS fecha 
										FROM inv_caja
									) 
									AND id_caja = (
										SELECT MAX(id_caja) AS fecha 
										FROM inv_caja
									)")->fetch_first(); 
//var_dump($ultimo_registro_caja);
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

// Almacena los permisos en variables
$permiso_crear 		= in_array('gastos-crear', $_views);
$permiso_modificar 	= in_array('gastos-modificar', $_views);
$permiso_eliminar 	= in_array('gastos-eliminar', $_views);
$permiso_cambiar = true;
$permiso_imprimir = true;

?>
<!-- variables usadas -->
<?php $total_ingresos = $total_ingresos_banco = $total_venta = $total_venta_banco = $total_egresos = $total_egresos_banco = $total_compra = $total_compra_banco = $total_gasto = $total_gasto_banco = 0; ?>

<?php require_once show_template('header-design'); ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-header">
            <h2 class="pageheader-title">Reporte General de Caja</h2>
            <p class="pageheader-text"></p>
            <div class="page-breadcrumb">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Caja</a></li>
                        <li class="breadcrumb-item"><a href="#" class="breadcrumb-link">Reporte General de Caja</a></li>
                        <!-- <li class="breadcrumb-item active" aria-current="page">Listar</li> -->
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- ============================================================== -->
<!-- end pageheader -->
<!-- ============================================================== -->
<div class="row">
    <!-- ============================================================== -->
    <!-- row -->
    <!-- ============================================================== -->
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="card">
            <!-- <h5 class="card-header">Generador de menús</h5> -->
            <div class="card-header">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6">
                        <div class="text-label hidden-xs">Seleccione:</div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-6 text-right">
                    	<a href="?/movimientos/imprimir-general/<?= $fecha; ?>" target="_blank" class="btn btn-default"><i class="glyphicon glyphicon-print"></i><span class="hidden-xs hidden-sm"> Exportar</span></a>
				<?php if ($ultimo_registro_caja): ?>
					<?php if ($ultimo_registro_caja['estado'] == 'INICIO' || $ultimo_registro_caja['estado'] == 'CAJA'): ?>
						<a href="?/movimientos/caja/<?= $fecha; ?>" target="_blank" class="btn btn-success"><i class="glyphicon glyphicon-list"></i><span class="hidden-xs hidden-sm"> Historial Caja</span></a>				
					<?php endif ?>
					<?php if ($ultimo_registro_caja['estado'] == 'CIERRE'): ?>
						<a href="#" data-toggle="modal" data-target="#abrir_caja" class="btn btn-info" data-backdrop="static" data-keyboard="false"><span class="glyphicon glyphicon-folder-close"></span> Abrir Caja</a>				
					<?php endif ?>
					<?php else: ?>
						<a href="#" data-toggle="modal" data-target="#iniciar_caja" class="btn btn-info" data-backdrop="static" data-keyboard="false"><span class="glyphicon glyphicon-file"></span> Iniciar Caja</a>
					<?php endif ?>

					<!-- <div class="btn-group">
						<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
							<span class="glyphicon glyphicon-wrench"></span>
							<span class="hidden-xs">Acciones</span>
						</button>
						<ul class="dropdown-menu dropdown-menu-right">
							<li class="dropdown-header visible-xs-block">Seleccionar acción</li>
							<li><a href="#" data-toggle="modal" data-target="#modal_cambiar" data-backdrop="static" data-keyboard="false"><span class="glyphicon glyphicon-calendar"></span> Cambiar fecha</a></li>
						</ul>
					</div> -->
                        <div class="btn-group">
                             <div class="input-group">
                                <div class="input-group-append be-addon">
                                    <button type="button" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">Acciones</button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item">Seleccionar acción</a>
                                        <div class="dropdown-divider"></div>
                                            <!-- <a class="dropdown-item" data-cambiar="true"><span class="glyphicon glyphicon-print"></span> Cambiar Fecha</a> -->
                                            <a href="#" data-toggle="modal" data-target="#modal_cambiar" data-backdrop="static" data-keyboard="false"><span class="glyphicon glyphicon-calendar"></span> Cambiar fecha</a>
                                        <!-- <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="?/movimientos/gastos-crear"><span class="glyphicon glyphicon-plus"></span> Nuevo</a> -->
                                    </div>
                                </div>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
			<?php if ($message = get_notification()) : ?>
			<div class="alert alert-<?= $message['type']; ?>">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong><?= $message['title']; ?></strong>
				<p><?= $message['content']; ?></p>
			</div>
			<?php endif ?>
            <div class="card-body"> 
                <!-- ============================================================== -->
                <!-- datos --> 
                <!-- ============================================================== -->
                <div class="row">
					<div class="col-sm-6">
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<p class="lead margin-none">
										<b>Total Efectivo:</b>
										<u id="total_cabecera"></u>
										<span><?= escape($moneda); ?></span>
									</p>
									<p class="margin-none">
										<em>El total corresponde a:</em>
										<samp><b>( Ingresos + Pensiones + Pensiones Extracurriculares ) - ( Egresos + Gastos ) </b></samp>
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-sm-6">				
						<div class="card">
							<div class="card-content">
								<div class="card-body">
									<p class="lead margin-none">
										<b>Empleado:</b>
										<span><?= escape($_user['nombres'] . ' ' . $_user['primer_apellido'] . ' ' . $_user['segundo_apellido']); ?></span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-sm-6">
						<!-- Ingresos -->
						<div class="col-sm-12">
							<p class="lead"><b><a href="?/movimientos/ingresos-listar" class="text-uppercase text-success"><i class="glyphicon glyphicon-log-in"></i> Ingresos</a></b></p>
							<?php if ($ingresos) : ?>
								<div class="table-responsive">
									<table id="compras" class="table table-bordered table-condensed table-striped table-hover margin-none">
										<thead class="">
											<tr class="success">
												<th class="text-justify" rowspan="2">Comprobante</th>
												<th class="text-nowrap" rowspan="2">Fecha</th>
												<th class="text-nowrap text-middle" rowspan="2">Detalle</th>
												<th class="text-middle text-middle" rowspan="2">Total Concepto</th>
												<th class="text-nowrap text-middle text-center" colspan="2">Total Pagado</th>
												<th class="text-nowrap text-middle text-center" rowspan="2">Empleado</th>
											</tr>
											<tr class="success">
												<th class="text-nowrap text-middle text-right">Monto</th>
												<th class="text-nowrap text-middle text-right">Tipo Pagado</th>
											</tr>
										</thead>
										<tbody>	
											<?php foreach ($ingresos as $nro => $ingreso) : ?>
												<tr>
													<td class="text-nowrap text-middle text-right"><?= $ingreso['nro_comprobante']; ?></td>	
													<td class="text-nowrap text-middle"><?= date_decode($ingreso['fecha_movimiento'], $_institution['formato']); ?></td>	
													<td class="text-nowrap text-middle"><?= escape($ingreso['concepto']) ?></td>	
													<td class="text-nowrap text-middle text-right"><?= number_format($ingreso['monto'], 2, '.', '') ?></td>	
													<td class="text-nowrap text-middle text-right"><?= number_format($ingreso['monto'], 2, '.', '') ?></td>	
													<td class="text-center">Efectivo</td>	
													<td class="width-middle text-right"><?= escape($ingreso['empleado']); ?></td>
													<?php $total_ingresos = $total_ingresos + $ingreso['monto']; ?>
												</tr>
											<?php endforeach ?>
											<?php $total_total_ingresos = (($total_ingresos_banco + $total_ingresos )) ? number_format(($total_ingresos_banco + $total_ingresos), 2, '.', ''): number_format(0, 2, '.', ''); ?>
										</tbody>

										<tfoot class="">
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Importe total efectivo <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_ingresos = ($total_ingresos) ? number_format($total_ingresos, 2, '.', ''):  number_format(0, 2, '.', ''); ?></th>
												<th class="text-nowrap text-left" data-subtotal="" colspan="2">Efectivo</th>

											</tr>
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Importe total entidad financiera <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_ingresos_banco = ($total_ingresos_banco ) ? number_format($total_ingresos_banco, 2, '.', ''): number_format(0, 2, '.', '');  ?></th>
												<th class="text-nowrap text-left" data-subtotal="" colspan="2">Entidad Bancaria</th>

											</tr>
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Total de totales <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_total_ingresos;  ?></th>
												<th class="text-nowrap text-left" data-subtotal="" colspan="2">Monto Total</th>

											</tr>

										</tfoot>							
									</table>
								</div>
							<?php else : ?>
								<div class="well">No hay ingresos</div>
								<?php $total_ingreso = 0; ?>
							<?php endif ?>
						</div>
                        
                        <!-- Cobros Pensiones -->
						<div class="col-sm-12"><br>
							<p class="lead"><b><a href="?/reportes/diario" class="text-uppercase text-success"><i class="glyphicon glyphicon-log-in" aria-hidden="true"></i> Pensiones</a></b></p>
							<?php if ($cobros) { ?>
								<div class="table-responsive margin-none">
									<table id="compras" class="table table-bordered table-condensed table-striped table-hover table-xs margin-none">
										<thead class="">
											<tr class="success">
												<th class="text-middle" rowspan="2">Comprobante</th>
												<th class="text-nowrap text-middle" rowspan="2">Fecha</th>
												<th class="text-nowrap text-middle" rowspan="2">Detalle</th>
												<th class="text-middle text-right" rowspan="2">Total Concepto</th>
												<th class="text-nowrap text-middle text-center" colspan="2">Total Pagado</th>
												<th class="text-nowrap text-middle text-center" rowspan="2">Empleado</th>
											</tr>
											<tr class="success">
												<th class="text-nowrap text-middle text-right">Monto</th>
												<th class="text-nowrap text-middle text-right">Tipo Pagado</th>
											</tr>
										</thead>				
										<tbody>								
											<?php foreach ($cobros as $key => $venta) { ?>
												<tr>
													<td class="text-nowrap text-middle text-right"><?= $venta['nro_factura']; ?></td>
													<td class="text-nowrap text-middle"><?= date_decode($venta['fecha_general'], $_institution['formato']); ?></td>
													<td class="text-middle"><?= $venta['nombre_cliente']; ?> <font size="2"> <?= ($venta['nit_ci']) ? 'NIT: '.$venta['nit_ci']. ' <BR> '. escape($venta['tipo']):''; ?></font></td>
													<?php if ($venta['tipo_pago'] == 'EFECTIVO'): ?>
															<td class="text-nowrap text-middle text-right">MENSUALIDAD</td>
															<td class="text-nowrap text-middle text-right"><?= number_format($venta['monto_total'], 2, '.', ''); ?></td>
															<td class="text-nowrap text-middle text-right"><?= $venta['tipo_pago']; ?></td>		
															<td class="width-middle text-right"><?= escape($venta['nombre_empleado']); ?></td>
                                                            <?php  $total_venta = $total_venta+$venta['monto_total']; ?>
													<?php else: ?>
													<?php endif ?>	
													</tr>
											<?php } ?>
											<?php //$total_total_venta = (($total_venta_banco + $total_venta )) ? number_format(($total_venta_banco + $total_venta), 2, '.', ''): number_format(0, 2, '.', ''); ?>
										</tbody>
										<tfoot class="">
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Importe total efectivo <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_venta = ($total_venta) ? number_format($total_venta, 2, '.', ''):  number_format(0, 2, '.', ''); ?></th>
												<th class="text-nowrap text-left" data-subtotal=""  colspan="2">Efectivo</th>
											</tr>
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Importe total entidad financiera <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_venta_banco = ($total_venta_banco ) ? number_format($total_venta_banco, 2, '.', ''): number_format(0, 2, '.', '');  ?></th>
												<th class="text-nowrap text-left" data-subtotal="" colspan="2">Entidad Bancaria</th>
											</tr>
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Total de totales <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_venta;  ?></th>
												<th class="text-nowrap text-left" data-subtotal="" colspan="2">Monto Total</th>
											</tr>
										</tfoot>							
									</table>
								</div>
							<?php } else { ?>
								<div class="well">No hay cobro de pensiones</div>
								<?php $total_venta = 0; ?>
							<?php } ?>
						</div>
                        
                        <!-- Cobros Pensiones Extracurriculares -->
						<div class="col-sm-12"><br>
							<p class="lead"><b><a href="?/reportes/diario" class="text-uppercase text-success"><i class="glyphicon glyphicon-log-in" aria-hidden="true"></i> Pensiones Extracurriculares</a></b></p>
							<?php if ($cobros_extra) { ?>
								<div class="table-responsive margin-none">
									<table id="compras" class="table table-bordered table-condensed table-striped table-hover table-xs margin-none">
										<thead class="h5">
											<tr class="success">
												<th class="text-middle" rowspan="2">Nº DOC.</th>
												<th class="text-nowrap text-middle" rowspan="2">FECHA</th>
												<th class="text-nowrap text-middle" rowspan="2">DETALLE</th>
												<th class="text-middle text-right" rowspan="2">TOTAL CONCEPTO</th>
												<th class="text-nowrap text-middle text-center" colspan="2">TOTAL PAGADO</th>
												<th class="text-nowrap text-middle text-center" rowspan="2">Empleado</th>
											</tr>
											<tr class="success">
												<th class="text-nowrap text-middle text-right">MONTO</th>
												<th class="text-nowrap text-middle text-right">TIPO PAGADO</th>
											</tr>
										</thead>				
										<tbody>								
											<?php foreach ($cobros_extra as $key => $venta) { ?>
												<tr>
													<td class="text-nowrap text-middle text-right"><?= $venta['nro_factura']; ?></td>
													<td class="text-nowrap text-middle"><?= date_decode($venta['fecha_egreso'], $_institution['formato']); ?></td>
													<td class="text-middle"><?= $venta['nombre_cliente']; ?><font size="1"> <?= ($venta['nit_ci']) ? 'NIT:'.$venta['nit_ci']. ' - '. escape($venta['tipo']):''; ?></font></td>
													<?php if ($venta['forma_pago'] == 'credito'): ?>
															<td class="text-nowrap text-middle text-right"><?= number_format($venta['monto_total'], 2, '.', ''); ?></td>
															<?php $pagos = $db->query("select id_pago, movimiento_id, pd.metodo, ifnull(SUM(abono),0) as subtotal from inv_pagos p left join inv_pago_detalles pd on p.id_pago= pd.pago_id where p.movimiento_id = '". $venta['id_egreso'] . "' AND pd.estado='1' AND p.tipo='Egreso' GROUP by movimiento_id")->fetch_first(); ?>

															<?php if ($pagos): ?>
																	<?php   if ($pagos['metodo'] == 'efectivo') 
																				$total_venta = $total_venta + $pagos['subtotal']; 
																			else 
																				$total_venta_banco = $total_venta_banco + $pagos['subtotal']; ?>
																	<td class="text-nowrap text-middle text-right"><?= number_format($pagos['subtotal'], 2, '.', ''); ?></td>
																	<td class="text-nowrap text-middle text-right"><?= $pagos['metodo']; ?></td>		
																	<td class="width-middle text-right"><?= escape($venta['nombres'] . ' ' . $venta['paterno'] . ' ' . $venta['materno']); ?></td>
																	<?php else: ?>											
																		<td class="text-nowrap text-middle text-right"><?= number_format(0, 2, '.', ''); ?></td>		
																		<td class="text-nowrap text-middle text-right"><i> SIN PAGO </i></td>
																		<td class="width-middle text-right"><?= escape($venta['nombres'] . ' ' . $venta['paterno'] . ' ' . $venta['materno']); ?></td>										
															<?php endif ?>

													<?php else: ?>
															<?php if ($venta['metodo'] == 'efectivo'): ?>
																<?php $total_venta = $total_venta + $venta['monto_total']; ?>
																<td class="text-nowrap text-middle text-right"><?= number_format($venta['monto_total'], 2, '.', ''); ?></td>																
																<td class="text-nowrap text-middle text-right"><?= number_format($venta['monto_total'], 2, '.', ''); ?></td>																
																<td class="text-nowrap text-middle text-right"><?= $venta['metodo']; ?></td>		
																<td class="width-middle text-right"><?= escape($venta['nombres'] . ' ' . $venta['paterno'] . ' ' . $venta['materno']); ?></td>
															<?php else: ?>
																<?php $total_venta_banco = $total_venta_banco + $venta['monto_total']; ?>
																<td class="text-nowrap text-middle text-right"><?= number_format($venta['monto_total'], 2, '.', ''); ?></td>
																<td class="text-nowrap text-middle text-right"><?= number_format($venta['monto_total'], 2, '.', ''); ?></td>														
																<td class="text-nowrap text-middle text-right"><?= $venta['tipo_de_pago']; ?></td>																		
																<td class="width-middle text-right"><?= escape($venta['nombres'] . ' ' . $venta['paterno'] . ' ' . $venta['materno']); ?></td>
															<?php endif ?>
													<?php endif ?>	
													</tr>
											<?php } ?>
											<?php $total_total_venta = (($total_venta_banco + $total_venta )) ? number_format(($total_venta_banco + $total_venta), 2, '.', ''): number_format(0, 2, '.', ''); ?>
										</tbody>
										<tfoot class="">
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Importe total efectivo <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_venta = ($total_venta) ? number_format($total_venta, 2, '.', ''):  number_format(0, 2, '.', ''); ?></th>
												<th class="text-nowrap text-left" data-subtotal=""  colspan="2">Efectivo</th>
											</tr>
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Importe total entidad financiera <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_venta_banco = ($total_venta_banco ) ? number_format($total_venta_banco, 2, '.', ''): number_format(0, 2, '.', '');  ?></th>
												<th class="text-nowrap text-left" data-subtotal="" colspan="2">Entidad Bancaria</th>
											</tr>
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Total de totales <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_total_venta;  ?></th>
												<th class="text-nowrap text-left" data-subtotal="" colspan="2">Monto Total</th>
											</tr>
										</tfoot>							
									</table>
								</div>
							<?php } else { ?>
								<div class="well">No hay cobro de extracurriculares</div>
								<?php $total_venta = 0; ?>
							<?php } ?>
						</div>
					</div>

					<div class="col-sm-6">
						<!-- Egresos -->
						<div class="col-sm-12">
							<p class="lead"><b><a href="?/movimientos/egresos-listar" class="text-uppercase text-danger"><i class="glyphicon glyphicon-log-out" aria-hidden="true"></i> Egresos</a></b></p>					
							<?php if ($egresos) : ?>
								<div class="table-responsive margin-none">
									<table id="compras" class="table table-bordered table-condensed table-striped table-hover table-xs margin-none">
										<thead class="">
											<tr class="danger">
												<th class="text-middle" rowspan="2">Nº DOC.</th>
												<th class="text-nowrap text-middle" rowspan="2">FECHA</th>
												<th class="text-nowrap text-middle" rowspan="2">DETALLE</th>
												<th class="text-middle text-right" rowspan="2">TOTAL CONCEPTO</th>
												<th class="text-nowrap text-middle text-center" colspan="2">TOTAL PAGADO</th>
												<th class="text-nowrap text-middle text-center" rowspan="2">Empleado</th>
											</tr>
											<tr class="danger">
												<th class="text-nowrap text-middle text-right">MONTO</th>
												<th class="text-nowrap text-middle text-right">TIPO PAGADO</th>
											</tr>
										</thead>				
										<tbody>	
											<?php foreach ($egresos as $nro => $egreso) : ?>
												<?php $total_egresos = $total_egresos + $egreso['monto']; ?>
												<tr>
													<td class="text-nowrap text-middle text-right"><?= $egreso['nro_comprobante']; ?></td>
													<td class="text-nowrap text-middle"><?= date_decode($egreso['fecha_movimiento'], $_institution['formato']); ?></td>
													<td class="text-middle"><?= escape($egreso['concepto']); ?></td>
													<td class="text-nowrap text-middle text-right"><?= number_format($egreso['monto'], 2, '.', ''); ?></td>
													<td class="text-nowrap text-middle text-right"><?= number_format($egreso['monto'], 2, '.', ''); ?></td>
													<td class="text-nowrap text-middle text-right">Efectivo</td>
													<td class="width-middle text-right"><?= escape($egreso['empleado']); ?></td>
												</tr>
											<?php endforeach ?>
											<?php $total_total_egresos = (($total_egresos_banco + $total_egresos )) ? number_format(($total_egresos_banco + $total_egresos), 2, '.', ''): number_format(0, 2, '.', ''); ?>
										</tbody>
										<tfoot class="">
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Importe total efectivo <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_egresos = ($total_egresos) ? number_format($total_egresos, 2, '.', ''):  number_format(0, 2, '.', ''); ?></th>
												<th class="text-nowrap text-left" data-subtotal=""   colspan="2">Efectivo</th>
											</tr>
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Importe total entidad financiera <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_egresos_banco = ($total_egresos_banco ) ? number_format($total_egresos_banco, 2, '.', ''): number_format(0, 2, '.', '');  ?></th>
												<th class="text-nowrap text-left" data-subtotal=""  colspan="2">Entidad Bancaria</th>
											</tr>
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Total de totales <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_total_egresos;  ?></th>
												<th class="text-nowrap text-left" data-subtotal=""   colspan="2">Monto Total</th>
											</tr>
										</tfoot>							
									</table>
								</div>
							<?php else : ?>
								<div class="well">No hay egresos</div>
								<?php $total_egreso = 0; ?>
							<?php endif ?>
						</div>

                        <br>
                        <!-- Gastos -->
						<div class="col-sm-12">
							<p class="lead"><b><a href="?/movimientos/gastos_listar" class="text-uppercase text-danger"><i class="glyphicon glyphicon-log-out" aria-hidden="true"></i> Gastos</a></b></p>
							<?php if ($gastos) : ?>
								<div class="table-responsive margin-none">
									<table id="compras" class="table table-bordered table-condensed table-striped table-hover table-xs margin-none">
										<thead class="">
											<tr class="danger">
												<th class="text-middle" rowspan="2">Nº DOC.</th>
												<th class="text-nowrap text-middle" rowspan="2">FECHA</th>
												<th class="text-nowrap text-middle" rowspan="2">DETALLE</th>
												<th class="text-middle text-right" rowspan="2">TOTAL CONCEPTO</th>
												<th class="text-nowrap text-middle text-center" colspan="2">TOTAL PAGADO</th>
												<th class="text-nowrap text-middle text-center" rowspan="2">Empleado</th>
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
													<td class="width-middle text-right"><?= escape($gasto['empleado']); ?></td>	
												</tr>
											<?php endforeach ?>
											<?php $total_total_gasto = (($total_gasto_banco + $total_gasto )) ? number_format(($total_gasto_banco + $total_gasto), 2, '.', ''): number_format(0, 2, '.', ''); ?>
										</tbody>
										<tfoot class="">
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Importe total efectivo <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_gasto = ($total_gasto) ? number_format($total_gasto, 2, '.', ''):  number_format(0, 2, '.', ''); ?></th>
												<th class="text-nowrap text-left" data-subtotal=""  colspan="2">Efectivo</th>
											</tr>
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Importe total entidad financiera <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_gasto_banco = ($total_gasto_banco ) ? number_format($total_gasto_banco, 2, '.', ''): number_format(0, 2, '.', '');  ?></th>
												<th class="text-nowrap text-left" data-subtotal="" colspan="2">Entidad Bancaria</th>
											</tr>
											<tr class="">
												<th class="text-nowrap text-right" colspan="4">Total de totales <?= escape($moneda); ?></th>
												<th class="text-nowrap text-right" data-subtotal=""><?= $total_total_gasto;  ?></th>
												<th class="text-nowrap text-left" data-subtotal="" colspan="2">Monto Total</th>
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
						<?php $dato_total = number_format(($total_ingresos + $total_venta) - ($total_egresos + $total_compra + $total_gasto) , 2, '.', '');  ?>
						<u id="total" data-total ="<?= $dato_total;  ?>"><?= number_format(($total_ingresos + $total_venta) - ($total_egresos + $total_compra + $total_gasto) , 2, '.', ''); ?></u>
						<span><?= escape($moneda); ?></span>
					</p>
					<p class="margin-none">
						<em>El total corresponde a la siguiente fórmula:</em>
						<samp><b>( Ingresos + Pensiones + Pensiones Extracurriculares ) - ( Egresos + Gastos ) </b></samp>
					</p>
				</div>
			</div>
		</div>
                <!-- ============================================================== -->
                <!-- end datos -->
                <!-- ============================================================== -->
    </div>
</div>
<!--     </div>
</div> -->
<!-- ============================================================== -->
<!-- end row -->
<!-- ============================================================== --> 

<!-- Modal cambiar inicio -->
<div id="modal_cambiar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/movimientos/mostrar" id="form_cambiar" class="modal-content loader-wrapper" autocomplete="off">
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

<div id="abrir_caja" class="abrir-caja modal fade">
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
		<form method="post" action="?/movimientos/abrir-caja" id="form_iniciar_caja" class="modal-content loader-wrapper" autocomplete="off">
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
		<form method="post" action="?/movimientos/cerrar-caja" id="form_cerrar_caja" class="modal-content loader-wrapper" autocomplete="off">
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


<!-- Modal cambiar inicio -->
<!-- <div id="modal_cambiar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/movimientos/ingresos-listar" id="form_cambiar" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<h4 class="modal-title">Cambiar fecha</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="inicial_fecha" class="control-label">Fecha inicial:</label>
					<input type="date" value="" name="inicial" id="inicial_fecha" class="form-control" data-validation="required date">
				</div>
				<div class="form-group">
					<label for="final_fecha" class="control-label">Fecha final:</label>
					<input type="date" value="" name="final" id="final_fecha" class="form-control" data-validation="required date">
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
</div> -->
<!-- Modal cambiar fin -->

<script src="<?= js; ?>/modernizr.min.js"></script>
<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.data-filters.min.js"></script>
<script src="<?= js; ?>/moment.min.js"></script>
<script src="<?= js; ?>/moment.es.js"></script>
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>

<?php require_once show_template('footer-design'); ?>
<script>
$(function () {

    // $('#table').DataFilter({
    //   filter: true,
    //   name: 'conceptos',
    // });
    $('#table').on('search.dt order.dt page.dt length.dt', function () {
    	console.log('hhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhhh');
		var suma = 0;
		$('[data-monto]:visible').each(function (i) {
			var monto = parseFloat($(this).attr('data-monto'));
			suma = suma + monto;
		});
		$('#monto').text(suma.toFixed(2));
	}).DataFilter({
		filter: true,
		name: 'movimientos',
		reports: 'excel|word|pdf|html',
		values: {
			stateSave: true
		}
	});
});

<?php if ($permiso_cambiar) { ?>
  var formato = $('[data-formato]').attr('data-formato');
  var mascara = $('[data-mascara]').attr('data-mascara');
  var gestion = $('[data-gestion]').attr('data-gestion');
  var $inicial_fecha = $('#inicial_fecha');
  var $final_fecha = $('#final_fecha');
  //var $usuario = $('#curso');

  $.validate({
    form: '#form_cambiar',
    modules: 'date',
    onSuccess: function () {
      var inicial_fecha = $.trim($('#inicial_fecha').val());
      var final_fecha = $.trim($('#final_fecha').val());
      //var usuario = $.trim($('#usuario').val());
      // var vacio = gestion.replace(new RegExp('9', 'g'), '0');
      // inicial_fecha = inicial_fecha.replace(new RegExp('\\.', 'g'), '-');
      // inicial_fecha = inicial_fecha.replace(new RegExp('/', 'g'), '-');
      // final_fecha = final_fecha.replace(new RegExp('\\.', 'g'), '-');
      // final_fecha = final_fecha.replace(new RegExp('/', 'g'), '-');
      // vacio = vacio.replace(new RegExp('\\.', 'g'), '-');
      // vacio = vacio.replace(new RegExp('/', 'g'), '-');
      final_fecha = (final_fecha != '') ? ('/' + final_fecha ) : '';
      inicial_fecha = (inicial_fecha != '') ? ('/' + inicial_fecha) :''; 
      //usuario = (usuario != '') ? ('/' + usuario) :'';
      var ruta_imprimir = '?/movimientos/gastos-listar' + inicial_fecha + final_fecha;
      // console.log('gggg');
      // $("#imprimir").attr('href', ruta_imprimir);
      // var g = $("#imprimir").attr('href');
      // console.log(g);
      window.location = '?/movimientos/gastos-listar' + inicial_fecha + final_fecha;
    }
  });

  var $form_fecha = $('#form_cambiar');
  var $modal_fecha = $('#modal_cambiar');

  $form_fecha.on('submit', function (e) {
    e.preventDefault();
  });

  $modal_fecha.on('show.bs.modal', function () {
    $form_fecha.trigger('reset');
  });

  $modal_fecha.on('shown.bs.modal', function () {
    $modal_fecha.find('[data-aceptar]').focus();
  });

  $modal_fecha.find('[data-cancelar]').on('click', function () {
    $modal_fecha.modal('hide');
  });

  $modal_fecha.find('[data-aceptar]').on('click', function () {
    $form_fecha.submit();
  });

  $('[data-cambiar]').on('click', function () {
    $('#modal_cambiar').modal({
      backdrop: 'static'
    });
  });

<?php } ?>
