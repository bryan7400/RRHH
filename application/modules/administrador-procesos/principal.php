<?php

// Obtiene las fechas inicial y final
$fecha_inicial = str_replace('/', '-', now($_format));
$fecha_final = str_replace('/', '-', now($_format));

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format); 

// Verifica si existen los parametros
if (sizeof($_params) == 2) {
	// Verifica el tipo de los parametros
	if (!is_date($_params[0]) || !is_date($_params[1])) {
		// Redirecciona la pagina
		redirect('?/administrador-procesos/principal/' . $fecha_inicial . '/' . $fecha_final);
	}
} else {
	// Redirecciona la pagina
	redirect('?/administrador-procesos/principal/' . $fecha_inicial . '/' . $fecha_final);
}

// Obtiene los parametros
$fecha_inicial = date_encode($_params[0]);
$fecha_final = date_encode($_params[1]);

// Obtiene los procesos
$procesos = $db->select('p.*, u.username')->from('sys_procesos p')->join('sys_users u', 'p.usuario_id = u.id_user', 'left')->between('p.fecha_proceso', $fecha_inicial, $fecha_final)->order_by(array('p.fecha_proceso' => 'desc', 'p.hora_proceso' => 'desc'))->fetch();

// Arreglo de procesos
$arreglo_procesos = array('c' => 'Creación', 'r' => 'Visualización', 'u' => 'Modificación', 'd' => 'Eliminación');

// Arreglo de niveles
$arreglo_niveles = array('l' => 'Bajo', 'm' => 'Medio', 'h' => 'Alto');

?>
<?php require_once show_template('header-design'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Administración de procesos</strong>
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
				<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
					<span class="glyphicon glyphicon-menu-hamburger"></span>
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
	<?php if ($procesos) : ?>
	<table id="table" class="table table-bordered table-condensed table-striped table-hover">
		<thead>
			<tr class="active">
				<th class="text-nowrap">#</th>
				<th class="text-nowrap">Fecha</th>
				<th class="text-nowrap">Hora</th>
				<th class="text-nowrap">Proceso</th>
				<th class="text-nowrap">Nivel</th>
				<th class="text-nowrap">Detalle</th>
				<th class="text-nowrap">Ruta</th>
				<th class="text-nowrap">Usuario</th>
			</tr>
		</thead>
		<tfoot>
			<tr class="active">
				<th class="text-nowrap text-middle" data-datafilter-filter="false">#</th>
				<th class="text-nowrap text-middle">Fecha</th>
				<th class="text-nowrap text-middle">Hora</th>
				<th class="text-nowrap text-middle">Proceso</th>
				<th class="text-nowrap text-middle">Nivel</th>
				<th class="text-nowrap text-middle">Detalle</th>
				<th class="text-nowrap text-middle">Ruta</th>
				<th class="text-nowrap text-middle">Usuario</th>
			</tr>
		</tfoot>
		<tbody>
			<?php foreach ($procesos as $nro => $proceso) : ?>
			<tr>
				<th class="text-nowrap"><?= $nro + 1; ?></th>
				<td class="text-nowrap"><?= date_decode($proceso['fecha_proceso'], $_format); ?></td>
				<td class="text-nowrap"><?= escape($proceso['hora_proceso']); ?></td>
				<td class="text-nowrap"><?= escape($arreglo_procesos[$proceso['proceso']]); ?></td>
				<td class="text-nowrap"><?= escape($arreglo_niveles[$proceso['nivel']]); ?></td>
				<td><?= escape($proceso['detalle']); ?></td>
				<td class="text-nowrap text-danger"><?= escape($proceso['direccion']); ?></td>
				<td class="text-nowrap"><?= escape($proceso['username']); ?></td>
			</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<?php else : ?>
	<div class="alert alert-info margin-none">
		<strong>Atención!</strong>
		<ul>
			<li>No existen procesos registrados.</li>
			<li>Puede buscar procesos por rango de fechas, verifique que las fechas sean válidas.</li>
		</ul>
	</div>
	<?php endif ?>
</div>

<!-- Modal cambiar inicio -->
<div id="modal_cambiar" class="modal fade" tabindex="-1">
	<div class="modal-dialog">
		<form method="post" action="?/administrador-procesos/principal" id="form_cambiar" class="modal-content loader-wrapper" autocomplete="off">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Cambiar fecha</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label for="inicial_cambiar" class="control-label">Fecha inicial:</label>
					<input type="text" value="<?= date_decode($fecha_inicial, $_format); ?>" name="inicial" id="inicial_cambiar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
				<div class="form-group">
					<label for="final_cambiar" class="control-label">Fecha final:</label>
					<input type="text" value="<?= date_decode($fecha_final, $_format); ?>" name="final" id="final_cambiar" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger">
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
<!-- Modal cambiar fin -->

<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>
<!-- <script src="<?= js; ?>/jquery.dataTables.min.js"></script>
<script src="<?= js; ?>/dataTables.bootstrap.min.js"></script> -->
<script>
$(function () {
	var $modal_cambiar = $('#modal_cambiar'), $form_cambiar = $('#form_cambiar'), $loader_cambiar = $('#loader_cambiar'), $inicial_cambiar = $('#inicial_cambiar'), $final_cambiar = $('#final_cambiar');

	$.validate({
		form: '#form_cambiar',
		modules: 'date',
		onSuccess: function () {
			$loader_cambiar.removeClass('hidden');
			var direccion_cambiar = $.trim($form_cambiar.attr('action')), inicial_cambiar = $.trim($inicial_cambiar.val()), final_cambiar = $.trim($final_cambiar.val());
			inicial_cambiar = inicial_cambiar.replace(new RegExp('/', 'g'), '-');
			final_cambiar = final_cambiar.replace(new RegExp('/', 'g'), '-');
			window.location = direccion_cambiar + '/' + inicial_cambiar + '/' + final_cambiar;
		}
	});

	$inicial_cambiar.datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	});

	$final_cambiar.datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	});

	$form_cambiar.on('submit', function (e) {
		e.preventDefault();
	});

	$modal_cambiar.on('hidden.bs.modal', function () {
		$form_cambiar.trigger('reset');
	}).on('show.bs.modal', function (e) {
		if ($('.modal:visible').size() != 0) { e.preventDefault(); }
	});

	$('#table').dataTable({
		displayLength: 50
	});
});
</script>
<?php require_once show_template('footer-full'); ?>