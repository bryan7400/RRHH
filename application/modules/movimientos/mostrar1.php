<?php

// Obtiene las fechas inicial y final
$fecha_inicial = str_replace('/', '-', now($_institution['formato']));
$fecha_final = str_replace('/', '-', now($_institution['formato']));

// Obtiene los formatos
$formato_textual = get_date_textual($_institution['formato']);
$formato_numeral = get_date_numeral($_institution['formato']);


// Obtiene el rango de fechas
$gestion = date('Y');
$gestion_base = date('Y-m-d');
//$gestion_base = ($gestion - 16) . date('-m-d');
$gestion_limite = ($gestion + 16) . date('-m-d');

// Obtiene fecha inicial
$fecha_inicial = (isset($params[0])) ? $params[0] : $gestion_base;
$fecha_inicial = (is_date($fecha_inicial)) ? $fecha_inicial : $gestion_base;
$fecha_inicial = date_encode($fecha_inicial);

// Obtiene fecha final
$fecha_final = (isset($params[1])) ? $params[1] : $gestion_limite;
$fecha_final = (is_date($fecha_final)) ? $fecha_final : $gestion_limite;
$fecha_final = date_encode($fecha_final);

// Obtiene los ingresos
$ingresos = $db->select("m.*, concat(e.nombres, ' ', e.paterno, ' ', e.materno) as empleado")->from('caj_movimientos m')->join('sys_empleados e', 'm.empleado_id = e.id_empleado', 'left')->where('m.empleado_id', $_user['persona_id'])->between('m.fecha_movimiento', $fecha_inicial, $fecha_final)->order_by('m.tipo desc, m.fecha_movimiento desc, m.hora_movimiento desc')->group_by('id_movimiento,')->fetch();

// Obtiene la moneda oficial
$moneda = $db->from('gen_monedas')->where('principal', 's')->fetch_first();
$moneda = ($moneda) ? '(' . $moneda['codigo'] . ')' : '';

// Almacena los permisos en variables
$permiso_crear 		= in_array('ingresos_crear', $_views);
$permiso_modificar 	= in_array('ingresos_modificar', $_views);
$permiso_eliminar 	= in_array('ingresos_eliminar', $_views);

?>
<?php require_once show_template('header-sidebar'); ?>
<div class="panel-heading" data-formato="<?= strtoupper($formato_textual); ?>" data-mascara="<?= $formato_numeral; ?>" data-gestion="<?= date_decode($gestion_base, $_institution['formato']); ?>">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Reporte General de Caja</strong>
	</h3>
