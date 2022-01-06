<?php

// Obtiene los parametros
$id_concepto_pago = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el concepto_pago
$concepto_pago = $db->select('z.*')->from('rhh_concepto_pago z')->where('z.id_concepto_pago', $id_concepto_pago)->fetch_first();

// Ejecuta un error 404 si no existe el concepto_pago
if (!$concepto_pago) { require_once not_found(); exit; }

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
		<strong>Modificar concepto pago</strong>
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
					<li><a href="?/concepto_pago/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar concepto pago</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/concepto_pago/crear"><span class="glyphicon glyphicon-plus"></span> Crear concepto pago</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/concepto_pago/ver/<?= $id_concepto_pago; ?>"><span class="glyphicon glyphicon-search"></span> Ver concepto pago</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/concepto_pago/eliminar/<?= $id_concepto_pago; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar concepto pago</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/concepto_pago/imprimir/<?= $id_concepto_pago; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir concepto pago</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/concepto_pago/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="nombre_concepto_pago" class="control-label">Nombre concepto pago:</label>
					<input type="text" value="<?= $concepto_pago['nombre_concepto_pago']; ?>" name="nombre_concepto_pago" id="nombre_concepto_pago" class="form-control" autofocus="autofocus" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max100">
					<input type="text" value="<?= $id_concepto_pago; ?>" name="id_concepto_pago" id="id_concepto_pago" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="porcentaje" class="control-label">Porcentaje:</label>
					<input type="text" value="<?= $concepto_pago['porcentaje']; ?>" name="porcentaje" id="porcentaje" class="form-control" data-validation="required number">
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
<script>
$(function () {
	$.validate({
		modules: 'basic'
	});
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/concepto_pago/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el concepto pago?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>