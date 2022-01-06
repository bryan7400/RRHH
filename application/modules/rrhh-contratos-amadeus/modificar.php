<?php

// Obtiene los parametros
$id_contrato = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

// Obtiene el contratos
$contratos = $db->select('z.*')->from('rhh_contratos z')->where('z.id_contrato', $id_contrato)->fetch_first();

// Ejecuta un error 404 si no existe el contratos
if (!$contratos) { require_once not_found(); exit; }

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
		<strong>Modificar contratos</strong>
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
					<li><a href="?/contratos/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar contratos</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/contratos/crear"><span class="glyphicon glyphicon-plus"></span> Crear contratos</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/contratos/ver/<?= $id_contrato; ?>"><span class="glyphicon glyphicon-search"></span> Ver contratos</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/contratos/eliminar/<?= $id_contrato; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar contratos</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/contratos/imprimir/<?= $id_contrato; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir contratos</a></li>
					<?php endif ?>
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
					<input type="text" value="<?= $contratos['tipo_contrato_id']; ?>" name="tipo_contrato_id" id="tipo_contrato_id" class="form-control" autofocus="autofocus" data-validation="required number">
					<input type="text" value="<?= $id_contrato; ?>" name="id_contrato" id="id_contrato" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="horario" class="control-label">Horario:</label>
					<input type="text" value="<?= $contratos['horario']; ?>" name="horario" id="horario" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="cargo_id" class="control-label">Cargo:</label>
					<input type="text" value="<?= $contratos['cargo_id']; ?>" name="cargo_id" id="cargo_id" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="sueldo_base" class="control-label">Sueldo base:</label>
					<input type="text" value="<?= $contratos['sueldo_base']; ?>" name="sueldo_base" id="sueldo_base" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="fecha_inicio" class="control-label">Fecha inicio:</label>
					<div class="row">
						<div class="col-xs-12">
							<input type="text" value="<?= date_decode($contratos['fecha_inicio'], $_format); ?>" name="fecha_inicio" id="fecha_inicio" class="form-control" data-validation="required date" data-validation-format="<?= $formato_textual; ?>">
						</div>
					</div>
				</div>
				<div class="form-group">
					<label for="fecha_final" class="control-label">Fecha final:</label>
					<input type="text" value="<?= $contratos['fecha_final']; ?>" name="fecha_final" id="fecha_final" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="forma_pago" class="control-label">Forma pago:</label>
					<input type="text" value="<?= $contratos['forma_pago']; ?>" name="forma_pago" id="forma_pago" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="entidad_financiera_id" class="control-label">Entidad_financiera:</label>
					<input type="text" value="<?= $contratos['entidad_financiera_id']; ?>" name="entidad_financiera_id" id="entidad_financiera_id" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="concepto_pago_id" class="control-label">Concepto_pago:</label>
					<input type="text" value="<?= $contratos['concepto_pago_id']; ?>" name="concepto_pago_id" id="concepto_pago_id" class="form-control" data-validation="required number">
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
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/contratos/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el contratos?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>