<?php

// Obtiene los parametros
$id_tipo_descuento = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene el tipo_descuento
$tipo_descuento = $db->select('z.*')->from('pen_tipo_descuento z')->where('z.id_tipo_descuento', $id_tipo_descuento)->fetch_first();

// Ejecuta un error 404 si no existe el tipo_descuento
if (!$tipo_descuento) { require_once not_found(); exit; }

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
		<strong>Modificar tipo descuento</strong>
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
					<li><a href="?/tipo_descuento/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar tipo descuento</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/tipo_descuento/crear"><span class="glyphicon glyphicon-plus"></span> Crear tipo descuento</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/tipo_descuento/ver/<?= $id_tipo_descuento; ?>"><span class="glyphicon glyphicon-search"></span> Ver tipo descuento</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/tipo_descuento/eliminar/<?= $id_tipo_descuento; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar tipo descuento</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/tipo_descuento/imprimir/<?= $id_tipo_descuento; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir tipo descuento</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/tipo_descuento/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="tipo_descuento" class="control-label">Tipo descuento:</label>
					<input type="text" value="<?= $tipo_descuento['tipo_descuento']; ?>" name="tipo_descuento" id="tipo_descuento" class="form-control" autofocus="autofocus" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max100">
					<input type="text" value="<?= $id_tipo_descuento; ?>" name="id_tipo_descuento" id="id_tipo_descuento" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="porcentaje" class="control-label">Porcentaje:</label>
					<input type="text" value="<?= $tipo_descuento['porcentaje']; ?>" name="porcentaje" id="porcentaje" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="descuento" class="control-label">Descuento:</label>
					<input type="text" value="<?= $tipo_descuento['descuento']; ?>" name="descuento" id="descuento" class="form-control" data-validation="letternumber" data-validation-allowing="-/.#() " data-validation-optional="true">
				</div>
				<div class="form-group">
					<label for="gestion_id" class="control-label">Gestion:</label>
					<input type="text" value="<?= $tipo_descuento['gestion_id']; ?>" name="gestion_id" id="gestion_id" class="form-control" data-validation="number" data-validation-optional="true">
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
					window.location = '?/tipo_descuento/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el tipo descuento?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>