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
		<strong>Crear gestion</strong>
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
					<li><a href="?/gestion-escolar/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar gestiones</a></li>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/gestion-escolar/guardar" id='fffff' autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="gestion" class="control-label">Gestion:</label>
					<input type="text" value="" name="gestion" id="gestion" class="form-control" autofocus="autofocus" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="inicio_gestion" class="control-label">Inicio gestion:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="" name="inicio_gestion" id="inicio_gestion" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="final_gestion" class="control-label">Final gestion:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="" name="final_gestion" id="final_gestion" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="inicio_vacaciones" class="control-label">Inicio vacaciones:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="" name="inicio_vacaciones" id="inicio_vacaciones" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="final_vacaciones" class="control-label">Final vacaciones:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="" name="final_vacaciones" id="final_vacaciones" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
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

	$('#inicio_gestion').datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	});

	$('#final_gestion').datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	});

	$('#inicio_vacaciones').datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	});

	$('#final_vacaciones').datetimepicker({
		format: '<?= strtoupper($formato_textual); ?>'
	});
});
fffff.onsubmit=()=>{
	console.log('asd')
	return false;
}
</script>
<?php require_once show_template('footer-full'); ?>