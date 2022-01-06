<?php

// Obtiene los parametros
$id_gestion = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

// Obtiene el gestion
$gestion = $db->select('z.*')->from('ins_gestion z')->where('z.id_gestion', $id_gestion)->fetch_first();

// Ejecuta un error 404 si no existe el gestion
if (!$gestion) { require_once not_found(); exit; }

// Obtiene los permisos
$permiso_listar = in_array('listar', $_views);
$permiso_crear = in_array('crear', $_views);
$permiso_ver = in_array('ver', $_views);
$permiso_eliminar = in_array('eliminar', $_views);
$permiso_imprimir = in_array('imprimir', $_views);

?>
<?php require_once show_template('header-full'); ?>
<div class="panel-heading">
	<h3 class="panel-title" data-header="true">
		<span class="glyphicon glyphicon-option-vertical"></span>
		<strong>Modificar gestion</strong>
	</h3>
</div>
<div class="panel-body">
	<?php if ($permiso_listar || $permiso_crear || $permiso_ver || $permiso_eliminar || $permiso_imprimir) : ?>
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
					<?php if ($permiso_listar) : ?>
					<li><a href="?/gestiones/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar gestiones</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/gestiones/crear"><span class="glyphicon glyphicon-plus"></span> Crear gestion</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/gestiones/ver/<?= $id_gestion; ?>"><span class="glyphicon glyphicon-search"></span> Ver gestion</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/gestiones/eliminar/<?= $id_gestion; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar gestion</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/gestiones/imprimir/<?= $id_gestion; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir gestion</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/gestiones/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="gestion" class="control-label">Gestion:</label>
					<input type="text" value="<?= $gestion['gestion']; ?>" name="gestion" id="gestion" class="form-control" autofocus="autofocus" data-validation="required number">
					<input type="text" value="<?= $id_gestion; ?>" name="id_gestion" id="id_gestion" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="inicio_gestion" class="control-label">Inicio gestion:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="<?= date_decode($gestion['inicio_gestion'], $_format); ?>" name="inicio_gestion" id="inicio_gestion" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="final_gestion" class="control-label">Final gestion:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="<?= date_decode($gestion['final_gestion'], $_format); ?>" name="final_gestion" id="final_gestion" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="inicio_vacaciones" class="control-label">Inicio vacaciones:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="<?= date_decode($gestion['inicio_vacaciones'], $_format); ?>" name="inicio_vacaciones" id="inicio_vacaciones" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="final_vacaciones" class="control-label">Final vacaciones:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="<?= date_decode($gestion['final_vacaciones'], $_format); ?>" name="final_vacaciones" id="final_vacaciones" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
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
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/gestiones/crear';
				break;
			}
		}
	});
	<?php endif ?>
	
	<?php if ($permiso_eliminar) : ?>
	$('[data-eliminar]').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		var csrf = '<?= $csrf; ?>';
		bootbox.confirm('¿Está seguro que desea eliminar el gestion?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>