</div>
<div class="panel-body">
	<div class="row">
		<div class="col-xs-6">
			<div class="text-label hidden-xs">Seleccionar acción:</div>
			<div class="text-label visible-xs-block">Acciones:</div>
		</div>
		<div class="col-xs-6 text-right">
			<div class="btn-group">
				<button class="btn btn-default" data-cambiar="true"><i class="glyphicon glyphicon-calendar"></i><span class="hidden-xs"> Cambiar</span></button>
			</div>
		</div>
	</div>
	<hr>
	<?php if (isset($_SESSION[temporary])) : ?>
	<div class="alert alert-<?= $_SESSION[temporary]['alert']; ?>">
		<button type="button" class="close" data-dismiss="alert">&times;</button>
		<strong><?= $_SESSION[temporary]['title']; ?></strong>
		<p><?= $_SESSION[temporary]['message']; ?></p>
	</div>
	<?php unset($_SESSION[temporary]); ?>
	<?php endif ?>
	<?php if ($ingresos) : ?>
	<table id="table" class="table table-bordered table-condensed table-striped table-hover">
		<thead>
			<tr class="active">
				<th class="text-nowrap">#</th>
				<th class="text-nowrap">Comprobante</th>
				<th class="text-nowrap">Fecha</th>
				<th class="text-nowrap">Concepto</th>
				<th class="text-nowrap">Monto <?= escape($moneda); ?></th>
				<th class="text-nowrap">Observación</th>
				<th class="text-nowrap">Tipo</th>
				<th class="text-nowrap">Empleado</th>
				<?php if ($permiso_modificar || $permiso_eliminar) : ?>
				<!-- <th class="text-nowrap">Opciones</th> -->
				<?php endif ?>
			</tr>
		</thead>
		<tfoot>
			<tr class="active">
				<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Comprobante</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Fecha</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Concepto</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Monto <?= escape($moneda); ?></th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Observación</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Tipo</th>
				<th class="text-nowrap text-middle" data-datafilter-filter="true">Empleado</th>
				<?php if ($permiso_modificar || $permiso_eliminar) : ?>
				<!-- <th class="text-nowrap text-middle" data-datafilter-filter="false">Opciones</th> -->
				<?php endif ?>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($ingresos as $nro => $ingreso) : ?>
			<tr>
				<th class="text-nowrap"><?= $nro + 1; ?></th>
				<td class="text-nowrap"><?= escape($ingreso['nro_comprobante']); ?></td>
				<td class="text-nowrap">
					<span><?= escape(date_decode($ingreso['fecha_movimiento'], $_institution['formato'])); ?></span>
					<span class="text-primary"><?= escape($ingreso['hora_movimiento']); ?></span>
				</td>
				<td><?= escape($ingreso['concepto']); ?></td>
				<td class="text-nowrap text-right" data-monto="<?= $ingreso['monto']; ?>"><?= escape($ingreso['monto']); ?></td>
				<td><?= escape($ingreso['observacion']); ?></td>
				<td><?php if (escape($ingreso['tipo']) == 'i'){ ?>
						<span class="text-primary">Ingreso a Caja</span>
					<?php } if (escape($ingreso['tipo']) == 'e'){ ?>
						<span class="text-warning">Egreso de Caja</span>
					<?php } if (escape($ingreso['tipo']) == 'g'){ ?>
						<span class="text-danger">Gastos</span>					
					<?php } ?>
				</td>
				<td class="text-nowrap"><?= escape($ingreso['empleado']); ?></td>
				<?php if ($permiso_modificar || $permiso_eliminar) : ?>
				<!-- <td class="text-nowrap">
					<?php if ($permiso_modificar) : ?>	
					<a href="?/movimientos/ingresos_modificar/<?= $ingreso['id_movimiento']; ?>" data-toggle="tooltip" data-title="Modificar ingreso"><i class="glyphicon glyphicon-edit"></i></a>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>	
					<a href="?/movimientos/ingresos_eliminar/<?= $ingreso['id_movimiento']; ?>" data-toggle="tooltip" data-title="Eliminar ingreso" data-eliminar="true"><i class="glyphicon glyphicon-trash"></i></a>
					<?php endif ?>
				</td> -->
				<?php endif ?>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<!-- <div class="well">
		<p class="lead margin-none">
			<b>Empleado:</b>
			<span><?= escape($_user['nombres'] . ' ' . $_user['paterno'] . ' ' . $_user['materno']); ?></span>
		</p>
		<p class="lead margin-none">
			<b>Total:</b>
			<u id="monto">0.00</u>
			<span><?= escape($moneda); ?></span>
		</p>
	</div> -->
	<?php else : ?>
	<div class="alert alert-info margin-none">
		<strong>Atención!</strong>
		<ul>
			<li>No existen ingresos registrados.</li>
			<li>Puede buscar ingresos por rango de fechas, verifique que las fechas sean válidas.</li>
		</ul>
	</div>
	<?php endif ?>
</div>

