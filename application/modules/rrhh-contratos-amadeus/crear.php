<?php

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);

?>
<?php require_once show_template('header-full'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Crear contratos</strong>
	</h3>
</div>
<div class="panel-body">
	<?php if ($permiso_listar) : ?>
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
					<li><a href="?/contratos/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar contratos</a></li>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/contratos/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="tipo_contrato_id" class="control-label">Tipo_contrato:</label>
					<input type="text" value="" name="tipo_contrato_id" id="tipo_contrato_id" class="form-control" autofocus="autofocus" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="horario" class="control-label">Horario:</label>
					<input type="text" value="" name="horario" id="horario" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="cargo_id" class="control-label">Cargo:</label>
					<input type="text" value="" name="cargo_id" id="cargo_id" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="sueldo_base" class="control-label">Sueldo base:</label>
					<input type="text" value="" name="sueldo_base" id="sueldo_base" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="fecha_inicio" class="control-label">Fecha inicio:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="" name="fecha_inicio" id="fecha_inicio" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="fecha_final" class="control-label">Fecha final:</label>
					<input type="text" value="" name="fecha_final" id="fecha_final" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="forma_pago" class="control-label">Forma pago:</label>
					<input type="text" value="" name="forma_pago" id="forma_pago" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="entidad_financiera_id" class="control-label">Entidad_financiera:</label>
					<input type="text" value="" name="entidad_financiera_id" id="entidad_financiera_id" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="concepto_pago_id" class="control-label">Concepto_pago:</label>
					<input type="text" value="" name="concepto_pago_id" id="concepto_pago_id" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-danger">
						<span class="glyphicon glyphicon-floppy-disk"></span>
						<span>Guardar</span>
					</button>
					<button type="reset" class="btn btn-default">
						<span class="glyphicon glyphicon-refresh"></span>
						<span>Restablecer</span>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script src="<?= js; ?>/jquery.form-validator.min.js"></script>
<script src="<?= js; ?>/jquery.form-validator.es.js"></script>
<script src="<?= js; ?>/bootstrap-datetimepicker.min.js"></script>
<script>
$(function () {
	$.validate({
		modules: 'basic'
	});

	$('#fecha_inicio').datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	});
});
</script>
<?php require_once show_template('footer-full'); ?>