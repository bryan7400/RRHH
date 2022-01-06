<?php

// Obtiene los parametros
$id_comunidado = (isset($_params[0])) ? $_params[0] : 0;

// Obtiene la cadena csrf
$csrf = set_csrf();

// Obtiene los formatos
$formato_textual = get_date_textual($_format);
$formato_numeral = get_date_numeral($_format);

// Obtiene el comunidados
$comunidados = $db->select('z.*')->from('ins_comunidados z')->where('z.id_comunidado', $id_comunidado)->fetch_first();

// Ejecuta un error 404 si no existe el comunidados
if (!$comunidados) { require_once not_found(); exit; }

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
		<strong>Modificar comunidados</strong>
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
					<li><a href="?/comunidados/listar"><span class="glyphicon glyphicon-list-alt"></span> Listar comunidados</a></li>
					<?php endif ?>
					<?php if ($permiso_crear) : ?>
					<li><a href="?/comunidados/crear"><span class="glyphicon glyphicon-plus"></span> Crear comunidados</a></li>
					<?php endif ?>
					<?php if ($permiso_ver) : ?>
					<li><a href="?/comunidados/ver/<?= $id_comunidado; ?>"><span class="glyphicon glyphicon-search"></span> Ver comunidados</a></li>
					<?php endif ?>
					<?php if ($permiso_eliminar) : ?>
					<li><a href="?/comunidados/eliminar/<?= $id_comunidado; ?>" data-eliminar="true"><span class="glyphicon glyphicon-trash"></span> Eliminar comunidados</a></li>
					<?php endif ?>
					<?php if ($permiso_imprimir) : ?>
					<li><a href="?/comunidados/imprimir/<?= $id_comunidado; ?>" target="_blank"><span class="glyphicon glyphicon-print"></span> Imprimir comunidados</a></li>
					<?php endif ?>
				</ul>
			</div>
		</div>
	</div>
	<hr>
	<?php endif ?>
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
			<form method="post" action="?/comunidados/guardar" autocomplete="off">
				<input type="hidden" name="<?= $csrf; ?>">
				<div class="form-group">
					<label for="fecha_inicio" class="control-label">Fecha inicio:</label>
					<input type="text" value="<?= $comunidados['fecha_inicio']; ?>" name="fecha_inicio" id="fecha_inicio" class="form-control" autofocus="autofocus" data-validation="required custom" data-validation-regexp="^([12][0-9]{3})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$">
					<input type="text" value="<?= $id_comunidado; ?>" name="id_comunidado" id="id_comunidado" class="translate" tabindex="-1" data-validation="required number" data-validation-error-msg="El campo no es válido">
				</div>
				<div class="form-group">
					<label for="fecha_final" class="control-label">Fecha final:</label>
					<input type="text" value="<?= $comunidados['fecha_final']; ?>" name="fecha_final" id="fecha_final" class="form-control" data-validation="required custom" data-validation-regexp="^([12][0-9]{3})-(0[1-9]|1[012])-(0[1-9]|[12][0-9]|3[01]) (0[0-9]|1[0-9]|2[0123])\:([012345][0-9])\:([012345][0-9])$">
				</div>
				<div class="form-group">
					<label for="nombre_evento" class="control-label">Nombre evento:</label>
					<input type="text" value="<?= $comunidados['nombre_evento']; ?>" name="nombre_evento" id="nombre_evento" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max100">
				</div>
				<div class="form-group">
					<label for="descripcion" class="control-label">Descripcion:</label>
					<input type="text" value="<?= $comunidados['descripcion']; ?>" name="descripcion" id="descripcion" class="form-control" data-validation="required number">
				</div>
				<div class="form-group">
					<label for="color" class="control-label">Color:</label>
					<input type="text" value="<?= $comunidados['color']; ?>" name="color" id="color" class="form-control" data-validation="required letternumber length" data-validation-allowing="-/.#() " data-validation-length="max20">
				</div>
				<div class="form-group">
					<label for="usuarios" class="control-label">Usuarios:</label>
					<input type="text" value="<?= $comunidados['usuarios']; ?>" name="usuarios" id="usuarios" class="form-control" data-validation="required number">
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
<script src="<?= js; ?>/jquery.maskedinput.min.js"></script>
<script>
$(function () {
	$.validate({
		modules: 'basic'
	});

	$('#fecha_inicio').mask('9999-99-99 99:99:99');

	$('#fecha_final').mask('9999-99-99 99:99:99');
	
	<?php if ($permiso_crear) : ?>
	$(window).bind('keydown', function (e) {
		if (e.altKey || e.metaKey) {
			switch (String.fromCharCode(e.which).toLowerCase()) {
				case 'n':
					e.preventDefault();
					window.location = '?/comunidados/crear';
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
		bootbox.confirm('¿Está seguro que desea eliminar el comunidados?', function (result) {
			if (result) {
				$.request(href, csrf);
			}
		});
	});
	<?php endif ?>
});
</script>
<?php require_once show_template('footer-full'); ?>