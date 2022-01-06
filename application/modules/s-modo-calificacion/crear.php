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
		<strong>Crear modo calificacion</strong>
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
					<li><a href="?/smodocalificacion/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar smodocalificacion</a></li>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/smodocalificacion/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="fecha_inicio" class="control-label">Fecha inicio:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="" name="fecha_inicio" id="fecha_inicio" class="form-control" autofocus="autofocus" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="fecha_final" class="control-label">Fecha final:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="" name="fecha_final" id="fecha_final" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="descripcion" class="control-label">Descripcion:</label>
					<input type="text" value="" name="descripcion" id="descripcion" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="gestion_id" class="control-label">Gestion:</label>
					<input type="text" value="" name="gestion_id" id="gestion_id" class="form-control" data-validation="required number">
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

	$('#fecha_final').datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	});
});
</script>
<?php require_once show_template('footer-full'); ?>