<!-- Modal cambiar inicio -->
<div id="modal_fecha" class="modal fade">
	<div class="modal-dialog">
		<form id="form_fecha" class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Cambiar fecha</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label for="inicial_fecha">Fecha inicial:</label>
							<input type="text" name="inicial" value="<?= ($fecha_inicial != $gestion_base) ? date_decode($fecha_inicial, $_institution['formato']) : ''; ?>" id="inicial_fecha" class="form-control" autocomplete="off" data-validation="date" data-validation-format="<?= $formato_textual; ?>" data-validation-optional="true">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<label for="final_fecha">Fecha final:</label>
							<input type="text" name="final" value="<?= ($fecha_final != $gestion_limite) ? date_decode($fecha_final, $_institution['formato']) : ''; ?>" id="final_fecha" class="form-control" autocomplete="off" data-validation="date" data-validation-format="<?= $formato_textual; ?>" data-validation-optional="true">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-aceptar="true">
					<span class="glyphicon glyphicon-ok"></span>
					<span>Aceptar</span>
				</button>
				<button type="button" class="btn btn-default" data-cancelar="true">
					<span class="glyphicon glyphicon-remove"></span>
					<span>Cancelar</span>
				</button>
			</div>
		</form>
	</div>
</div>
<!-- Modal cambiar fin -->

<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>
<script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script>
<script src="<?= js; ?>/jquery.base64.js"></script>
<script src="<?= js; ?>/pdfmake.min.js"></script>
<script src="<?= js; ?>/vfs_fonts.js"></script>
<script src="<?= js; ?>/jquery.dataFilters.min.js"></script>
<script>
$(function () {
	<?php if ($permiso_eliminar) : ?>
	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var url = $(this).attr('href');
		bootbox.confirm('¿Está seguro que desea eliminar el ingreso?', function (result) {
			if(result){
				window.location = url;
			}
		});
	});
	<?php endif ?>
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/movimientos/mostrar';
				break;
			}
		}
	});
	<?php endif ?>

	var formato = $('[data-formato]').attr('data-formato');
	var mascara = $('[data-mascara]').attr('data-mascara');
	var gestion = $('[data-gestion]').attr('data-gestion');
	var $inicial_fecha = $('#inicial_fecha');
	var $final_fecha = $('#final_fecha');
	$.validate({
		form: '#form_fecha',
		modules: 'date',
		onSuccess: function () {
			var inicial_fecha = $.trim($('#inicial_fecha').val());
			var final_fecha = $.trim($('#final_fecha').val());
			var vacio = gestion.replace(new RegExp('9', 'g'), '0');

			inicial_fecha = inicial_fecha.replace(new RegExp('\\.', 'g'), '-');
			inicial_fecha = inicial_fecha.replace(new RegExp('/', 'g'), '-');
			final_fecha = final_fecha.replace(new RegExp('\\.', 'g'), '-');
			final_fecha = final_fecha.replace(new RegExp('/', 'g'), '-');
			vacio = vacio.replace(new RegExp('\\.', 'g'), '-');
			vacio = vacio.replace(new RegExp('/', 'g'), '-');
			final_fecha = (final_fecha != '') ? ('/' + final_fecha ) : '';
			inicial_fecha = (inicial_fecha != '') ? ('/' + inicial_fecha) : ((final_fecha != '') ? ('/' + vacio) : ''); 
			
			window.location = '?/movimientos/mostrar' + inicial_fecha + final_fecha;
		}
	});

	//$inicial_fecha.mask(mascara).datetimepicker({
	$inicial_fecha.datetimepicker({
		format: formato
	});

	//$final_fecha.mask(mascara).datetimepicker({
	$final_fecha.datetimepicker({
		format: formato
	});

	$inicial_fecha.on('dp.change', function (e) {
		$final_fecha.data('DateTimePicker').minDate(e.date);
	});
	
	$final_fecha.on('dp.change', function (e) {
		$inicial_fecha.data('DateTimePicker').maxDate(e.date);
	});
	var $form_fecha = $('#form_fecha');
	var $modal_fecha = $('#modal_fecha');

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
		$('#modal_fecha').modal({
			backdrop: 'static'
		});
	});

	$('#table').on('search.dt order.dt page.dt length.dt', function () {
		var suma = 0;
		$('[data-monto]:visible').each(function (i) {
			var monto = parseFloat($(this).attr('data-monto'));
			suma = suma + monto;
		});
		$('#monto').text(suma.toFixed(2));
	}).DataFilter({
		name: 'movimientos',
		reports: 'excel|word|pdf|html',
		values: {
			stateSave: true
		}
	});
});
</script>
<?php require_once show_template('footer-sidebar'); ?>